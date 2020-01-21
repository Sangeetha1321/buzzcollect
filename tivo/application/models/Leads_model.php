<?php

class Leads_Model extends CI_Model 
{

	public function add_lead_form($data) 
	{
		$this->db->insert("lead_forms", $data);
		return $this->db->insert_id();
	}

	public function get_lead_form($id) 
	{
		return $this->db
			->where("lead_forms.ID", $id)
			->select("lead_forms.ID, lead_forms.title, 
				lead_forms.welcome, lead_forms.timestamp,
				lead_forms.userid, lead_forms.assignedid, 
				lead_forms.collect_user, lead_forms.default_statusid,
				lead_forms.default_sourceid,
				users.username as assigned_username, 
				users.avatar assigned_avatar, users.online_timestamp as 
				assigned_online_timestamp, users.email as assigned_email, 
				users.email_notification as assigned_email_notification")
			->join("users", "users.ID = lead_forms.assignedid", "left outer")
			->get("lead_forms");
	}

	public function get_lead_forms($page) 
	{
		return $this->db
			->select("lead_forms.ID, lead_forms.title, 
				lead_forms.welcome, lead_forms.timestamp,
				lead_forms.userid, lead_forms.collect_user,
				users.username as assigned_username, 
				users.avatar assigned_avatar, users.online_timestamp as 
				assigned_online_timestamp")
			->join("users", "users.ID = lead_forms.assignedid", "left outer")
			->limit(15, $page)
			->get("lead_forms");
	}

	public function delete_lead_form($id) 
	{
		$this->db->where("ID", $id)->delete("lead_forms");
	}

	public function update_lead_form($id, $data) 
	{
		$this->db->where("ID", $id)->update("lead_forms", $data);
	}

	public function add_field($data) 
	{
		$this->db->insert("lead_form_fields", $data);
	}

	public function get_form_fields($formid) 
	{
		return $this->db->where("formid", $formid)->get("lead_form_fields");
	}

	public function update_form_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("lead_form_fields", $data);
	}

	public function delete_form_field($id) 
	{
		$this->db->where("ID", $id)->delete("lead_form_fields");
	}

	public function add_user_lead($data) 
	{
		$this->db->insert("user_leads", $data);
		return $this->db->insert_id();
	}

	public function add_user_lead_answer($data)
	{
		$this->db->insert("user_lead_fields", $data);
	}

	public function get_user_leads($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"lead_forms.title",
			"users.username",
			"as.username",
			"user_leads.IP",
			)
		);

		return $this->db
			->select("user_leads.ID, user_leads.timestamp, user_leads.IP,
				user_leads.userid, user_leads.status, user_leads.first_name,
				user_leads.last_name,
				lead_forms.title, lead_forms.ID as formid, lead_forms.collect_user,
				users.username as client_username, users.avatar as client_avatar,
				users.online_timestamp as client_online_timestamp,
				users.first_name as client_first_name, 
				users.last_name as client_last_name,
				as.username as assigned_username, as.avatar as assigned_avatar,
				as.online_timestamp as assigned_online_timestamp,
				as.first_name as assigned_first_name, 
				as.last_name as assigned_last_name,
				lead_statuses.name as status,
				lead_sources.name as source")
			->join("lead_forms", "lead_forms.ID = user_leads.formid")
			->join("users", "users.ID = user_leads.userid", "left outer")
			->join("users as as", "as.ID = user_leads.assignedid", "left outer")
			->join("lead_statuses", "lead_statuses.ID = user_leads.statusid", "left outer")
			->join("lead_sources", "lead_sources.ID = user_leads.sourceid", "left outer")
			->limit($datatable->length, $datatable->start)
			->order_by("user_leads.ID", "DESC")
			->get("user_leads");
	}

