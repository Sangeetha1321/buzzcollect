<?php

class chat_Model extends CI_Model 
{

	public function add_new_chat($data) 
	{
		$this->db->insert("live_chat", $data);
		return $this->db->insert_id();
	}

	public function update_chat($id, $data) 
	{
		$this->db->where("ID", $id)->update("live_chat", $data);
	}

	public function add_chat_user($data) 
	{
		$this->db->insert("live_chat_users", $data);
	}

	public function get_active_chats($userid) 
	{
		return $this->db
			->where("live_chat_users.userid", $userid)
			->select("live_chat.ID, live_chat_users.title, live_chat_users.active,
				live_chat_users.unread, live_chat_users.ID as chatuserid, 
				live_chat.title as lc_title")
			->join("live_chat", "live_chat.ID = live_chat_users.chatid")
			->get("live_chat_users");
	}

	public function get_live_chat($id) 
	{
		return $this->db->where("ID", $id)
			->get("live_chat");
	}

	public function get_chat_user($chatid, $userid) 
	{
		return $this->db->where("userid", $userid)
			->where("chatid", $chatid)
			->get("live_chat_users");
	}

	public function get_chat_user_id($id) 
	{
		return $this->db->where("ID", $id)->get("live_chat_users");
	}

	public function update_chat_user($id, $data) 
	{
		$this->db->where("ID", $id)->update("live_chat_users", $data);
	}

	public function add_chat_message($data) 
	{
		$this->db->insert("live_chat_messages", $data);
	}

	public function get_chat_messages($id, $limit) 
	{
		return $this->db
			->where("live_chat_messages.chatid", $id)
			->select("live_chat_messages.ID, live_chat_messages.message,
				live_chat_messages.timestamp, live_chat_messages.userid,
				users.username, users.first_name, users.last_name,
				users.avatar, users.online_timestamp")
			->join("users", "users.ID = live_chat_messages.userid")
			->limit($limit)
			->order_by("live_chat_messages.ID", "DESC")
			->get("live_chat_messages");
	}

	public function update_chat_users($chatid, $data) 
	{
		$this->db->where("chatid", $chatid)->update("live_chat_users", $data);
	}

	public function delete_chat_user($id) 
	{
		$this->db->where("ID", $id)->delete("live_chat_users");
	}

	public function get_chat_users($chatid) 
	{
		return $this->db
			->where("live_chat_users.chatid", $chatid)
			->select("live_chat_users.ID, live_chat_users.chatid,
				live_chat_users.userid, live_chat_users.title,
				live_chat_users.active, live_chat_users.unread,
				users.username, users.first_name, users.last_name,
				users.online_timestamp, users.avatar")
			->join("users", "users.ID = live_chat_users.userid")
			->get("live_chat_users");
	}

	public function delete_chat($id) 
	{
		$this->db->where("ID", $id)->delete("live_chat");
	}

	public function get_total_chat_messages($chatid) 
	{
		$s = $this->db
			->where("chatid", $chatid)
			->select("COUNT(*) as num")->get("live_chat_messages");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_chat_messages_page($id, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"live_chat_messages.message"
			)
		);

		return $this->db
			->where("live_chat_messages.chatid", $id)
			->select("live_chat_messages.ID, live_chat_messages.message,
				live_chat_messages.timestamp, live_chat_messages.userid,
				users.username, users.first_name, users.last_name,
				users.avatar, users.online_timestamp")
			->join("users", "users.ID = live_chat_messages.userid")
			->limit($datatable->length, $datatable->start)
			->order_by("live_chat_messages.ID", "DESC")
			->get("live_chat_messages");
	}

	public function get_chat_message($id) 
	{
		return $this->db->where("ID", $id)->get("live_chat_messages");
	}

	public function delete_chat_message($id) 
	{
		$this->db->where("ID", $id)->delete("live_chat_messages");
	}

}

?>