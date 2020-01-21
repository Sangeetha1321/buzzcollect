<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IPN extends CI_Controller 
{

	public $project = null;

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("ipn_model");
		$this->load->model("user_model");
		$this->load->model("invoices_model");
		$this->config->set_item('csrf_protection', FALSE);

	}

	public function index() 
	{
		exit();
	}

	public function checkout2() 
	{
		$id = intval($_REQUEST['titan_invoiceid']);
		$hash = $this->common->nohtml($_REQUEST['titan_invoice_hash']);
		$this->ipn_model->log_ipn("[CHECKOUT2] Invoice attempted to be paid " . 
				$id . " Hash:" . $hash);

		$settings = $this->invoices_model->get_invoice_settings();
		$settings = $settings->row();

		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}
		$invoice = $invoice->row();

		if($invoice->hash != $hash) {
			$this->template->error(lang("error_6"));
		}

		$hashSecretWord = $invoice->checkout2_secret_key; //2Checkout Secret Word
		$hashSid = $invoice->checkout2_account_number; //2Checkout account number
		

		/*if(!empty($invoice->checkout2_hash)) {
			$this->template->error("This invoice has already been paid!");
		}*/

		$this->ipn_model->log_ipn("[CHECKOUT2] Verified the Invoice. Attempting to match 2checkout hash with server hash.");

		$hashTotal = (string) $_REQUEST['total']; //Sale total to validate against
		$hashOrder = $_REQUEST['order_number']; //2Checkout Order Number

		$demo = true;
		if($demo) {
			$this->ipn_model->log_ipn("[CHECKOUT2] DEMO STRING.");
			$StringToHash = strtoupper(md5($hashSecretWord . $hashSid . 1 . $hashTotal));
		} else {
			$StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
		}

		$key = $_REQUEST['key'];

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
			$email = $this->user->info->email;
		} else {
			$userid = 0;
			if(isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
				$email = $this->common->nohtml($_REQUEST['email']);
			} else {
				if(!empty($invoice->guest_email)) {
					$email = $invoice->guest_email;
				} else {
					$email = "None Provided";
				}
			}
		}

		if($key == $StringToHash) {
			$this->ipn_model->log_ipn("[CHECKOUT2] Server hash and 2checkout hash match.");
			$this->ipn_model->add_payment(array(
				"userid" => $userid, 
				"amount" => $hashTotal,
				"invoiceid" => $id,
				"timestamp" => time(), 
				"email" => $email,
				"processor" => "2Checkout"
				)
			);

			// Check total amount is greater than the Invoice total cost
			// If it is, set Invoice status to paid
			$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
			if($payments_total >= $invoice->total) {
				$this->invoices_model->update_invoice($invoice->ID, array(
					"status" => 2,
					"date_paid" => time(),
					"paid_by" => $this->common->nohtml($payer_email),
					"time_date_paid" => date("Y-m-d")
					)
				);
			} else {
				// Check for partial payments
				if($payments_total > 0) {
					$status = 4; // Partial Payment Status
				} else {
					$status = 1;
				}
				$this->invoices_model->update_invoice($invoice->ID, array(
					"status" => $status
					)
				);
			}
			

			$this->ipn_model->log_ipn("[CHECKOUT2] Invoice successfully marked as paid.");

			$this->session->set_flashdata("globalmsg", lang("success_156"));
			redirect(site_url("invoices/view/" . $id ."/" . $hash));
		} else {
			$this->ipn_model->log_ipn("[CHECKOUT2] Server hash and 2checkout hash FAILED." . $key . " != " . $StringToHash . " hashsecret: " . $hashSecretWord . " Accno: " . $hashSid . " total: " . $hashTotal );
			$this->session->set_flashdata("globalmsg", lang("success_157"));
			redirect(site_url("invoices/view/" . $id ."/" . $hash));
		}
	}

	public function stripe($id, $hash) 
	{
		$this->ipn_model->log_ipn("[STRIPE] Tried to pay Invoice with STRIPE InvoiceID: " . 
				$id . " Hash:" . $hash);
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		if($invoice->hash != $hash) {
			$this->template->error(lang("error_6"));
		}

		$this->ipn_model->log_ipn("[STRIPE] Obtained invoice successfully.");

		$settings = $this->invoices_model->get_invoice_settings();
		$settings = $settings->row();

		// Processing stripe payments
		// Stripe
		require_once(APPPATH . 'third_party/stripe/init.php');

		
		if(!empty($invoice->stripe_secret_key) && !empty($invoice->stripe_publishable_key)) {
			// Stripe
			require_once(APPPATH . 'third_party/stripe/init.php');

			$stripe = array(
			  "secret_key"      => $invoice->stripe_secret_key,
			  "publishable_key" => $invoice->stripe_publishable_key
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		} else {
			$stripe = null;
		}
		

		if($stripe === null) {
			$this->template->error("No Stripe Keys found!");
		}


		if(!isset($_POST['stripeToken'])) {
			$this->template->error("No Stripe Token");
		}

		$token  = $_POST['stripeToken'];

		$this->ipn_model->log_ipn("[STRIPE] Connected successfully to API.");

		$stripeInfo =\Stripe\Token::retrieve($token);

		$charge_amount = floatval($this->input->post("charge_amount"));

		// Create a charge: this will charge the user's card
		try {
		  $charge = \Stripe\Charge::create(array(
		    "amount" => str_replace(".","", $charge_amount), // Amount in cents
		    "currency" => $invoice->code,
		    "source" => $token,
		    "description" => "Invoice #".$invoice->invoice_id." @ " .$this->settings->info->site_name
		    ));
		  $this->ipn_model->log_ipn("[STRIPE] Payment made successfully.");
		} catch(\Stripe\Error\Card $e) {
		  // The card has been declined
			$this->ipn_model->log_ipn("[STRIPE] Credit Card was declined or error happened. Amount: " . $charge_amount);
			$this->template->error("Your card has been declined by the payment gateway (STRIPE).");
		}


		$this->ipn_model->log_ipn("[STRIPE] Invoice successfully marked as paid.");

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		$this->ipn_model->add_payment(array(
				"userid" => $userid, 
				"amount" => $charge_amount,
				"invoiceid" => $id,
				"timestamp" => time(), 
				"email" => $stripeInfo->email,
				"processor" => "Stripe"
				)
			);

		// Check total amount is greater than the Invoice total cost
		// If it is, set Invoice status to paid
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
		if($payments_total >= $invoice->total) {
			$this->invoices_model->update_invoice($invoice->ID, array(
				"status" => 2,
				"date_paid" => time(),
				"paid_by" => $this->common->nohtml($payer_email),
				"time_date_paid" => date("Y-m-d")
				)
			);
		} else {
			// Check for partial payments
			if($payments_total > 0) {
				$status = 4; // Partial Payment Status
			} else {
				$status = 1;
			}
			$this->invoices_model->update_invoice($invoice->ID, array(
				"status" => $status
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_156"));
		redirect(site_url("invoices/view/" . $id ."/" . $hash));
	}

	public function process() 
	{
		require_once('paypal/config.php');
		$this->ipn_model->log_ipn("Attempted to pay invoice");
		// Read the post from PayPal system and add 'cmd'   
		$req = 'cmd=_notify-validate';  

		// Store each $_POST value in a NVP string: 1 string encoded 
		// and 1 string decoded   
		$ipn_email = '';  
		$ipn_data_array = array();
		foreach ($_POST as $key => $value)   
		{   
		 $value = urlencode(stripslashes($value));   
		 $req .= "&" . $key . "=" . $value;   
		 $ipn_email .= $key . " = " . urldecode($value) . '<br />';  
		 $ipn_data_array[$key] = urldecode($value);
		}

		// Store IPN data serialized for RAW data storage later
		$ipn_serialized = serialize($ipn_data_array);
		  
		// Validate IPN with PayPal
		require_once('paypal/validate.php');

		// Load IPN data into PHP variables
		require_once('paypal/parse-ipn-data.php');

		$ipn_log_data['ipn_data_serialized'] = $ipn_serialized;

		if(strtoupper($txn_type) == 'WEB_ACCEPT') {
			$this->ipn_model->log_ipn($ipn_log_data['ipn_data_serialized']);
			// Invoice Payment
			$invoice_hash = $this->common->nohtml($custom);
			$id = intval($item_number);
			$this->ipn_model->log_ipn("Tried to pay Invoice ($mc_gross) InvoiceID: " . 
				$id . " Hash:" . $invoice_hash);

			$invoice = $this->invoices_model->get_invoice($id);
			if($invoice->num_rows() == 0) {
				$this->ipn_model->log_ipn("Could not find invoice in the DB: " . $id);
				exit();
			}
			$invoice = $invoice->row();
			if($invoice->hash != $invoice_hash) {
				$this->ipn_model->log_ipn("Invalid Hash for Invoice: " . $id . " Hash:" . $invoice_hash);
				exit();
			}

			// Get amount
			$amount = abs($mc_gross);
			
			$this->ipn_model->log_ipn("Invoice Paid $id for " . $amount);
			$this->ipn_model->add_payment(array(
				"userid" => 0, 
				"amount" => $amount,
				"invoiceid" => $id,
				"timestamp" => time(), 
				"email" => $this->common->nohtml($payer_email),
				"processor" => "PayPal"
				)
			);

			// Check total amount is greater than the Invoice total cost
			// If it is, set Invoice status to paid
			$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
			if($payments_total >= $invoice->total) {
				$this->invoices_model->update_invoice($invoice->ID, array(
					"status" => 2,
					"date_paid" => time(),
					"paid_by" => $this->common->nohtml($payer_email),
					"time_date_paid" => date("Y-m-d")
					)
				);
			} else {
				// Check for partial payments
				if($payments_total > 0) {
					$status = 4; // Partial Payment Status
				} else {
					$status = 1;
				}
				$this->invoices_model->update_invoice($invoice->ID, array(
					"status" => $status
					)
				);
			}
		}
	}

	public function process2() 
	{
		require_once('paypal/config.php');
		$this->ipn_model->log_ipn("Attempted to pay Funds");
		// Read the post from PayPal system and add 'cmd'   
		$req = 'cmd=_notify-validate';  

		// Store each $_POST value in a NVP string: 1 string encoded 
		// and 1 string decoded   
		$ipn_email = '';  
		$ipn_data_array = array();
		foreach ($_POST as $key => $value)   
		{   
		 $value = urlencode(stripslashes($value));   
		 $req .= "&" . $key . "=" . $value;   
		 $ipn_email .= $key . " = " . urldecode($value) . '<br />';  
		 $ipn_data_array[$key] = urldecode($value);
		}

		// Store IPN data serialized for RAW data storage later
		$ipn_serialized = serialize($ipn_data_array);
		  
		// Validate IPN with PayPal
		require_once('paypal/validate.php');

		// Load IPN data into PHP variables
		require_once('paypal/parse-ipn-data.php');

		$ipn_log_data['ipn_data_serialized'] = $ipn_serialized;

		if(strtoupper($txn_type) == 'WEB_ACCEPT') {
			$this->ipn_model->log_ipn($ipn_log_data['ipn_data_serialized']);
			// Invoice Payment
			$userid = intval($this->common->nohtml($custom));
			$id = intval($item_number);
			$this->ipn_model->log_ipn("Tried to pay Funds ($mc_gross): " . 
				$id . " Userid:" . $userid);

			// Get amount
			$amount = abs($mc_gross);
			$this->user_model->add_points($userid, $amount);
			$this->ipn_model->log_ipn("Payment Added to user: $userid, $amount");
			$this->ipn_model->add_payment(array(
				"userid" => $userid, 
				"amount" => $amount, 
				"timestamp" => time(), 
				"email" => $this->common->nohtml($payer_email),
				"processor" => "PayPal"
				)
			);
		}
	}

}

?>