	public function get_user_leads_total() 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->get("user_leads");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_leads_assigned($datatable, $userid) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"lead_forms.title",
			"users.username",
			"as.username",
			"user_leads.IP",
			)
		);

		return $this->db
			->where("user_leads.assignedid", $userid)
			->select("user_leads.ID, user_leads.timestamp, user_leads.IP,
				user_leads.userid, user_leads.status, user_leads.first_name,
				user_leads.last_name,
				lead_forms.title, lead_forms.ID as formid, lead_forms.collect_user,
				users.username as client_username, users.avatar as client_avatar,
				users.online_timestamp as client_online_timestamp,
				users.first_name as client_first_name, 
				users.last_name as client_last_name,
				as.username as assigned_username, as.avatar as assigned_avatar,
				as.online_timestamp as assigned_online_timestamp,
				as.first_name as assigned_first_name, 
				as.last_name as assigned_last_name,
				lead_statuses.name as status,
				lead_sources.name as source")
			->join("lead_forms", "lead_forms.ID = user_leads.formid")
			->join("users", "users.ID = user_leads.userid", "left outer")
			->join("users as as", "as.ID = user_leads.assignedid", "left outer")
			->join("lead_statuses", "lead_statuses.ID = user_leads.statusid", "left outer")
			->join("lead_sources", "lead_sources.ID = user_leads.sourceid", "left outer")
			->limit($datatable->length, $datatable->start)
			->order_by("user_leads.ID", "DESC")
			->get("user_leads");
	}

	public function get_user_leads_total_assigned($userid) 
	{
		$s = $this->db
			->where("assignedid", $userid)
			->select("COUNT(*) as num")
			->get("user_leads");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_lead($id) 
	{
		return $this->db
			->where("user_leads.ID", $id)
			->select("user_leads.ID, user_leads.timestamp, user_leads.IP,
				user_leads.userid, user_leads.status, user_leads.notes,
				user_leads.first_name, user_leads.last_name, user_leads.address_1,
				user_leads.address_2, user_leads.city, user_leads.state, user_leads.zipcode,
				user_leads.country, user_leads.email, user_leads.user_added,
				user_leads.statusid, user_leads.sourceid,
				lead_forms.title, lead_forms.ID as formid, lead_forms.collect_user,
				users.username as client_username, users.avatar as client_avatar,
				users.online_timestamp as client_online_timestamp,
				as.username as assigned_username, as.avatar as assigned_avatar,
				as.online_timestamp as assigned_online_timestamp,
				lead_statuses.name as status,
				lead_sources.name as source")
			->join("lead_forms", "lead_forms.ID = user_leads.formid")
			->join("users", "users.ID = user_leads.userid", "left outer")
			->join("users as as", "as.ID = user_leads.assignedid", "left outer")
			->join("lead_statuses", "lead_statuses.ID = user_leads.statusid", "left outer")
			->join("lead_sources", "lead_sources.ID = user_leads.sourceid", "left outer")
			->get("user_leads");
	}

	public function get_lead_fields($id, $formid) 
	{
		return $this->db
			->select("lead_form_fields.ID, lead_form_fields.title, lead_form_fields.type,
				lead_form_fields.required, lead_form_fields.description,
				lead_form_fields.options,
				user_lead_fields.answer")
			->join("user_lead_fields", "user_lead_fields.fieldid = lead_form_fields.ID
			 AND user_lead_fields.leadid = " . $id, "LEFT OUTER")
			->where("lead_form_fields.formid", $formid)
			->get("lead_form_fields");

	}

	public function delete_user_lead($id) 
	{
		$this->db->where("ID", $id)->delete("user_leads");
	}

	public function delete_user_lead_fields($id) 
	{
		$this->db->where("leadid", $id)->delete("user_lead_fields");
	}

	public function update_user_lead($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_leads", $data);
	}

	public function add_user_custom_field($data) 
	{
		$this->db->insert("user_lead_custom_fields", $data);
	}

	public function get_user_custom_field($leadid) 
	{
		return $this->db
			->select("custom_fields.name, user_lead_custom_fields.value,
				user_lead_custom_fields.ID as ulcfid, custom_fields.required, custom_fields.type,
				custom_fields.options, custom_fields.help_text, custom_fields.ID")
			->where("custom_fields.leads", 1)
			->join("user_lead_custom_fields", "user_lead_custom_fields.fieldid = custom_fields.ID AND user_lead_custom_fields.leadid = " . $leadid, "left outer")
			->get("custom_fields");
	}

	public function delete_user_lead_custom_fields($leadid) 
	{
		$this->db->where("leadid", $leadid)->delete("user_lead_custom_fields");
	}

	public function add_source($data) 
	{
		$this->db->insert("lead_sources", $data);
	}

	public function add_status($data) 
	{
		$this->db->insert("lead_statuses", $data);
	}

	public function get_statuses() 
	{
		return $this->db->get("lead_statuses");
	}

	public function get_sources() 
	{
		return $this->db->get("lead_sources");
	}

	public function get_status($id) 
	{
		return $this->db->where("ID", $id)->get("lead_statuses");
	}

	public function get_source($id) 
	{
		return $this->db->where("ID", $id)->get("lead_sources");
	}

	public function delete_status($id) 
	{
		$this->db->where("ID", $id)->delete("lead_statuses");
	}

	public function delete_source($id) 
	{
		$this->db->where("ID", $id)->delete("lead_sources");
	}

	public function get_lead_notes($leadid, $limit) 
	{
		return $this->db->where("lead_notes.leadid", $leadid)
			->select("lead_notes.ID, lead_notes.note, lead_notes.timestamp,
				lead_notes.userid,
				users.username, users.avatar, users.online_timestamp,
				users.first_name, users.last_name")
			->join("users", "users.ID = lead_notes.userid")
			->limit(5, $limit)
			->order_by("lead_notes.ID", "DESC")
			->get("lead_notes");
	}

	public function get_lead_note($id) 
	{
		return $this->db->where("ID", $id)->get("lead_notes");
	}

	public function add_lead_note($data) 
	{
		$this->db->insert("lead_notes", $data);
	}

	public function delete_lead_note($id) 
	{
		$this->db->where("ID", $id)->delete("lead_notes");
	}

	public function update_lead_note($id, $data) 
	{
		$this->db->where("ID", $id)->update("lead_notes", $data);
	}

	public function get_total_lead_notes($id) 
	{
		$s = $this->db->where("leadid", $id)
			->select("COUNT(*) as num")->get("lead_notes");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;

	}

}

?>