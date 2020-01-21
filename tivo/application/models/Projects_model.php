<?php

class Projects_Model extends CI_Model 
{
	/* public function get_project_categories() 
	{
		return $this->db->get("project_categories");
	}
	 */
	
	public function get_project_categories() 
	{ 
		return $this->db
			->select("COUNT(projects.catid) AS status_count,project_categories.name, project_categories.color, project_categories.ID")
			->join("projects", "projects.catid = project_categories.ID", "left outer") 
			->group_by('project_categories.name')
			->order_by('project_categories.ID', 'asc')
			->get("project_categories");
	}

	public function add_category($data) 
	{
		$this->db->insert("project_categories", $data);
	}

	public function get_category($id) 
	{
		return $this->db->where("ID", $id)->get("project_categories");
	}

	public function delete_category($id) 
	{
		$this->db->where("ID", $id)->delete("project_categories");
	}

	public function update_category($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_categories", $data);
	}

	public function add_project($data) 
	{
		$this->db->insert("projects", $data);
		return $this->db->insert_id();
	}

	public function get_projects($catid, $datatable) 
	{
		if($catid > 0) {
			$this->db->where("projects.catid", $catid);
		}
		$datatable->db_order();
		$datatable->db_search(array(
			"projects.name",
			"projects.publisher",
			"projects.process_name",
			"projects.broadcast_day_start_time",
			"projects.team_name",
			"projects.user_name"
			)
		);
		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.complete,projects.user_name,
				projects.complete_sync,projects.team_name,projects.broadcast_day_start_time,
				project_categories.name as catname,
				project_categories.color as cat_color,projects.publisher,projects.customer,projects.process_name,projects.pm,projects.frequency,projects.country")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("projects");
	}

