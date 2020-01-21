<?php

class Admin_Model extends CI_Model 
{

	public function updateSettings($data) 
	{
		$this->db->where("ID", 1)->update("site_settings", $data);
	}

	public function add_ipblock($ip, $reason) 
	{
		$this->db->insert("ip_block", array(
			"IP" => $ip,
			"reason" => $reason,
			"timestamp" => time()
			)
		);
	}
	
	public function get_member_categories() 
	{
		return $this->db->get("member_categories");
	}
	
	public function get_members_skills() 
	{
		return $this->db->get("user_skills");
	}
	
	public function add_skills($data) 
	{
		$this->db->insert("user_skills", $data);
	}

	public function get_skills($id) 
	{
		return $this->db->where("ID", $id)->get("user_skills");
	}

	public function delete_skills($id) 
	{
		$this->db->where("ID", $id)->delete("user_skills");
	}

	public function update_skills($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_skills", $data);
	}

	public function get_ip_blocks() 
	{
		return $this->db->get("ip_block");
	}

	public function get_ip_block($id) 
	{
		return $this->db->where("ID", $id)->get("ip_block");
	}

	public function delete_ipblock($id) {
		$this->db->where("ID", $id)->delete("ip_block");
	}

	public function get_email_template($id) 
	{
		return $this->db->where("ID", $id)->get("email_templates");
	}

	public function add_email_template($data) 
	{
		$this->db->insert("email_templates", $data);
	}

	public function update_email_template($id, $data) 
	{
		$this->db->where("ID", $id)->update("email_templates", $data);
	}

	public function delete_email_template($id) 
	{
		$this->db->where("ID", $id)->delete("email_templates");
	}
	
	public function get_user_groups() 
	{
		return $this->db->get("user_groups");
	}

