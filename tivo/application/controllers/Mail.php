<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("mail_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("mail" => array("general" => 1)));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
	}


	public function index($default_mail=0, $page = 0) 
	{
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/mail.js" /></script>
			'
		);
		$page = intval($page);

		$mail = $this->mail_model->get_user_mail($this->user->info->ID, $page);
		$default_mail = intval($default_mail);

		if($default_mail == 0 && $mail->num_rows() > 0) {
			$df = $mail->row();
			$default_mail = $df->ID;
		}

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("mail/index/0/");
		$config['total_rows'] = $this->mail_model
			->get_total_mail_count($this->user->info->ID);
		$config['per_page'] = 8;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		

		$this->template->loadContent("mail/index.php", array(
			"mail" => $mail,
			"default_mail" => $default_mail		
			)
		);
	}

	public function search() 
	{
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/mail.js" /></script>
			'
		);

		$search = $this->common->nohtml($this->input->post("search"));
		if(empty($search)) {
			$this->template->error(lang("error_135"));
		}

		$mail = $this->mail_model->get_user_mail_search($this->user->info->ID,
			$search);
		$default_mail = 0;

		if($default_mail == 0 && $mail->num_rows() > 0) {
			$df = $mail->row();
			$default_mail = $df->ID;
		}
		

		$this->template->loadContent("mail/index.php", array(
			"mail" => $mail,
			"default_mail" => $default_mail,
			"search" => $search	
			)
		);
	}

	public function view_mail($id, $page=0) 
	{
		$id = intval($id);
		$page = intval($page);
		$mail = $this->mail_model->get_mail($id, $this->user->info->ID);
		if($mail->num_rows() == 0) {
			$this->template->errori(lang("error_136"));
		}
		$mail = $mail->row();

		// Check for unread
		if($mail->userid == $this->user->info->ID) {
			if($mail->unread_userid) {
				$this->mail_model->update_mail($mail->ID, array(
					"unread_userid" => 0
					)
				);
				$this->user_model
					->decrement_field($mail->userid, "email_count", 1);
			}
		} elseif($mail->toid == $this->user->info->ID) {
			if($mail->unread_toid) {
				$this->mail_model->update_mail($mail->ID, array(
					"unread_toid" => 0
					)
				);
				$this->user_model
					->decrement_field($mail->toid, "email_count", 1);
			}
		}

		$replies = $this->mail_model->get_mail_replies($id, $page);

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("mail/view_mail/" . $id);
		$config['total_rows'] = $this->mail_model
			->get_total_mail_replies_count($id);
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config2.php");

		$this->pagination->initialize($config); 

		$replies_array = array();
		foreach($replies->result() as $r) {
			$replies_array[] = $r;
		}

		$replies = array_reverse($replies_array);

		$this->template->loadAjax("mail/view.php", array(	
			"mail" => $mail,
			"replies" => $replies
			), 0
		);
	}

	public function compose() 
	{
		$username = $this->common->nohtml($this->input->get("username"));
		$this->template->loadAjax("mail/compose.php", array("username" => $username)
		);
	}

	public function compose_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$username = $this->common->nohtml($this->input->post("username"));
		$reply = $this->lib_filter->go($this->input->post("reply"));

		if(empty($title)) $this->template->error(lang("error_106"));
		if(empty($reply)) $this->template->error(lang("error_137"));

		// Check user is good
		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) $this->template->error(lang("error_138"));
		$user = $user->row();

		// Create mail
		$mailid = $this->mail_model->create_mail(array(
			"userid" => $this->user->info->ID,
			"toid" => $user->ID,
			"timestamp" => time(),
			"title" => $title,
			"unread_toid" => 1
			)
		);

		$this->user_model->increment_field($user->ID, "email_count", 1);

		$replyid = $this->mail_model->add_reply(array(
			"mailid" => $mailid,
			"userid" => $this->user->info->ID,
			"body" => $reply,
			"timestamp" => time()
			)
		);

		$this->mail_model->update_mail($mailid, array(
			"last_reply_timestamp" => time(),
			"last_replyid" => $replyid,
			"replies" => 1
			)
		);

		// Send notifications ?

		$this->session->set_flashdata("globalmsg", lang("success_60"));
		redirect(site_url("mail"));
	}

	public function reply($id) 
	{
		$id = intval($id);
		$mail = $this->mail_model->get_mail($id, $this->user->info->ID);
		if($mail->num_rows() == 0) {
			$this->template->errori(lang("error_136"));
		}
		$mail = $mail->row();

		$reply = $this->lib_filter->go($this->input->post("reply"));
		if(empty($reply)) $this->template->error(lang("error_139"));

		$replyid = $this->mail_model->add_reply(array(
			"mailid" => $id,
			"userid" => $this->user->info->ID,
			"body" => $reply,
			"timestamp" => time()
			)
		);

		$this->mail_model->update_mail($id, array(
			"last_reply_timestamp" => time(),
			"last_replyid" => $replyid,
			"replies" => $mail->replies+1
			)
		);

		// Check for unread
		if($mail->userid == $this->user->info->ID) {
			if(!$mail->unread_toid) {
				$this->mail_model->update_mail($mail->ID, array(
					"unread_toid" => 1
					)
				);
				$this->user_model
					->increment_field($mail->toid, "email_count", 1);
			}
		} elseif($mail->toid == $this->user->info->ID) {
			if(!$mail->unread_userid) {
				$this->mail_model->update_mail($mail->ID, array(
					"unread_userid" => 1
					)
				);
				$this->user_model
					->increment_field($mail->userid, "email_count", 1);
			}
		}



		$this->session->set_flashdata("globalmsg", lang("success_60"));
		redirect(site_url("mail"));
	}

	public function delete($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$mail = $this->mail_model->get_mail($id, $this->user->info->ID);
		if($mail->num_rows() == 0) {
			$this->template->errori(lang("error_136"));
		}
		$mail = $mail->row();

		if ($this->user->info->ID == $mail->userid) {
			$this->mail_model->update_mail($mail->ID, array(
				"delete_userid" => 1
				)
			);
		} else {
			$this->mail_model->update_mail($mail->ID, array(
				"delete_toid" => 1
				)
			);
		}

		if ($mail->delete_userid || $mail->delete_toid) {
			$this->mail_model->delete($id);
		}
		$this->session->set_flashdata("globalmsg", lang("success_61"));
		redirect(site_url("mail"));
	}

	public function blocked() 
	{
		$blocks = $this->mail_model->get_blocked_users($this->user->info->ID);
		$this->template->loadContent("mail/blocked.php", array(
			"blocks" => $blocks
			)
		);
	}

	public function add_blocked_user() 
	{
		$username = $this->common->nohtml($this->input->post("username"));
		$reason = $this->common->nohtml($this->input->post("reason"));
		if(empty($username)) $this->template->error(lang("error_140"));

		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) $this->template->error(lang("error_138"));
		$user = $user->row();

		// Check not already on blocked list
		$block = $this->mail_model
			->get_blocked_user($this->user->info->ID, $user->ID);
		if($block->num_rows() > 0) {
			$this->template->error(lang("error_141"));
		}

		// Add
		$this->mail_model->add_blocked_user(array(
			"userid" => $this->user->info->ID,
			"blockid" => $user->ID,
			"timestamp" => time(),
			"reason" => $reason
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_62"));
		redirect(site_url("mail/blocked"));
	}

	public function remove_block($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->erro(lang("error_6"));
		}
		$id = intval($id);
		$block = $this->mail_model->get_block($this->user->info->ID, $id);
		if($block->num_rows() == 0) $this->template->error(lang("error_142"));

		$this->mail_model->delete_block($id);
		$this->session->set_flashdata("globalmsg", 
			lang("success_63"));
		redirect(site_url("mail/blocked"));
	}

}

?>