	public function get_projects_index($catid) 
	{
		if($catid > 0) {
			$this->db->where("projects.catid", $catid);
		}
		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.complete,projects.user_name,
				projects.complete_sync,projects.team_name,projects.broadcast_day_start_time,
				project_categories.name as catname,
				project_categories.color as cat_color,projects.publisher,projects.customer,projects.process_name,projects.pm,projects.frequency,projects.country")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer") 
			->get("projects");
	}
	
	public function get_projects_user($userid) 
	{
		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.complete,
				projects.complete_sync,
				project_categories.name as catname,
				project_categories.color as cat_color")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->limit(5)
			->order_by("projects.ID", "DESC")
			->get("projects");
	}

	public function get_projects_user_all_no_pagination($userid, $permissions="") 
	{
		if($permissions) {
			$this->db->where($permissions);
		}

		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.calendar_id,
				projects.calendar_color, projects.complete,
				projects.complete_sync,
				project_categories.name as catname,
				project_categories.color as cat_color")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->order_by("projects.ID", "DESC")
			->get("projects");
	}
	
	public function get_projects_all_no_page() 
	{
		return $this->db->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.calendar_id,
				projects.calendar_color, projects.complete,
				projects.complete_sync")
			->where("projects.status", 0)
			->order_by("projects.ID", "DESC")
			->get("projects");
	}

	public function get_home_projects_user_all_no_pagination($userid, $permissions="") 
	{
		if($permissions) {
			$this->db->where($permissions);
		}

		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.calendar_id,
				projects.calendar_color, projects.complete,
				projects.complete_sync,
				project_categories.name as catname,
				project_categories.color as cat_color")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->where("projects.status", 0)
			->order_by("projects.ID", "DESC")
			->limit(10)
			->get("projects");
	}
	
	public function get_home_projects_all_no_page() 
	{
		return $this->db->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.calendar_id,
				projects.calendar_color, projects.complete,
				projects.complete_sync")
			->where("projects.status", 0)
			->order_by("projects.ID", "DESC")
			->limit(10)
			->get("projects");
	}
	
	public function get_shortest_startdate($projectid) 
	{
		return $this->db
			->select("FROM_UNIXTIME(project_tasks.start_date,'%d/%m/%Y') as start_date")
			//->join("projects", "projects.ID = project_tasks.projectid")
			->where("project_tasks.projectid", $projectid)
			->order_by("project_tasks.start_date", "ASC")
			->limit(1)
			->get("project_tasks");
	}
	
	public function get_longest_duedate($projectid) 
	{
		return $this->db
			->select("FROM_UNIXTIME(project_tasks.due_date,'%d/%m/%Y') as due_date")
			//->join("projects", "projects.ID = project_tasks.projectid")
			->where("project_tasks.projectid", $projectid)
			->order_by("project_tasks.due_date", "DESC")
			->limit(1)
			->get("project_tasks");
	}
	
	public function get_sum_price_project_tasks($projectid) 
	{
		return $this->db
			->select("SUM(totalPages) as totalPages,SUM(totalPagesPrice) as totalPagesPrice")
			->where("project_tasks.projectid", $projectid)
			->get("project_tasks");	
			
		/* return $this->db
		->select("SUM(totalPages) as totalPages,SUM(totalArticles) as totalArticles,SUM(totalPagesPrice) as totalPagesPrice,SUM(totalArticlesPrice) as totalArticlesPrice")
		->where("project_tasks.projectid", $projectid)
		->get("project_tasks"); */
	}
	
	public function get_projects_user_all($catid, $userid, $datatable) 
	{
		if($catid > 0) {
			$this->db->where("projects.catid", $catid);
		}

		$datatable->db_order();

		$datatable->db_search(array(
			"projects.name",
			"projects.publisher",
			"projects.process_name",
			"projects.broadcast_day_start_time",
			"projects.team_name",
			"projects.user_name"
			)
		);

		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status, projects.complete,projects.user_name,
				projects.complete_sync,projects.team_name,projects.broadcast_day_start_time,
				project_categories.name as catname,
				project_categories.color as cat_color,projects.publisher,projects.customer,projects.process_name,projects.pm,projects.frequency,projects.country")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->limit($datatable->length, $datatable->start)
			->get("projects");
	}

	public function get_total_projects_user_all_count($catid, $userid) 
	{
		if($catid > 0) {
			$this->db->where("projects.catid", $catid);
		}
		
		$s = $this->db
			->select("COUNT(*) as num")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->order_by("projects.ID", "DESC")
			->get("projects");
		$r = $s->row();
		if(isset($r)) return $r->num;
		return 0;
	}

	public function get_total_projects_count($catid) 
	{
		if($catid > 0) {
			$this->db->where("projects.catid", $catid);
		}
		$s= $this->db
			->select("COUNT(*) as num")
			->get("projects");
		$r = $s->row();
		if(isset($r)) return $r->num;
		return 0;
	}

	public function get_project($id) 
	{
		return $this->db
			->select("projects.*,
				project_categories.name as catname,
				project_categories.color as cat_color")
			->where("projects.ID", $id)
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->get("projects");
	}

	public function get_project_calendarid($id) 
	{
		return $this->db
			->select("projects.name, projects.ID, projects.description, 
				projects.timestamp, projects.image, projects.userid,
				projects.catid, projects.status,
				projects.calendar_id, projects.calendar_color,
				project_categories.name as catname,
				project_categories.color as cat_color")
			->where("projects.calendar_id", $id)
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->get("projects");
	}
	
	public function get_teams_by_groupuser() 
	{
		return $this->db
			->select("users.team_name,users.reporting")
			->group_by("users.team_name")
			->get("users");
	}
	
	public function get_user_by_teamname($team_name) 
	{		
		$string_version = "'" . implode( "','", $team_name) . "'";	
		$this->db->select("users.username,users.user_role");
		$this->db->where("users.team_name IN (".$string_version.")",NULL, false);
		$this->db->order_by("users.username", "asc"); 
		$this->db->from("users"); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_srcdetails_by_sourcename($source_name) 
	{		
		/* $string_version = "'" . implode( "','", $source_name) . "'"; */	
		$this->db->select("source_auto_populate.source_id,source_auto_populate.long_name,source_auto_populate.scheduling_team");
		/* $this->db->where("source_auto_populate.source_name IN (".$string_version.")",NULL, false); */
		$this->db->where("source_auto_populate.source_name", $source_name);   
		$this->db->from("source_auto_populate"); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_all_active_projects() 
	{
		return $this->db->where("status", 0)->get("projects");
	}

	public function delete_project($id) 
	{
		$this->db->where("ID", $id)->delete("projects");
	}

	public function update_project($id, $data) 
	{
		$this->db->where("ID", $id)->update("projects", $data);
	}

	public function get_messages($id, $page) 
	{
		return $this->db
			->where("project_chat.projectid", $id)
			->select("project_chat.ID, project_chat.message, project_chat.timestamp,
				project_chat.userid, project_chat.projectid,
				users.ID as userid, users.username, users.avatar, users.online_timestamp,
				users.first_name, users.last_name")
			->join("users", "users.ID = project_chat.userid")
			->order_by("project_chat.ID", "DESC")
			->limit(5, $page)
			->get("project_chat");
	}
	
	public function get_pro_namebyid($id) 
	{
		return $this->db
			->select("projects.name")
			->where("projects.ID", $id)
			->get("projects");
	}
	
	public function get_last_taskid() 
	{
		return $this->db
			->select("project_tasks.ID")
			->order_by("project_tasks.ID", "DESC")
			->limit(1)
			->get("project_tasks");
	}
	
	public function get_total_messages($id) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->where("projectid", $id)
			->get("project_chat");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function add_message($data) 
	{
		$this->db->insert("project_chat", $data);
	}

	public function get_message($id) 
	{
		return $this->db->where("ID", $id)->get("project_chat");
	}

	public function delete_message($id) 
	{
		$this->db->where("ID", $id)->delete("project_chat");
	}

	public function get_custom_fields() 
	{
		return $this->db->get("project_custom_fields");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("project_custom_fields", $data);
	}

	public function get_custom_field($id) 
	{
		return $this->db->where("ID", $id)->get("project_custom_fields");
	}

	public function update_custom_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_custom_fields", $data);
	}

	public function delete_custom_field($id) 
	{
		$this->db->where("ID", $id)->delete("project_custom_fields");
	}

	public function add_custom_field_value($data) 
	{
		$this->db->insert("project_custom_fields_value", $data);
	}

	public function get_custom_fields_answers($projectid) 
	{
		
		return $this->db
			->select("project_custom_fields.ID, project_custom_fields.name, project_custom_fields.type,
				project_custom_fields.required, project_custom_fields.help_text,
				project_custom_fields.options,
				project_custom_fields_value.value")
			->join("project_custom_fields_value", "project_custom_fields_value.fieldid = project_custom_fields.ID
			 AND project_custom_fields_value.projectid = " . $projectid, "LEFT OUTER")
			->get("project_custom_fields");

	}

	public function get_project_cf($fieldid, $projectid)
	{
		return $this->db
			->where("fieldid", $fieldid)
			->where("projectid", $projectid)
			->get("project_custom_fields_value");
	}

	public function update_custom_field_value($fieldid, $projectid, $value) 
	{
		$this->db->where("fieldid", $fieldid)
			->where("projectid", $projectid)
			->update("project_custom_fields_value", array("value" => $value));
	}

	public function get_immediate_reporters() 
	{
	 	$s = $this->db
			->select("users.reporting")
			->where("users.reporting != ", "")
			->order_by("users.reporting", "DESC")
			->get("users");
			
        return $s->result();   
	}
	
	public function check_projectname_is_free($projectname) 
	{
		$s=$this->db->where("name", $projectname)->get("projects");
		if($s->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function getfromcfg($cfgfile) 
	{
		return $this->db
			->where("projects.image", $cfgfile)
			->select("projects.ID,projects.image")
			->get("projects");
	}
	
	public function get_members_daily_productivity($team_group) 
	{
		/* $hour = 12;
		$today = strtotime($hour . ':00:00');
		echo $yesterday = strtotime('-1 day', $today); */
		$yesterday = date('Y-m-d',strtotime("-1 days"));
		$this->db->select("project_task_objectives.title, project_task_objectives.taskid,
			project_task_objectives.complete, project_task_objectives.completed_date
			, project_task_objectives.completed_time, project_task_objectives.productivity_percentage,
			project_task_objective_members.userid,users.username,users.team_name,users.avatar,users.online_timestamp,
			SUM(`project_task_objectives`.`productivity_percentage`)/COUNT(`project_task_objective_members`.`userid`) AS percent");
			$this->db->where("project_task_objectives.complete", 1);
			$this->db->where("project_task_objectives.completed_date", $yesterday);
			if($team_group != ''){
				$this->db->where("users.team_name", $team_group);
			} 
			$this->db->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID");
			$this->db->join("users", "users.ID = project_task_objective_members.userid");
			$this->db->group_by('project_task_objective_members.userid');
			$this->db->order_by('project_task_objective_members.userid', 'asc');
			$query = $this->db->get("project_task_objectives");
		/* echo $this->db->last_query(); */	
		return $query;
	}
	
	public function get_members_weekly_productivity() 
	{
		$query = $this->db->select("project_task_objectives.title, project_task_objectives.taskid,
			project_task_objectives.complete, project_task_objectives.completed_date
			, project_task_objectives.completed_time, project_task_objectives.productivity_percentage,
			project_task_objective_members.userid,users.username,users.team_name,users.avatar,users.online_timestamp,
			SUM(`project_task_objectives`.`productivity_percentage`)/COUNT(`project_task_objective_members`.`userid`) AS percent")
			->where("project_task_objectives.complete", 1)
			->where("project_task_objectives.completed_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()")
			//->where("YEARWEEK(`project_task_objectives.completed_date`, 1)", "YEARWEEK(CURDATE(), 1)") 
			->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID")
			->join("users", "users.ID = project_task_objective_members.userid")
			->group_by('project_task_objective_members.userid')
			->order_by('project_task_objective_members.userid', 'asc')
			->get("project_task_objectives");
		//echo $this->db->last_query();	
		return $query;
	} 
	
	public function get_members_monthly_productivity($from_date,$to_date,$team_group) 
	{
			$this->db->select("project_task_objectives.title, project_task_objectives.taskid,
			project_task_objectives.complete, project_task_objectives.completed_date
			, project_task_objectives.completed_time, project_task_objectives.productivity_percentage,
			project_task_objective_members.userid,users.username,users.team_name,users.avatar,users.online_timestamp,
			SUM(`project_task_objectives`.`productivity_percentage`)/COUNT(`project_task_objective_members`.`userid`) AS percent");
			$this->db->where("project_task_objectives.complete", 1);
			$this->db->where("MONTH(project_task_objectives.completed_date)", date('m')); 
			$this->db->where("YEAR(project_task_objectives.completed_date)", date('Y'));
			if($team_group != ''){
				$this->db->where("users.team_name", $team_group);
			} 
			if($from_date != '' && $to_date != ''){
				$this->db->where('project_task_objectives.completed_date <',$to_date);
				$this->db->where('project_task_objectives.completed_date >',$from_date); 
			} 
			$this->db->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID");
			$this->db->join("users", "users.ID = project_task_objective_members.userid");
			$this->db->group_by('project_task_objective_members.userid');
			$this->db->order_by('project_task_objective_members.userid', 'asc');
			$query = $this->db->get("project_task_objectives");
			
		/* echo $this->db->last_query(); */ 	 
		return $query;
	} 
	
	/* public function get_members_daily_productivity() 
	{
		$yesterday = date('Y-m-d',strtotime("-1 days"));
		$query = $this->db->select("project_task_objectives.title, project_task_objectives.taskid,
			project_task_objectives.complete, project_task_objectives.completed_date
			, project_task_objectives.completed_time, project_task_objectives.productivity_percentage,
			project_task_objective_members.userid,users.username,users.avatar,users.online_timestamp
			->where("project_task_objectives.complete", 1)
			->where("project_task_objectives.completed_date", $yesterday)
			->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID")
			->join("users", "users.ID = project_task_objective_members.userid")
			->get("project_task_objectives");
		echo $this->db->last_query();	
		return $query;
	}
	
	public function get_all_prod_members() 
	{
		return $this->db->select("users.ID as userid, users.username, users.avatar, 
				users.first_name, users.last_name,
				users.email, users.email_notification, users.online_timestamp,
				users.employee_id")
			->get("users");
	} */
	
	public function get_team_name() 
	{
	 	$s = $this->db
			->select("team_name.*")
			->order_by("team_name.team_name", "ASC")
			->get("team_name");
			
        return $s->result();   
	}	
	
	public function get_user_team_name($userid) 
	{
	 	$s = $this->db
			->select("users.team_name")
			->where("users.ID", $userid)
			->get("users");
			
        return $s->result(); 		
	}
	
	public function get_default_team_name($userid) 
	{
	 	$s = $this->db
			->select("users.team_name")
			->where("users.ID", $userid)
			->get("users");
			
        return $s->result_array(); 		
	}
	
	public function get_project_name() 
	{
	 	$s = $this->db
			->select("projects.ID,projects.name")
			->order_by("projects.name", "asc")
			->get("projects");
			
        return $s->result();   
	}
	
	public function get_user_project_name($userid) 
	{
	 	$s = $this->db
			->select("projects.ID,projects.name")
			->join("projects", "projects.ID = project_members.projectid")
			->where("project_members.userid", $userid)
			->order_by("projects.name", "asc")
			->get("project_members");
			/* echo $this->db->last_query();  */
        return $s->result(); 		
	}
	
	public function get_user_projects_name($userid) 
	{
	 	$s = $this->db
			->select("projects.ID")
			->join("projects", "projects.ID = project_members.projectid")
			->where("project_members.userid", $userid)
			->order_by("projects.name", "asc")
			->get("project_members");
			/* echo $this->db->last_query(); */
        return $s->result_array(); 		
	}
	
	public function getdefault_projectgroup($userid) 
	{
		$this->db->select("projects.ID");
			$this->db->join("projects", "projects.ID = project_members.projectid");
			$this->db->where("project_members.userid", $userid);
			$this->db->order_by("projects.name", "asc");
			$this->db->limit(1);
			$query = $this->db->get("project_members");
		/* echo $this->db->last_query();  */	
		return $query->result_array();
	} 
	
/* 	public function projectwise_status() 
	{
	 	$query=$this->db->query("
        SELECT `projects`.`name` AS project_name, `task_status`.`name` as tasks_status, COUNT(`project_tasks`.`status`) AS tasks_status_count
        FROM `project_tasks`  
        JOIN `projects` ON `projects`.`ID` = `project_tasks`.`projectid` 
        JOIN `task_status` ON `task_status`.`ID` = `project_tasks`.`status` 
        WHERE `project_tasks`.`archived` = 0 AND `project_tasks`.`template` = 0
        GROUP BY `projects`.`ID`, `project_tasks`.`status`
        ORDER BY `projects`.`ID` DESC;");
		$result = $query->result_array();
		return $result; 
	} */
	
	public function projectwise_status($project_group) 
	{
	/* echo $pro_group = "'" . str_replace(",", "','", $project_group) . "'";  */
		$this->db->select("projects.ID,projects.name AS project_name, ,GROUP_CONCAT(`task_status`.`name` ORDER BY `task_status`.`ID` ASC) as tasks_status, COUNT(project_tasks.status) AS tasks_status_count");
			$this->db->where("project_tasks.archived", 0);
			$this->db->where("project_tasks.template", 0);
			if($project_group != ''){
				/* $this->db->where_in("projects.ID",$pro_group); */
				$this->db->where("projects.ID IN (".$project_group.")",NULL, false);
			}  
			/* if($project_group == 0){
				$this->db->join("project_members", "project_members.projectid = projects.ID");
				$this->db->where("project_members.userid", $userid);
			}   */
			$this->db->join("projects", "projects.ID = project_tasks.projectid");
			$this->db->join("task_status", "task_status.ID = project_tasks.status");
			$this->db->group_by('projects.ID');
			$this->db->order_by('projects.name', 'asc');
			$query = $this->db->get("project_tasks");
		/* echo $this->db->last_query(); */  
		return $query->result_array();
	} 
	
	public function projectstatus_overall() 
	{
		$this->db->select("task_status.name AS tasks_status, COUNT(`project_tasks`.`status`) AS tasks_status_count");
			$this->db->where("project_tasks.archived", 0);
			$this->db->where("project_tasks.template", 0);
			$this->db->join("task_status", "task_status.ID = project_tasks.status");
			$this->db->join("projects", "projects.ID = project_tasks.projectid");
			$this->db->group_by('project_tasks.status');
			$this->db->order_by('project_tasks.status', 'asc');
			$query = $this->db->get("project_tasks");
		/* echo $this->db->last_query();  */	
		return $query;
	}
	
		
	public function project_total() 
	{
		$this->db->select("COUNT(`projects`.`ID`) as total_projects");
			$query = $this->db->get("projects");
		/* echo $this->db->last_query(); */	
		return $query;
	}
	
	public function titles_total() 
	{
		$this->db->select("COUNT(`project_tasks`.`ID`) as total_titles");
		$this->db->join("projects", "projects.ID = project_tasks.projectid");
		$query = $this->db->get("project_tasks");
		/* echo $this->db->last_query();  */
		return $query;
	}
	
	public function get_pro_publisher() 
	{
		return $this->db
			->select("DISTINCT(project_task_unitprice.publisher) as publisher")
			->order_by("project_task_unitprice.ID", " ASC")
			->get("project_task_unitprice");
	}
	
	public function get_pro_process_name() 
	{
		return $this->db
			->select("DISTINCT(project_task_unitprice.process) as process_name")
			->order_by("project_task_unitprice.ID", " ASC")
			->get("project_task_unitprice");
	}
	
	public function get_pro_stage() 
	{
		return $this->db
			->select("DISTINCT(project_task_unitprice.stage) as stage")
			->where("project_task_unitprice.stage IS NOT NULL", NULL, FALSE)
			->order_by("project_task_unitprice.ID", " ASC")
			->get("project_task_unitprice");
	}
	
	public function get_pro_pdf_type() 
	{
		return $this->db
			->select("DISTINCT(project_task_unitprice.pdfType) as pdfType")
			->where("project_task_unitprice.pdfType IS NOT NULL", NULL, FALSE)
			->order_by("project_task_unitprice.ID", " ASC")
			->get("project_task_unitprice");
	}
	
	public function get_pro_unit() 
	{
		return $this->db
			->select("DISTINCT(project_task_unitprice.unit) as unit")
			->where("project_task_unitprice.unit IS NOT NULL", NULL, FALSE)
			->order_by("project_task_unitprice.ID", " ASC")
			->get("project_task_unitprice");
	}
	
	public function projectssexport($userid){
		return $this->db->select("projects.ID, projects.customer as source_id, projects.name as source_name,  
		project_categories.name as transition_status, projects.process_name as format_used,
		projects.team_name, projects.user_name, projects.image as channel_config, 
		projects.broadcast_day_start_time as broadcast_day_start_time,
		DATE_FORMAT(FROM_UNIXTIME(projects.timestamp), '%d-%m-%Y %h:%i:%s') as created_date")
			->join("project_categories", "project_categories.ID = projects.catid", "left outer")
			->join("project_members as pm2", "pm2.projectid = projects.ID")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid")
			->where("pm2.userid", $userid)
			->order_by("projects.ID", "DESC")
			->get("projects");
    }
	
	public function projectssexportall() 
	{
		return $this->db
			->select("projects.ID, projects.customer as source_id, projects.name as source_name,  
		project_categories.name as transition_status, projects.process_name as format_used,
		projects.team_name, projects.user_name, projects.image as channel_config, 
		projects.broadcast_day_start_time as broadcast_day_start_time, 
			DATE_FORMAT(FROM_UNIXTIME(projects.timestamp), '%d-%m-%Y %h:%i:%s') as created_date")
			->join("project_categories", 
					"project_categories.ID = projects.catid", "left outer")
			->order_by("projects.ID", "DESC")		
			->get("projects");
	}
	
	public function importprojectsdata($data) 
	{
		$res = $this->db->insert("projects", $data);
		/* $res = $this->db->insert_batch("projects", $data); */
		if($res){
           // return TRUE;
			return $this->db->insert_id();
        }else{
            return FALSE;
        }
	}
	
	public function getprojectsimage($rid){
		return $this->db->select("projects.ID, projects.image")
			->where("projects.ID", $rid)
			->order_by("projects.ID", "DESC")
			->get("projects");
    }
	
	public function add_source_autopopulate($data) 
	{
		$this->db->insert("source_auto_populate", $data);
		return $this->db->insert_id();
	}
}
?>