<?php

class Team_Model extends CI_Model 
{

	public function add_member($data) 
	{
		$this->db->insert("project_members", $data);
	}
	
	public function get_reporting_person($id) 
	{
		return $this->db->where("project_members.ID", $id)
			->select("project_members.reporting")
			->get("project_members");
	}
	
	public function get_members_all_user($projectid, $userid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("project_members.projectid", $projectid);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"project_roles.name",
			"users.team_name"
			)
		);

		return $this->db
			->select("project_members.ID,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp, users.first_name, users.last_name,
				projects.name as project_name, 
				project_roles.name as team_role_name,
				pr2.admin, pr2.team, pr2.file,
				pr2.time, pr2.task, pr2.calendar,
				pr2.finance, pr2.notes, pr2.reports,
				pr2.client, pr2.doc, pr2.invoice, users.team_name")
			->join("projects", "projects.ID = project_members.projectid")
			->join("users", "users.ID = project_members.userid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("project_members as pm2", "pm2.projectid = project_members.projectid")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->limit($datatable->length, $datatable->start)
			->get("project_members");
	}

	public function get_members_for_project($projectid) 
	{
		$this->db->where("project_members.projectid", $projectid);
		return $this->db
			->select("project_members.ID,users.user_role,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp, users.first_name, users.last_name,
				users.email, users.email_notification,
				projects.name as project_name, 
				project_roles.name as team_role_name")
			->join("projects", "projects.ID = project_members.projectid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("users", "users.ID = project_members.userid")
			->order_by("users.username", "ASC")
			->get("project_members");
	}
	
	public function get_team_members_by_userid($user_id) 
	{
		//$this->db->where("users.ID IN (".$user_id.")",NULL, false);
		return $this->db
			->select("users.team_name")
			->where_in("users.ID",$user_id)
			->group_by("users.team_name")
			->order_by("users.team_name", "ASC")
			->get("users");
	}

	public function get_projects_for_user($userid) 
	{
		$this->db->where("project_members.userid", $userid);
		return $this->db
			->select("project_members.ID,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp, users.first_name, users.last_name,
				projects.name as project_name, 
				project_roles.name as team_role_name")
			->join("projects", "projects.ID = project_members.projectid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("users", "users.ID = project_members.userid")
			->get("project_members");
	}

	public function get_members_all_user_count($projectid, $userid) 
	{
		if($projectid > 0) {
			$this->db->where("project_members.projectid", $projectid);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_members.projectid")
			->join("users", "users.ID = project_members.userid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("project_members as pm2", "pm2.projectid = project_members.projectid")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->get("project_members");
		$r = $s->row();
		if(isset($r)) return $r->num;
		return 0;
	}

	public function get_members_all($projectid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("project_members.projectid", $projectid);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"project_roles.name",
			"users.team_name"
			)
		);

		return $this->db
			->select("project_members.ID,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp, users.first_name, users.last_name,
				projects.name as project_name, 
				project_roles.name as team_role_name, users.team_name")
			->join("projects", "projects.ID = project_members.projectid")
			->join("users", "users.ID = project_members.userid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->where("projects.status", 0)
			->limit($datatable->length, $datatable->start)
			->get("project_members");
	}

	public function get_members_all_count($projectid) 
	{
		if($projectid > 0) {
			$this->db->where("project_members.projectid", $projectid);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_members.projectid")
			->join("users", "users.ID = project_members.userid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->where("projects.status", 0)
			->get("project_members");
		$r = $s->row();
		if(isset($r)) return $r->num;
		return 0;
	}

	public function get_member_of_project($userid, $projectid) 
	{
		return $this->db
			->select("project_members.ID, project_members.projectid, 
				project_members.userid, project_members.roleid,
				project_roles.admin, project_roles.team, project_roles.file,
				project_roles.time, project_roles.task, project_roles.calendar,
				project_roles.finance, project_roles.notes, project_roles.reports,
				project_roles.client, project_roles.doc, project_roles.invoice,
				project_roles.name,
				users.username, users.email, users.email_notification")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("users", "users.ID = project_members.userid")
			->where("project_members.userid", $userid)
			->where("project_members.projectid", $projectid)
			->get("project_members");
	}

	public function get_team_member($id) 
	{
		return $this->db->where("project_members.ID", $id)
			->select("project_members.ID, project_members.projectid,
				project_members.roleid, project_members.userid, 
				projects.name,
				users.username, users.avatar, users.email, 
				users.email_notification, users.first_name, users.last_name")
			->join("users", "users.ID = project_members.userid")
			->join("projects", "projects.ID = project_members.projectid")
			->get("project_members");
	}

	public function get_team_roles() 
	{
		return $this->db->get("project_roles");
	}

	public function update_team_member($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_members", $data);
	}

	public function add_role($data) 
	{
		$this->db->insert("project_roles", $data);
	}

	public function get_role($id) 
	{
		return $this->db->where("ID", $id)->get("project_roles");
	}

	public function update_role($id, $data)
	{
		$this->db->where("ID", $id)->update("project_roles", $data);
	}

	public function delete_role($id) 
	{
		$this->db->where("ID", $id)->delete("project_roles");
	}

	public function get_members_with_role($roleid) 
	{
		return $this->db->where("roleid", $roleid)->get("project_members");
	}

	public function delete_member($id) 
	{
		$this->db->where("ID", $id)->delete("project_members");
	}

	public function get_user_log($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"projects.name",
			"user_action_log.message",
			"user_action_log.IP"
			)
		);
		return $this->db
			->where("user_action_log.userid", $userid)
			->select("user_action_log.ID, user_action_log.message, 
				user_action_log.timestamp, user_action_log.IP, user_action_log.url,
				user_action_log.projectid,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp, users.first_name, users.last_name,
				projects.name")
			->join("users", "users.ID = user_action_log.userid")
			->join("projects", "projects.ID = user_action_log.projectid", "left outer")
			->limit($datatable->length, $datatable->start)
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
	}

	public function get_all_user_log($userid, $page, $max=10) 
	{
		return $this->db
			->select("user_action_log.ID, user_action_log.message, 
				user_action_log.timestamp, user_action_log.IP, user_action_log.url,
				user_action_log.projectid,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp, users.first_name, users.last_name,
				projects.name")
			->join("users", "users.ID = user_action_log.userid")
			->join("projects", "projects.ID = user_action_log.projectid", "left outer")
			->join("project_members as pm2", "pm2.projectid = user_action_log.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->group_end()
			->or_group_start()
			->where("user_action_log.userid", $userid)
			->group_end()
			->limit($max, $page)
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
	}

	public function get_project_log($projectid, $page, $max=10) 
	{
		return $this->db
			->select("user_action_log.ID, user_action_log.message, 
				user_action_log.timestamp, user_action_log.IP, user_action_log.url,
				user_action_log.projectid,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp, users.first_name, users.last_name,
				projects.name")
			->join("users", "users.ID = user_action_log.userid")
			->join("projects", "projects.ID = user_action_log.projectid", "left outer")
			->where("user_action_log.projectid", $projectid)
			->limit($max, $page)
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
	}

	public function get_total_user_log_count($userid) 
	{
		$s = $this->db
			->where("userid", $userid)
			->select("COUNT(*) as num")->get("user_action_log");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_members_for_project_roles($projectid, $roles=array()) 
	{
		$this->db->where("project_members.projectid", $projectid);
		$this->db->group_start();
		foreach($roles as $role) {
			$this->db->or_where("project_roles." . $role, 1);
		}
		$this->db->group_end();
		return $this->db
			->select("project_members.ID,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp, users.first_name, users.last_name,
				projects.name as project_name, 
				project_roles.name as team_role_name")
			->join("projects", "projects.ID = project_members.projectid")
			->join("project_roles", "project_roles.ID = project_members.roleid")
			->join("users", "users.ID = project_members.userid")
			->get("project_members");
	}


	public function check_member_of_projects($projects, $userid)
	{
		$this->db->group_start();
		foreach($projects as $projectid) {
			$this->db->or_where("project_members.projectid", $projectid);
		}
		$this->db->group_end();

		return $this->db
			->where("project_members.userid", $userid)
			->get("project_members");
	}

}

?>