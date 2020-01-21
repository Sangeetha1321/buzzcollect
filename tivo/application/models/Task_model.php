<?php

class Task_Model extends CI_Model 
{

	public function add_task($data) 
	{
		$this->db->insert("project_tasks", $data);
		return $this->db->insert_id();
	}
	
	public function importtasksdata($data) 
	{
		$res = $this->db->insert_batch("project_tasks", $data);
		if($res){
            return TRUE;
        }else{
            return FALSE;
        }
	}
	
	public function add_task_bulk_member($data) 
	{
		$this->db->insert("project_task_members", $data);
	}

	public function get_projectname() 
	{
	 	$s = $this->db
			->select("projects.ID,projects.name")
			->order_by("projects.name", "asc")
			->get("projects");
			
        return $s->result();   
	}
	
	public function get_all_uncompleted_tasks() 
	{
		return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->where("project_tasks.archived", 0)
			->get("project_tasks");
	}

	public function get_tasks() 
	{
		return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->get("project_tasks");
	}

	public function get_all_project_tasks($projectid) 
	{
		return $this->db
			->where("projectid", $projectid)
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete, 
				project_tasks.complete_sync,
				projects.name as project_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->get("project_tasks");
	}

	public function get_project_tasks($projectid, $status, $userid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name",
			"project_tasks.jobid",
			"users.username",
			"users.team_name",
			"from_unixtime(project_tasks.start_date, '%d/%m/%Y')",
			"from_unixtime(project_tasks.due_date, '%d/%m/%Y')"
			)
		);

		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 0);

		/* return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)")
			->where("projects.status", 0)
			->group_end()
			->order_by("project_tasks.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_tasks");  */
			
			return $this->db
			->select("project_tasks.name, project_tasks.jobid, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				from_unixtime(project_tasks.start_date, '%d/%m/%Y') as start_date_formatted,
				from_unixtime(project_tasks.due_date, '%d/%m/%Y') as due_date_formatted, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name, project_task_members.userid, 
				GROUP_CONCAT(users.username SEPARATOR ',') as username, GROUP_CONCAT(users.team_name SEPARATOR ',') as team_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("project_task_members", "project_task_members.taskid = project_tasks.ID")
			->join("users", "users.ID = project_task_members.userid")
			->group_start()
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)")
			->where("projects.status", 0)
			->group_end()
			->group_by("project_tasks.ID")
			->order_by("project_tasks.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_tasks");
			
	}
	
	public function get_teams_and_users($taskid) 
	{
	
		return $this->db
			->select("project_task_members.userid,users.username")
			->join("users", "users.ID = project_task_members.userid")
			->where("project_task_members.taskid", $taskid)
			->order_by("project_task_members.userid", "ASC")
			->get("project_task_members");
	}

	public function get_team_members($taskid) 
	{
	
		return $this->db
			->select("project_task_members.userid,users.username")
			->join("users", "users.ID = project_task_members.userid")
			->where("project_task_members.taskid", $taskid)
			->order_by("project_task_members.userid", "ASC")
			->get("project_task_members");
	}
	
	public function get_project_tasks_total($projectid, $status, $userid) 
	{
		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 0);

		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}
		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)")
			->get("project_tasks");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_tasks($projectid, $status, $datatable, $archived=0) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name",
			"project_tasks.jobid",
			"users.username",
			"users.team_name",
			"from_unixtime(project_tasks.start_date, '%d/%m/%Y')",
			"from_unixtime(project_tasks.due_date, '%d/%m/%Y')"
			)
		);

		$this->db->where("project_tasks.archived", $archived);
		$this->db->where("project_tasks.template", 0); 

		return $this->db
			->select("project_tasks.name, project_tasks.jobid, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				from_unixtime(project_tasks.start_date, '%d/%m/%Y') as start_date_formatted,
				from_unixtime(project_tasks.due_date, '%d/%m/%Y') as due_date_formatted,
				project_tasks.description,project_tasks.complete,
				projects.name as project_name, 
				GROUP_CONCAT(users.username SEPARATOR ',') as username, 
				GROUP_CONCAT(users.team_name SEPARATOR ',') as team_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_task_members", "project_task_members.taskid = project_tasks.ID")
			->join("users", "users.ID = project_task_members.userid")
			->group_by("project_tasks.ID")
			->order_by("project_tasks.ID", "DESC")
			->where("projects.status", 0)
			->limit($datatable->length, $datatable->start)
			->get("project_tasks");
	}


	public function get_all_tasks_total($projectid, $status, $archived=0) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$this->db->where("project_tasks.archived", $archived);
		$this->db->where("project_tasks.template", 0);

		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_tasks.projectid")
			->where("projects.status", 0)
			->get("project_tasks");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}


	public function get_user_assigned_tasks($projectid, $status, $userid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name",
			"project_tasks.jobid",
			"users.username",
			"users.team_name",
			"from_unixtime(project_tasks.start_date, '%d/%m/%Y')",
			"from_unixtime(project_tasks.due_date, '%d/%m/%Y')"
			)
		);

		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 0);

		return $this->db
			->select("project_tasks.name, project_tasks.jobid, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				from_unixtime(project_tasks.start_date, '%d/%m/%Y') as start_date_formatted,
				from_unixtime(project_tasks.due_date, '%d/%m/%Y') as due_date_formatted,  
				project_tasks.description, project_tasks.complete,
				projects.name as project_name, projects.image, 
				GROUP_CONCAT(users.username SEPARATOR ',') as username,
				GROUP_CONCAT(users.team_name SEPARATOR ',') as team_name")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("project_task_members", "project_task_members.taskid=project_tasks.ID")
			->join("users", "users.ID = project_task_members.userid")
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1)")
			->where("project_task_members.userid", $userid)
			->where("projects.status", 0)
			->group_by("project_tasks.ID")
			->order_by("project_tasks.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_tasks");
	}

	public function get_user_assigned_tasks_total($projectid, $status, $userid) 
	{
		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 0);

		$s= $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("project_task_members", "project_task_members.taskid=project_tasks.ID")
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1)")
			->where("project_task_members.userid", $userid)
			->where("projects.status", 0)
			->get("project_tasks");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_tasks_no_pagination($projectid, $status, $archived=0) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$this->db->where("project_tasks.archived", $archived);
		$this->db->where("project_tasks.template", 0);

		return $this->db
				->select("project_tasks.name, project_tasks.ID, project_tasks.projectid, project_tasks.status, 
					project_tasks.due_date, project_tasks.start_date, project_tasks.description,
					project_tasks.complete, project_tasks.template, projects.name as project_name")
				->join("projects", "projects.ID = project_tasks.projectid")
				->order_by("project_tasks.start_date")
				->where("projects.status", 0)
				->get("project_tasks");
	}

	public function get_task($taskid) 
	{
		return $this->db->where("ID", $taskid)->get("project_tasks");
	}

	public function delete_task($taskid) 
	{
		$this->db->where("ID", $taskid)->delete("project_tasks");
	}

	public function update_task($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_tasks", $data);
	}

	public function get_task_member($userid, $taskid) 
	{
		return $this->db->select("project_task_members.ID, 
			project_task_members.userid, project_task_members.taskid,
			users.email, users.username, users.email_notification")
			->where("project_task_members.taskid", $taskid)
			->where("project_task_members.userid", $userid)
			->join("users", "users.ID = project_task_members.userid")
			->get("project_task_members");
	}

	public function add_task_member($data) 
	{
		$this->db->insert("project_task_members", $data);
	}

	public function get_task_members($taskid) 
	{
		return $this->db->where("project_task_members.taskid", $taskid)
			->select("users.ID as userid, users.username, users.avatar, 
				users.first_name, users.last_name,
				users.email, users.email_notification, users.online_timestamp,
				project_task_members.ID,users.employee_id")
			->join("users", "users.ID = project_task_members.userid")
			->get("project_task_members");
	}
	
	public function get_productivity_members($taskid) 
	{
		return $this->db->select("project_task_objectives.title, project_task_objectives.taskid,
			project_task_objectives.complete, project_task_objectives.completed_datetime, project_task_objectives.productivity_percentage,
			project_task_objective_members.userid")
			->where("project_task_objectives.taskid", $taskid)
			->where("project_task_objectives.complete", 1)
			->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID")
			->get("project_task_objectives");
	}

	public function remove_member($userid, $taskid) 
	{
		$this->db->where("userid", $userid)->where("taskid", $taskid)
			->delete("project_task_members");
	}

	public function get_task_member_id($id) 
	{
		return $this->db->where("project_task_members.ID", $id)
			->select("project_tasks.ID as taskid, project_task_members.userid,
				project_tasks.projectid, project_tasks.name,
				users.email, users.username, users.email_notification")
			->join("project_tasks", "project_tasks.ID = project_task_members.taskid")
			->join("users", "users.ID = project_task_members.userid")
			->get("project_task_members");
	}

	public function add_objective($data) 
	{
		$this->db->insert("project_task_objectives", $data);
		return $this->db->insert_id();
	}
	
	public function add_default_objective($user,$time,$taskid,$complexity,$unit) 
	{
	if(($complexity=='complex') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 40,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='complex') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 40,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 80,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 250,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 250,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 80,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 250,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 250,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='simple') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 100,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	else{
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Content Extraction',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 100,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
		$this->db->insert_batch('project_task_objectives', $data); 
		/* echo $this->db->last_query();
		exit;  */
		return $this->db->insert_id();
	}
	
	public function add_wtx_objective($user,$time,$taskid,$complexity,$unit) 
	{
	if(($complexity=='complex') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='complex') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='simple') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 700,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	else{
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 700,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
		$this->db->insert_batch('project_task_objectives', $data); 
		/*  echo $this->db->last_query();
		exit;  */
		return $this->db->insert_id();
	}
	
	public function add_xtx_objective($user,$time,$taskid,$complexity,$unit) 
	{
	if(($complexity=='complex') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 15,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='complex') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 15,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 200,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 20,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='medium') && ($unit=='Article')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 20,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 300,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 500,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	elseif(($complexity=='simple') && ($unit=='Page')){
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 700,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 30,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalPages' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
	else{
		$data = array(
				   array(
					  'title' => 'Input Analysis',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 700,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'XML QC',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 30,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Image Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 400,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'PDF Conversion',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 750,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Dataset/Package Creation',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 600,
					  'complexity' => $complexity,
					  'unit' => $unit
				   ),
				   array(
					  'title' => 'Upload',
					  'description' => '',
					  'userid' => $user,
					  'timestamp' => $time,
					  'taskid' => $taskid,
					  'description' => '',
					  'complete' => 0,
					  'task_status' => '',
					  'totalArticles' => 0,
					  'complexity' => $complexity,
					  'unit' => $unit
				   )
				);
	}
		$this->db->insert_batch('project_task_objectives', $data); 
		/* echo $this->db->last_query();
		exit;  */
		return $this->db->insert_id();
	}

	public function add_objective_member($objectiveid, $userid) 
	{
		$this->db->insert("project_task_objective_members", array(
			"objectiveid" => $objectiveid,
			"userid" => $userid
			)
		);
	}

	public function get_task_objectives($taskid) 
	{
		return $this->db
			->where("taskid", $taskid)
			->get("project_task_objectives");
	}

	public function get_task_objective_members($objectiveid) 
	{
		return $this->db
			->where("project_task_objective_members.objectiveid", $objectiveid)
			->select("users.ID as userid, users.username, users.avatar,
				users.online_timestamp,users.employee_id")
			->join("users", "users.ID = project_task_objective_members.userid")
			->get("project_task_objective_members");
	}

	public function get_task_objective($id) 
	{
		return $this->db
			->where("project_task_objectives.ID", $id)
			->select("project_task_objectives.*,
				project_tasks.ID as taskid, project_tasks.projectid, project_tasks.name,
				project_tasks.complete_sync, project_tasks.status,
				project_tasks.template")
			->join("project_tasks", "project_tasks.ID = project_task_objectives.taskid")
			->get("project_task_objectives");
	}

	public function update_objective($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_task_objectives", $data);
	}

	public function delete_objective($id) 
	{
		$this->db->where("ID", $id)->delete("project_task_objectives");
	}

	public function delete_objective_members($id) 
	{
		$this->db->where("objectiveid", $id)->delete("project_task_objective_members");
	}

	public function get_attached_file($fileid, $taskid) 
	{
		return $this->db
			->where("fileid", $fileid)
			->where("taskid", $taskid)
			->get("project_task_files");
	}

	public function get_attached_file_id($id, $taskid) 
	{
		return $this->db
			->where("project_task_files.ID", $id)
			->where("project_task_files.taskid", $taskid)
			->select("project_task_files.ID,
				project_files.file_name")
			->join("project_files", "project_files.ID = project_task_files.fileid")
			->get("project_task_files");
	}
	
	public function check_task_details_is_free($taskname,$isbn,$eisbn) 
	{
		/* $s=$this->db->where("name", $taskname)->get("project_tasks"); */
		$s=$this->db
		->where("name", $taskname)
		->where("isbn", $isbn)
		->where("eisbn", $eisbn)
		->get("project_tasks"); 
		if($s->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function add_file($data) 
	{
		$this->db->insert("project_task_files", $data);
	}

	public function delete_file($id) 
	{
		$this->db->where("ID", $id)->delete("project_task_files");
	}

	public function get_attached_files($taskid) 
	{
		return $this->db->where("project_task_files.taskid", $taskid)
			->select("project_files.ID as fileid, project_files.file_name,
				project_files.extension, project_files.upload_file_name,
				project_files.file_size, project_files.file_url,
				project_files.file_type, project_files.projectid,
				project_task_files.ID, project_task_files.taskid")
			->join("project_files", "project_files.ID = project_task_files.fileid")
			->get("project_task_files");
	}

	public function add_message($data) 
	{
		$this->db->insert("project_task_messages", $data);
	}

	public function get_task_messages($taskid, $page)
	{
		return $this->db
				->where("project_task_messages.taskid", $taskid)
				->select("project_task_messages.ID, project_task_messages.message,
					project_task_messages.timestamp, project_task_messages.userid,
					project_task_messages.taskid,
					users.username, users.avatar, users.online_timestamp")
				->join("users", "users.ID = project_task_messages.userid")
				->limit(5, $page)
				->order_by("project_task_messages.ID", "DESC")
				->get("project_task_messages");
	}

	public function get_task_messages_all($taskid)
	{
		return $this->db
				->where("project_task_messages.taskid", $taskid)
				->select("project_task_messages.ID, project_task_messages.message,
					project_task_messages.timestamp, project_task_messages.userid,
					project_task_messages.taskid,
					users.username, users.avatar, users.online_timestamp")
				->join("users", "users.ID = project_task_messages.userid")
				->order_by("project_task_messages.ID", "DESC")
				->get("project_task_messages");
	}

	public function get_task_messages_total($taskid) 
	{
		$s = $this->db
				->where("project_task_messages.taskid", $taskid)
				->select("COUNT(*) as num")
				->join("users", "users.ID = project_task_messages.userid")
				->get("project_task_messages");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_message($id, $taskid) 
	{
		return $this->db->where("ID", $id)->where("taskid", $taskid)
			->get("project_task_messages");
	}

	public function delete_message($id) 
	{
		$this->db->where("ID", $id)->delete("project_task_messages");
	}

	public function get_activity_log($taskid) 
	{
		return $this->db->where("user_action_log.taskid", $taskid)
			->select("user_action_log.timestamp, user_action_log.ID,
				user_action_log.message, user_action_log.url,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp")
			->join("users", "users.ID = user_action_log.userid")
			->limit(5)
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
	}

	public function get_task_activity($taskid, $page) 
	{
		return $this->db->where("user_action_log.taskid", $taskid)
			->select("user_action_log.timestamp, user_action_log.ID,
				user_action_log.message, user_action_log.url,
				users.ID as userid, users.username, users.avatar")
			->join("users", "users.ID = user_action_log.userid")
			->limit(15, $page)
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
	}

	public function get_task_activity_total($taskid) 
	{
		$s = $this->db->where("user_action_log.taskid", $taskid)
			->select("COUNT(*) as num")
			->join("users", "users.ID = user_action_log.userid")
			->order_by("user_action_log.ID", "DESC")
			->get("user_action_log");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_tasks_for_project($projectid) 
	{
		return $this->db
			->where("projectid", $projectid)
			->get("project_tasks");
	}

	public function get_user_assigned_tasks_fp($projectid, $status, $userid, $start, $max) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$this->db->where("project_tasks.archived", 0);

		return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name, projects.image")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("project_task_members", "project_task_members.taskid=project_tasks.ID")
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1)")
			->where("project_task_members.userid", $userid)
			->where("projects.status", 0)
			->order_by("project_tasks.ID", "DESC")
			->limit($max, $start)
			->get("project_tasks");
	}
	
	public function get_user_assigned_tasks_all($projectid, $status, $userid, $start, $max) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$this->db->where("project_tasks.archived", 0);

		return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				projects.name as project_name, projects.image")
			->join("projects", "projects.ID = project_tasks.projectid")
			->where("projects.status", 0)
			->order_by("project_tasks.ID", "DESC")
			->limit($max, $start)
			->get("project_tasks");
	}

	public function get_project_task_templates($projectid, $status, $userid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("project_tasks.template_projectid", $projectid);
		}

		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"project_tasks.name"
			)
		);

		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 1);

		return $this->db
			->select("project_tasks.name, project_tasks.ID, 
				project_tasks.projectid, project_tasks.status, 
				project_tasks.due_date, project_tasks.start_date, 
				project_tasks.description, project_tasks.complete,
				project_tasks.template, project_tasks.template_projectid,
				proj.name as project_name")
			->join("projects as proj", "proj.ID = project_tasks.template_projectid", "left outer")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)")
			->where("projects.status", 0)
			->group_end()
			->order_by("project_tasks.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_tasks");
	}

	public function get_project_task_templates_total($projectid, $status, $userid) 
	{
		$this->db->where("project_tasks.archived", 0);
		$this->db->where("project_tasks.template", 1);

		if($projectid > 0) {
			$this->db->where("project_tasks.projectid", $projectid);
		}
		if($status > 0) {
			$this->db->where("project_tasks.status", $status);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_tasks.projectid")
			->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)")
			->get("project_tasks");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_templates_for_all() 
	{
		return $this->db->where("template", 1)->where("template_projectid", 0)->get("project_tasks");
	}

	public function get_dependencies($taskid) 
	{
		return $this->db
			->where("task_dependencies.taskid_primary", $taskid)
			->select("project_tasks.ID as taskid, project_tasks.name,
				task_dependencies.ID, task_dependencies.taskid_secondary, 
				task_dependencies.taskid_primary")
			->join("project_tasks", "project_tasks.ID = task_dependencies.taskid_secondary")
			->get("task_dependencies");
	}

	public function add_task_dependency($data) 
	{
		$this->db->insert("task_dependencies", $data);
	}

	public function get_task_dependency($id) 
	{
		return $this->db->where("ID", $id)->get("task_dependencies");
	}

	public function delete_task_dependency($id) 
	{
		$this->db->where("ID", $id)->delete("task_dependencies");
	}
	
	public function getfromprojects($pro_id) 
	{
		return $this->db
			->where("projects.ID", $pro_id)
			->select("projects.publisher,projects.process_name,projects.stage,projects.pdfType")
			->get("projects");
	}
	
	public function getunitprice($com,$unit,$pub,$pro,$stg,$pdf) 
	{
		    $this->db->where("project_task_unitprice.complexity", $com);
			$this->db->where("project_task_unitprice.unit", $unit);
			$this->db->where("project_task_unitprice.publisher", $pub);
			$this->db->where("project_task_unitprice.process", $pro);
			if($stg == "" || $stg == NULL){
				$this->db->where("project_task_unitprice.stage IS NULL");
			} else {
				$this->db->where("project_task_unitprice.stage", $stg);
			}
			if($pdf == "" || $pdf == NULL){
				$this->db->where("project_task_unitprice.pdfType IS NULL");
			} else {
				$this->db->where("project_task_unitprice.pdfType", $pdf);
			}
			$this->db->select("project_task_unitprice.unitprice");
			$this->db->from("project_task_unitprice");
		$q = $this->db->get();
		/* echo $this->db->last_query(); */  
		return $q; 
	}
	
	public function tasklistsexport($pro_id,$userid,$from_date = "",$to_date = ""){
		
		/* $this->db->select("project_tasks.name as title_name, project_tasks.jobid,  
				projects.name as project_name, task_status.name as title_status, 
				from_unixtime(project_tasks.start_date, '%d/%m/%Y') as start_date,
				from_unixtime(project_tasks.due_date, '%d/%m/%Y') as due_date, 
				project_tasks.description, project_tasks.complete as title_completion,project_tasks.vendor_name,
				project_tasks.vendor_process_name,project_tasks.vendor_unit,project_tasks.vendor_unitPricePerPage,
				project_tasks.vendor_totalPages,project_tasks.vendor_totalPagesPrice,project_tasks.vendor_unitPricePerArticle,
				project_tasks.vendor_totalArticles,project_tasks.vendor_totalArticlesPrice,from_unixtime(project_tasks.vendor_start_date, '%d/%m/%Y') as vendor_start_date,
				from_unixtime(project_tasks.vendor_due_date, '%d/%m/%Y') as vendor_due_date,project_tasks.vendor_status,
				projects.name as project_name, 
				GROUP_CONCAT(users.username SEPARATOR ',') as username, GROUP_CONCAT(users.team_name SEPARATOR ',') as team_name");
			 */	
		$this->db->select("project_tasks.jobid,project_tasks.name as title,task_status.name as title_status,   
				projects.name as project_name,project_tasks.complete as title_completion,  
				from_unixtime(project_tasks.start_date, '%d/%m/%Y') as start_date,
				from_unixtime(project_tasks.due_date, '%d/%m/%Y') as due_date,
				GROUP_CONCAT(DISTINCT users.team_name ORDER BY users.team_name ASC SEPARATOR ',') as team_name,
				GROUP_CONCAT(DISTINCT users.username ORDER BY users.username ASC SEPARATOR ',') as username");
		/* $this->db->select('project_tasks.*');  */
		$this->db->from('project_tasks');
		$this->db->join("projects", "projects.ID = project_tasks.projectid");
		$this->db->join("project_members as pm2", "pm2.projectid = project_tasks.projectid", "left outer");
		$this->db->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer");
		$this->db->join("project_task_members", "project_task_members.taskid = project_tasks.ID");
		$this->db->join("users", "users.ID = project_task_members.userid");
		$this->db->join("task_status", "task_status.ID = project_tasks.status");
		$this->db->where('project_tasks.status >', 0);
		if($pro_id != '') {
			$this->db->where('project_tasks.projectid', $pro_id);
		}
		$this->db->where('project_tasks.archived', 0);
		$this->db->where('project_tasks.template', 0);
		if($from_date != '' && $to_date != ''){
			$this->db->where("from_unixtime(project_tasks.due_date, '%Y-%m-%d') <=",$to_date);
			$this->db->where("from_unixtime(project_tasks.due_date, '%Y-%m-%d') >=",$from_date); 
		} else {
			/* $this->db->where("MONTH(project_task_objectives.completed_datetime)", date('m')); 
			$this->db->where("YEAR(project_task_objectives.completed_datetime)", date('Y')); */
		}
		$this->db->group_start();
		/* $this->db->where("pm2.userid", $userid); */
		$this->db->where("(pr2.admin = 1 OR pr2.task = 1 OR pr2.client)");
		$this->db->where("projects.status", 0);
		$this->db->group_end();
		$this->db->group_by("project_tasks.ID");
		$this->db->order_by('project_tasks.ID', 'DESC');
        $query_resultant1 = $this->db->get();
        /*  echo $this->db->last_query();  */
		return $query_resultant1;
    }
	
	public function get_task_objective_mutiple($id) 
	{
		return $this->db
			->where_in("project_task_objectives.ID", $id)
			->select("project_task_objectives.*,
				project_tasks.ID as taskid, project_tasks.projectid, project_tasks.name,
				project_tasks.complete_sync, project_tasks.status,
				project_tasks.template")
			->join("project_tasks", "project_tasks.ID = project_task_objectives.taskid")
			->get("project_task_objectives");
	}
	
	/* function deleteselectedTasks($id)
    {
		if (!empty($id)) {
			$this->db->where_in('project_tasks.ID', $id);
			$this->db->delete('project_tasks');
			return $this->db->affected_rows();
		}
    } */
	
	public function deleteselectedTasks($id) 
	{
		$this->db->where_in("ID", $id)->delete("project_task_objectives");
	}

	public function delete_objective_members_multiple($id) 
	{
		$this->db->where_in("objectiveid", $id)->delete("project_task_objective_members");
	}
	
	public function get_project_tasks_chaptername($taskid){
		$this->db->select('project_tasks.chaptername'); 
		$this->db->from('project_tasks');
		$this->db->where("project_tasks.ID", $taskid);
		$res = $this->db->get();
        /* echo $this->db->last_query();  */ 
		return $res->result_array();
	}
	
	public function update_task_status($taskid){
		/* $data=array('current_login'=>date('Y-m-d H:i:s'));
		$this->db->set('last_login','current_login',false);
		$this->db->where('id',$taskid);
		$this->db->update('login_table',$data); */
		$upval = array('project_tasks.status'=>6);
		$this->db->where('project_tasks.ID', $taskid);
        $this->db->update('project_tasks', $upval);        
        return TRUE;
	}
}
?>