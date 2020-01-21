<?php

class Tickets_Model extends CI_Model 
{

	public function add_department($data) 
	{
		$this->db->insert("ticket_departments", $data);
		return $this->db->insert_id();
	}

	public function get_departments() 
	{
		return $this->db->get("ticket_departments");
	}

	public function get_department($id) 
	{
		return $this->db->where("ID", $id)->get("ticket_departments");
	}

	public function add_category_group($data) 
	{
		$this->db->insert("ticket_category_groups", $data);
	}

	public function get_cat_groups($catid) 
	{
		return $this->db
			->select("user_groups.ID, user_groups.name, ticket_category_groups.ID as cid")
			->join("ticket_category_groups", "ticket_category_groups.groupid = user_groups.ID 
				AND ticket_category_groups.catid = " . $catid, "left outer")
			->get("user_groups");
	}

	public function delete_category_groups($catid) 
	{
		$this->db->where("catid", $catid)->delete("ticket_category_groups");
	}

	public function update_department($id, $data) 
	{
		$this->db->where("ID", $id)->update("ticket_departments", $data);
	}

	public function delete_department($id)
	{
		$this->db->where("ID", $id)->delete("ticket_departments");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("ticket_custom_fields", $data);
	}

	public function get_custom_fields() 
	{
		return $this->db->get("ticket_custom_fields");
	}

	public function get_custom_field($id) 
	{
		return $this->db->where("ID", $id)->get("ticket_custom_fields");
	}

	public function delete_custom_field($id) 
	{
		$this->db->where("ID", $id)->delete("ticket_custom_fields");
	}

	public function update_custom_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("ticket_custom_fields", $data);
	}

	public function add_ticket($data) 
	{
		$this->db->insert("tickets", $data);
		return $this->db->insert_id();
	}

	public function add_custom_field_data($data) 
	{
		$this->db->insert("ticket_custom_field_data", $data);
	}

	public function get_tickets($departmentid, $datatable) 
	{
		if($departmentid > 0) {
			$this->db->where("tickets.departmentid", $departmentid);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"tickets.title",
			"users.username",
			"users2.username",
			)
		);

		return $this->db
			->select("tickets.ID, tickets.title, tickets.body, tickets.timestamp,
				tickets.priority, tickets.status, tickets.last_reply_userid,
				tickets.last_reply_timestamp, tickets.departmentid,
				ticket_departments.name as catname,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				users2.ID as assignedid, users2.username as assigned_username, 
				users2.avatar as assigned_avatar, users2.online_timestamp as 
				assigned_online_timestamp,
				users3.avatar as lr_avatar, users3.username as lr_username,
				users3.online_timestamp as lr_online_timestamp")
			->join("users", "users.ID = tickets.userid", "left outer")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->limit($datatable->length, $datatable->start)
			->get("tickets");
	}

	public function get_tickets_user($userid, $departmentid, $datatable) 
	{
		if($departmentid > 0) {
			$this->db->where("tickets.departmentid", $departmentid);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"tickets.title",
			"users.username",
			"users2.username",
			)
		);

		return $this->db
			->where("tickets.assignedid", $userid)
			->select("tickets.ID, tickets.title, tickets.body, tickets.timestamp,
				tickets.priority, tickets.status, tickets.last_reply_userid,
				tickets.last_reply_timestamp, tickets.departmentid,
				ticket_departments.name as catname,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				users2.ID as assignedid, users2.username as assigned_username, 
				users2.avatar as assigned_avatar, users2.online_timestamp as 
				assigned_online_timestamp,
				users3.avatar as lr_avatar, users3.username as lr_username,
				users3.online_timestamp as lr_online_timestamp")
			->join("users", "users.ID = tickets.userid", "left outer")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->limit($datatable->length, $datatable->start)
			->order_by("tickets.last_reply_timestamp", "DESC")
			->get("tickets");
	}

