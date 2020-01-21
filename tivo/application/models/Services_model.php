<?php

class Services_Model extends CI_Model 
{

	public function add_service($data) 
	{
		$this->db->insert("service_forms", $data);
		return $this->db->insert_id();
	}

	public function add_field($data) 
	{
		$this->db->insert("service_form_fields", $data);
	}

	public function get_services_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("service_forms");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_services() 
	{
		return $this->db
		->select("service_forms.ID, service_forms.title, service_forms.cost,
				service_forms.invoice, service_forms.welcome, 
				service_forms.currencyid, service_forms.invoice_message,
				currencies.symbol")
		->join("currencies", "currencies.ID = service_forms.currencyid")
		->get("service_forms");
	}

	public function get_services($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"service_forms.title",
			"users.username",
			)
		);

		return $this->db
			->select("service_forms.ID, service_forms.title, service_forms.cost,
				service_forms.invoice, service_forms.welcome, 
				service_forms.currencyid, service_forms.invoice_message,
				users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = service_forms.userid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("service_forms");
	}

	public function get_service($id) 
	{
		return $this->db
			->where("service_forms.ID", $id)
			->select("service_forms.ID, service_forms.title, service_forms.cost,
				service_forms.invoice, service_forms.welcome, 
				service_forms.currencyid, service_forms.invoice_message,
				service_forms.require_login, service_forms.userid, 
				service_forms.paying_accountid,
				users.username, users.avatar, users.online_timestamp, users.email 
				as assigned_email, 
				users.email_notification as assigned_email_notification")
			->join("users", "users.ID = service_forms.userid", "left outer")
			->get("service_forms");
	}

	public function delete_service($id) 
	{
		$this->db->where("ID", $id)->delete("service_forms");
	}

	public function update_service($id, $data) 
	{
		$this->db->where("ID", $id)->update("service_forms", $data);
	}

	public function get_form_fields($id) 
	{
		return $this->db->where("formid", $id)->get("service_form_fields");
	}

	public function delete_form_field($id) 
	{
		$this->db->where("ID", $id)->delete("service_form_fields");
	}

	public function update_form_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("service_form_fields", $data);
	}

	public function add_user_service($data) 
	{
		$this->db->insert("user_services", $data);
		return $this->db->insert_id();
	}

	public function add_user_service_answer($data) 
	{
		$this->db->insert("user_service_fields", $data);
	}

	public function get_orders_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("user_services");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_orders($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"service_forms.title",
			"user_services.email",
			"users.username"
			)
		);

		return $this->db
			->select("service_forms.ID as serviceid, service_forms.title,
				users.username, users.avatar, users.online_timestamp,
				user_services.ID, user_services.total_cost, user_services.invoiceid, 
				user_services.email, user_services.timestamp, user_services.IP,
				user_services.name,
				invoices.status")
			->join("users", "users.ID = user_services.userid", "left outer")
			->join("service_forms", "service_forms.ID = user_services.formid")
			->join("invoices", "invoices.ID = user_services.invoiceid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("user_services");
	}

	public function get_order($id) 
	{
		return $this->db
			->where("user_services.ID", $id)
			->select("user_services.ID, user_services.email, user_services.timestamp,
				user_services.IP, user_services.userid, user_services.total_cost,
				user_services.invoiceid, user_services.name,
				service_forms.ID as formid, service_forms.title, service_forms.currencyid,
				service_forms.cost, service_forms.invoice_message, 
				service_forms.paying_accountid,
				invoices.hash as invoice_hash")
			->join("users", "users.ID = user_services.userid", "left outer")
			->join("service_forms", "service_forms.ID = user_services.formid")
			->join("invoices", "invoices.ID = user_services.invoiceid", "left outer")
			->get("user_services");
	}

	public function delete_order($id) 
	{
		$this->db->where("ID", $id)->delete("user_services");
	}

	public function get_order_fields($serviceid, $formid) 
	{

		return $this->db
			->where("service_form_fields.formid", $formid)
			->select("service_form_fields.ID, service_form_fields.title,
				service_form_fields.description, service_form_fields.required,
				service_form_fields.type, service_form_fields.options, 
				service_form_fields.cost,
				user_service_fields.ID as usfid, user_service_fields.answer")
			->join("user_service_fields", "user_service_fields.fieldid = service_form_fields.ID AND user_service_fields.serviceid = " . $serviceid, "left outer")
			->get("service_form_fields");
		return $this->db->where("formid", $id)->get("service_form_fields");
	}

	public function delete_order_answers($id) 
	{
		$this->db->where("serviceid", $id)->delete("user_service_fields");
	}

	public function update_user_service($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_services", $data);
	}



}

?>