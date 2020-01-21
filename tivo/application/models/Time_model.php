<?php

class Time_Model extends CI_Model 
{

	public function add_timer($data)
	{
		$this->db->insert("user_timers", $data);
	}

	public function get_all_timers($projectid, $datatable, $paid = null) 
	{
		if($projectid > 0) {
			$this->db->where("user_timers.projectid", $projectid);
		}
		if($paid) {
			$this->db->where("user_timers.status", 0);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name"
			)
		);

		return $this->db
			->select("users.username, users.avatar, users.online_timestamp,
				users.first_name, users.last_name,
				projects.name as projectname,
				user_timers.ID, user_timers.start_time, user_timers.end_time, 
				user_timers.added, user_timers.note, user_timers.rate, 
				user_timers.userid, user_timers.projectid, user_timers.status,
				project_tasks.name as task_name, project_tasks.ID as taskid,
				(user_timers.end_time - user_timers.start_time) as time_diff,
				(user_timers.rate * (user_timers.end_time - user_timers.start_time)) as earnings")
			->join("users", "users.ID = user_timers.userid")
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->join("project_tasks", "project_tasks.ID = user_timers.taskid", "left outer")
			->order_by("user_timers.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("user_timers");
	}

	public function get_all_timers_count($projectid, $paid = null) 
	{
		if($projectid > 0) {
			$this->db->where("user_timers.projectid", $projectid);
		}
		if($paid) {
			$this->db->where("user_timers.status", 0);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->get("user_timers");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_user_timers($userid, $projectid, $datatable, $paid=null) 
	{
		if($projectid > 0) {
			$this->db->where("user_timers.projectid", $projectid);
		}
		if($paid) {
			$this->db->where("user_timers.status", 0);
		}
		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name"
			)
		);

		return $this->db
			->where("user_timers.userid", $userid)
			->select("users.username, users.avatar, users.online_timestamp,
				users.first_name, users.last_name,
				projects.name as projectname,
				user_timers.ID, user_timers.start_time, user_timers.end_time, 
				user_timers.added, user_timers.note, user_timers.rate, 
				user_timers.userid, user_timers.projectid, user_timers.status,
				project_tasks.name as task_name, project_tasks.ID as taskid,
				(user_timers.end_time - user_timers.start_time) as time_diff,
				(user_timers.rate * (user_timers.end_time - user_timers.start_time)) as earnings")
			->join("users", "users.ID = user_timers.userid")
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->join("project_tasks", "project_tasks.ID = user_timers.taskid", "left outer")
			->order_by("user_timers.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("user_timers");
	}

	public function get_user_timers_count($userid, $projectid, $paid=null) 
	{
		if($projectid > 0) {
			$this->db->where("projectid", $projectid);
		}
		if($paid) {
			$this->db->where("user_timers.status", 0);
		}
		$s = $this->db
			->where("user_timers.userid", $userid)
			->select("COUNT(*) as num")
			->get("user_timers");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_active_user_timers($userid) 
	{
		return $this->db
			->where("user_timers.userid", $userid)
			->select("users.username, users.avatar, users.online_timestamp,
				projects.name,
				user_timers.ID, user_timers.start_time, user_timers.end_time, 
				user_timers.added, user_timers.note, user_timers.rate, 
				user_timers.userid, user_timers.projectid, user_timers.status,
				project_tasks.name as task_name, project_tasks.ID as taskid")
			->join("users", "users.ID = user_timers.userid")
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->join("project_tasks", "project_tasks.ID = user_timers.taskid", "left outer")
			->where("user_timers.end_time", 0)
			->order_by("user_timers.ID", "DESC")
			->get("user_timers");
	}

	public function get_all_timers_search($data, $page) 
	{
		if(isset($data['userid']) && $data['userid'] != 0) {
			$this->db->where("user_timers.userid", $data['userid']);
		}
		if(isset($data['projectid']) && $data['projectid'] != -1) {
			$this->db->where("user_timers.projectid", $data['projectid']);
		}

		return $this->db
			->select("users.username, users.avatar, users.online_timestamp,
				projects.name,
				user_timers.ID, user_timers.start_time, user_timers.end_time, 
				user_timers.added, user_timers.note, user_timers.rate, 
				user_timers.userid, user_timers.projectid, user_timers.status,
				project_tasks.name as task_name, project_tasks.ID as taskid")
			->join("users", "users.ID = user_timers.userid")
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->join("project_tasks", "project_tasks.ID = user_timers.taskid", "left outer")
			->order_by("user_timers.ID", "DESC")
			->limit(10, $page)
			->get("user_timers");
	}

	public function get_all_timers_search_count($data) 
	{
		if(isset($data['userid']) && $data['userid'] != 0) {
			$this->db->where("user_timers.userid", $data['userid']);
		}
		if(isset($data['projectid']) && $data['projectid'] != -1) {
			$this->db->where("user_timers.projectid", $data['projectid']);
		}

		$s = $this->db
			->select("COUNT(*) as num")
			->get("user_timers");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function update_timer($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_timers", $data);
	}

	public function get_timer($id) 
	{
		return $this->db->where("ID", $id)->get("user_timers");
	}

	public function delete_timer($id) 
	{
		$this->db->where("ID", $id)->delete("user_timers");
	}

	public function count_hours_date($date, $userid) {
		return $this->db
			->where("user_timers.date_stamp", $date)
			->where("user_timers.userid", $userid)
			->select("(user_timers.end_time - user_timers.start_time) as time, user_timers.rate, user_timers.projectid, projects.name")
			->where("end_time >", 0)
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->get("user_timers");
	}

	public function count_hours_date_task($date, $taskid) {
		return $this->db
			->where("user_timers.date_stamp", $date)
			->where("user_timers.taskid", $taskid)
			->select("(user_timers.end_time - user_timers.start_time) as time, user_timers.rate, user_timers.projectid, projects.name")
			->where("end_time >", 0)
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->get("user_timers");
	}

	public function count_hours_project($projectid) {
		return $this->db
			->where("user_timers.projectid", $projectid)
			->select("(user_timers.end_time - user_timers.start_time) as time, user_timers.rate, user_timers.projectid, projects.name")
			->where("end_time >", 0)
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->get("user_timers");
	}

}

?>