	public function add_group($data) 
	{
		$this->db->insert("user_groups", $data);
	}

	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}

	public function delete_group($id) {
		$this->db->where("ID", $id)->delete("user_groups");
	}

	public function delete_users_from_group($id) 
	{
		$this->db->where("groupid", $id)->delete("user_group_users");
	}

	public function update_group($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_groups", $data);
	}

	public function get_users_from_groups($id, $page) 
	{
		return $this->db->where("user_group_users.groupid", $id)
			->select("users.ID as userid, users.username, user_groups.name, 
				user_groups.ID as groupid, user_groups.default")
			->join("users", "users.ID = user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->limit(20, $page)
			->get("user_group_users");
	}

	public function get_all_group_users($id) 
	{
		return $this->db->where("user_group_users.groupid", $id)
			->select("users.ID as userid, users.email, users.username, 
				user_groups.name, user_groups.ID as groupid, 
				user_groups.default")
			->join("users", "users.ID = user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->get("user_group_users");
	}

	public function get_total_user_group_members_count($groupid) 
	{
		$s= $this->db->where("groupid", $groupid)
			->select("COUNT(*) as num")->get("user_group_users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_from_group($userid, $id) 
	{
		return $this->db->where("userid", $userid)
			->where("groupid", $id)->get("user_group_users");
	}

	public function delete_user_from_group($userid, $id) 
	{
		$this->db->where("userid", $userid)
			->where("groupid", $id)->delete("user_group_users");
	}

	public function add_user_to_group($userid, $id) 
	{
		$this->db->insert("user_group_users", 
			array(
			"userid" => $userid, 
			"groupid" => $id
			)
		);
	}

	public function get_all_users() 
	{
		return $this->db->select("users.email, users.ID as userid")
			->get("users");
	}

	public function add_payment_plan($data) 
	{
		$this->db->insert("payment_plans", $data);
	}

	public function get_payment_plans() 
	{
		return $this->db->get("payment_plans");
	}

	public function get_payment_plan($id) 
	{
		return $this->db->where("ID", $id)->get("payment_plans");
	}

	public function delete_payment_plan($id) 
	{
		$this->db->where("ID", $id)->delete("payment_plans");
	}

	public function update_payment_plan($id, $data)
	{
		$this->db->where("ID", $id)->update("payment_plans", $data);
	}

	public function get_payment_logs($page) 
	{
		return $this->db->select("users.ID as userid, users.username, users.email,
			payment_logs.email, payment_logs.amount, payment_logs.timestamp, 
			payment_logs.ID, payment_logs.processor")
			->join("users", "users.ID = payment_logs.userid")
			->limit(20, $page)
			->order_by("payment_logs.ID", "DESC")
			->get("payment_logs");
	}

	public function get_total_payment_logs_count() 
	{
		$s= $this->db
			->select("COUNT(*) as num")->get("payment_logs");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_roles() 
	{
		return $this->db->get("user_roles");
	}

	public function add_user_role($data) 
	{
		$this->db->insert("user_roles", $data);
	}

	public function get_user_role($id) 
	{
		return $this->db->where("ID", $id)->get("user_roles");
	}

	public function update_user_role($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_roles", $data);
	}

	public function delete_user_role($id) 
	{
		$this->db->where("ID", $id)->delete("user_roles");
	}

	public function get_premium_users($page) 
	{
		
		$this->db->order_by("users.ID", "DESC");
		
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider, 
			payment_plans.name, users.premium_time")
		->join("payment_plans", "payment_plans.ID = users.premium_planid")
		->where("users.premium_time >", time())
		->or_where("users.premium_time", -1)
		->limit(20, $page)
		->get("users");
	}

	public function get_total_premium_users_count() 
	{
		$s= $this->db
			->select("COUNT(*) as num")->where("premium_time >", time())
			->or_where("users.premium_time", -1)
			->join("payment_plans", "payment_plans.ID = users.premium_planid")
			->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function add_currency($data) 
	{
		$this->db->insert("currencies", $data);
	}

	public function get_currency($id) 
	{
		return $this->db->where("ID", $id)->get("currencies");
	}

	public function get_currencies() 
	{
		return $this->db->get("currencies");
	}

	public function delete_currency($id) 
	{
		$this->db->where("ID", $id)->delete("currencies");
	}

	public function update_currency($id, $data) 
	{
		$this->db->where("ID", $id)->update("currencies", $data);
	}

	public function get_layouts() 
	{
		return $this->db->get("site_layouts");
	}

	public function get_layout($id) 
	{
		return $this->db->where("ID", $id)->get("site_layouts");
	}

	public function get_user_role_permissions() 
	{
		return $this->db->get("user_role_permissions");
	}

	public function get_total_email_templates() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("email_templates");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_email_templates($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"email_templates.title",
			"email_templates.language"
			)
		);
		
		return $this->db
		->limit($datatable->length, $datatable->start)
		->get("email_templates");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("custom_fields", $data);
	}

	public function get_custom_fields($data) 
	{
		if(isset($data['register'])) {
			$this->db->where("register", 1);
		}
		return $this->db->get("custom_fields");
	}

	public function get_custom_field($id) 
	{
		return $this->db->where("ID", $id)->get("custom_fields");
	}

	public function update_custom_field($id, $data) 
	{
		return $this->db->where("ID", $id)->update("custom_fields", $data);
	}

	public function delete_custom_field($id) 
	{
		$this->db->where("ID", $id)->delete("custom_fields");
	}
	
	public function get_tat_calc() 
	{
		return $this->db->get("user_skills");
	}
	
	public function get_pro_unitprice() 
	{
		return $this->db->get("project_task_unitprice");
	}
	
	public function get_total_unitprice_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("project_task_unitprice");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_unitprice_admin($datatable) 
	{
		$datatable->db_order();
		$datatable->db_search(array(
			"project_task_unitprice.publisher",
			"project_task_unitprice.process",
			"project_task_unitprice.stage",
			"project_task_unitprice.pdfType",
			"project_task_unitprice.complexity",
			"project_task_unitprice.unit",
			"project_task_unitprice.unitprice"
			)
		);

		return $this->db->select("project_task_unitprice.*")
		->limit($datatable->length, $datatable->start)
		->get("project_task_unitprice");
	}
	
	public function add_unitprice($data) 
	{
		$this->db->insert("project_task_unitprice", $data);
	}

	public function get_unitprice($id) 
	{
		return $this->db->where("ID", $id)->get("project_task_unitprice");
	}

	public function delete_unitprice($id) 
	{
		$this->db->where("ID", $id)->delete("project_task_unitprice");
	}

	public function update_unitprice($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_task_unitprice", $data);
	}
}

?>