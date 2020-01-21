<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leads extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("leads_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");
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
			lang("ctn_1048"), 
			array("admin", "project_admin", "lead_manage"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("lead" => array("general" => 1)));

		$this->template->loadContent("leads/index.php", array(
			"page" => "index"
			)
		);
	}

	public function your() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("lead" => array("your" => 1)));

		$this->template->loadContent("leads/index.php", array(
			"page" => "your"
			)
		);
	}

	public function lead_page($page) 
	{
		$this->check_requirements();
		
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_leads.timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"lead_forms.title" => 0
				 ),
				 3 => array(
				 	"user_leads.status" => 0
				 ),
				 4 => array(
				 	"user_leads.timestamp" => 0
				 )
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->leads_model->get_user_leads_total()
			);

			$leads = $this->leads_model->get_user_leads($this->datatables);
		} elseif($page == "your") {
			$this->datatables->set_total_rows(
				$this->leads_model->get_user_leads_total_assigned($this->user->info->ID)
			);

			$leads = $this->leads_model->get_user_leads_assigned($this->datatables, $this->user->info->ID);
		}

		foreach($leads->result() as $r) {

			if(isset($r->status)) {
				$status = $r->status;
			} else {
				$status = lang("ctn_46");
			}

			if(isset($r->source)) {
				$source = $r->source;
			} else {
				$source = lang("ctn_46");
			}
			
			if(!isset($r->client_username)) {
				$client = "<span class='small-text'>" . lang("ctn_819") . ": " .$r->first_name . " " . $r->last_name . "</span>";
			} else {
				$client = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp, "first_name" => $r->client_first_name, "last_name" => $r->client_last_name));
			}
			$this->datatables->data[] = array(
				$r->title,
				$client,
				$this->common->get_user_display(array("username" => $r->assigned_username, "avatar" => $r->assigned_avatar, "online_timestamp" => $r->assigned_online_timestamp, "first_name" => $r->assigned_first_name, "last_name" => $r->assigned_last_name)),
				"<label class='label label-round'>" . $status . "</label>",
				$source,
				date($this->settings->info->date_format, $r->timestamp),
				$r->IP,
				'<a href="'.site_url("leads/view_lead/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("leads/edit_lead/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("leads/delete_lead/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'" onclick="return confirm(\''.lang("ctn_317").'\')"><span class="glyphicon glyphicon-trash"></span></a>'

				);
		}
		echo json_encode($this->datatables->process());
	}

	public function view_lead($id, $page=0) 
	{
		$this->check_requirements();
		$page = intval($page);
		$this->template->loadData("activeLink", 
			array("lead" => array("general" => 1)));
		
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}
		$lead = $lead->row();

		$fields = $this->leads_model->get_lead_fields($id, $lead->formid);

		$cfields = $this->leads_model->get_user_custom_field($id);

		$notes = $this->leads_model->get_lead_notes($id, $page);

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("leads/view_lead/" . $id);
		$config['total_rows'] = $this->leads_model
			->get_total_lead_notes($id);
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");
		$this->pagination->initialize($config);

		$this->template->loadContent("leads/view_lead.php", array(
			"lead" => $lead,
			"fields" => $fields,
			"cfields" => $cfields,
			"notes" => $notes
			)
		);
	}

	public function edit_lead($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("lead" => array("general" => 1)));
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}
		$lead = $lead->row();

		$fields = $this->leads_model->get_lead_fields($id, $lead->formid);
		$cfields = $this->leads_model->get_user_custom_field($id);

		$statuses = $this->leads_model->get_statuses();
		$sources = $this->leads_model->get_sources();

		$this->template->loadContent("leads/edit_lead.php", array(
			"lead" => $lead,
			"fields" => $fields,
			"cfields" => $cfields,
			"statuses" => $statuses,
			"sources" => $sources
			)
		);
	}

	public function edit_lead_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}
		$lead = $lead->row();

		$client_username = $this->common->nohtml($this->input->post("username"));
		$assigned_username = $this->common->nohtml($this->input->post("assigned_username"));

		$statusid = intval($this->input->post("statusid"));
		$sourceid = intval($this->input->post("sourceid"));

		$status = $this->leads_model->get_status($statusid);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_164"));
		}

		$source = $this->leads_model->get_source($sourceid);
		if($source->num_rows() == 0) {
			$this->template->error(lang("error_257"));
		}

		$clientid = 0;
		if(!empty($client_username)) {
			$user = $this->user_model->get_user_by_username($client_username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_258"));
			}
			$user = $user->row();
			$clientid = $user->ID;
		}

		$assignedid = 0;
		if(!empty($assigned_username)) {
			$user = $this->user_model->get_user_by_username($assigned_username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_259"));
			}
			$user = $user->row();
			$assignedid = $user->ID;
		}

		$first_name = $this->common->nohtml($this->input->post("first_name"));
		$last_name = $this->common->nohtml($this->input->post("last_name"));
		$address_1 = $this->common->nohtml($this->input->post("address_1"));
		$address_2 = $this->common->nohtml($this->input->post("address_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zipcode = $this->common->nohtml($this->input->post("zipcode"));
		$country = $this->common->nohtml($this->input->post("country"));
		$email = $this->common->nohtml($this->input->post("email"));

		// User Custom Fields
		$cfields = $this->user_model->get_custom_fields(array("leads"=>1));

		$fields = $this->leads_model->get_form_fields($lead->formid);

		// Process fields
		$canswers = array();
		foreach($cfields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$fail = lang("error_158") . $r->name;
					}
					if($flag) {
						$canswers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$fail = lang("error_158") . $r->name;
				}
				if($flag) {
					$canswers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
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
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
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
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
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
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		// Update entry
		$this->leads_model->update_user_lead($id, array(
			"userid" => $clientid,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"address_1" => $address_1,
			"address_2" => $address_2,
			"city" => $city,
			"state" => $state,
			"zipcode" => $zipcode,
			"country" => $country,
			"email" => $email,
			"assignedid" => $assignedid,
			"statusid" => $statusid,
			"sourceid" => $sourceid
			)
		);


		// Update User Lead Fields
		$this->leads_model->delete_user_lead_custom_fields($id);

		// Lead User Custom Fields
		foreach($canswers as $answer) {
			$this->leads_model->add_user_custom_field(array(
				"leadid" => $id,
				"fieldid" => $answer['fieldid'],
				"value" => $answer['answer']
				)
			);
		}

		// Update form Fields
		$this->leads_model->delete_user_lead_fields($id);

		foreach($answers as $a) {
			$this->leads_model->add_user_lead_answer(array(
				"leadid" => $id,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer']
				)
			);
		}
		
		$this->session->set_flashdata("globalmsg", lang("success_72"));
		redirect(site_url("leads"));
	}

	public function delete_lead($id, $hash) 
	{
		$this->check_requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_154"));
		}
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}

		$this->leads_model->delete_user_lead($id);
		$this->session->set_flashdata("globalmsg", lang("success_73"));
		redirect(site_url("leads"));
	}

	public function forms($page=0) 
	{
		$this->check_requirements();
		$page = intval($page);
		$this->template->loadData("activeLink", 
			array("lead" => array("forms" => 1)));

		$forms = $this->leads_model->get_lead_forms($page);

		$this->template->loadContent("leads/forms.php", array(
			"forms" => $forms
			)
		);
	}

	public function add_form() 
	{
		$this->check_requirements();
		$this->template->loadExternal(
			'<script src="' . base_url() . 'scripts/custom/leads.js">
			</script>'
		);
		$this->template->loadData("activeLink", 
			array("lead" => array("forms" => 1)));

		$statuses = $this->leads_model->get_statuses();
		$sources = $this->leads_model->get_sources();

		$this->template->loadContent("leads/add_form.php", array(
			"statuses" => $statuses,
			"sources" => $sources
			)
		);
	}

	public function add_form_pro() 
	{
		$this->check_requirements();
		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$collect_user = intval($this->input->post("collect_user"));

		$statusid = intval($this->input->post("statusid"));
		$sourceid = intval($this->input->post("sourceid"));

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
			$this->template->error(lang("error_106"));
		}

		$fields = array();
		$field_count = intval($this->input->post("field_count"));
		for($i=1;$i<=$field_count;$i++) {
			$ftitle = $this->common->nohtml($this->input->post("field_title_" . $i));
			$ftype = intval($this->input->post("field_type_" . $i));
			$frequired = intval($this->input->post("field_require_" . $i));
			$fdesc = $this->common->nohtml($this->input->post("field_desc_" . $i));
			$foptions = $this->common->nohtml($this->input->post("field_options_" . $i));

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions
				);
			}
		}

		$formid = $this->leads_model->add_lead_form(array(
			"title" => $title,
			"welcome" => $welcome,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"assignedid" => $userid,
			"collect_user" => $collect_user,
			"default_statusid" => $statusid,
			"default_sourceid" => $sourceid
			)
		);

		foreach($fields as $r) {
			$this->leads_model->add_field(array(
				"formid" => $formid,
				"title" => $r['title'],
				"type" => $r['type'],
				"required" => $r['required'],
				"description" => $r['desc'],
				"options" => $r['options']
				)
			);
		}

		$this->session->set_flashdata("globalmsg", 
			lang("success_74"));
		redirect(site_url("leads/forms"));
	}

	public function delete_form($id, $hash) 
	{
		$this->check_requirements();
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}

		$this->leads_model->delete_lead_form($id);
		$this->session->set_flashdata("globalmsg", 
			lang("success_75"));
		redirect(site_url("leads/forms"));
	}

	public function edit_form($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("lead" => array("forms" => 1)));
		$this->template->loadExternal(
			'<script src="' . base_url() . 'scripts/custom/leads.js">
			</script>'
		);
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}
		$form = $form->row();

		$fields = $this->leads_model->get_form_fields($form->ID);

		$statuses = $this->leads_model->get_statuses();
		$sources = $this->leads_model->get_sources();

		$this->template->loadContent("leads/edit_form.php", array(
			"form" => $form,
			"fields" => $fields,
			"statuses" => $statuses,
			"sources" => $sources
			)
		);
	}

	public function edit_form_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}
		$form = $form->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$collect_user = intval($this->input->post("collect_user"));

		$statusid = intval($this->input->post("statusid"));
		$sourceid = intval($this->input->post("sourceid"));

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
			$this->template->error(lang("error_106"));
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

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions,
					"fid" => $fid
				);
			}
		}

		$fields_r = $this->leads_model->get_form_fields($id);
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
				$this->leads_model->delete_form_field($r->ID);
			}
		}

		$this->leads_model->update_lead_form($id, array(
			"title" => $title,
			"welcome" => $welcome,
			"assignedid" => $userid,
			"collect_user" => $collect_user,
			"default_sourceid" => $sourceid,
			"default_statusid" => $statusid
			)
		);

		foreach($fields as $r) {
			if($r['fid'] > 0) {
				$this->leads_model->update_form_field($r['fid'], array(
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options']
					)
				);
			} else {
				$this->leads_model->add_field(array(
					"formid" => $id,
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options']
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", 
			lang("success_76"));
		redirect(site_url("leads/forms"));

	}

	public function add_client_pro($id) 
	{
		$this->load->model("register_model");
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}
		$lead = $lead->row();

		$email = $this->common->nohtml($this->input->post("email"));
		$username = $this->common->nohtml($this->input->post("username"));
		$first_name = $this->common->nohtml($this->input->post("first_name"));
		$last_name = $this->common->nohtml($this->input->post("last_name"));
		$address_1 = $this->common->nohtml($this->input->post("address_1"));
		$address_2 = $this->common->nohtml($this->input->post("address_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zipcode = $this->common->nohtml($this->input->post("zipcode"));
		$country = $this->common->nohtml($this->input->post("country"));

		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$pass2 = $this->common->nohtml(
			$this->input->post("password2", true));

		$cfields = $this->leads_model->get_user_custom_field($id);

		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if (!$this->register_model->check_username_is_free($username)) {
			 $this->template->error(lang("error_16"));
		}

		if ($pass != $pass2) $this->template->error(lang("error_22"));

		if (strlen($pass) <= 5) {
			 $this->template->error(lang("error_17"));
		}

		$this->load->helper('email');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if (!$this->register_model->checkEmailIsFree($email)) {
			 $this->template->error(lang("error_20"));
		}

		// Process fields
		$fail ="";
		$canswers = array();
		foreach($cfields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				$canswers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$fail = lang("error_158") . $r->name;
					}
					if($flag) {
						$canswers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$fail = lang("error_158") . $r->name;
				}
				if($flag) {
					$canswers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		if(!empty($fail)) {
			$this->template->error($fail);
		}

		$pass = $this->common->encrypt($pass);
		$userid = $this->register_model->add_user(array(
			"username" => $username,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"password" => $pass,
			"user_role" => $this->settings->info->client_user_role,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"joined" => time(),
			"joined_date" => date("n-Y"),
			"address_1" => $address_1,
			"address_2" => $address_2,
			"city" => $city,
			"state" => $state,
			"zipcode" => $zipcode,
			"country" => $country
			)
		);

		// Custom fields
		// Add Custom Fields data
		foreach($canswers as $answer) {
			$this->user_model->add_custom_field(array(
				"userid" => $userid,
				"fieldid" => $answer['fieldid'],
				"value" => $answer['answer']
				)
			);
		}

		$this->leads_model->update_user_lead($id, array("user_added" => 1));

		$this->session->set_flashdata("globalmsg", lang("success_139"));
		redirect(site_url("leads/view_lead/" . $id));
	}

	public function view($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("lead" => array("forms" => 1)));
		
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}

		$form = $form->row();

		$fields = $this->leads_model->get_form_fields($form->ID);

		// Custom User Fields
		$cfields = $this->user_model->get_custom_fields(array("leads"=>1));

		$statuses = $this->leads_model->get_statuses();
		$sources = $this->leads_model->get_sources();

		$this->template->loadContent("leads/view_form.php", array(
			"form" => $form,
			"fields" => $fields,
			"cfields" => $cfields,
			"statuses" => $statuses,
			"sources" => $sources
			)
		);
	}

	public function manage() 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("lead" => array("manage" => 1)));

		$statuses = $this->leads_model->get_statuses();
		$sources = $this->leads_model->get_sources();
		
		$this->template->loadContent("leads/manage.php", array(
			"statuses" => $statuses,
			"sources" => $sources
			)
		);
	}

	public function add_status()
	{
		$name = $this->common->nohtml($this->input->post("name"));
		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}
		$this->leads_model->add_status(array(
			"name" => $name
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_140"));
		redirect(site_url("leads/manage"));
	}

	public function delete_status($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$status = $this->leads_model->get_status($id);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_164"));
		}

		$this->leads_model->delete_status($id);
		$this->session->set_flashdata("globalmsg", lang("success_141"));
		redirect(site_url("leads/manage"));
	}

	public function add_source()
	{
		$name = $this->common->nohtml($this->input->post("name"));
		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}
		$this->leads_model->add_source(array(
			"name" => $name
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_142"));
		redirect(site_url("leads/manage"));
	}

	public function delete_source($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$source = $this->leads_model->get_source($id);
		if($source->num_rows() == 0) {
			$this->template->error(lang("error_257"));
		}

		$this->leads_model->delete_source($id);
		$this->session->set_flashdata("globalmsg", lang("success_143"));
		redirect(site_url("leads/manage"));
	}

	public function view_form_full($id) 
	{
		$this->template->loadData("activeLink", 
			array("lead" => array("forms" => 1)));
		
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}

		$form = $form->row();

		$fields = $this->leads_model->get_form_fields($form->ID);

		// Custom User Fields
		$cfields = $this->user_model->get_custom_fields(array("leads"=>1));

		$this->template->loadAjax("leads/view_form_full.php", array(
			"form" => $form,
			"fields" => $fields,
			"cfields" => $cfields
			),1
		);
	}

	public function process_form($id, $guest=0) 
	{
		if($guest) {
			$this->template->error_hack = 1;
		}
		$id = intval($id);
		$form = $this->leads_model->get_lead_form($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_157"));
		}

		$form = $form->row();

		$fields = $this->leads_model->get_form_fields($form->ID);	

		$admin_check = intval($this->input->post("admin_check"));

		$clientid = 0;
		$assignedid = 0;

		$userid = 0;

		if($admin_check) {
			$this->check_requirements();
			$client_username = $this->common->nohtml($this->input->post("username"));
			$assigned_username = $this->common->nohtml($this->input->post("assigned_username"));
			$statusid = intval($this->input->post("statusid"));
			$sourceid = intval($this->input->post("sourceid"));

			$status = $this->leads_model->get_status($statusid);
			if($status->num_rows() == 0) {
				$this->template->error(lang("error_164"));
			}
			$form->default_statusid = $statusid;

			$source = $this->leads_model->get_source($sourceid);
			if($source->num_rows() == 0) {
				$this->template->error(lang("error_257"));
			}
			$form->default_sourceid = $sourceid;

			if(!empty($client_username)) {
				$user = $this->user_model->get_user_by_username($client_username);
				if($user->num_rows() == 0) {
					$this->template->error(lang("error_258"));
				}
				$user = $user->row();
				$userid = $user->ID;
			}

			if(!empty($assigned_username)) {
				$user = $this->user_model->get_user_by_username($assigned_username);
				if($user->num_rows() == 0) {
					$this->template->error(lang("error_259"));
				}
				$user = $user->row();
				$form->assignedid = $user->ID;
			}
		}

		$canswers = array();

		if($form->collect_user) {
			$first_name = $this->common->nohtml($this->input->post("first_name"));
			$last_name = $this->common->nohtml($this->input->post("last_name"));
			$address_1 = $this->common->nohtml($this->input->post("address_1"));
			$address_2 = $this->common->nohtml($this->input->post("address_2"));
			$city = $this->common->nohtml($this->input->post("city"));
			$state = $this->common->nohtml($this->input->post("state"));
			$zipcode = $this->common->nohtml($this->input->post("zipcode"));
			$country = $this->common->nohtml($this->input->post("country"));
			$email = $this->common->nohtml($this->input->post("email"));

			// Validate
			$this->load->helper('email');
			if (!valid_email($email)) {
				$this->template->error(lang("error_243"));
			}

			if(empty($first_name) || empty($last_name)) {
				$this->template->error(lang("error_260"));
			}

			// Custom Fields
			$cfields = $this->user_model->get_custom_fields(array("leads"=>1));

			// Process fields
			$canswers = array();
			foreach($cfields->result() as $r) {
				$answer = "";
				if($r->type == 0) {
					// Look for simple text entry
					$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

					if($r->required && empty($answer)) {
						$fail = lang("error_158") . $r->name;
					}
					// Add
					$canswers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				} elseif($r->type == 1) {
					// HTML
					$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

					if($r->required && empty($answer)) {
						$fail = lang("error_158") . $r->name;
					}
					// Add
					$canswers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				} elseif($r->type == 2) {
					// Checkbox
					$options = explode(",", $r->options);
					foreach($options as $k=>$v) {
						// Look for checked checkbox and add it to the answer if it's value is 1
						$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
						if($ans) {
							if(empty($answer)) {
								$answer .= $v;
							} else {
								$answer .= ", " . $v;
							}
						}
					}

					if($r->required && empty($answer)) {
						$fail = lang("error_158") . $r->name;
					}
					$canswers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);

				} elseif($r->type == 3) {
					// radio
					$options = explode(",", $r->options);
					if(isset($_POST['cf_radio_' . $r->ID])) {
						$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
						
						$flag = false;
						foreach($options as $k=>$v) {
							if($k == $answer) {
								$flag = true;
								$answer = $v;
							}
						}
						if($r->required && !$flag) {
							$fail = lang("error_158") . $r->name;
						}
						if($flag) {
							$canswers[] = array(
								"fieldid" => $r->ID,
								"answer" => $answer
							);
						}
					}

				} elseif($r->type == 4) {
					// Dropdown menu
					$options = explode(",", $r->options);
					$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$fail = lang("error_158") . $r->name;
					}
					if($flag) {
						$canswers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}
			}
		} else {
			$first_name = "";
			$last_name = "";
			$address_1 = "";
			$address_2 = "";
			$city = "";
			$state = "";
			$zipcode = "";
			$country = "";
			$email = "";
		}

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
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
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
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
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
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
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		// Add new entry
		$leadid = $this->leads_model->add_user_lead(array(
			"userid" => $userid,
			"timestamp" => time(),
			"formid" => $form->ID,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"first_name" => $first_name,
			"last_name" => $last_name,
			"address_1" => $address_1,
			"address_2" => $address_2,
			"city" => $city,
			"state" => $state,
			"zipcode" => $zipcode,
			"country" => $country,
			"email" => $email,
			"statusid" => $form->default_statusid,
			"sourceid" => $form->default_sourceid,
			"assignedid" => $form->assignedid
			)
		);

		foreach($answers as $a) {
			$this->leads_model->add_user_lead_answer(array(
				"leadid" => $leadid,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer']
				)
			);
		}

		// Lead User Custom Fields
		foreach($canswers as $answer) {
			$this->leads_model->add_user_custom_field(array(
				"leadid" => $leadid,
				"fieldid" => $answer['fieldid'],
				"value" => $answer['answer']
				)
			);
		}

		if($form->assignedid > 0) {
			// Notification
			$this->user_model->increment_field($form->assignedid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $form->assignedid,
				"url" => "leads/view_lead/" . $leadid,
				"timestamp" => time(),
				"message" => lang("ctn_1049"),
				"status" => 0,
				"fromid" => 1,
				"email" => $form->assigned_email,
				"username" => $form->assigned_username,
				"email_notification" => $form->assigned_email_notification
				)
			);
		}
		$this->session->set_flashdata("globalmsg", 
			lang("success_77"));
		if(!$guest) { 
			redirect(site_url("leads/forms"));
		} else {
			redirect(site_url("leads/view_form_full/" . $id));
		}
	}

	public function add_lead_note($id) 
	{
		$this->check_requirements();
		$this->template->loadData("activeLink", 
			array("lead" => array("general" => 1)));
		
		$id = intval($id);
		$lead = $this->leads_model->get_user_lead($id);
		if($lead->num_rows() == 0) {
			$this->template->error(lang("error_154"));
		}
		$lead = $lead->row();

		$note = $this->lib_filter->go($this->input->post("note"));

		if(empty($note)) {
			$this->template->error(lang("error_261"));
		}

		$this->leads_model->add_lead_note(array(
			"leadid" => $id,
			"userid" => $this->user->info->ID,
			"note" => $note,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_144"));
		redirect(site_url("leads/view_lead/" . $id));
	}

	public function delete_lead_note($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$note = $this->leads_model->get_lead_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_262"));
		}
		$note = $note->row();
		$this->leads_model->delete_lead_note($id);
		$this->session->set_flashdata("globalmsg", lang("success_145"));
		redirect(site_url("leads/view_lead/" . $note->leadid));
	}

}

?>