	public function get_tickets_user_fp($userid, $priority, $status, $departmentid, $page, $max=10) 
	{
		if($departmentid > 0) {
			$this->db->where("tickets.departmentid", $departmentid);
		}

		return $this->db
			->where("tickets.assignedid", $userid)
			->select("tickets.ID, tickets.title, tickets.body, tickets.timestamp,
				tickets.priority, tickets.status, tickets.last_reply_userid,
				tickets.last_reply_timestamp, tickets.departmentid,
				ticket_departments.name as catname,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				users2.ID as assignedid, users2.username as assigned_username, 
				users2.avatar as assigned_avatar, users2.online_timestamp as 
				assigned_online_timestamp,
				users3.avatar as lr_avatar, users3.username as lr_username,
				users3.online_timestamp as lr_online_timestamp")
			->join("users", "users.ID = tickets.userid", "left outer")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->limit($max, $page)
			->order_by("tickets.last_reply_timestamp", "DESC")
			->get("tickets");
	}

	public function add_attached_files($data) 
	{
		$this->db->insert("ticket_files", $data);
	}

	public function get_total_tickets_count($departmentid) 
	{
		if($departmentid > 0) {
			$this->db->where("tickets.departmentid", $departmentid);
		}

		$s = $this->db->select("COUNT(*) as num")->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_tickets_user_count($userid, $departmentid) 
	{
		if($departmentid > 0) {
			$this->db->where("tickets.departmentid", $departmentid);
		}

		$s = $this->db
			->where("assignedid", $userid)
			->select("COUNT(*) as num")
			->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_ticket($id) 
	{
		return $this->db->where("tickets.ID", $id)
			->select("tickets.ID, tickets.title, tickets.body, tickets.assignedid,
				tickets.userid, tickets.timestamp, tickets.status, tickets.priority,
				tickets.last_reply_timestamp, tickets.departmentid, tickets.notes,
				tickets.message_id_hash,
				users2.username as assigned_username, users2.email as assigned_email,
				users2.email_notification as assigned_email_notification,
				users.username as client_username, users.avatar as client_avatar,
				users.email as client_email, users.online_timestamp as 
				client_online_timestamp, users.first_name, users.last_name,
				users.address_1, users.address_2, users.city, users.state,
				users.zipcode, users.country,
				users.email_notification as client_email_notification,
				ticket_departments.name as catname,
				users3.avatar as lr_avatar, users3.username as lr_username")
			->join("users", "users.ID = tickets.userid")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->get("tickets");
	}

	public function delete_ticket($id) 
	{
		$this->db->where("ID", $id)->delete("tickets");
	}

	public function delete_ticket_replies($id) 
	{
		$this->db->where("ticketid", $id)->delete("ticket_replies");
	}

	public function delete_ticket_files($id) 
	{
		$this->db->where("ticketid", $id)->delete("ticket_files");
	}

	public function get_custom_field_ticket_data($ticketid) 
	{
		return $this->db
			->select("ticket_custom_fields.ID, ticket_custom_fields.name, 
				ticket_custom_fields.help_text, ticket_custom_fields.type,
				ticket_custom_fields.select_options, 
				ticket_custom_fields.required,
				ticket_custom_field_data.value")
			->join("ticket_custom_field_data", "ticket_custom_field_data.fieldid = ticket_custom_fields.ID AND ticket_custom_field_data.ticketid = " . $ticketid, "left outer")
			->get("ticket_custom_fields");
	}

	public function get_ticket_files($ticketid) 
	{
		return $this->db->where("ticketid", $ticketid)->get("ticket_files");
	}

	public function get_reply_files($replyid) 
	{
		return $this->db->where("replyid", $replyid)->get("ticket_files");
	}

	public function delete_ticket_custom_data($id) 
	{
		$this->db->where("ticketid", $id)->delete("ticket_custom_field_data");
	}

	public function update_ticket($id, $data) 
	{
		$this->db->where("ID", $id)->update("tickets", $data);
	}

	public function get_ticket_file($id) 
	{
		return $this->db->where("ID", $id)->get("ticket_files");
	}

	public function delete_ticket_file($id) 
	{
		$this->db->where("ID", $id)->delete("ticket_files");
	}

	public function add_ticket_reply($data) 
	{
		$this->db->insert("ticket_replies", $data);
		return $this->db->insert_id();
	}

	public function get_ticket_replies($id) 
	{
		return $this->db
			->where("ticket_replies.ticketid", $id)
			->select("ticket_replies.ID, ticket_replies.body, 
				ticket_replies.timestamp, ticket_replies.files,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp")
			->join("users", "users.ID = ticket_replies.userid")
			->get("ticket_replies");
	}

	public function get_ticket_reply($id)
	{
		return $this->db->where("ID", $id)->get("ticket_replies");
	}

	public function delete_ticket_reply($id) 
	{
		$this->db->where("ID", $id)->delete("ticket_replies");
	}

	public function get_total_ticket_replies_count($id) 
	{
		$s = $this->db->where("ticketid", $id)
			->select("COUNT(*) as num")
			->get("ticket_replies");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function update_ticket_reply($id, $data) 
	{
		$this->db->where("ID", $id)->update("ticket_replies", $data);
	}

	public function get_client_tickets($userid, $datatable) 
	{
		
		$datatable->db_order();

		$datatable->db_search(array(
			"tickets.title",
			)
		);

		return $this->db
			->where("tickets.userid", $userid)
			->select("tickets.ID, tickets.title, tickets.body, tickets.timestamp,
				tickets.priority, tickets.status, tickets.last_reply_userid,
				tickets.last_reply_timestamp, tickets.departmentid,
				ticket_departments.name as catname,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				users2.ID as assignedid, users2.username as assigned_username, 
				users2.avatar as assigned_avatar, users2.online_timestamp as 
				assigned_online_timestamp,
				users3.avatar as lr_avatar, users3.username as lr_username,
				users3.online_timestamp as lr_online_timestamp")
			->join("users", "users.ID = tickets.userid", "left outer")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->limit($datatable->length, $datatable->start)
			->order_by("tickets.last_reply_timestamp", "DESC")
			->get("tickets");
	}

	public function get_total_tickets_client_count($userid) 
	{
		$s = $this->db->where("userid", $userid)->select("COUNT(*) as num")->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_client_tickets_fp($userid) 
	{
		
		return $this->db
			->where("tickets.userid", $userid)
			->select("tickets.ID, tickets.title, tickets.body, tickets.timestamp,
				tickets.priority, tickets.status, tickets.last_reply_userid,
				tickets.last_reply_timestamp, tickets.departmentid,
				ticket_departments.name as catname,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				users2.ID as assignedid, users2.username as assigned_username, 
				users2.avatar as assigned_avatar, users2.online_timestamp as 
				assigned_online_timestamp,
				users3.avatar as lr_avatar, users3.username as lr_username,
				users3.online_timestamp as lr_online_timestamp")
			->join("users", "users.ID = tickets.userid", "left outer")
			->join("users as users2", "users2.ID = tickets.assignedid", "left outer")
			->join("users as users3", "users3.ID = tickets.last_reply_userid", "left outer")
			->join("ticket_departments", "ticket_departments.ID = tickets.departmentid")
			->limit(5, 0)
			->order_by("tickets.last_reply_timestamp", "DESC")
			->get("tickets");
	}

	public function get_open_tickets() 
	{
		return $this->db->select("tickets.ID, tickets.title, tickets.assignedid,
			tickets.userid")
			->where("tickets.userid = tickets.last_reply_userid")
			->where("tickets.status != 3")
			->get("tickets");
	}

	public function get_users_from_groups($categoryid) 
	{
		return $this->db
			->select("users.ID, users.username, users.avatar, users.online_timestamp,
				users.email, users.email_notification,
				user_groups.name")
			->join("users", "users.ID = user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->join("ticket_category_groups", "ticket_category_groups.groupid = user_groups.ID AND ticket_category_groups.catid = " . $categoryid)
			->group_by("users.ID")
			->get("user_group_users");
	}

}

?>