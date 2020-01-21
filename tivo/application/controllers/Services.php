<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("invoices_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");
		$this->load->model("services_model");
	}

	private function check_requirements() 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}

		$this->common->check_permissions(
			"Services", 
			array("admin", "project_admin", "services_manage"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadContent("services/index.php", array(
			)
		);
	}

	public function services_page() 
	{
		$this->check_requirements();
		
		$this->load->library("datatables");

		$this->datatables->set_default_order("service_forms.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"service_forms.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"service_forms.cost" => 0
				 )
			)
		);

		
		$this->datatables->set_total_rows(
			$this->services_model->get_services_total()
		);

		$services = $this->services_model->get_services($this->datatables);

		foreach($services->result() as $r) {
			
			if(!isset($r->username)) {
				$user = lang("ctn_990");
			} else {
				$user = $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp));
			}
			$this->datatables->data[] = array(
				$r->title,
				$user,
				number_format($r->cost, 2),
				'<a href="'.site_url("services/view_service/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("services/edit_service/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("services/delete_service/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'" onclick="return confirm(\''.lang("ctn_317").'\')"><span class="glyphicon glyphicon-trash"></span></a>'

				);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_service($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}
		$service = $service->row();

		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
		);

		$currencies = $this->invoices_model->get_currencies();

		$fields = $this->services_model->get_form_fields($id);


		$accounts = $this->invoices_model->get_all_paying_accounts();


		$this->template->loadContent("services/edit.php", array(
			"service" => $service,
			"currencies" => $currencies,
			"fields" => $fields,
			"accounts" => $accounts
			)
		);

	}

	public function edit_service_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}
		$service = $service->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$cost = abs($this->input->post("cost"));
		$invoice = intval($this->input->post("invoice"));
		$currencyid = intval($this->input->post("currencyid"));
		$paying_accountid = intval($this->input->post("paying_accountid"));


		$invoice_message = $this->lib_filter->go($this->input->post("invoice_message"));
		$require_login = intval($this->input->post("require_login"));

		$userid = 0;
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_156"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		if(empty($title)) {
			$this->template->error(lang("error_239"));
		}

		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		// Check all fields
		$fields = array();
		$field_count = intval($this->input->post("field_count"));
		for($i=1;$i<=$field_count;$i++) {
			$ftitle = $this->common->nohtml($this->input->post("field_title_" . $i));
			$ftype = intval($this->input->post("field_type_" . $i));
			$frequired = intval($this->input->post("field_require_" . $i));
			$fdesc = $this->common->nohtml($this->input->post("field_desc_" . $i));
			$foptions = $this->common->nohtml($this->input->post("field_options_" . $i));
			$fid = intval($this->input->post("form_field_id_" . $i));
			$fcost = abs($this->input->post("field_cost_" . $i));

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions,
					"fid" => $fid,
					"cost" => $fcost
				);
			}
		}

		$fields_r = $this->services_model->get_form_fields($id);
		foreach($fields_r->result() as $r) {
			// Check to see if FID is in our array.
			$flag = false;
			foreach($fields as $rr) {
				if($rr['fid'] == $r->ID) {
					$flag = true;
				}
			}

			if(!$flag) {
				// Delete field
				$this->services_model->delete_form_field($r->ID);
			}
		}

		if($invoice) {
			$account = $this->invoices_model->get_paying_account($paying_accountid);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
		}

		$this->services_model->update_service($id, array(
			"title" => $title,
			"welcome" => $welcome,
			"userid" => $userid,
			"invoice" => $invoice,
			"cost" => $cost,
			"currencyid" => $currencyid,
			"require_login" => $require_login,
			"invoice_message" => $invoice_message,
			"paying_accountid" => $paying_accountid
			)
		);

		foreach($fields as $r) {
			if($r['fid'] > 0) {
				$this->services_model->update_form_field($r['fid'], array(
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options'],
					"cost" => $r['cost']
					)
				);
			} else {
				$this->services_model->add_field(array(
					"formid" => $id,
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options'],
					"cost" => $r['cost']
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_118"));
		redirect(site_url("services"));


	}

	public function delete_service($id, $hash) 
	{
		$this->check_requirements();
		$id = intval($id);
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}

		$this->services_model->delete_service($id);
		$this->session->set_flashdata("globalmsg", lang("success_119"));
		redirect(site_url("services"));
	}

	public function add() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
		);

		$currencies = $this->invoices_model->get_currencies();


		$accounts = $this->invoices_model->get_all_paying_accounts();

		$this->template->loadContent("services/add.php", array(
			"currencies" => $currencies,
			"accounts" => $accounts
			)
		);
	}

	public function add_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$cost = abs($this->input->post("cost"));
		$invoice = intval($this->input->post("invoice"));
		$currencyid = intval($this->input->post("currencyid"));
		$paying_accountid = intval($this->input->post("paying_accountid"));

		$invoice_message = $this->lib_filter->go($this->input->post("invoice_message"));
		$require_login = intval($this->input->post("require_login"));

		$userid = 0;
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_156"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		if(empty($title)) {
			$this->template->error(lang("error_239"));
		}

		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		$fields = array();
		$field_count = intval($this->input->post("field_count"));
		for($i=1;$i<=$field_count;$i++) {
			$ftitle = $this->common->nohtml($this->input->post("field_title_" . $i));
			$ftype = intval($this->input->post("field_type_" . $i));
			$frequired = intval($this->input->post("field_require_" . $i));
			$fdesc = $this->common->nohtml($this->input->post("field_desc_" . $i));
			$foptions = $this->common->nohtml($this->input->post("field_options_" . $i));
			$fcost = abs($this->input->post("field_cost_" . $i));

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions,
					"cost" => $fcost
				);
			}
		}

		if($invoice) {
			$account = $this->invoices_model->get_paying_account($paying_accountid);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
		}

		$formid = $this->services_model->add_service(array(
			"title" => $title,
			"welcome" => $welcome,
			"userid" => $userid,
			"invoice" => $invoice,
			"cost" => $cost,
			"currencyid" => $currencyid,
			"require_login" => $require_login,
			"invoice_message" => $invoice_message,
			"paying_accountid" => $paying_accountid
			)
		);

		foreach($fields as $r) {
			$this->services_model->add_field(array(
				"formid" => $formid,
				"title" => $r['title'],
				"type" => $r['type'],
				"required" => $r['required'],
				"description" => $r['desc'],
				"options" => $r['options'],
				"cost" => $r['cost']
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_120"));
		redirect(site_url("services"));
	}

	public function orders() 
	{
		$this->template->loadData("activeLink", 
			array("services" => array("orders" => 1)));
		$this->template->loadContent("services/orders.php", array(
			)
		);
	}

	public function orders_page() 
	{
		$this->check_requirements();
		
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_services.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"service_forms.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"user_services.email" => 0
				 ),
				 3 => array(
				 	"user_services.total_cost" => 0
				 ),
				 4 => array(
				 	"invoices.status" => 0
				 ),
				 5 => array(
				 	"user_services.timestamp" => 0
				 ),

			)
		);

		
		$this->datatables->set_total_rows(
			$this->services_model->get_orders_total()
		);

		$orders = $this->services_model->get_orders($this->datatables);

		foreach($orders->result() as $r) {
			
			if(!isset($r->username)) {
				$user = lang("ctn_819");
			} else {
				$user = $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp));
			}

			if(!isset($r->status)) {
				$status = "No Invoice";
			} elseif($r->status == 1) {
				$status = "<label class='label label-danger'>".lang("ctn_595")."</label>";
			} elseif($r->status == 2) {
				$status = "<label class='label label-success'>".lang("ctn_596")."</label>";
			} elseif($r->status == 3) {
				$status = "<label class='label label-default'>".lang("ctn_597")."</label>";
			}
			$this->datatables->data[] = array(
				$r->title,
				$user,
				$r->email,
				number_format($r->total_cost, 2),
				$status,
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="'.site_url("services/remind_order/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_1254").'">'.lang("ctn_1254").'</a> <a href="'.site_url("services/edit_order/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("services/delete_order/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'" onclick="return confirm(\''.lang("ctn_317").'\')"><span class="glyphicon glyphicon-trash"></span></a>'

				);
		}
		echo json_encode($this->datatables->process());
	}

	public function remind_order($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->erorr(lang("error_6"));
		}
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$invoiceid = $order->invoiceid;
		$hash = $order->invoice_hash;
		$email = $order->email;
		$name = $order->name;

		// Send Email
		$this->load->model("home_model");
		if(!isset($_COOKIE['language'])) {
			// Get first language in list as default
			$lang = $this->config->item("language");
		} else {
			$lang = $this->common->nohtml($_COOKIE["language"]);
		}

		// Send Email
		$email_template = $this->home_model->get_email_template_hook("ordered_service", $lang);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $name,
			"[SITE_URL]" => site_url(),
			"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$this->common->send_email($email_template->title,
			 $email_template->message, $email);

		$this->session->set_flashdata("globalmsg", lang("success_121"));
		redirect(site_url("services/orders"));
	}

	public function edit_order($id) 
	{
		$this->template->loadData("activeLink", 
			array("services" => array("orders" => 1)));
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$fields = $this->services_model->get_order_fields($id, $order->formid);

		$currency = $this->invoices_model->get_currency($order->currencyid);

		$this->template->loadContent("services/edit_order.php", array(
			"order" => $order,
			"fields" => $fields,
			"currency" => $currency->row()
			)
		);
	}

	public function edit_order_pro($id) 
	{
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$invoice_generate = intval($this->input->post("invoice_generate"));
		$invoice_delete = intval($this->input->post("invoice_delete"));
		$email_remind = intval($this->input->post("email_remind"));

		// Email
		$email = $this->common->nohtml($this->input->post("email"));
		$name = $this->common->nohtml($this->input->post("name"));
		if(empty($email)) {
			$this->template->error(lang("error_241"));
		}
		if(empty($name)) {
			$this->template->error(lang("error_242"));
		}
		$this->load->helper("email");
		if(!valid_emaiL($email)) {
			$this->template->error(lang("error_243"));
		}

		$total_cost = $order->cost;

		$fields = $this->services_model->get_form_fields($order->formid);

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			$cost = 0;
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 3) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("field_checkbox_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}

				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);

			} elseif($r->type == 4) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['field_id_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->title);
					}

					if($flag) {
						$cost = $r->cost;
					}
					if($flag) {
						$total_cost += $cost;
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer,
							"cost" => $cost,
							"field_name" => $r->title
						);
					}
				}

			} elseif($r->type == 5) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->title);
				}
				if($flag) {
					$cost = $r->cost;
					$total_cost += $cost;
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer,
						"cost" => $cost,
						"field_name" => $r->title
					);
				}
			}
		}

		$invoiceid = $order->invoiceid;
		$hash = $order->invoice_hash;

		if($invoice_delete) {
			$this->invoices_model->delete_invoice($order->invoiceid);
			$invoiceid = 0;
		}

		if($invoice_generate) {
			// Get previous invoice for this service
			// To generate ID
			$previnvoice = $this->invoices_model->get_invoice_serviceid($order->formid);
			if($previnvoice->num_rows() == 0) {
				$invoice_tmp_id = "service_id_" . $order->formid . "_0001";
			} else {
				$previnvoice = $previnvoice->row();
				$invoice_tmp_id = $previnvoice->invoice_id;
				// Get last 4 digits
				if (preg_match('#(\d+)$#', $invoice_tmp_id, $matches)) {
					$num = intval($matches[1]);
					$pad = strlen($matches[1]);
					$num++;
					$num = str_pad($num, $pad, '0', STR_PAD_LEFT);
					$invoice_tmp_id = 
						substr($invoice_tmp_id, 0, strlen($invoice_tmp_id)-$pad);
					$invoice_tmp_id = $invoice_tmp_id . $num;
				} else {
					$invoice_tmp_id = $invoice_tmp_id . "_0001";
				}
			}

			$hash = sha1(rand(1,100000) . $order->title . time());

			// Get site settings for invoice address
			$settings = $this->invoices_model->get_invoice_settings();
			$settings = $settings->row();

			// Process
			$invoiceid = $this->invoices_model->add_invoice(array(
				"invoice_id" => $invoice_tmp_id,
				"title" => $order->title,
				"notes" => $order->invoice_message,
				"userid" => 1, // Person who made the occuring invoice
				"status" => 1,
				"clientid" => $order->userid,
				"projectid" => 0,
				"currencyid" => $order->currencyid,
				"timestamp" => time(),
				"due_date" => time(),
				"total" => $total_cost,
				"hash" => $hash,
				"paypal" => 0,
				"paying_accountid" => $order->paying_accountid,
				"stripe" => 0,
				"checkout2" => 0,
				"time_date" => date("Y-m-d"),
				"serviceid" => $order->formid,
				"guest_name" => $name,
				"guest_email" => $email
				)
			);

			// Add invoice items
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $order->title,
				"quantity" => 1,
				"amount" => $order->cost
				)
			);

			foreach($answers as $a) {
				if($a['cost'] > 0) {
					$this->invoices_model->add_invoice_item(array(
						"invoiceid" => $invoiceid,
						"name" => $a['field_name'],
						"quantity" => 1,
						"amount" => $a['cost']
						)
					);
				}
			}
		}

		if($email_remind) {
			// Send Email
			$this->load->model("home_model");
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ordered_service", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $name,
				"[SITE_URL]" => site_url(),
				"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $email);
		}

		// Delete old answers
		$this->services_model->delete_order_answers($id);

		// update entry
		$this->services_model->update_user_service($id, array(
			"email" => $email,
			"total_cost" => $total_cost,
			"invoiceid" => $invoiceid,
			"name" => $name
			)
		);

		foreach($answers as $a) {
			$this->services_model->add_user_service_answer(array(
				"serviceid" => $id,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer'],
				"cost" => $a['cost']
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_122"));
		redirect(site_url("services/orders"));

	}

	public function delete_order($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->tempalte->error(lang("error_6"));
		}
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}

		$this->services_model->delete_order($id);
		$this->session->set_flashdata("globalmsg", lang("success_123"));
		redirect(site_url("services/orders"));
	}


	public function view_service($id) 
	{
		$this->template->loadData("activeLink", 
			array("quote" => array("forms" => 1)));
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script>'
		);
		$id = intval($id);
		$form = $this->services_model->get_service($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_244"));
		}

		$form = $form->row();
		if($form->require_login) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_245"));
			}
		}

		$currency = $this->invoices_model->get_currency($form->currencyid);

		$fields = $this->services_model->get_form_fields($form->ID);
		$this->template->loadAjax("services/view_full_service.php", array(
			"form" => $form,
			"fields" => $fields,
			"currency" => $currency->row()
			),1
		);
	}

	public function process_form($id, $guest=0) 
	{
		if($guest) {
			$this->template->error_hack = 1;
		}
		$id = intval($id);
		$form = $this->services_model->get_service($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}

		$form = $form->row();

		if($form->require_login) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_245"));
			}
		}

		$fields = $this->services_model->get_form_fields($form->ID);

		$email = $this->common->nohtml($this->input->post("email"));
		$name = $this->common->nohtml($this->input->post("name"));

		if(empty($email)) {
			$this->template->error(lang("error_241"));
		}
		if(empty($name)) {
			$this->template->error(lang("error_242"));
		}

		$this->load->helper("email");

		if(!valid_emaiL($email)) {
			$this->template->error(lang("error_243"));
		}

		$total_cost = $form->cost;

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			$cost = 0;
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 3) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("field_checkbox_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}

				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);

			} elseif($r->type == 4) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['field_id_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->title);
					}

					if($flag) {
						$cost = $r->cost;
					}
					if($flag) {
						$total_cost += $cost;
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer,
							"cost" => $cost,
							"field_name" => $r->title
						);
					}
				}

			} elseif($r->type == 5) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->title);
				}
				if($flag) {
					$cost = $r->cost;
					$total_cost += $cost;
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer,
						"cost" => $cost,
						"field_name" => $r->title
					);
				}
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		$invoiceid = 0;
		$hash = "";
		// Generate invoice time
		if($form->invoice) {

			// Get paying account


			// Get previous invoice for this service
			// To generate ID
			$previnvoice = $this->invoices_model->get_invoice_serviceid($id);
			if($previnvoice->num_rows() == 0) {
				$invoice_tmp_id = "service_id_" . $id . "_0001";
			} else {
				$previnvoice = $previnvoice->row();
				$invoice_tmp_id = $previnvoice->invoice_id;
				// Get last 4 digits
				if (preg_match('#(\d+)$#', $invoice_tmp_id, $matches)) {
					$num = intval($matches[1]);
					$pad = strlen($matches[1]);
					$num++;
					$num = str_pad($num, $pad, '0', STR_PAD_LEFT);
					$invoice_tmp_id = 
						substr($invoice_tmp_id, 0, strlen($invoice_tmp_id)-$pad);
					$invoice_tmp_id = $invoice_tmp_id . $num;
				} else {
					$invoice_tmp_id = $invoice_tmp_id . "_0001";
				}
			}

			$hash = sha1(rand(1,100000) . $form->title . time());

			// Get site settings for invoice address
			$settings = $this->invoices_model->get_invoice_settings();
			$settings = $settings->row();

			// Process
			$invoiceid = $this->invoices_model->add_invoice(array(
				"invoice_id" => $invoice_tmp_id,
				"title" => $form->title,
				"notes" => $form->invoice_message,
				"userid" => 1, // Person who made the occuring invoice
				"status" => 1,
				"clientid" => $userid,
				"projectid" => 0,
				"currencyid" => $form->currencyid,
				"timestamp" => time(),
				"due_date" => time(),
				"total" => $total_cost,
				"hash" => $hash,
				"paypal" => 0,
				"paying_accountid" => $form->paying_accountid,
				"stripe" => 0,
				"checkout2" => 0,
				"time_date" => date("Y-m-d"),
				"serviceid" => $form->ID,
				"guest_name" => $name,
				"guest_email" => $email
				)
			);

			// Add invoice items
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $form->title,
				"quantity" => 1,
				"amount" => $form->cost
				)
			);

			foreach($answers as $a) {
				if($a['cost'] > 0) {
					$this->invoices_model->add_invoice_item(array(
						"invoiceid" => $invoiceid,
						"name" => $a['field_name'],
						"quantity" => 1,
						"amount" => $a['cost']
						)
					);
				}
			}

		}

		// Add new entry
		$serviceid = $this->services_model->add_user_service(array(
			"userid" => $userid,
			"timestamp" => time(),
			"formid" => $form->ID,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"email" => $email,
			"total_cost" => $total_cost,
			"invoiceid" => $invoiceid,
			"name" => $name
			)
		);

		foreach($answers as $a) {
			$this->services_model->add_user_service_answer(array(
				"serviceid" => $serviceid,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer'],
				"cost" => $a['cost']
				)
			);
		}

		// Send Email
		$this->load->model("home_model");
		$email_template = $this->home_model->get_email_template(5);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $name,
			"[SITE_URL]" => site_url(),
			"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$this->common->send_email($email_template->title,
			 $email_template->message, $email);

		if($form->userid > 0) {
			// Notification
			$this->user_model->increment_field($form->userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $form->userid,
				"url" => "services/edit_order/" . $serviceid,
				"timestamp" => time(),
				"message" => lang("ctn_1262"),
				"status" => 0,
				"fromid" => 1,
				"email" => $form->assigned_email,
				"username" => $form->username,
				"email_notification" => $form->assigned_email_notification
				)
			);
		}
		$this->session->set_flashdata("globalmsg", lang("success_124"));
		if($invoiceid > 0) {
			redirect(site_url("invoices/view/" . $invoiceid . "/" . $hash));
		} else {
			if(!$guest) { 
				redirect(site_url("services"));
			} else {
				redirect(site_url("services/view_service/" . $id));
			}
		}
	}

}

?>