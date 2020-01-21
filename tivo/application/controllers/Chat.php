<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("chat_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		if(!$this->settings->info->enable_chat ||
			!$this->common->has_permissions(array("admin", "live_chat"), 
				$this->user)) {
			$this->template->error(lang("error_2"));
		}
	}

	public function load_new_chat() 
	{
		$username = $this->common->nohtml($this->input->get("username"));
		$this->template->loadAjax("chat/new_chat_window.php", array(
			"username" => $username
			),1
		);
	}

	public function load_multi_chat() 
	{
		$this->template->loadAjax("chat/new_chat_window_multi.php", array(
			),1
		);
	}

	public function load_project_chat() 
	{
		// Get projects
		// Get projects
		// If user is Admin, Project-Admin
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "notes_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID);
		}

		$this->template->loadAjax("chat/new_chat_window_projects.php", array(
			"projects" => $projects
			),1
		);
	}

	public function get_active_chats() 
	{
		$chats = $this->chat_model->get_active_chats($this->user->info->ID);

		$view = $this->load->view("chat/active_chats.php", array(
			"chats" => $chats
			), 
		TRUE);

		$active_chats = array();
		foreach($chats->result() as $r) {
			$active_chats[] = $r->ID;
		}

		$data = array(
			"view" => $view,
			"active_chats" => $active_chats
		);
		echo json_encode($data);
		exit();
	}

	public function get_active_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();

		$this->chat_model->update_chat_user($member->ID, array(
			"active" => 1
			)
		);

		if(!empty($chat->title)) {
			$member->title = $chat->title;
		}
		

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
		);

		echo json_encode($data);
		exit();
	}

	public function get_chat_messages($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();

		// Only mark chat unread if window is active
		if($member->unread && $member->active) {
			$member->unread = 0;
			$this->chat_model->update_chat_user($member->ID, array(
				"unread" => 0
				)
			);
		}
		

		$msgs = array();
		$limit = 5;
		$last_reply_id = 0;
		$messages = $this->chat_model->get_chat_messages($chatid, $limit);
		foreach($messages->result() as $m) {
			$msgs[] = $m;
			if($last_reply_id == 0) {
				$last_reply_id = $m->ID;
			}
		}

		$msgs = array_reverse($msgs);

		$messages_template = $this->load->view("chat/chat_messages.php", array(
			"msgs" => $msgs,
			"chat" => $chat,
			"last_reply_id" => $last_reply_id
			), 
		TRUE);

		$data = array(
			"messages_template" => $messages_template,
			"unread" => $member->unread,
			"chatid" => $chatid,
			"title" => $member->title
		);
		echo json_encode($data);
		exit();
	}

	public function get_all_chat_messages() 
	{
		$chats = $this->chat_model->get_active_chats($this->user->info->ID);

		$chat_windows = array();
		foreach($chats->result() as $r) {
			$c = array();

			// Only mark chat unread if window is active
			if($r->unread && $r->active) {
				$r->unread = 0;
				$this->chat_model->update_chat_user($r->chatuserid, array(
					"unread" => 0
					)
				);
			}

			// If a chat title is set, replace the users
			if(!empty($r->lc_title)) {
				$r->title = $r->lc_title;
			}

			// chat data
			$c['title'] = $r->title;
			$c['chatid'] = $r->ID;
			$c['unread'] = $r->unread;
			$c['active'] = $r->active;

			// Get chat messages
			$msgs = array();
			$limit = 5;
			$last_reply_id = 0;
			$messages = $this->chat_model->get_chat_messages($r->ID, $limit);
			foreach($messages->result() as $m) {
				$msgs[] = $m;
				if($last_reply_id == 0) {
					$last_reply_id = $m->ID;
				}
			}

			$msgs = array_reverse($msgs);

			$messages_template = $this->load->view("chat/chat_messages.php", array(
				"msgs" => $msgs,
				"chat" => $r,
				"last_reply_id" => $last_reply_id
				), 
			TRUE);

			// Store template
			$c['messages_template'] = $messages_template;

			// Chat bubble template
			$c['chat_bubble_template'] = $this->load->view("chat/chat_bubble.php", array(
				"chat" => $r,
				), 
			TRUE);

			// Add Chat to array
			$chat_windows[] = $c;
		}

		echo json_encode(array("chats" => $chat_windows));
		exit();
	}

	public function close_active_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();


		$this->chat_model->update_chat_user($member->ID, array(
			"active" => 0
			)
		);

		if(!empty($chat->title)) {
			$member->title = $chat->title;
		}

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
			"unread" => $member->unread
		);

		echo json_encode($data);
		exit();
	}

	public function delete_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();

		$this->chat_model->delete_chat_user($member->ID);

		// Delete chat if no users left
		$users = $this->chat_model->get_chat_users($chatid);
		if($users->num_rows() > 0) {
			// Post a message that the user left the convo
			$this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => "<i><strong>".lang("ctn_1393")."</strong></i>",
				"timestamp" => time()
				)
			);

			// Update all chat users of unread message
			$this->chat_model->update_chat_users($chatid, array(
				"unread" => 1
				)
			);
		} else {
			$this->chat_model->delete_chat($chatid);
		}

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
			"unread" => $member->unread
		);

		echo json_encode($data);
		exit();
	}

	public function start_new_chat() 
	{
		$username = $this->common->nohtml($this->input->get("username"));
		$title = $this->common->nohtml($this->input->get("title"));
		$projectid = intval($this->input->get("projectid"));

		if($projectid == 0) {
			if(empty($username)) {
				$this->template->jsonError(lang("error_254"));
			}
		}

		$message = $this->common->nohtml($this->input->get("message"));
		if(empty($message)) {
			$this->template->jsonError(lang("error_187"));
		}

		// Check for multiple usernames
		$username_old = $username;
		$username_old = explode(",", $username_old);
		$usernames = array();
		foreach($username_old as $u) {
			$u = trim($u);
			if($u == $this->user->info->username) {
				$this->template->jsonError(lang("error_255"));
			}
			$usernames[] = $u;
		}

		if($projectid > 0) {

			// Check user is a member of the project
			$this->common->check_permissions(
				lang("error_144"), 
				array("admin", "project_admin"), // User Roles
				array(),  // Team Roles
				$projectid,
				lang("error_2"),
				"jsonError"
			);

			// Get all project team members
			$members = $this->team_model->get_members_for_project($projectid);

			if(empty($title)) {
				$title = lang("ctn_1394");
			}

			// Create Chat
			$chatid = $this->chat_model->add_new_chat(array(
				"userid" => $this->user->info->ID,
				"timestamp" => time(),
				"title" => $title
				)
			);

			// Get message
			$this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);

			// Add all users
			// Add current user
			$this->chat_model->add_chat_user(array(
				"userid" => $this->user->info->ID,
				"chatid" => $chatid,
				"title" => $title
				)
			);

			foreach($members->result() as $r) {
				if($r->userid != $this->user->info->ID) {
					$this->chat_model->add_chat_user(array(
						"userid" => $r->userid,
						"chatid" => $chatid,
						"unread" => 1
						)
					);
				}
			}

		} elseif(count($usernames) > 1) {

			// Validate all users
			$users = array();
			foreach($usernames as $u) {
				// Get user
				$user = $this->user_model->get_user_by_username($u);
				if($user->num_rows() == 0) { 
					$this->template->jsonError(lang("error_161"));
				}
				$user = $user->row();
				$users[] = $user->ID;
			}

			$users = array_unique($users);

			if(empty($title)) {
				$title = lang("ctn_1394");
			}

			// Create Chat
			$chatid = $this->chat_model->add_new_chat(array(
				"userid" => $this->user->info->ID,
				"timestamp" => time(),
				"title" => $title
				)
			);

			// Get message
			$this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);

			// Add all users
			// Add current user
			$this->chat_model->add_chat_user(array(
				"userid" => $this->user->info->ID,
				"chatid" => $chatid,
				"title" => $title
				)
			);

			foreach($users as $uid) {
				$this->chat_model->add_chat_user(array(
					"userid" => $uid,
					"chatid" => $chatid,
					"unread" => 1
					)
				);
			}

		} else {
			// One on one chat
			// Get user
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) { 
				$this->template->jsonError(lang("error_161"));
			}
			$user = $user->row();

			
			$title = lang("ctn_1395") . " <strong>" . $user->username . "</strong>";
			$title2= lang("ctn_1395") . " <strong>" . $this->user->info->username . "</strong>";
			



			// Create Chat
			$chatid = $this->chat_model->add_new_chat(array(
				"userid" => $this->user->info->ID,
				"timestamp" => time(),
				)
			);

			// Get message
			$this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);


			// Add Members
			$this->chat_model->add_chat_user(array(
				"userid" => $this->user->info->ID,
				"chatid" => $chatid,
				"title" => $title
				)
			);

			$this->chat_model->add_chat_user(array(
				"userid" => $user->ID,
				"chatid" => $chatid,
				"title" => $title2,
				"unread" => 1
				)
			);
		}

		$data = array(
			"success" => 1,
			"chatid" => $chatid
			);
		echo json_encode($data);
		exit();
	}

	public function send_chat_message($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();

		$message = $this->common->nohtml($this->input->get("message"));
		$hash = $this->common->nohtml($this->input->get("hash"));

		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}

		if(empty($message)) {
			$this->template->jsonError(lang("error_187"));
		}

		$this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $this->user->info->ID,
			"message" => $message,
			"timestamp" => time()
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		$data = array(
			"success" => 1,
			"chatid" => $chatid
			);
		echo json_encode($data);
		exit();
	}

	public function view($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_253"));
		}
		$member = $member->row();

		if(empty($chat->title)) {
			$chat->title = $member->title;
		}

		// Get all chat users
		$users = $this->chat_model->get_chat_users($chatid);

		$this->template->loadContent("chat/view.php", array(
			"chat" => $chat,
			"users" => $users
			)
		);
	}

	public function chat_page($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_253"));
		}
		$member = $member->row();

		$this->load->library("datatables");

		$this->datatables->set_default_order("live_chat_messages.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"live_chat_messages.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->chat_model
				->get_total_chat_messages($chatid)
		);
		$messages = $this->chat_model->get_chat_messages_page($chatid, $this->datatables);

		foreach($messages->result() as $r) {
			$options = "";
			if($r->userid == $this->user->info->ID || $this->common->has_permissions(array("admin"), $this->user)) {
				$options = '<a href="'.site_url("chat/delete_chat_message/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>';
			}

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->message,
				date($this->settings->info->date_format, $r->timestamp),
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function delete_chat_message($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$message = $this->chat_model->get_chat_message($id);
		if($message->num_rows() == 0) {
			$this->template->error(lang("error_188"));
		}
		$message = $message->row();

		$chatid = $message->chatid;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Delete Message if user made it
		if($this->user->info->ID != $message->userid && 
			!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_256"));
		}

		// Delete
		$this->chat_model->delete_chat_message($id);
		$this->session->set_flashdata("globalmsg", lang("success_131"));
		redirect(site_url("chat/view/" . $chatid));

	}

	public function load_active_users() 
	{
		$users = $this->user_model->get_online_users();
		$this->template->loadAjax("chat/online_users.php", array(
			"users" => $users
			),1
		);
	}

	public function edit_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_253"));
		}
		$member = $member->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		// Get all chat users
		$users = $this->chat_model->get_chat_users($chatid);

		$this->template->loadContent("chat/edit_chat.php", array(
			"chat" => $chat,
			"users" => $users
			)
		);
	}

	public function edit_chat_pro($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_253"));
		}
		$member = $member->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		$title = $this->common->nohtml($this->input->post("title"));

		$this->chat_model->update_chat($chatid, array(
			"title" => $title
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_132"));
		redirect(site_url("chat/view/" . $chatid));
	}

	public function remove_from_chat($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$user = $this->chat_model->get_chat_user_id($id);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$user = $user->row();

		$chatid = $user->chatid;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		// Get message
		$this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $user->userid,
			"message" => "<strong><i>".lang("ctn_1396")."</i></strong>",
			"timestamp" => time()
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		// Delete
		$this->chat_model->delete_chat_user($id);
		$this->session->set_flashdata("globalmsg", lang("success_133"));
		redirect(site_url("chat/edit_chat/" . $chatid));

	}

	public function add_user($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_252"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_253"));
		}
		$member = $member->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		$username = $this->common->nohtml($this->input->post("username"));
		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_161"));
		}
		$user = $user->row();

		// Add
		// Get message
		$this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $user->ID,
			"message" => "<strong><i>".lang("ctn_1397")."</i></strong>",
			"timestamp" => time()
			)
		);

		if(!empty($chat->title)) {
			$title = $chat->title;
		} else {
			$title = lang("ctn_1395") . " <strong>" . $this->user->info->username . "</strong>";
		}

		// Add all users
		// Add current user
		$this->chat_model->add_chat_user(array(
			"userid" => $user->ID,
			"chatid" => $chatid,
			"title" => $title
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_134"));
		redirect(site_url("chat/edit_chat/" . $chatid));
	}

}

?>