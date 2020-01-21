<?php

class Mail_Model extends CI_Model 
{

	public function get_user_mail($userid, $page) 
	{
		return $this->db
		->where("((mail.delete_userid = 0 AND mail.userid = $userid) OR 
			(mail.delete_toid = 0 AND mail.toid = $userid)) AND 
			(mail.userid = $userid OR mail.toid = $userid)")
		->select("users.username, users.avatar, users.online_timestamp,
			users2.username as username2, users2.avatar as avatar2, 
			users2.online_timestamp as online_timestamp2,
			mail.title, mail.toid,
			mail.userid, mail.timestamp, mail.ID, mail.replies, 
			mail.unread_userid, mail.unread_toid, mail.last_reply_timestamp,
			mail_replies.body")
		->join("users", "users.ID = mail.userid")
		->join("users as users2", "users2.ID = mail.toid")
		->join("mail_replies", "mail_replies.ID = mail.last_replyid", "left outer")
		->order_by("mail.last_reply_timestamp", "DESC")
		->limit(8, $page)
		->get("mail");
	}

	public function get_user_mail_search($userid, $search) 
	{
		$this->db
		->where("((mail.delete_userid = 0 AND mail.userid = $userid) OR 
			(mail.delete_toid = 0 AND mail.toid = $userid)) AND 
			(mail.userid = $userid OR mail.toid = $userid)");
		$this->db->like("mail.title", $search);
		$this->db->or_like("users2.username", $search);
		$this->db->or_like("users.username", $search);
		$this->db->or_like("users.email", $search);
		$this->db->or_like("users2.email", $search);
		return $this->db->select("users.username, users.avatar, users.online_timestamp,
			users2.username as username2, users2.avatar as avatar2, 
			users2.online_timestamp as online_timestamp2,
			mail.title, mail.toid,
			mail.userid, mail.timestamp, mail.ID, mail.replies, 
			mail.unread_userid, mail.unread_toid, mail.last_reply_timestamp,
			mail_replies.body")
		->join("users", "users.ID = mail.userid")
		->join("users as users2", "users2.ID = mail.toid")
		->join("mail_replies", "mail_replies.ID = mail.last_replyid", "left outer")
		->order_by("mail.last_reply_timestamp", "DESC")
		->limit(20)
		->get("mail");
	}

	public function get_user_mail_recent($userid, $limit) 
	{
		return $this->db
		->where("((mail.delete_userid = 0 AND mail.userid = $userid) OR 
			(mail.delete_toid = 0 AND mail.toid = $userid)) AND 
			(mail.userid = $userid OR mail.toid = $userid)")
		->select("users.username, users.avatar, users.online_timestamp,
			users2.username as username2, users2.avatar as avatar2, 
			users2.online_timestamp as online_timestamp2,
			mail.title, mail.toid,
			mail.userid, mail.timestamp, mail.ID, mail.replies, 
			mail.unread_userid, mail.unread_toid, mail.last_reply_timestamp,
			mail_replies.body")
		->join("users", "users.ID = mail.userid")
		->join("users as users2", "users2.ID = mail.toid")
		->join("mail_replies", "mail_replies.ID = mail.last_replyid", "left outer")
		->order_by("mail.last_reply_timestamp", "DESC")
		->limit($limit)
		->get("mail");
	}

	public function get_mail($id, $userid) 
	{
		return $this->db
		->where("((mail.delete_userid = 0 AND mail.userid = $userid) OR 
			(mail.delete_toid = 0 AND mail.toid = $userid)) AND 
			(mail.userid = $userid OR mail.toid = $userid)")
		->where("mail.ID", $id)
		->select("users.username, users.avatar, users.online_timestamp,
			users2.username as username2, users2.avatar as avatar2, 
			users2.online_timestamp as online_timestamp2,
			mail.title, mail.toid,
			mail.userid, mail.timestamp, mail.ID, mail.replies, 
			mail.unread_userid, mail.unread_toid, mail.last_reply_timestamp,
			mail.delete_userid, mail.delete_toid,
			mail_replies.body")
		->join("users", "users.ID = mail.userid")
		->join("users as users2", "users2.ID = mail.toid")
		->join("mail_replies", "mail_replies.ID = mail.last_replyid", "left outer")
		->order_by("mail.last_reply_timestamp", "DESC")
		->get("mail");
	}

	public function get_mail_replies($id, $page) 
	{
		return $this->db
			->where("mail_replies.mailid", $id)
			->select("mail_replies.ID, mail_replies.body, mail_replies.timestamp,
				users.ID as userid, users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = mail_replies.userid")
			->order_by("mail_replies.ID", "DESC")
			->limit(5,$page)
			->get("mail_replies");
	}

	public function get_total_mail_replies_count($id) 
	{
		$s = $this->db->where("mail_replies.mailid", $id)
			->select("COUNT(*) as num")->get("mail_replies");
		$s = $s->row();
		if(isset($s)) return $s->num;
		return 0;
	}

	public function get_total_mail_count($userid) 
	{
		$s = $this->db
		->where("((mail.delete_userid = 0 AND mail.userid = $userid) OR 
			(mail.delete_toid = 0 AND mail.toid = $userid)) AND 
			(mail.userid = $userid OR mail.toid = $userid)")
		->select("COUNT(*) as num")
		->get("mail");
		$s = $s->row();
		if(isset($s)) return $s->num;
		return 0;
	}

	public function add_reply($data) 
	{
		$this->db->insert("mail_replies", $data);
		return $this->db->insert_id();
	}

	public function update_mail($id, $data) 
	{
		$this->db->where("ID", $id)->update("mail", $data);
	}

	public function create_mail($data) 
	{
		$this->db->insert("mail", $data);
		return $this->db->insert_id();
	}

	public function delete($id) 
	{
		$this->db->where("ID", $id)->delete("mail");
		$this->db->where("mailid", $id)->delete("mail_replies");
	}

	public function get_blocked_users($userid)
	{
		return $this->db
			->where("user_blocks.userid", $userid)
			->select("user_blocks.timestamp, user_blocks.ID, user_blocks.reason,
				users.ID as userid, users.username, users.avatar")
			->join("users", "users.ID = user_blocks.blockid")
			->get("user_blocks");
	}

	public function get_blocked_user($userid, $blockid) 
	{
		return $this->db
			->where("user_blocks.userid", $userid)
			->where("user_blocks.blockid", $blockid)
			->get("user_blocks");
	}

	public function add_blocked_user($data) 
	{
		$this->db->insert("user_blocks", $data);
	}

	public function get_block($userid, $id)
	{
		return $this->db
			->where("userid", $userid)
			->where("ID", $id)
			->get("user_blocks");
	}

	public function delete_block($id) 
	{
		$this->db->where("ID", $id)->delete("user_blocks");
	}

}

?>