<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("tickets_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
	}

	public function index($departmentid = 0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));

		$departmentid = intval($departmentid);

		// Get department
		$department = null;
		if($departmentid > 0) {
			$department = $this->tickets_model->get_department($departmentid);
			if($department->num_rows() == 0) {
				$this->template->error(lang("error_201"));
			}
			$department = $department->row();
		}

		$this->template->loadContent("tickets/index.php", array(
			"department" => $department,
			"departmentid" => $departmentid,
			"page" => "index",
			)
		);
		
	}

	public function tickets_page($departmentid=0,$page="index",$userid=0) 
	{
		$this->load->library("datatables");
		$departmentid = intval($departmentid);
		$userid = intval($userid);

		$this->datatables->set_default_order("tickets.last_reply_timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"tickets.title" => 0
				 ),
				 1 => array(
				 	"tickets.priority" => 0
				 ),
				 2 => array(
				 	"tickets.status" => 0
				 ),
				 6 => array(
				 	"tickets.last_reply_timestamp" => 0
				 )
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->tickets_model->get_total_tickets_count($departmentid)
			);

			$tickets = $this->tickets_model->get_tickets($departmentid, 
				$this->datatables);
		} elseif($page == "your") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_total_tickets_user_count($this->user->info->ID,
						$departmentid)
			);
			$tickets = $this->tickets_model
				->get_tickets_user($this->user->info->ID,$departmentid,
					$this->datatables);
		} elseif($page == "client") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_total_tickets_client_count($userid)
			);

			$tickets = $this->tickets_model
				->get_client_tickets($userid, $this->datatables);
		}

		$prioritys = array(
			1 => "<span class='label label-info'>".lang("ctn_931")."</span>",
			2 => "<span class='label label-primary'>".lang("ctn_932")."</span>", 
			3=> "<span class='label label-warning'>".lang("ctn_933")."</span>", 
			4 => "<span class='label label-danger'>".lang("ctn_934")."</span>"
		);

		$statuses = array(1=>lang("ctn_927"), 2 => lang("ctn_928"), 3 => lang("ctn_929"));


		foreach($tickets->result() as $r) {
			$this->datatables->data[] = array(
				$r->title,
				$prioritys[$r->priority],
				$statuses[$r->status],
				'<a href="'.site_url("tickets/".$page."/" . $r->departmentid).'">'.$r->catname.'</a>',
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$this->common->get_user_display(array("username" => $r->assigned_username, "avatar" => $r->assigned_avatar, "online_timestamp" => $r->assigned_online_timestamp)),
				date($this->settings->info->date_format, $r->last_reply_timestamp) . " " . $this->common->get_user_display(array("username" => $r->lr_username, "avatar" => $r->lr_avatar, "online_timestamp" => $r->lr_online_timestamp)),
				'<a href="'.site_url("tickets/view/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("tickets/edit_ticket/" . $r->ID).'" data-toggle="tooltip" data-placement="right" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_ticket/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function your($departmentid = 0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("tickets" => array("your" => 1)));

		$departmentid = intval($departmentid);

		// Get department
		$department = null;
		if($departmentid > 0) {
			$department = $this->tickets_model->get_department($departmentid);
			if($department->num_rows() == 0) {
				$this->template->error(lang("error_201"));
			}
			$department = $department->row();
		}

		$this->template->loadContent("tickets/index.php", array(
			"departmentid" => $departmentid,
			"department" => $department,
			"page" => "your",
			)
		);
		
	}

	public function view($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker", "ticket_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadExternal(
			'<script src="'.base_url().'scripts/custom/tickets.js">
			</script>'
		);
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_202"));
		}
		$ticket = $ticket->row();

		$replies = $this->tickets_model->get_ticket_replies($id);

		$files = $this->tickets_model->get_ticket_files($id);

		$this->template->loadContent("tickets/view.php", array(
			"ticket" => $ticket,
			"replies" => $replies,
			"files" => $files
			)
		);
	}

	public function change_status() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$ticketid = intval($this->input->get("ticketid"));
		$status = intval($this->input->get("status"));
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->jsonError(lang("error_202"));
		}
		$ticket = $ticket->row();

		if($status < 1 || $status > 3) {
			$this->template->jsonError(lang("error_203"));
		}

		if($status == 3) {
			$close_ticket = date("d-n-Y");
		} else {
			$close_ticket = "";
		}

		$this->tickets_model->update_ticket($ticketid, array(
			"status" => $status,
			"close_ticket_date" => $close_ticket
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1083"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $ticketid
			)
		);
		echo json_encode(array("success" => 1));
		exit();
	}

	public function delete_ticket_reply($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_204"));
		}
		$reply = $reply->row();

		if($reply->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "ticket_manage", "ticket_worker"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
		}

		$this->tickets_model->delete_ticket_reply($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1084"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $reply->ticketid
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_100"));
		redirect(site_url("tickets/view/" . $reply->ticketid));
	}

	public function edit_ticket_reply($id) 
	{
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));
		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_204"));
		}
		$reply = $reply->row();
		if($reply->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "ticket_manage", "ticket_worker"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
		}

		$this->template->loadContent("tickets/edit_reply.php", array(
			"reply" => $reply
			)
		);
	}

	public function edit_ticket_reply_pro($id) 
	{
		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_204"));
		}
		$reply = $reply->row();

		if($reply->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "ticket_manage", "ticket_worker"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
		}

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_205"));
		}
		$this->load->library("upload");

		$file_count = intval($this->input->post("file_count"));
		$file_data = array();
		$files_flag = $reply->files;
		if(!$this->settings->info->disable_ticket_upload) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_' . $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_94") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();
		            $files_flag = 1;
		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Add
		$this->tickets_model->update_ticket_reply($id, array(
			"body" => $body,
			"files" => $files_flag
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"replyid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1085"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $reply->ticketid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_101"));
		redirect(site_url("tickets/view/" . $reply->ticketid));
	}

	public function ticket_reply($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker", "ticket_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_202"));
		}
		$ticket = $ticket->row();

		if($ticket->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "ticket_manage", "ticket_worker"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
		}

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_205"));
		}
		$this->load->library("upload");

		$file_count = intval($this->input->post("file_count"));
		$file_data = array();
		$files_flag = 0;
		if(!$this->settings->info->disable_ticket_upload) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_' . $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_94") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();
		            $files_flag = 1;
		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Notification
		if($ticket->userid == $this->user->info->ID) {
			// Alert assigned user of new reply
			$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $ticket->assignedid,
				"url" => "tickets/view/" . $ticket->ID,
				"timestamp" => time(),
				"message" => lang("ctn_1086"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $ticket->assigned_username,
				"email" => $ticket->assigned_email,
				"email_notification" => $ticket->assigned_email_notification
				)
			);
		} elseif($this->user->info->ID == $ticket->assignedid) {
			// Alert user of new reply
			$this->user_model->increment_field($ticket->userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $ticket->userid,
				"url" => "tickets/view/" . $ticket->ID,
				"timestamp" => time(),
				"message" => lang("ctn_1086"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $ticket->client_username,
				"email" => $ticket->client_email,
				"email_notification" => $ticket->client_email_notification
				)
			);
		} else {
			// Alert both in case of random user reply
			$this->user_model->increment_field($ticket->userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $ticket->userid,
				"url" => "tickets/view/" . $ticket->ID,
				"timestamp" => time(),
				"message" => lang("ctn_1086"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $ticket->client_username,
				"email" => $ticket->client_email,
				"email_notification" => $ticket->client_email_notification
				)
			);
			$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $ticket->assignedid,
				"url" => "tickets/view/" . $ticket->ID,
				"timestamp" => time(),
				"message" => lang("ctn_1086"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $ticket->assigned_username,
				"email" => $ticket->assigned_email,
				"email_notification" => $ticket->assigned_email_notification
				)
			);
		}

		$new_message_id_hash = md5(rand(1,100000000)."fhhfh".time());

		// Add
		$replyid = $this->tickets_model->add_ticket_reply(array(
			"ticketid" => $id,
			"userid" => $this->user->info->ID,
			"body" => $body,
			"timestamp" => time(),
			"files" => $files_flag,
			"hash" => $new_message_id_hash
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"replyid" => $replyid,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		if($ticket->userid != $this->user->info->ID) {
			// Send email
			$this->load->model("home_model");
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ticket_reply", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $ticket->client_username,
				"[SITE_URL]" => site_url(),
				"[TICKET_BODY]" => $body,
				"[TICKET_ID]" => $id,
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$headers = array(
				"References" => $ticket->message_id_hash,
				"In-Reply-To" => $ticket->message_id_hash,
				"Message-ID" => $new_message_id_hash
				);
			$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $id . "]: " . $ticket->title,
				 $email_template->message, $ticket->client_email, $headers);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1089"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $ticket->ID
			)
		);

		// Update last reply
		$this->tickets_model->update_ticket($ticket->ID, array(
			"last_reply_userid" => $this->user->info->ID,
			"last_reply_timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_102"));
		redirect(site_url("tickets/view/" . $id));
	}

	public function delete_ticket($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_202"));
		}
		$ticket = $ticket->row();

		$this->tickets_model->delete_ticket($id);
		$this->tickets_model->delete_ticket_files($id);
		$this->tickets_model->delete_ticket_replies($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1090") . $ticket->title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $id
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_103"));
		redirect(site_url("tickets"));
	}

	public function edit_ticket($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));

		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_202"));
		}
		$ticket = $ticket->row();

		$departments = $this->tickets_model->get_departments();
		$fields = $this->tickets_model->get_custom_field_ticket_data($ticket->ID);
		$files = $this->tickets_model->get_ticket_files($ticket->ID);

		$this->template->loadContent("tickets/edit_ticket.php", array(
			"ticket" => $ticket,
			"departments" => $departments,
			"fields" => $fields,
			"files" => $files
			)
		);
	}

	public function edit_ticket_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_202"));
		}
		$ticket = $ticket->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$username_client = $this->common->nohtml($this->input->post("username_client"));
		$username_assigned = $this->common->nohtml($this->input->post("username_assigned"));
		$status = intval($this->input->post("status"));
		$priority = intval($this->input->post("priority"));
		$departmentid = intval($this->input->post("departmentid"));
		$body = $this->lib_filter->go($this->input->post("body"));
		$file_count = intval($this->input->post("file_count"));
		$notes = $this->lib_filter->go($this->input->post("notes"));

		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		if($status < 1 || $status > 3) {
			$this->template->error(lang("error_203"));
		}

		if($priority < 1 || $priority > 4) {
			$this->template->error(lang("error_206"));
		}

		$department = $this->tickets_model->get_department($departmentid);
		if($department->num_rows() == 0) {
			$this->template->error(lang("error_201"));
		}

		$user = $this->user_model->get_user_by_username($username_client);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_207") . $username_client);
		}
		$user = $user->row();

		$userid2 = 0;
		$username2 = "";
		$email2 = "";
		$email_notification2 = 0;
		if(!empty($username_assigned)) {
			$user2 = $this->user_model->get_user_by_username($username_assigned);
			if($user2->num_rows() == 0) {
				$this->template->error(lang("error_208") . $username_assigned);
			}
			$user2 = $user2->row();
			$userid2 = $user2->ID;
			$username2 = $user2->username;
			$email2 = $user2->email;
			$email_notification2 = $user2->email_notification;
		}	

		if(empty($body)) {
			$this->template->error(lang("error_209"));
		}
		$this->load->library("upload");

		// Check custom fields
		$fields = $this->tickets_model->get_custom_fields();
		$user_data = array();
		foreach($fields->result() as $r) {
			if(isset($_POST['field_id_' . $r->ID])) {
				$value = $this->common->nohtml($this->input->post("field_id_" . $r->ID));
				if(!empty($value)) {
					$user_data[] = array("id" => $r->ID, "data" => $value);
				} else {
					if($r->required) {
						$this->template->error(lang("error_210"));
					}
				}
			} else {
				if($r->required) {
					$this->template->error(lang("error_210"));
				}
			}
		}

		$file_data = array();
		if(!$this->settings->info->disable_ticket_upload) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_' . $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_94") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();

		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		if($status == 3) {
			$close_ticket = date("d-n-Y");
		} else {
			$close_ticket = "";
		}

		// Create ticket
		$this->tickets_model->update_ticket($id, array(
			"title" => $title,
			"body" => $body,
			"userid" => $user->ID,
			"assignedid" => $userid2,
			"departmentid" => $departmentid,
			"status" => $status,
			"priority" => $priority,
			"notes" => $notes,
			"close_ticket_date" => $close_ticket
			)
		);

		if($ticket->assignedid == 0 && $userid2 > 0) {
			// Send notification to assigned user
			$this->user_model->increment_field($userid2, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $userid2,
					"url" => "tickets/view/" . $id,
					"timestamp" => time(),
					"message" => "You have been assigned to a new ticket!",
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $username2,
					"email" => $email2,
					"email_notification" => $email_notification2
					)
				);
		}

		// Custom field data
		// Delete old before adding new
		$this->tickets_model->delete_ticket_custom_data($id);
		foreach($user_data as $d) {
			$this->tickets_model->add_custom_field_data(array(
				"ticketid" => $id,
				"fieldid" => $d['id'],
				"value" => $d['data']
				)
			);
		}

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"ticketid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1410") ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $id
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_104"));
		redirect(site_url("tickets"));
	}

	public function delete_file_attachment($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$file = $this->tickets_model->get_ticket_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_211"));
		}
		$file = $file->row();
		if($file->ID != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "ticket_manage", "ticket_worker"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
		}


		// Delete
		$this->tickets_model->delete_ticket_file($id);

		// Check for files if reply so we know when not to display
		if($file->replyid > 0) {
			$reply = $this->tickets_model->get_ticket_reply($file->replyid);
			$reply = $reply->row();
			$file->ticketid = $reply->ticketid;
			$files = $this->tickets_model->get_reply_files($file->replyid);
			if($files->num_rows() == 0) {
				$this->tickets_model->update_ticket_reply($file->replyid, array(
					"files" => 0
					)
				);
			}
		} 

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1091"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $file->ticketid
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_105"));
		redirect(site_url("tickets/view/" . $file->ticketid));
	}

	public function add_ticket() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker", "ticket_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));

		$departments = $this->tickets_model->get_departments();
		$fields = $this->tickets_model->get_custom_fields();
		
		$this->template->loadContent("tickets/add_ticket.php", array(
			"departments" => $departments,
			"fields" => $fields
			)
		);
	}

	public function add_ticket_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker", "ticket_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$title = $this->common->nohtml($this->input->post("title"));
		$username_client = $this->common->nohtml($this->input->post("username_client"));
		$username_assigned = $this->common->nohtml($this->input->post("username_assigned"));
		$status = intval($this->input->post("status"));
		$priority = intval($this->input->post("priority"));
		$departmentid = intval($this->input->post("departmentid"));
		$body = $this->lib_filter->go($this->input->post("body"));
		$file_count = intval($this->input->post("file_count"));
		$notes = $this->lib_filter->go($this->input->post("notes"));

		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		if($priority < 1 || $priority > 4) {
			$this->template->error(lang("error_206"));
		}

		$department = $this->tickets_model->get_department($departmentid);
		if($department->num_rows() == 0) {
			$this->template->error(lang("error_201"));
		}

		if($this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			if($status < 1 || $status > 3) {
				$this->template->error(lang("error_203"));
			}
			$user = $this->user_model->get_user_by_username($username_client);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_207") . $username_client);
			}
			$user = $user->row();
			$username = $user->username;
			$userid = $user->ID;
			$email = $user->email;

			$userid2 = 0;
			$username2 = "";
			$email2 = "";
			$email_notification2 = 0;
			if(!empty($username_assigned)) {
				$user2 = $this->user_model->get_user_by_username($username_assigned);
				if($user2->num_rows() == 0) {
					$this->template->error(lang("error_208") . $username_assigned);
				}
				$user2 = $user2->row();
				$userid2 = $user2->ID;
				$username2 = $user2->username;
				$email2 = $user2->email;
				$email_notification2 = $user2->email_notification;
			}
			$redirect_page = "tickets";
		} else {
			$userid2 = 0;
			$username2 = "";
			$email2 = "";
			$email_notification2 = 0;
			$userid = $this->user->info->ID;
			$username = $this->user->info->username;
			$status = 1;
			$notes = "";
			$redirect_page = "tickets/client";
			$email = $this->user->info->email;
		}

		if(empty($body)) {
			$this->template->error(lang("error_209"));
		}
		$this->load->library("upload");

		// Check custom fields
		$fields = $this->tickets_model->get_custom_fields();
		$user_data = array();
		foreach($fields->result() as $r) {
			if(isset($_POST['field_id_' . $r->ID])) {
				$value = $this->common->nohtml($this->input->post("field_id_" . $r->ID));
				if(!empty($value)) {
					$user_data[] = array("id" => $r->ID, "data" => $value);
				} else {
					if($r->required) {
						$this->template->error(lang("error_210"));
					}
				}
			} else {
				if($r->required) {
					$this->template->error(lang("error_210"));
				}
			}
		}

		$file_data = array();
		if(!$this->settings->info->disable_ticket_upload) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_' . $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_94") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();

		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Message id hash
		$message_id_hash = md5(rand(1,100000) . $title . time());

		// Create ticket
		$ticketid = $this->tickets_model->add_ticket(array(
			"title" => $title,
			"body" => $body,
			"userid" => $userid,
			"assignedid" => $userid2,
			"timestamp" => time(),
			"departmentid" => $departmentid,
			"status" => $status,
			"priority" => $priority,
			"last_reply_timestamp" => time(),
			"notes" => $notes,
			"message_id_hash" => $message_id_hash,
			"ticket_date" => date("d-n-Y")
			)
		);

		if($userid2 > 0) {
			// Send notification to assigned user
			$this->user_model->increment_field($userid2, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $userid2,
					"url" => "tickets/view/" . $ticketid,
					"timestamp" => time(),
					"message" => "You have been assigned to a new ticket!",
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $username2,
					"email" => $email2,
					"email_notification" => $email_notification2
					)
				);
		}

		// Custom field data
		foreach($user_data as $d) {
			$this->tickets_model->add_custom_field_data(array(
				"ticketid" => $ticketid,
				"fieldid" => $d['id'],
				"value" => $d['data']
				)
			);
		}

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"ticketid" => $ticketid,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1092") . $title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/view/" . $ticketid
			)
		);

		$users = $this->tickets_model->get_users_from_groups($departmentid);
		foreach($users->result() as $r) {
			$this->user_model->increment_field($r->ID, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->ID,
					"url" => "tickets/view/" . $ticketid,
					"timestamp" => time(),
					"message" => lang("ctn_1406"),
					"status" => 0,
					"fromid" => $userid,
					"username" => $r->username,
					"email" => $r->email,
					"email_notification" => $r->email_notification
					)
				);
		}

		// Send email
		$this->load->model("home_model");
		if(!isset($_COOKIE['language'])) {
			// Get first language in list as default
			$lang = $this->config->item("language");
		} else {
			$lang = $this->common->nohtml($_COOKIE["language"]);
		}

		// Send Email
		$email_template = $this->home_model->get_email_template_hook("ticket_creation", $lang);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $username,
			"[SITE_URL]" => site_url(),
			"[TICKET_BODY]" => $body,
			"[TICKET_ID]" => $ticketid,
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$headers = array(
			"Message-ID" => $message_id_hash
			);
		$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $ticketid . "]: " . $title,
			 $email_template->message, $email, $headers);
		

		$this->session->set_flashdata("globalmsg", lang("success_106"));
		redirect(site_url("tickets/view/" . $ticketid));
	}

	public function custom() 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("tickets" => array("custom" => 1)));

		$fields = $this->tickets_model->get_custom_fields();

		
		$this->template->loadContent("tickets/custom.php", array(
			"fields" => $fields
			)
		);
	}

	public function add_custom_field() 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$help = $this->common->nohtml($this->input->post("help_text"));
		$select = $this->common->nohtml($this->input->post("select_options"));
		$required = intval($this->input->post("required"));

		if(empty($name)) {
			$this->template->error(lang("error_212"));
		}

		if($type < 0 || $type > 3) {
			$this->template->error(lang("error_213"));
		}

		$this->tickets_model->add_custom_field(array(
			"name" => $name,
			"type" => $type,
			"help_text" => $help,
			"select_options" => $select,
			"required" => $required
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1093"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/custom"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_107"));
		redirect(site_url("tickets/custom"));
	}

	public function edit_custom_field($id) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_214"));
		}

		$this->template->loadContent("tickets/edit_custom_field.php", array(
			"field" => $field->row()
			)
		);
	}

	public function edit_custom_field_pro($id) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_214"));
		}
		$field = $field->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$help = $this->common->nohtml($this->input->post("help_text"));
		$select = $this->common->nohtml($this->input->post("select_options"));
		$required = intval($this->input->post("required"));

		if(empty($name)) {
			$this->template->error(lang("error_212"));
		}

		if($type < 0 || $type > 3) {
			$this->template->error(lang("error_213"));
		}

		$this->tickets_model->update_custom_field($id, array(
			"name" => $name,
			"type" => $type,
			"help_text" => $help,
			"select_options" => $select,
			"required" => $required
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1094"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/custom"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_108"));
		redirect(site_url("tickets/custom"));
	}

	public function delete_custom_field($id, $hash) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_214"));
		}

		$this->tickets_model->delete_custom_field($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1095"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/custom"
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_109"));
		redirect(site_url("tickets/custom"));
	}


	public function departments() 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);

		$this->template->loadData("activeLink", 
			array("tickets" => array("departments" => 1)));

		$departments = $this->tickets_model->get_departments();

		$user_groups = $this->user_model->get_all_user_groups();

		
		$this->template->loadContent("tickets/departments.php", array(
			"departments" => $departments,
			"user_groups" => $user_groups
			)
		);
		
	}

	public function add_department() 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("desc"));

		$user_groups = $this->input->post("user_groups");

		// Check ticket categories
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $groupid) {
				$groupid = intval($groupid);
				if($groupid > 0) {
					$group = $this->user_model->get_user_group($groupid);
					if($group->num_rows() == 0) {
						$this->template->error(lang("error_268"));
					}
				}
				$groups[] = $groupid;
			}
		}

		if(empty($name)) {
			$this->template->error(lang("error_215"));
		}

		$catid = $this->tickets_model->add_department(array(
			"name" => $name,
			"description" => $desc
			)
		);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->tickets_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $catid
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1096"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/departments"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_110"));
		redirect(site_url("tickets/departments"));
	}


	public function edit_department($id) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);

		$id = intval($id);
		$department = $this->tickets_model->get_department($id);
		if($department->num_rows() == 0) {
			$this->template->error(lang("error_201"));
		}

		$department = $department->row();

		$user_groups = $this->tickets_model->get_cat_groups($id);

		$this->template->loadContent("tickets/edit_department.php", array(
			"department" => $department,
			"user_groups" => $user_groups
			)
		);
	}

	public function edit_department_pro($id) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$id = intval($id);
		$department = $this->tickets_model->get_department($id);
		if($department->num_rows() == 0) {
			$this->template->error(lang("error_201"));
		}

		$department = $department->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("desc"));

		if(empty($name)) {
			$this->template->error(lang("error_215"));
		}

		$user_groups = $this->input->post("user_groups");

		// Check ticket categories
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $groupid) {
				$groupid = intval($groupid);
				if($groupid > 0) {
					$group = $this->user_model->get_user_group($groupid);
					if($group->num_rows() == 0) {
						$this->template->error(lang("error_268"));
					}
				}
				$groups[] = $groupid;
			}
		}

		$this->tickets_model->update_department($id, array(
			"name" => $name,
			"description" => $desc
			)
		);

		$this->tickets_model->delete_category_groups($id);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->tickets_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $id
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1097"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/departments"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_111"));
		redirect(site_url("tickets/departments"));
	}

	public function delete_department($id, $hash) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "ticket_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$department = $this->tickets_model->get_department($id);
		if($department->num_rows() == 0) {
			$this->template->error(lang("error_201"));
		}

		$this->tickets_model->delete_department($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1098"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "tickets/departments"
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_112"));
		redirect(site_url("tickets/departments"));
	}

	public function client() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker", "ticket_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("tickets" => array("client" => 1)));

		$this->template->loadContent("tickets/client.php", array(
			)
		);
	}

	public function client_page() 
	{

		$this->load->library("datatables");

		$this->datatables->set_default_order("tickets.last_reply_timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"tickets.title" => 0
				 ),
				 1 => array(
				 	"tickets.priority" => 0
				 ),
				 2 => array(
				 	"tickets.status" => 0
				 ),
				 5 => array(
				 	"tickets.last_reply_timestamp" => 0
				 )
			)
		);

		
		$this->datatables->set_total_rows(
			$this->tickets_model
				->get_total_tickets_client_count($this->user->info->ID)
		);

		$tickets = $this->tickets_model
			->get_client_tickets($this->user->info->ID, $this->datatables);
		

		$prioritys = array(
			1 => "<span class='label label-info'>".lang("ctn_931")."</span>",
			2 => "<span class='label label-primary'>".lang("ctn_932")."</span>", 
			3=> "<span class='label label-warning'>".lang("ctn_933")."</span>", 
			4 => "<span class='label label-danger'>".lang("ctn_934")."</span>"
		);

		$statuses = array(1=>lang("ctn_927"), 2 => lang("ctn_928"), 3 => lang("ctn_929"));


		foreach($tickets->result() as $r) {
			$this->datatables->data[] = array(
				$r->title,
				$prioritys[$r->priority],
				$statuses[$r->status],
				'<a href="'.site_url("tickets/client/" . $r->departmentid).'">'.$r->catname.'</a>',
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				date($this->settings->info->date_format, $r->last_reply_timestamp) . " " . $this->common->get_user_display(array("username" => $r->lr_username, "avatar" => $r->lr_avatar, "online_timestamp" => $r->lr_online_timestamp)),
				'<a href="'.site_url("tickets/view/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function assign_user_pro($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error("Invalid Ticket!");
		}
		$ticket = $ticket->row();

		$username = $this->common->nohtml($this->input->post("username"));
		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) {
			$this->template->error("Invalid User");
		}

		$user = $user->row();

		$this->tickets_model->update_ticket($ticketid, array(
			"assignedid" => $user->ID
			)
		);

		$this->user_model->increment_field($user->ID, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $user->ID,
			"url" => "tickets/view/" . $ticketid,
			"timestamp" => time(),
			"message" => lang("ctn_1514"),
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"username" => $user->username,
			"email" => $user->email,
			"email_notification" => $user->email_notification
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_162"));
		redirect(site_url("tickets/view/" . $ticket->ID));
	}

}

?>