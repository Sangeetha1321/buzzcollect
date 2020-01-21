<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class User 
{

	var $info=array();
	var $loggedin=false;
	var $u=null;
	var $p=null;
	var $oauth_provider = null;
	var $oauth_id = null;
	var $oauth_token = null;
	var $oauth_secret = null;

	public function __construct() 
	{
		$CI =& get_instance();
		$config = $CI->config->item("cookieprefix");
		$this->u = $CI->input->cookie($config . "un", TRUE);
		$this->p = $CI->input->cookie($config . "tkn", TRUE);

		$this->oauth_provider = $CI->input->cookie($config . "provider", TRUE);
		$this->oauth_id = $CI->input->cookie($config . "oauthid", TRUE);
		$this->oauth_token = $CI->input->cookie($config . "oauthtoken", TRUE);
		$this->oauth_secret = $CI->input->cookie($config . "oauthsecret", TRUE);
 		
 		$user = null; 

 		// Columns
 		$select = "users.`ID`, users.`username`, users.`email`, 
				users.first_name, 
				users.last_name, users.`online_timestamp`, users.avatar,
				users.email_notification, users.aboutme, users.points,
				users.noti_count, users.email_count, users.timer_count,
				users.active_projectid, users.time_rate,
				users.premium_time, users.user_role, user_roles.name as ur_name,
				users.address_1, users.address_2, 
				users.city, users.state, users.zipcode, users.country,
				users.active, users.activate_code, users.profile_comments,
				user_roles.admin, user_roles.admin_settings, 
				user_roles.admin_members, user_roles.admin_payment,
				user_roles.project_admin, user_roles.team_manage,
				user_roles.team_worker, user_roles.time_worker, 
				user_roles.project_worker, user_roles.file_manage,
				user_roles.file_worker, user_roles.task_manage, 
				user_roles.task_worker, user_roles.calendar_manage,
				user_roles.calendar_worker, user_roles.ticket_manage,
				user_roles.ticket_worker, user_roles.finance_worker,
				user_roles.finance_manage, user_roles.invoice_manage,
				user_roles.invoice_client, user_roles.ticket_client,
				user_roles.notes_manage, user_roles.notes_worker, 
				user_roles.banned, user_roles.reports_manage, 
				user_roles.invoice_worker,
				user_roles.reports_worker, user_roles.project_client,
				user_roles.task_client, user_roles.services_manage,
				user_roles.live_chat, user_roles.client,
				user_roles.doc_manage, user_roles.doc_worker,
				user_roles.lead_manage, user_roles.ID as user_role_id,
				projects.ID as projectid, projects.name as project_name,
				projects.image as project_image";
 		
 		// Twitter
		if($this->oauth_provider === "twitter") {
			if($this->oauth_provider && $this->oauth_id &&
			  $this->oauth_token && $this->oauth_secret) {
			 	$user = $CI->db->select($select)
				 ->where("oauth_provider", $this->oauth_provider)
				 ->where("oauth_id", $this->oauth_id)
				 ->where("oauth_token", $this->oauth_token)
				 ->where("oauth_secret", $this->oauth_secret)
				 ->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
				 ->join("projects", "projects.ID = users.active_projectid", 
				 	"left outer")
				 ->get("users"); 
			}
		}

		// Facebook
		if($this->oauth_provider === "facebook") {
			if($this->oauth_provider && $this->oauth_id &&
			  $this->oauth_token) {
			 	$user = $CI->db->select($select)
				 ->where("oauth_provider", $this->oauth_provider)
				 ->where("oauth_id", $this->oauth_id)
				 ->where("oauth_token", $this->oauth_token)
				 ->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
				 ->join("projects", "projects.ID = users.active_projectid", 
				 	"left outer")
				 ->get("users"); 
			}
		}

		// Google
		if($this->oauth_provider === "google") {
			if($this->oauth_provider && $this->oauth_id &&
			  $this->oauth_token) {
			 	$user = $CI->db->select($select)
				 ->where("oauth_provider", $this->oauth_provider)
				 ->where("oauth_id", $this->oauth_id)
				 ->where("oauth_token", $this->oauth_token)
				 ->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
				 ->join("projects", "projects.ID = users.active_projectid", 
				 	"left outer")
				 ->get("users"); 
			}
		}

		if ($this->u && $this->p && empty($this->oauth_provider)) {
			$user = $CI->db->select($select)
			->where("users.email", $this->u)->where("users.token", $this->p)
			->join("user_roles", "user_roles.ID = users.user_role", "left outer")
			->join("projects", "projects.ID = users.active_projectid", 
				 	"left outer")
			->get("users");
		}

		if($user !== null) {
			if ($user->num_rows() == 0) {
				$this->loggedin=false;
			} else {
				$this->loggedin=true;
				$this->info = $user->row();

				if( (empty($this->info->username) || empty($this->info->email)) && ($CI->router->fetch_class() != "register") && ($CI->router->fetch_class() != "login")) {
					redirect(site_url("register/add_username"));
				}

				if($this->info->online_timestamp < time() - 60*5) {
					$this->update_online_timestamp($this->info->ID);
				}

				if (isset($this->info->banned) && $this->info->banned) {
					$CI->load->helper("cookie");
					$this->loggedin = false;
					$CI->session->set_flashdata("globalmsg", 
						"This account has been deactivated and can no longer be used.");
					delete_cookie($config . "un");
					delete_cookie($config . "tkn");
					redirect(site_url("login/banned"));
				}
			}
		}
	}

	public function getPassword() 
	{
		$CI =& get_instance();
		$user = $CI->db->select("users.`password`")
		->where("ID", $this->info->ID)->get("users");
		$user = $user->row();
		return $user->password;
	}

	public function update_online_timestamp($userid) 
	{
		$CI =& get_instance();
		$CI->db->where("ID", $userid)->update("users", array(
			"online_timestamp" => time()
			)
		);
	}

}

?>
