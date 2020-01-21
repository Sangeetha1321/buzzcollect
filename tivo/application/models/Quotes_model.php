<?php

class Quotes_Model extends CI_Model 
{

	public function add_quote_form($data) 
	{
		$this->db->insert("quote_forms", $data);
		return $this->db->insert_id();
	}

	public function get_quote_form($id) 
	{
		return $this->db
			->where("quote_forms.ID", $id)
			->select("quote_forms.ID, quote_forms.title, 
				quote_forms.welcome, quote_forms.timestamp,
				quote_forms.userid, quote_forms.assignedid,
				users.username as assigned_username, 
				users.avatar assigned_avatar, users.online_timestamp as 
				assigned_online_timestamp, users.email as assigned_email, 
				users.email_notification as assigned_email_notification")
			->join("users", "users.ID = quote_forms.assignedid", "left outer")
			->get("quote_forms");
	}

	public function get_quote_forms($page) 
	{
		return $this->db
			->select("quote_forms.ID, quote_forms.title, 
				quote_forms.welcome, quote_forms.timestamp,
				quote_forms.userid,
				users.username as assigned_username, 
				users.avatar assigned_avatar, users.online_timestamp as 
				assigned_online_timestamp")
			->join("users", "users.ID = quote_forms.assignedid", "left outer")
			->limit(15, $page)
			->get("quote_forms");
	}

	public function delete_quote_form($id) 
	{
		$this->db->where("ID", $id)->delete("quote_forms");
	}

	public function update_quote_form($id, $data) 
	{
		$this->db->where("ID", $id)->update("quote_forms", $data);
	}

	public function add_field($data) 
	{
		$this->db->insert("quote_form_fields", $data);
	}

	public function get_form_fields($formid) 
	{
		return $this->db->where("formid", $formid)->get("quote_form_fields");
	}

	public function update_form_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("quote_form_fields", $data);
	}

	public function delete_form_field($id) 
	{
		$this->db->where("ID", $id)->delete("quote_form_fields");
	}

	public function add_user_quote($data) 
	{
		$this->db->insert("user_quotes", $data);
		return $this->db->insert_id();
	}

	public function add_user_quote_answer($data)
	{
		$this->db->insert("user_quote_fields", $data);
	}

	public function get_user_quotes($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"quote_forms.title",
			"users.username",
			"as.username",
			"user_quotes.IP",
			)
		);

		return $this->db
			->select("user_quotes.ID, user_quotes.timestamp, user_quotes.IP,
				user_quotes.userid, user_quotes.status,
				quote_forms.title, quote_forms.ID as formid,
				users.username as client_username, users.avatar as client_avatar,
				users.online_timestamp as client_online_timestamp,
				as.username as assigned_username, as.avatar as assigned_avatar,
				as.online_timestamp as assigned_online_timestamp")
			->join("quote_forms", "quote_forms.ID = user_quotes.formid")
			->join("users", "users.ID = user_quotes.userid", "left outer")
			->join("users as as", "as.ID = quote_forms.assignedid", "left outer")
			->limit($datatable->length, $datatable->start)
			->order_by("user_quotes.ID", "DESC")
			->get("user_quotes");
	}

	public function get_user_quotes_total() 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->get("user_quotes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_quote($id) 
	{
		return $this->db
			->where("user_quotes.ID", $id)
			->select("user_quotes.ID, user_quotes.timestamp, user_quotes.IP,
				user_quotes.userid, user_quotes.status, user_quotes.notes,
				quote_forms.title, quote_forms.ID as formid,
				users.username as client_username, users.avatar as client_avatar,
				users.online_timestamp as client_online_timestamp,
				as.username as assigned_username, as.avatar as assigned_avatar,
				as.online_timestamp as assigned_online_timestamp")
			->join("quote_forms", "quote_forms.ID = user_quotes.formid")
			->join("users", "users.ID = user_quotes.userid", "left outer")
			->join("users as as", "as.ID = quote_forms.assignedid", "left outer")
			->get("user_quotes");
	}

	public function get_quote_fields($id, $formid) 
	{
		return $this->db
			->select("quote_form_fields.ID, quote_form_fields.title, quote_form_fields.type,
				quote_form_fields.required, quote_form_fields.description,
				quote_form_fields.options,
				user_quote_fields.answer")
			->join("user_quote_fields", "user_quote_fields.fieldid = quote_form_fields.ID
			 AND user_quote_fields.quoteid = " . $id, "LEFT OUTER")
			->where("quote_form_fields.formid", $formid)
			->get("quote_form_fields");

	}

	public function delete_user_quote($id) 
	{
		$this->db->where("ID", $id)->delete("user_quotes");
	}

	public function update_user_quote($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_quotes", $data);
	}

}

?>