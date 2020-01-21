<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("invoices_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");
		$this->load->model("time_model");
		$this->load->model("finance_model");
	}

	public function check_requirements($user_roles=array()) 
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

		if(empty($user_roles)) {
			$user_roles = array("admin", "project_admin",
			 "invoice_manage");
		}
		if(!$this->common->has_permissions($user_roles, $this->user)) {
			$this->template->error(lang("error_2"));
		}
	}

	public function get_payment_gateway($id, $hash) 
	{
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		if($invoice->hash != $hash) {
			$this->template->error(lang("error_6"));
		}

		$settings = $this->invoices_model->get_invoice_settings();
		$settings = $settings->row();

		// Calulate amount left to pay
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);


		$type = intval($this->input->get("type"));
		if($type == 0) {
			// PayPal
			if(!$settings->enable_paypal) {
				$this->template->errori(lang("error_271"));
			}
			$amount_left = $invoice->total - $payments_total;
			$this->template->loadAjax("invoices/paypal_form.php", array(
				"invoice" => $invoice,
				"amount_left" => $amount_left
				),1
			);
		}

		// Stripe Payment Gateway
		if($type == 1) {
			$amount = floatval($this->input->get("amount"));
			if(!$settings->enable_stripe || ((empty($invoice->stripe_secret_key) || empty($invoice->stripe_publishable_key)) )) {
				$this->template->errori(lang("error_270"));
			}
			if($amount > 0) {
				$amount_left = $invoice->total - $payments_total;
				$this->template->loadAjax("invoices/stripe_form.php", array(
					"invoice" => $invoice,
					"amount" => $amount
					),1
				);
			} else {
				$amount_left = $invoice->total - $payments_total;
				$this->template->loadAjax("invoices/stripe_form_p1.php", array(
					"invoice" => $invoice,
					"amount_left" => $amount_left
					),1
				);
			}
		}

		// 2Checkout
		if($type == 2) {
			if(!$settings->enable_checkout2) {
				$this->template->errori(lang("error_272"));
			}
			$amount_left = $invoice->total - $payments_total;
			$this->template->loadAjax("invoices/2checkout_form.php", array(
				"invoice" => $invoice,
				"amount_left" => $amount_left
				),1
			);
		}

		exit();
	}

	public function add_check() 
	{
		$formData = $this->input->post("formData");
		parse_str($formData, $data);

		// Invocie data
		$invoice_id = $this->common->nohtml($data["invoice_id"]);
		$title = $this->common->nohtml($data["title"]);
		$notes = $this->lib_filter->go($data["notes"]);
		$client_username = $this->common->nohtml($data["client_username"]);
		$guest_name = $this->common->nohtml($data["guest_name"]);
		$guest_email = $this->common->nohtml($data["guest_email"]);
		$clientid = intval($data['clientid']);
		$projectid = intval($data["projectid"]);
		$status = intval($data["status"]);
		$currencyid = intval($data["currencyid"]);
		$due_date = $this->common->nohtml($data["due_date"]);

		$template = 0;
		if(isset($data['template'])) {
			$template = intval($data["template"]);
		}
		$paying_accountid = intval($data["paying_accountid"]);

		$field_errors = array();

		if($paying_accountid == -1) {
			// Check valid Paying Account
			$account = $this->invoices_model->get_user_paying_account($this->user->info->ID);
			if($account->num_rows() == 0) {
				$field_errors['paying_accountid'] = lang("error_296");
			} else {
				$account = $account->row();
				$paying_accountid = $account->ID;
			}
		} else {
			// Check valid Paying Account
			$account = $this->invoices_model->get_paying_account($paying_accountid);
			if($account->num_rows() == 0) {
				$field_errors['paying_accountid'] = lang("error_269");
			}
		}

		if(empty($invoice_id)) {
			$field_errors['invoice_id'] = lang("error_124");
		}
		if(empty($title)) {
			$field_errors['title'] = lang("error_106");
		}

		if($projectid > 0) {
			// Project
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$field_errors['projectid'] = lang("error_72");
			} else {
				$project = $project->row();
				

				$msg = $this->common->check_permissions(
					lang("ctn_587"), 
					array("admin", "project_admin", "invoice_manage"), // User Roles
					array("admin", "invoice"),  // Team Roles
					$projectid,
					"",
					"",
					true // returns error
				);

				if(!empty($msg)) {
					$field_errors['projectid'] = $msg;
				}
			}
		}

		if($clientid > 0) {
			// Check client is legit and from above project
			$member = $this->team_model->get_member_of_project($clientid, $projectid);
			if($member->num_rows() == 0) {
				$field_errors['clientid'] = lang("error_273");
			}
		} elseif($clientid == -1) {
			// Looking for guest client
			if(empty($guest_name)) {
				$field_errors['guest_name'] = lang("ctn_274");
			}
			if(empty($guest_email)) {
				$field_errors['guest_email'] = lang("ctn_275");
			}
			// Check valid guest email (?)
			$this->load->helper('email');
			if (!valid_email($guest_email)) {
				$field_errors['guest_email'] = lang("error_19");
			}
		} elseif($clientid == -2) {
			$client = $this->user_model->get_user_by_username($client_username);
			if($client->num_rows() == 0) {
				$field_errors['client_username'] = lang("ctn_276");
			}
		} else {
			$field_errors['clientid'] = lang("ctn_277");
		}

		if($status < 1 || $status > 4) {
			$field_errors['status'] = lang("error_122");
		}

		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$field_errors['currencyid'] = lang("error_126");
		}

		// Items 
		$items = intval($data["items_count"]);
		
		$sub_total=0;
		$count = 0;
		for ($i=1;$i<=$items;$i++) {
			if(isset($data['item_quantity_' . $i]) && isset($data['item_name_' . $i])
				&& isset($data['item_price_' . $i]) ) {
				$count++;
				$quantity = floatval($data["item_quantity_" . $i]);
				if ($quantity < 0) {
					$field_errors['item_quantity_' . $i] = lang("error_128");
				}
				$amount = floatval($data["item_price_" . $i]);
				if ($amount < 0) {
					$field_errors['item_price_' . $i] = lang("error_129");
				}
				$name = $this->common->nohtml($data["item_name_" . $i]);
				if(empty($name) ) {
					$field_errors['item_name_' . $i] = lang("error_130");
				}
				$sub_total += $amount*$quantity;

			}
		}
		$total = $sub_total;

		if($count == 0) {
			$field_errors['items_count'] = lang("error_127");
		}

		if(empty($field_errors)) {
			echo json_encode(array("success" => 1));
		} else {
			echo json_encode(array("field_errors" => 1,"fieldErrors" => $field_errors));
		}
		exit();
	}

	public function index($projectid = 0) 
	{
		$this->check_requirements(array("admin", "project_admin",
			 "invoice_manage", "invoice_worker"));
		$this->template->loadData("activeLink", 
			array("invoice" => array("general" => 1)));

		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}


		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "invoices_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, "(pr2.admin = 1 OR pr2.invoice = 1)");
		}

		$project = null;
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$this->common->check_permissions(
				"You can't view documentation for this project!", 
				array("admin", "project_admin", "invoice_manage"), // User Roles
				array("admin", "invoice"),  // Team Roles
				$projectid
			);
		}

		$this->template->loadContent("invoices/index.php", array(
			"page" => "index",
			"projects" => $projects,
			"project" => $project
			)
		);
	}

	public function invoice_page($page="index", $userid=0, $projectid=0) 
	{
		$userid = intval($userid);
		$projectid = intval($projectid);
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$this->common->check_permissions(
				"You can't view documentation for this project!", 
				array("admin", "project_admin", "invoice_manage"), // User Roles
				array("admin", "invoice"),  // Team Roles
				$projectid
			);
		}
		$this->load->library("datatables");

		$this->datatables->set_default_order("invoices.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"invoices.invoice_id" => 0
				 ),
				 1 => array(
				 	"invoices.title" => 0
				 ),
				 4 => array(
				 	"invoices.status" => 0
				 ),
				 5 => array(
				 	"invoices.due_date" => 0
				 ),
				 6 => array(
				 	"invoices.total" => 0
				 )
			)
		);

		if($page == "index") {
			$this->check_requirements(array("admin", "project_admin",
			 "invoice_manage", "invoice_worker"));

			if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) {
				$this->datatables->set_total_rows(
					$this->invoices_model->get_invoices_total($projectid)
				);

				$invoices = $this->invoices_model->get_invoices($projectid, $this->datatables);
			} else {
				$this->datatables->set_total_rows(
					$this->invoices_model->get_invoices_user_total($projectid, $this->user->info->ID)
				);

				$invoices = $this->invoices_model->get_invoices_user($projectid, $this->user->info->ID, $this->datatables);
			}
		} elseif($page == "templates") {
			$this->check_requirements(array("admin", "project_admin",
			 "invoice_manage", "invoice_worker"));
			$invoices = $this->invoices_model->get_invoice_templates($this->datatables);
			$this->datatables->set_total_rows(
				$invoices->num_rows()
			);
		} elseif($page == "client") {
			$this->check_requirements(array("admin", "project_admin", 
				"invoice_manage", "invoice_client"));
			$invoices = $this->invoices_model->get_invoices_client(
				$this->user->info->ID, $this->datatables);

			$this->datatables->set_total_rows(
				$this->invoices_model
					->get_invoices_client_total($this->user->info->ID)
			);
		} elseif($page == "client_user") {
			$this->check_requirements(array("admin", "project_admin", 
				"invoice_manage", "invoice_client"));
			$invoices = $this->invoices_model->get_invoices_client(
				$userid, $this->datatables);

			$this->datatables->set_total_rows(
				$this->invoices_model
					->get_invoices_client_total($userid)
			);
		}


		foreach($invoices->result() as $r) {
			if($r->status == 1) {
				$status = "<label class='label label-danger'>".lang("ctn_595")."</label>";
			} elseif($r->status == 2) {
				$status = "<label class='label label-success'>".lang("ctn_596")."</label>";
			} elseif($r->status == 3) {
				$status = "<label class='label label-default'>".lang("ctn_597")."</label>";
			} elseif($r->status == 4) {
				$status = "<label class='label label-warning'>".lang("ctn_1430")."</label>";
			}

			$options = '';
			if($page != "templates") {
				$options .= '<a href="'.site_url("invoices/get_pdf/" . $r->ID . "/" . $r->hash).'" class="btn btn-info btn-xs" title="'.lang("ctn_666").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-save"></span></a> ';
			}
			if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) {
				$options .= '<a href="'.site_url("invoices/edit_invoice/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("invoices/delete_invoice/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="bottom" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>';
			}

			if(!isset($r->projectname)) {
				$projectname = lang("ctn_46");
			} else {
				$projectname = $r->projectname;
			}

			if(isset($r->client_username)) {
				$client = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp, "first_name" => $r->client_first_name,
					"last_name" => $r->client_last_name));
			} else {
				$client = lang("ctn_819");
			}

			$this->datatables->data[] = array(
				'<a href="'.site_url("invoices/view/" . $r->ID . "/" . $r->hash).'">'.$r->invoice_id.'</a>',
				'<a href="'.site_url("invoices/view/" . $r->ID . "/" . $r->hash).'">'.$r->title.'</a>',
				$client,
				$projectname,
				$status,
				date($this->settings->info->date_format, $r->due_date),
				$r->symbol . number_format($r->total,2),
				$options

			);
		}
		echo json_encode($this->datatables->process());
	}

	public function reoccuring() 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("reoccuring" => 1)));

		$templates = $this->invoices_model->get_invoice_templates_all();
	
		$this->template->loadContent("invoices/reoccuring.php", array(
			"templates" => $templates
			)
		);
	}

	public function reoccuring_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("invoice_reoccur.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"invoices.title" => 0
				 ),
				 2 => array(
				 	"invoice_reoccur.status" => 0
				 ),
				 3 => array(
				 	"invoice_reoccur.amount_time" => 0, 
				 	"invoice_reoccur.amount" => "desc"
				 ),
				 4 => array(
				 	"invoice_reoccur.last_occurence" => 0
				 ),
				 5 => array(
				 	"invoice_reoccur.next_occurence" => 0
				 )
			)
		);

		$this->check_requirements();
		
		$this->datatables->set_total_rows(
			$this->invoices_model->get_reoccuring_invoices_total()
		);

		$invoices = $this->invoices_model
			->get_reoccuring_invoices($this->datatables);

		foreach($invoices->result() as $r) {
			if($r->status == 0) {
			  $status = "<label class='label label-warning'>".lang("ctn_647")."</label>";
			} elseif($r->status == 1) {
			  $status = "<label class='label label-success'>".lang("ctn_648")."</label>";
			} elseif($r->status == 2) {
			  $status = "<label class='label label-info'>".lang("ctn_649")."</label>";
			}

			if($r->amount > 1) {
				if($r->amount_time == 0) {
				  $amount_time = lang("ctn_667");
				} elseif($r->amount_time == 1) {
				  $amount_time = lang("ctn_668");
				} elseif($r->amount_time == 2) {
				  $amount_time = lang("ctn_669");
				} elseif($r->amount_time == 3) {
				  $amount_time = lang("ctn_670");
				}
			} else {
			 	if($r->amount_time == 0) {
				  $amount_time = lang("ctn_640");
				} elseif($r->amount_time == 1) {
				  $amount_time = lang("ctn_641");
				} elseif($r->amount_time == 2) {
				  $amount_time = lang("ctn_671");
				} elseif($r->amount_time == 3) {
				  $amount_time = lang("ctn_643");
				}
			}

			if($r->last_occurence > 0) {
				$last_occurence = date($this->settings->info->date_format, $r->last_occurence);
			} else {
				$last_occurence = lang("ctn_672");
			}
			if($r->next_occurence > 0) {
				$next_occurence = date($this->settings->info->date_format, $r->next_occurence);
			} else {
				$next_occurence = lang("ctn_672");
			}


			$this->datatables->data[] = array(
				$r->title,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$status,
				$r->amount . " " . $amount_time,
				$last_occurence,
				$next_occurence,
				'<a href="'.site_url("invoices/edit_reoccur_invoice/" . $r->ID).'" class="btn btn-warning btn-xs"  data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("invoices/delete_reoccur_invoice/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_reoccuring_invoice() 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("reoccuring" => 1)));
		$client_username = $this->common->nohtml($this->input->post("client_username"));
		$templateid = intval($this->input->post("templateid"));
		$amount = intval($this->input->post("amount"));
		$amount_time = intval($this->input->post("amount_time"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$status = intval($this->input->post("status"));

		$userid = 0;
		if(!empty($client_username)) {
			$user = $this->user_model->get_user_by_username($client_username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_115"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		$template = $this->invoices_model->get_invoice($templateid);
		if($template->num_rows() == 0) {
			$this->template->error(lang("error_116"));
		}
		$template = $template->row();
		if(!$template->template) {
			$this->template->error(lang("error_117"));
		}

		if($userid == 0) {
			if($template->clientid == 0) {
				$this->template->error(lang("error_118"));
			}
			$userid = $template->clientid;
		}

		if($amount ==0) {
			$this->template->error(lang("error_119"));
		}
		if($amount_time < 0 || $amount_time > 3) {
			$this->template->error(lang("error_120"));
		}

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$this->template->error(lang("error_121"));
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->date_picker_format, $end_date);
			$ed_timestamp = $ed->getTimestamp();
		} else {
			$ed_timestamp = 0;
		}

		if($status < 0 || $status > 2) {
			$this->template->error(lang("error_122"));
		}

		$this->invoices_model->add_reoccuring_invoice(array(
			"clientid" => $userid,
			"templateid" => $templateid,
			"amount" => $amount,
			"amount_time" => $amount_time,
			"start_date" => $sd_timestamp,
			"end_date" => $ed_timestamp,
			"status" => $status,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"next_occurence" => $sd_timestamp
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_54"));
		redirect(site_url("invoices/reoccuring"));
	}

	public function edit_reoccur_invoice($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("reoccuring" => 1)));

		$id = intval($id);
		$invoice = $this->invoices_model->get_reoccuring_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}
		$invoice = $invoice->row();

		$templates = $this->invoices_model->get_invoice_templates_all();

		$this->template->loadContent("invoices/edit_reoccuring.php", array(
			"templates" => $templates,
			"invoice" => $invoice
			)
		);
	}

	public function edit_reoccur_invoice_pro($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("reoccuring" => 1)));

		$id = intval($id);
		$invoice = $this->invoices_model->get_reoccuring_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}
		$invoice = $invoice->row();

		$client_username = $this->common->nohtml($this->input->post("client_username"));
		$templateid = intval($this->input->post("templateid"));
		$amount = intval($this->input->post("amount"));
		$amount_time = intval($this->input->post("amount_time"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$status = intval($this->input->post("status"));

		$userid = 0;
		if(!empty($client_username)) {
			$user = $this->user_model->get_user_by_username($client_username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_115"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		$template = $this->invoices_model->get_invoice($templateid);
		if($template->num_rows() == 0) {
			$this->template->error(lang("error_116"));
		}
		$template = $template->row();
		if(!$template->template) {
			$this->template->error(lang("error_117"));
		}

		if($userid == 0) {
			if($template->userid == 0) {
				$this->template->error(lang("error_118"));
			}
			$userid = $template->userid;
		}

		if($amount ==0) {
			$this->template->error(lang("error_119"));
		}
		if($amount_time < 0 || $amount_time > 3) {
			$this->template->error(lang("error_120"));
		}

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$this->template->error(lang("error_121"));
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->date_picker_format, $end_date);
			$ed_timestamp = $ed->getTimestamp();
		} else {
			$ed_timestamp = 0;
		}

		if($status < 0 || $status > 2) {
			$this->template->error(lang("error_122"));
		}

		// Calculate next occurence
		$current_date = DateTime::createFromFormat("m/d/Y h:i:s", 
			date("m/d/Y", $invoice->last_occurence) . " 00:00:00");
		$amount = $amount;
		$amount_time = $amount_time;
		$day = 3600*24;
		$week = ((3600*24) *7);
		$month = ((3600*24) * 30);
		$year = ((3600*24) * 365);
		if($amount_time == 0) {
			// Days 
			$next = $current_date->getTimestamp() + ( $day * $amount );
		} elseif($amount_time == 1) {
			// Weeks
			$next = $current_date->getTimestamp() + ( $week * $amount );
		} elseif($amount_time == 2) {
			// Months
			$next = $current_date->getTimestamp() + ( $month * $amount);
		} elseif($amount_time == 3) {
			// Year
			$next = $current_date->getTimestamp() + ( $year * $amount);
		}

		if($ed_timestamp > 0) {
			// Check to make sure end date isn't exceeded
			$end_date = DateTime::createFromFormat("m/d/Y h:i:s", 
				date("m/d/Y", $ed->getTimestamp()) . " 00:00:00");

			if($end_date->getTimestamp() < $next) {
				$next = 0;
			}
		}

		$this->invoices_model->update_reoccuring_invoice($id, array(
			"clientid" => $userid,
			"templateid" => $templateid,
			"amount" => $amount,
			"amount_time" => $amount_time,
			"start_date" => $sd_timestamp,
			"end_date" => $ed_timestamp,
			"status" => $status,
			"next_occurence" => $next
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_55"));
		redirect(site_url("invoices/reoccuring"));
	}

	public function delete_reoccur_invoice($id, $hash) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("reoccuring" => 1)));
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$invoice = $this->invoices_model->get_reoccuring_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}

		// Delete
		$this->invoices_model->delete_reoccuring_invoice($id);
		$this->session->set_flashdata("globalmsg", lang("success_56"));
		redirect(site_url("invoices/reoccuring"));
	}

	public function templates() 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("invoice" => array("templates" => 1)));

		$this->template->loadContent("invoices/index.php", array(
			"page" => "templates"
			)
		);
	}

	public function get_project_clients($projectid) 
	{
		// Project
		$projectid = intval($projectid);
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("ctn_587"), 
			array("admin", "project_admin", "invoice_manage"), // User Roles
			array(),  // Team Roles
			$project->ID,
			"",
			"jsonError"
		);

		$team = $this->team_model->get_members_for_project($projectid);


		$html = $this->template->loadAjaxReturn("invoices/get_clients.php", array(
			"team" => $team
			)
		);

		echo json_encode(array("success" => 1, "html" => $html));
		exit();
	}

	public function get_itemdb_items() 
	{
		// Project
		$projectid = intval($this->input->get("projectid"));
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->jsonError(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("ctn_587"), 
				array("admin", "project_admin", "invoice_manage"), // User Roles
				array(),  // Team Roles
				0,
				"",
				"jsonError"
			);
		}

		$items = $this->invoices_model->get_invoice_items_db($projectid);
		$html = $this->template->loadAjaxReturn("invoices/get_itemdb.php", array(
			"items" => $items,
			)
		);

		echo json_encode(array("success" => 1, "html" => $html));
		exit();
	}

	public function get_itemdb_item($itemid) 
	{
		$itemid = intval($itemid);
		$item = $this->invoices_model->get_invoice_item_db($itemid);
		if($item->num_rows() == 0) {
			$this->template->jsonError(lang("error_278"));
		}
		$item = $item->row();

		echo json_encode(array(
			"item_name" => $item->name,
			"item_desc" => $item->description,
			"item_price" => $item->price,
			"item_quantity" => $item->quantity
			)
		);
		exit();
	}

	public function add() 
	{
		$this->check_requirements(array("admin", "project_admin",
			 "invoice_manage", "invoice_worker"));
		$this->template->loadData("activeLink", 
			array("invoice" => array("general" => 1)));
		
		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "invoices_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, "(pr2.admin = 1 OR pr2.invoice = 1)");
		}

		$currencies = $this->invoices_model->get_currencies();
		$accounts = $this->invoices_model->get_all_paying_accounts();

		$last_invoice = $this->invoices_model
			->get_last_invoice();
		if ($last_invoice->num_rows() == 0) {
			$invoice_tmp_id = "invoice_0001";
		} else {
			$inv = $last_invoice->row();
			$invoice_tmp_id = $inv->invoice_id;
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

		// Get invoice themes
		$themes = $this->invoices_model->get_invoice_themes();

		$this->template->loadContent("invoices/add.php", array(
			"projects" => $projects,
			"currencies" => $currencies,
			"invoice_id" => $invoice_tmp_id,
			"accounts" => $accounts,
			"themes" => $themes
			)
		);
	}

	public function add_pro() 
	{
		$this->check_requirements(array("admin", "project_admin",
			 "invoice_manage", "invoice_worker"));
		$invoice_id = $this->common->nohtml($this->input->post("invoice_id"));
		$title = $this->common->nohtml($this->input->post("title"));
		$notes = $this->common->nohtml($this->input->post("notes"));
		$term_notes = $this->common->nohtml($this->input->post("term_notes"));
		$hidden_notes = $this->common->nohtml($this->input->post("hidden_notes"));

		$client_username = $this->common->nohtml($this->input->post("client_username"));
		$clientid = intval($this->input->post("clientid"));
		$guest_name = $this->common->nohtml($this->input->post("guest_name"));
		$guest_email = $this->common->nohtml($this->input->post("guest_email"));

		$projectid = intval($this->input->post("projectid"));
		$status = intval($this->input->post("status"));
		$currencyid = intval($this->input->post("currencyid"));
		$due_date = $this->common->nohtml($this->input->post("due_date"));
		
		$template = intval($this->input->post("template"));
		$finance = intval($this->input->post("finance"));
		$paying_accountid = intval($this->input->post("paying_accountid"));
		$themeid = intval($this->input->post("themeid"));


		if($paying_accountid == -1) {
			// Check valid Paying Account
			$account = $this->invoices_model->get_user_paying_account($this->user->info->ID);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
			$account = $account->row();
			$paying_accountid = $account->ID;
		} else {
			// Check valid Paying Account
			$account = $this->invoices_model->get_paying_account($paying_accountid);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
		}

		// Check no empty fields
		if(empty($invoice_id)) {
			$this->template->error(lang("error_124"));
		}
		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		// Check for valid theme
		$theme = $this->invoices_model->get_invoice_theme($themeid);
		if($theme->num_rows() == 0) {
			$this->template->error(lang("error_279"));
		}


		// Check the user has entered a valid client for the invoice.
		// 0 = no client selected
		// -1 = Guest Client
		// -2 = Enter username
		// > 0 = Selected user from dropdown list
		$userid = 0;
		if($clientid == 0) {
			$this->template->error(lang("error_277"));
		} elseif($clientid == -1) {
			// Check valid guest email (?)
			if(empty($guest_name)) {
				$this->template->error(lang("error_274"));
			}
			if(empty($guest_email)) {
				$this->template->error(lang("error_275"));
			}

			$this->load->helper('email');
			if (!valid_email($guest_email)) {
				$this->template->error(lang("error_19"));
			}
		} elseif($clientid == -2) {
			$client = $this->user_model->get_user_by_username($client_username);
			if($client->num_rows() == 0) {
				$this->template->error(lang("error_125"));
			}
			$client = $client->row();
			$userid = $client->ID;
		} elseif($clientid > 0) {
			// Check client is legit and from above project
			$client = $this->team_model->get_member_of_project($clientid, $projectid);
			if($client->num_rows() == 0) {
				$this->template->error(lang("error_273"));
			}
			$client = $client->row();
			$userid = $client->userid;
		}

		// If user selected a project to assign the invoice to, test they
		// have permission to use it
		if($projectid > 0) {
			// Project
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("ctn_587"), 
				array("admin", "project_admin", "invoice_manage"), // User Roles
				array("admin", "invoice"),  // Team Roles
				$project->ID
			);
		}


		// Valid Status check
		if($status < 1 || $status > 4) {
			$this->template->error(lang("error_122"));
		}

		// Valid Currency check
		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		// Make sure Due Date is of right format, otherwise set to 0.
		if(!empty($due_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $due_date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = 0;
		}

		// Loop through all the Invoice Items.
		// Make sure quantity and cost are > 0 and each item has a name
		// If item marked for saving, add to ItemDB for Invoice.
		$items = intval($this->input->post("items_count"));
		

		$sub_total=0;
		$count = 0;
		$invoice_items = array();
		for ($i=1;$i<=$items;$i++) {
			$quantity = floatval($this->input->post("item_quantity_" . $i));
			if ($quantity < 0) $this->template->error(lang("error_128"));
			$amount = floatval($this->input->post("item_price_" . $i));
			if ($amount < 0) $this->template->error(lang("error_129"));
			$name = $this->common->nohtml($this->input->post("item_name_" . $i));
			$desc = $this->common->nohtml($this->input->post("item_desc_" . $i));
			if(empty($name) && ($amount > 0 || $quantity > 0) ) {
				$this->template->error(lang("error_130"));
			}
			$save = intval($this->input->post("save_" . $i));
			$timerid = intval($this->input->post("item_timer_id_" . $i));
			if(!empty($name)) {
				$count++;
				$invoice_items[] = array(
					"name" => $name,
					"desc" => $desc,
					"amount" => $amount,
					"quantity" => $quantity,
					"save" => $save,
					"timerid" => $timerid
				);
				$sub_total += $amount*$quantity;
			}
		}
		$total = $sub_total;

		if($count == 0) {
			$this->template->error(lang("error_127"));
		}


		// Manage Invoice Tax
		// Add extra tax to total invoice bill based on user input %.
		$tax_name_1 = $this->common->nohtml($this->input->post("tax_name_1"));
		$tax_rate_1 = floatval($this->input->post("tax_rate_1"));
		$tax_name_2 = $this->common->nohtml($this->input->post("tax_name_2"));
		$tax_rate_2 = floatval($this->input->post("tax_rate_2"));

		if($tax_rate_1>0) {
			$extra = floatval($sub_total/100*$tax_rate_1);
			$total = $total + $extra;
		}
		if($tax_rate_2>0) {
			$extra = floatval($sub_total/100*$tax_rate_2);
			$total = $total + $extra;
		}

		// Invoice Hash for identifying the Invoice in URLs
		// Helps keep invoice's private
		$hash = sha1(rand(1,100000) . $title);

		// If the status of the invocie is set to paid, set the paid date to
		// today
		$time_date_paid = "";
		if($status == 2) {
			$time_date_paid = date("Y-m-d");
		}

		// Add invoice to the system
		$invoiceid = $this->invoices_model->add_invoice(array(
			"invoice_id" => $invoice_id,
			"title" => $title,
			"notes" => $notes,
			"term_notes" => $term_notes,
			"hidden_notes" => $hidden_notes,
			"themeid" => $themeid,
			"userid" => $this->user->info->ID,
			"status" => $status,
			"clientid" => $userid,
			"projectid" => $projectid,
			"currencyid" => $currencyid,
			"timestamp" => time(),
			"due_date" => $dd_timestamp,
			"tax_name_1" => $tax_name_1,
			"tax_rate_1" => $tax_rate_1,
			"tax_name_2" => $tax_name_2,
			"tax_rate_2" => $tax_rate_2,
			"total" => $total,
			"hash" => $hash,
			"template" => $template,
			"time_date" => date("Y-m-d"),
			"time_date_paid" => $time_date_paid,
			"guest_name" => $guest_name,
			"guest_email" => $guest_email,
			"paying_accountid" => $paying_accountid
			)
		);

		// Add items to invoice, save any that have been selected to be saved
		foreach($invoice_items as $item) {
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $item['name'],
				"quantity" => $item['quantity'],
				"amount" => $item['amount'],
				"description" => $item['desc']
				)
			);

			if($item['timerid'] > 0) {
				// Flag this timer as paid if user owns it | admin
				$timer = $this->time_model->get_timer($item['timerid']);
				if($timer->num_rows() > 0) {
					$timer = $timer->row();
					$error = "";
					if($timer->userid != $this->user->info->ID) {
						$error = $this->common->check_permissions(
							lang("error_221"), 
							array("admin", "project_admin", "time_manage"), // User Roles
							array("admin"), // Team Roles
							$timer->projectid,
							"", // Custom error message
							"jsonError",
							1 
						);
					}
					if(empty($error)) {
						// Flag it
						$this->time_model->update_timer($item['timerid'], array("status" => 1));
					}

				}
			}

			if($item['save']) {
				$this->invoices_model->add_invoice_item_db(array(
					"name" => $item['name'],
					"description" => $item['desc'],
					"price" => $item['amount'],
					"quantity" => $item['quantity'],
					"projectid" => $projectid
					)
				);
			}
		}
	
		// Send notification to CLIENT about new invoice
		if($userid > 0) {
			$this->user_model->increment_field($userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $userid,
				"url" => "invoices/view/" . $invoiceid . "/" . $hash,
				"timestamp" => time(),
				"message" => lang("ctn_1035"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"email" => $client->email,
				"username" => $client->username,
				"email_notification" => $client->email_notification
				)
			);
		}

		if($finance) {
			// Add

			$sd_timestamp = time();

			$this->finance_model->add_finance(array(
				"title" => $title,
				"notes" => $invoice_id,
				"categoryid" => 0,
				"projectid" => $projectid,
				"userid" => $this->user->info->ID,
				"amount" => $total,
				"timestamp" => $sd_timestamp,
				"month" => date("n",$sd_timestamp),
				"year" => date("Y",$sd_timestamp),
				"time_date" => date("Y-m-d",$sd_timestamp)
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1036") ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "invoices/view/" . $invoiceid . "/" . $hash
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_57"));
		if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) {
			redirect(site_url("invoices"));
		} else {
			redirect(site_url("invoices/client"));
		}

	}

	public function edit_invoice($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$this->template->loadData("activeLink", 
			array("invoice" => array("general" => 1)));

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "invoices_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, "(pr2.admin = 1 OR pr2.invoice = 1)");
		}

		$currencies = $this->invoices_model->get_currencies();

		$items = $this->invoices_model->get_invoice_items($invoice->ID);

		$accounts = $this->invoices_model->get_all_paying_accounts();

		$themes = $this->invoices_model->get_invoice_themes();

		$payments = $this->invoices_model->get_invoice_payments($invoice->ID);

		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);

		$this->template->loadContent("invoices/edit.php", array(
			"projects" => $projects,
			"currencies" => $currencies,
			"invoice" => $invoice,
			"items" => $items,
			"accounts" => $accounts,
			"themes" => $themes,
			"payments" => $payments,
			"payments_total" => $payments_total
			)
		);
	}

	public function edit_invoice_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$invoice_id = $this->common->nohtml($this->input->post("invoice_id"));
		$title = $this->common->nohtml($this->input->post("title"));
		$notes = $this->lib_filter->go($this->input->post("notes"));
		$term_notes = $this->common->nohtml($this->input->post("term_notes"));
		$hidden_notes = $this->common->nohtml($this->input->post("hidden_notes"));

		$client_username = $this->common->nohtml($this->input->post("client_username"));
		$clientid = intval($this->input->post("clientid"));
		$guest_name = $this->common->nohtml($this->input->post("guest_name"));
		$guest_email = $this->common->nohtml($this->input->post("guest_email"));

		$projectid = intval($this->input->post("projectid"));
		$status = intval($this->input->post("status"));
		$currencyid = intval($this->input->post("currencyid"));
		$due_date = $this->common->nohtml($this->input->post("due_date"));
		
		$paying_accountid = intval($this->input->post("paying_accountid"));
		$remind = intval($this->input->post("remind"));
		$themeid = intval($this->input->post("themeid"));

		if($paying_accountid == -1) {
			// Check valid Paying Account
			$account = $this->invoices_model->get_user_paying_account($this->user->info->ID);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
			$account = $account->row();
			$paying_accountid = $account->ID;
		} else {
			// Check valid Paying Account
			$account = $this->invoices_model->get_paying_account($paying_accountid);
			if($account->num_rows() == 0) {
				$this->template->error(lang("error_269"));
			}
		}

		// Check for valid theme
		$theme = $this->invoices_model->get_invoice_theme($themeid);
		if($theme->num_rows() == 0) {
			$this->template->error(lang("error_279"));
		}

		// Check no empty fields
		if(empty($invoice_id)) {
			$this->template->error(lang("error_124"));
		}
		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		// Check the user has entered a valid client for the invoice.
		// 0 = no client selected
		// -1 = Guest Client
		// -2 = Enter username
		// > 0 = Selected user from dropdown list
		$userid = 0;
		if($clientid == 0) {
			$this->template->error(lang("error_277"));
		} elseif($clientid == -1) {
			// Check valid guest email (?)
			if(empty($guest_name)) {
				$this->template->error(lang("error_274"));
			}
			if(empty($guest_email)) {
				$this->template->error(lang("error_275"));
			}

			$this->load->helper('email');
			if (!valid_email($guest_email)) {
				$this->template->error(lang("error_19"));
			}
		} elseif($clientid == -2) {
			$client = $this->user_model->get_user_by_username($client_username);
			if($client->num_rows() == 0) {
				$this->template->error(lang("error_125"));
			}
			$client = $client->row();
			$userid = $client->ID;
		} elseif($clientid > 0) {
			// Check client is legit and from above project
			$client = $this->team_model->get_member_of_project($clientid, $projectid);
			if($client->num_rows() == 0) {
				$this->template->error(lang("error_273"));
			}
			$client = $client->row();
			$userid = $client->userid;
		}

		// If user selected a project to assign the invoice to, test they
		// have permission to use it
		if($projectid > 0) {
			// Project
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("ctn_587"), 
				array("admin", "project_admin", "invoice_manage"), // User Roles
				array("admin", "invoice"),  // Team Roles
				$project->ID
			);
		}


		// Valid Status check
		if($status < 1 || $status > 4) {
			$this->template->error(lang("error_122"));
		}

		// Valid Currency check
		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		// Make sure Due Date is of right format, otherwise set to 0.
		if(!empty($due_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $due_date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = 0;
		}

		// Loop through all the Invoice Items.
		// Make sure quantity and cost are > 0 and each item has a name
		// If item marked for saving, add to ItemDB for Invoice.
		$items = intval($this->input->post("items_count"));

		$sub_total=0;
		$count = 0;
		$invoice_items = array();
		for ($i=1;$i<=$items;$i++) {
			$quantity = floatval($this->input->post("item_quantity_" . $i));
			if ($quantity < 0) $this->template->error(lang("error_128"));
			$amount = floatval($this->input->post("item_price_" . $i));
			if ($amount < 0) $this->template->error(lang("error_129"));
			$name = $this->common->nohtml($this->input->post("item_name_" . $i));
			$desc = $this->common->nohtml($this->input->post("item_desc_" . $i));
			$timerid = intval($this->input->post("item_timer_id_" . $i));
			if(empty($name) && ($amount > 0 || $quantity > 0) ) {
				$this->template->error(lang("error_130"));
			}
			$save = intval($this->input->post("save_" . $i));
			if(!empty($name)) {
				$count++;
				$invoice_items[] = array(
					"name" => $name,
					"desc" => $desc,
					"amount" => $amount,
					"quantity" => $quantity,
					"save" => $save,
					"timerid" => $timerid
				);
				echo "A: " . $amount . " x " . $quantity . "<br />";
				$sub_total += $amount*$quantity;
			}
		}
		$total = $sub_total;



		if($count == 0) {
			$this->template->error(lang("error_127"));
		}

		// Manage Invoice Tax
		// Add extra tax to total invoice bill based on user input %.
		$tax_name_1 = $this->common->nohtml($this->input->post("tax_name_1"));
		$tax_rate_1 = floatval($this->input->post("tax_rate_1"));
		$tax_name_2 = $this->common->nohtml($this->input->post("tax_name_2"));
		$tax_rate_2 = floatval($this->input->post("tax_rate_2"));

		if($tax_rate_1>0) {
			$extra = floatval($sub_total/100*$tax_rate_1);
			$total = $total + $extra;
		}
		if($tax_rate_2>0) {
			$extra = floatval($sub_total/100*$tax_rate_2);
			$total = $total + $extra;
		}

		// If the status of the invocie is set to paid, set the paid date to
		// today
		$time_date_paid = $invoice->time_date_paid;
		if($status == 2 && $invoice->status != 2) {
			$time_date_paid = date("Y-m-d");
		}

		// update invoice to the system
		$invoiceid = $id;
		$this->invoices_model->update_invoice($id, array(
			"invoice_id" => $invoice_id,
			"title" => $title,
			"notes" => $notes,
			"term_notes" => $term_notes,
			"hidden_notes" => $hidden_notes,
			"themeid" => $themeid,
			"userid" => $this->user->info->ID,
			"status" => $status,
			"clientid" => $userid,
			"projectid" => $projectid,
			"currencyid" => $currencyid,
			"due_date" => $dd_timestamp,
			"tax_name_1" => $tax_name_1,
			"tax_rate_1" => $tax_rate_1,
			"tax_name_2" => $tax_name_2,
			"tax_rate_2" => $tax_rate_2,
			"total" => $total,
			"time_date_paid" => $time_date_paid,
			"guest_name" => $guest_name,
			"guest_email" => $guest_email,
			"paying_accountid" => $paying_accountid
			)
		);

		// Delete old invoice items and readd them
		// Lazy method (lol) ;)
		$this->invoices_model->delete_invoice_items($id);

		// Add items to invoice, save any that have been selected to be saved
		foreach($invoice_items as $item) {
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $item['name'],
				"quantity" => $item['quantity'],
				"amount" => $item['amount'],
				"description" => $item['desc']
				)
			);

			if($item['timerid'] > 0) {
				// Flag this timer as paid if user owns it | admin
				$timer = $this->time_model->get_timer($item['timerid']);
				if($timer->num_rows() > 0) {
					$timer = $timer->row();
					$error = "";
					if($timer->userid != $this->user->info->ID) {
						$error = $this->common->check_permissions(
							lang("error_221"), 
							array("admin", "project_admin", "time_manage"), // User Roles
							array("admin"), // Team Roles
							$timer->projectid,
							"", // Custom error message
							"jsonError",
							1 
						);
					}
					if(empty($error)) {
						// Flag it
						$this->time_model->update_timer($item['timerid'], array("status" => 1));
					}

				}
			}

			if($item['save']) {
				$this->invoices_model->add_invoice_item_db(array(
					"name" => $item['name'],
					"description" => $item['desc'],
					"price" => $item['amount'],
					"quantity" => $item['quantity'],
					"projectid" => $projectid
					)
				);
			}
		}
	
		

		if($remind == 1 && $userid > 0) {
			$this->load->model("home_model");
			// Send notification
			$this->user_model->increment_field($client->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" =>$client->ID,
				"url" => "invoices/view/" . $invoiceid . "/" . $invoice->hash,
				"timestamp" => time(),
				"message" => lang("ctn_1037"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"email" => $client->email,
				"username" => $client->username,
				"email_notification" => $client->email_notification
				)
			);

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("invoice_reminder", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $client->username,
				"[SITE_URL]" => site_url(),
				"[INVOICE_URL]" => 
					site_url("invoices/view/" . $invoice->ID . "/" . $invoice->hash),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $client->email);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1038") ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "invoices/view/" . $invoiceid . "/" . $invoice->hash
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_58"));
		redirect(site_url("invoices"));
	}


	public function delete_invoice($id, $hash) 
	{
		$this->check_requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$this->invoices_model->delete_invoice($id);
		$this->invoices_model->delete_invoice_items($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1039") ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "invoices"
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_59"));
		redirect(site_url("invoices"));
	}

	public function add_invoice_payment($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$amount = floatval($this->input->post("amount"));
		$type = intval($this->input->post("type"));
		$email = $this->common->nohtml($this->input->post("email"));
		$date = $this->common->nohtml($this->input->post("date"));
		$notes = $this->common->nohtml($this->input->post("notes"));


		if(!empty($date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = time();
		}

		

		$this->invoices_model->add_invoice_payment(array(
			"invoiceid" => $invoice->ID,
			"amount" => $amount,
			"processor" => $type,
			"timestamp" => $dd_timestamp,
			"userid" => $this->user->info->ID,
			"email" => $email,
			"notes" => $notes
			)
		);

		// Check total amount is greater than the Invoice total cost
		// If it is, set Invoice status to paid
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
		if($payments_total >= $invoice->total) {
			$this->invoices_model->update_invoice($invoice->ID, array(
				"status" => 2
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

		$this->session->set_flashdata("globalmsg", lang("success_150"));
		redirect(site_url("invoices/edit_invoice/" . $invoice->ID . "?tab=4#invoice-bottom" ));
	}

	public function delete_invoice_payment($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$this->check_requirements();
		$id = intval($id);
		$payment = $this->invoices_model->get_invoice_payment($id);
		if($payment->num_rows() == 0) {
			$this->template->error(lang("error_280"));
		}
		$payment = $payment->row();

		$invoice = $this->invoices_model->get_invoice($payment->invoiceid);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$this->invoices_model->delete_invoice_payment($id);


		// Check total amount is greater than the Invoice total cost
		// If it is, set Invoice status to paid
		$payments_total = $this->invoices_model->get_invoice_payments_total($payment->invoiceid);
		
		if($payments_total >= $invoice->total) {
			$this->invoices_model->update_invoice($payment->invoiceid, array(
				"status" => 2
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
		$this->session->set_flashdata("globalmsg", lang("success_151"));
		redirect(site_url("invoices/edit_invoice/" . $payment->invoiceid . "?tab=4#invoice-bottom"));
	}

	public function view($id, $hash) 
	{
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		if($invoice->hash != $hash) {
			$this->template->error(lang("error_6"));
		}

		$items = $this->invoices_model->get_invoice_items($id);
		$settings = $this->invoices_model->get_invoice_settings();
		$settings = $settings->row();

		$stripe = null;
		$checkout2 = null;
		$paypal = null;

		if($settings->enable_stripe) {
			if(!empty($invoice->stripe_secret_key) && !empty($invoice->stripe_publishable_key)) {
				$stripe = true;
			}
		}

		if($settings->enable_paypal) { 
			if(!empty($invoice->paypal_email)) {
				$paypal = true;
			}
		}

		if($settings->enable_checkout2) {
			if(!empty($invoice->checkout2_account_number) && !empty($invoice->checkout2_secret_key)) {
				$checkout2 = true;
			}
		}


		if($invoice->clientid == 0) {
			$invoice->client_first_name = $invoice->guest_name;
			$invoice->client_last_name = "";
			$invoice->client_address_1 = ""; 
			$invoice->client_address_2 = "";
			$invoice->client_city = "";
			$invoice->client_state = "";
			$invoice->client_zipcode = "";
			$invoice->client_country = "";
			$invoice->client_username = lang("ctn_819");
			$invoice->client_email = $invoice->guest_email;
		}

		// Calulate amount left to pay
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
		$payments = $this->invoices_model->get_invoice_payments($invoice->ID);


		$this->template->loadAjax("invoices/themes/".$invoice->theme_file.".php", array(
			"invoice" => $invoice,
			"items" => $items,
			"settings" => $settings,
			"payments_total" => $payments_total,
			"payments" => $payments,
			"stripe" => $stripe,
			"paypal" => $paypal,
			"checkout2" => $checkout2
			), 1
		);
	}

	public function get_pdf($id, $hash) 
	{
		$id = intval($id);
		$invoice = $this->invoices_model->get_invoice($id);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		if($invoice->hash != $hash) {
			$this->template->error(lang("error_6"));
		}

		$items = $this->invoices_model->get_invoice_items($id);
		$settings = $this->invoices_model->get_invoice_settings();
		$settings = $settings->row();

		if($invoice->clientid == 0) {
			$invoice->client_first_name = $invoice->guest_name;
			$invoice->client_last_name = "";
			$invoice->client_address_1 = ""; 
			$invoice->client_address_2 = "";
			$invoice->client_city = "";
			$invoice->client_state = "";
			$invoice->client_zipcode = "";
			$invoice->client_country = "";
			$invoice->client_username = lang("ctn_819");
			$invoice->client_email = $invoice->guest_email;
		}

		// Calulate amount left to pay
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
		$payments = $this->invoices_model->get_invoice_payments($invoice->ID);

		ob_start();
		$this->template->loadAjax("invoices/themes/".$invoice->theme_file."_pdf.php", array(
			"invoice" => $invoice,
			"items" => $items,
			"settings" => $settings,
			"payments" => $payments,
			"payments_total" => $payments_total
			)
		);
		$out = ob_get_contents();
		ob_end_clean();
		require_once APPPATH . 'third_party/mpdf/vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf(array(
			"mode" => "UTF-8"
			)
		);
		$stylesheet = file_get_contents('styles/invoice2.css');
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($out);
		$mpdf->Output();
	}

	public function client() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage", "invoice_client"));
		$this->template->loadData("activeLink", 
			array("invoice" => array("client" => 1)));

		$this->template->loadContent("invoices/index.php", array(
			"page" => "client"
			)
		);
	}

	public function paying_accounts() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));

		$this->template->loadData("activeLink", 
			array("invoice" => array("pay" => 1)));

		$this->template->loadContent("invoices/paying_accounts.php", array(
			)
		);
	}

	public function paying_account_page() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$this->load->library("datatables");

		$this->datatables->set_default_order("paying_accounts.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"paying_accounts.name" => 0
				 ),
				 1 => array(
				 	"paying_accounts.paypal_email" => 0
				 ),
			)
		);

		$this->datatables->set_total_rows(
			$this->invoices_model
				->get_total_paying_accounts()
		);
		$accounts = $this->invoices_model->get_paying_accounts($this->datatables);
		

		foreach($accounts->result() as $r) {

			$this->datatables->data[] = array(
				$r->name,
				$r->paypal_email,
				$r->address_line_1,
				$r->country,
				'<a href="'.site_url("invoices/edit_paying_account/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("invoices/delete_paying_account/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_payment_account() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$name = $this->common->nohtml($this->input->post("name"));

		if(empty($name)) {
			$this->template->error(lang("error_81"));
		}

		$paypal_email = $this->common->nohtml($this->input->post("paypal_email"));
		$stripe_secret_key = $this->common->nohtml($this->input->post("stripe_secret_key"));
		$stripe_publishable_key = $this->common->nohtml($this->input->post("stripe_publishable_key"));
		$checkout2_account_number = $this->common->nohtml($this->input->post("checkout2_account_number"));
		$checkout2_secret_key = $this->common->nohtml($this->input->post("checkout2_secret_key"));
		$email = $this->common->nohtml($this->input->post("email"));

		$address_line_1 = $this->common->nohtml($this->input->post("address_line_1"));
		$address_line_2 = $this->common->nohtml($this->input->post("address_line_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zip = $this->common->nohtml($this->input->post("zip"));
		$country = $this->common->nohtml($this->input->post("country"));

		$first_name = $this->common->nohtml($this->input->post("first_name"));
		$last_name = $this->common->nohtml($this->input->post("last_name"));

		$this->invoices_model->add_paying_account(array(
			"name" => $name,
			"email" => $email,
			"paypal_email" => $paypal_email,
			"stripe_secret_key" => $stripe_secret_key,
			"stripe_publishable_key" => $stripe_publishable_key,
			"checkout2_account_number" => $checkout2_account_number,
			"checkout2_secret_key" => $checkout2_secret_key,
			"address_line_1" => $address_line_1,
			"address_line_2" => $address_line_2,
			"city" => $city,
			"state" => $state,
			"zip" => $zip,
			"country" => $country,
			"first_name" => $first_name,
			"last_name" => $last_name
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_136"));
		redirect(site_url("invoices/paying_accounts"));
	}

	public function delete_paying_account($id, $hash) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$account = $this->invoices_model->get_paying_account($id);
		if($account->num_rows() == 0) {
			$this->template->error(lang("error_124"));
		}

		$this->invoices_model->delete_paying_account($id);
		$this->session->set_flashdata("globalmsg", lang("success_137"));
		redirect(site_url("invoices/paying_accounts"));
	}

	public function edit_paying_account($id) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$id = intval($id);
		$account = $this->invoices_model->get_paying_account($id);
		if($account->num_rows() == 0) {
			$this->template->error(lang("error_124"));
		}
		$account = $account->row();

		$this->template->loadData("activeLink", 
			array("invoice" => array("pay" => 1)));

		$this->template->loadContent("invoices/edit_paying_account.php", array(
			"account" => $account
			)
		);
	}

	public function edit_paying_account_pro($id) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$id = intval($id);
		$account = $this->invoices_model->get_paying_account($id);
		if($account->num_rows() == 0) {
			$this->template->error(lang("error_124"));
		}
		$account = $account->row();

		$this->template->loadData("activeLink", 
			array("invoice" => array("pay" => 1)));

		$name = $this->common->nohtml($this->input->post("name"));

		if(empty($name)) {
			$this->template->error(lang("error_81"));
		}

		$paypal_email = $this->common->nohtml($this->input->post("paypal_email"));
		$stripe_secret_key = $this->common->nohtml($this->input->post("stripe_secret_key"));
		$stripe_publishable_key = $this->common->nohtml($this->input->post("stripe_publishable_key"));
		$checkout2_account_number = $this->common->nohtml($this->input->post("checkout2_account_number"));
		$checkout2_secret_key = $this->common->nohtml($this->input->post("checkout2_secret_key"));
		$email = $this->common->nohtml($this->input->post("email"));

		$address_line_1 = $this->common->nohtml($this->input->post("address_line_1"));
		$address_line_2 = $this->common->nohtml($this->input->post("address_line_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zip = $this->common->nohtml($this->input->post("zip"));
		$country = $this->common->nohtml($this->input->post("country"));

		$first_name = $this->common->nohtml($this->input->post("first_name"));
		$last_name = $this->common->nohtml($this->input->post("last_name"));

		$this->invoices_model->update_paying_account($id, array(
			"name" => $name,
			"email" => $email,
			"paypal_email" => $paypal_email,
			"stripe_secret_key" => $stripe_secret_key,
			"stripe_publishable_key" => $stripe_publishable_key,
			"checkout2_account_number" => $checkout2_account_number,
			"checkout2_secret_key" => $checkout2_secret_key,
			"address_line_1" => $address_line_1,
			"address_line_2" => $address_line_2,
			"city" => $city,
			"state" => $state,
			"zip" => $zip,
			"country" => $country,
			"first_name" => $first_name,
			"last_name" => $last_name
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_138"));
		redirect(site_url("invoices/paying_accounts"));
	}

	public function edit_invoice_payment($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$payment = $this->invoices_model->get_invoice_payment($id);
		if($payment->num_rows() == 0) {
			$this->template->error(lang("error_280"));
		}
		$payment = $payment->row();

		$invoice = $this->invoices_model->get_invoice($payment->invoiceid);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$this->template->loadAjax("invoices/edit_invoice_payment.php", array(
			"payment" => $payment,
			"invoice" => $invoice
			)
		);
	}

	public function edit_invoice_payment_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$payment = $this->invoices_model->get_invoice_payment($id);
		if($payment->num_rows() == 0) {
			$this->template->error(lang("error_280"));
		}
		$payment = $payment->row();

		$invoice = $this->invoices_model->get_invoice($payment->invoiceid);
		if($invoice->num_rows() == 0) {
			$this->template->error(lang("error_131"));
		}

		$invoice = $invoice->row();

		$amount = floatval($this->input->post("amount"));
		$type = intval($this->input->post("type"));
		$email = $this->common->nohtml($this->input->post("email"));
		$date = $this->common->nohtml($this->input->post("date"));
		$notes = $this->common->nohtml($this->input->post("notes"));


		if(!empty($date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = time();
		}

		

		$this->invoices_model->update_invoice_payment($id, array(
			"amount" => $amount,
			"processor" => $type,
			"timestamp" => $dd_timestamp,
			"userid" => $this->user->info->ID,
			"email" => $email,
			"notes" => $notes
			)
		);

		// Check total amount is greater than the Invoice total cost
		// If it is, set Invoice status to paid
		$payments_total = $this->invoices_model->get_invoice_payments_total($invoice->ID);
		if($payments_total >= $invoice->total) {
			$this->invoices_model->update_invoice($invoice->ID, array(
				"status" => 2
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

		$this->session->set_flashdata("globalmsg", lang("success_152"));
		redirect(site_url("invoices/edit_invoice/" . $invoice->ID . "?tab=4#invoice-bottom" ));
	}

	public function items() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		
		$this->template->loadData("activeLink", 
			array("invoice" => array("items" => 1)));

		$projects = $this->projects_model->get_all_active_projects();

		$this->template->loadContent("invoices/items.php", array(
			"projects" => $projects
			)
		);
	}

	public function item_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("invoice_item_db.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"invoice_item_db.name" => 0
				 ),
				 2 => array(
				 	"invoice_item_db.price" => 0
				 ),
				 3 => array(
				 	"invoice_item_db.quantity" => 0
				 ),
				 4 => array(
				 	"projects.name" => 0
				 )
			)
		);

		$this->check_requirements();
		$this->datatables->set_total_rows(
			$this->invoices_model->get_item_db_total()
		);

		$items = $this->invoices_model->get_item_db($this->datatables);

		foreach($items->result() as $r) {
			

			if(!isset($r->projectname)) {
				$projectname = lang("ctn_46");
			} else {
				$projectname = $r->projectname;
			}

		
			$this->datatables->data[] = array(
				$r->name,
				$r->description,
				$r->price,
				$r->quantity,
				$projectname,
				'<a href="'.site_url("invoices/edit_item/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("invoices/delete_item/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="bottom" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'

			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_item() 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$price = floatval($this->input->post("price"));
		$quantity = floatval($this->input->post("quantity"));
		$projectid = intval($this->input->post("projectid"));

		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		$this->invoices_model->add_invoice_item_db(array(
			"name" => $name,
			"description" => $desc,
			"price" => $price,
			"quantity" => $quantity,
			"projectid" => $projectid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_153"));
		redirect(site_url("invoices/items"));
	}

	public function edit_item($id) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$id = intval($id);
		$item = $this->invoices_model->get_item_db_single($id);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_278"));
		}
		$item = $item->row();

		$this->template->loadData("activeLink", 
			array("invoice" => array("items" => 1)));

		$projects = $this->projects_model->get_all_active_projects();

		$this->template->loadContent("invoices/edit_item.php", array(
			"projects" => $projects,
			"item" => $item
			)
		);
	}

	public function edit_item_pro($id) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		$id = intval($id);
		$item = $this->invoices_model->get_item_db_single($id);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_278"));
		}
		$item = $item->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$price = floatval($this->input->post("price"));
		$quantity = floatval($this->input->post("quantity"));
		$projectid = intval($this->input->post("projectid"));

		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		$this->invoices_model->update_item_db($id, array(
			"name" => $name,
			"description" => $desc,
			"price" => $price,
			"quantity" => $quantity,
			"projectid" => $projectid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_154"));
		redirect(site_url("invoices/items"));
	}

	public function delete_item($id, $hash) 
	{
		$this->check_requirements(array("admin", "project_admin", 
			"invoice_manage"));
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$item = $this->invoices_model->get_item_db_single($id);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_278"));
		}

		$this->invoices_model->delete_item_db($id);
		$this->session->set_flashdata("globalmsg", lang("success_155"));
		redirect(site_url("invoices/items"));
	}

}
?>