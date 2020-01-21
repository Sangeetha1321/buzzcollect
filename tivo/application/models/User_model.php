<?php

class User_Model extends CI_Model 
{

	public function getUser($email, $pass) 
	{
		return $this->db->select("ID")
		->where("email", $email)->where("password", $pass)->get("users");
	}

	public function get_user_by_id($userid) 
	{
		return $this->db->where("ID", $userid)->get("users");
	}

	public function get_user_by_usersname($username) 
	{
		return $this->db->where("users.username IN (".$username.")",NULL, false)->get("users");
	}
	
	public function get_user_by_username($username) 
	{
		return $this->db->where("username", $username)->get("users"); 
	}

	public function delete_user($id) 
	{
		$this->db->where("ID", $id)->delete("users");
	}

	public function get_new_members($limit) 
	{
		return $this->db->select("email, username, joined, oauth_provider")
		->order_by("ID", "DESC")->limit($limit)->get("users");
	}

	public function get_registered_users_date($month, $year) 
	{
		$s= $this->db->where("joined_date", $month . "-" . $year)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_oauth_count($provider) 
	{
		$s= $this->db->where("oauth_provider", $provider)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_members_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_active_today_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_new_today_count() 
	{
		$s= $this->db->where("joined >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_online_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 60*15)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_online_users() 
	{
		return $this->db
		->where("users.online_timestamp >", time() - 60*15)
		->select("users.username, users.first_name, users.last_name, users.ID,
			users.online_timestamp, users.avatar")
		->get("users");
	}

	public function get_members($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"user_roles.name"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_admin($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.employee_id",
			"users.first_name",
			"users.last_name",
			"users.designation",
			"users.department",
			"users.category",	
			"users.team_name",	
			"users.email",
			"user_roles.name",
			"users.doj",
			"users.reporting",
			"users.bloodGroup",
			"users.mobile",
			"users.skillSet",
			"users.education"
			)
		);

		return $this->db->select("users.*,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_by_search($search) 
	{
		return $this->db->select("users.username, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_username($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_email($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.email", $search)
		->get("users");
	}

	public function search_by_first_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.first_name", $search)
		->get("users");
	}

	public function search_by_last_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.last_name", $search)
		->get("users");
	}

	public function update_user($userid, $data) {
		$this->db->where("ID", $userid)->update("users", $data);
	}
	
	public function update_project_stat($projectid, $data) {
		$this->db->where("ID", $projectid)->update("projects", $data);
	}

	public function check_block_ip() 
	{
		$s = $this->db->where("IP", $_SERVER['REMOTE_ADDR'])->get("ip_block");
		if($s->num_rows() == 0) return false;
		return true;
	}

	public function get_user_groups($userid) 
	{
		return $this->db->where("user_group_users.userid", $userid)
			->select("user_groups.name,user_groups.ID as groupid, 
				user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->get("user_group_users");
	}

	public function check_user_in_group($userid, $groupid) 
	{
		$s = $this->db->where("userid", $userid)->where("groupid", $groupid)
			->get("user_group_users");
		if($s->num_rows() == 0) return 0;
		return 1;
	}

	public function get_default_groups() 
	{
		return $this->db->where("default", 1)->get("user_groups");
	}

	public function add_user_to_group($userid, $groupid) 
	{
		$this->db->insert("user_group_users", array(
			"userid" => $userid, 
			"groupid" => $groupid
			)
		);
	}

	public function add_points($userid, $points) 
	{
        $this->db->where("ID", $userid)
        	->set("points", "points+$points", FALSE)->update("users");
    }

    public function get_notifications($userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit(5)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function delete_notification($id) 
    {
    	$this->db->where("ID", $id)->delete("user_notifications");
    }

    public function get_notifications_unread($userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit(5)
    		->where("user_notifications.status", 0)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notification($id, $userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->where("user_notifications.ID", $id)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all($userid, $datatable) 
    {
    	$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"user_notifications.message",
			)
		);

    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			users.online_timestamp,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit($datatable->length, $datatable->start)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all_fp($userid, $page, $max=10) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			users.online_timestamp,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit($max, $page)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all_total($userid) 
    {
    	$s = $this->db
    		->where("user_notifications.userid", $userid)
    		->select("COUNT(*) as num")
    		->get("user_notifications");
    	$r = $s->row();
    	if(isset($r->num)) return $r->num;
    	return 0;
    }

    public function add_notification($data) 
    {
    	if(isset($data['email']) && isset($data['email_notification']) 
    		&& $data['email_notification']) {
	    	// Send Email
	    	$subject = $this->settings->info->site_name .' - '. lang("ctn_1414");
	    	
	    	if(isset($data['username'])) {
				$username = $data['username'] . ",";
			} else {
				$username = lang("ctn_357");
			}

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$this->load->model("home_model");
			$email_template = $this->home_model->get_email_template_hook("new_notification", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($subject,
				 $email_template->message, $data['email']);
		}
		unset($data['email']);
		unset($data['email_notification']);
		unset($data['username']);
    	$this->db->insert("user_notifications", $data);
    }

    public function update_notification($id, $data) 
    {
    	$this->db->where("ID", $id)->update("user_notifications", $data);
    }

    public function update_user_notifications($userid, $data) 
    {
    	$this->db->where("userid", $userid)
    		->update("user_notifications", $data);
    }

    public function increment_field($userid, $field, $amount) 
    {
    	$this->db->where("ID", $userid)
    		->set($field, $field . '+' . $amount, FALSE)->update("users");
    }

    public function decrement_field($userid, $field, $amount) 
    {
    	$this->db->where("ID", $userid)
    		->set($field, $field . '-' . $amount, FALSE)->update("users");
    }

    public function get_usernames($username) 
    {
    	return $this->db->like("username", $username)->limit(10)->get("users");
    }

    public function add_user_log($data) 
    {
    	$this->db->insert("user_action_log", $data);
    }

    public function get_verify_user($code, $username) 
    {
    	return $this->db
    		->where("activate_code", $code)
    		->where("username", $username)
    		->get("users");
    }

    public function get_user_event($request) 
    {
    	return $this->db->where("IP", $_SERVER['REMOTE_ADDR'])
    		->where("event", $request)
    		->order_by("ID", "DESC")
    		->get("user_events");
    }

    public function add_user_event($data) 
    {
    	$this->db->insert("user_events", $data);
    }

    public function get_user_role($roleid) 
    {
    	return $this->db->where("ID", $roleid)->get("user_roles");
    }

    public function get_custom_fields($data) 
	{
		if(isset($data['register'])) {
			$this->db->where("register", 1);
		}
		return $this->db->get("custom_fields");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("user_custom_fields", $data);
	}

	public function get_custom_fields_answers($data, $userid) 
	{
		if(isset($data['edit'])) {
			$this->db->where("custom_fields.edit", 1);
		}
		return $this->db
			->select("custom_fields.ID, custom_fields.name, custom_fields.type,
				custom_fields.required, custom_fields.help_text,
				custom_fields.options,
				user_custom_fields.value")
			->join("user_custom_fields", "user_custom_fields.fieldid = custom_fields.ID
			 AND user_custom_fields.userid = " . $userid, "LEFT OUTER")
			->get("custom_fields");

	}

	public function get_user_cf($fieldid, $userid)
	{
		return $this->db
			->where("fieldid", $fieldid)
			->where("userid", $userid)
			->get("user_custom_fields");
	}

	public function update_custom_field($fieldid, $userid, $value) 
	{
		$this->db->where("fieldid", $fieldid)
			->where("userid", $userid)
			->update("user_custom_fields", array("value" => $value));
	}

	public function get_profile_comments($userid, $page) 
	{
		return $this->db
			->where("profile_comments.profileid", $userid)
			->select("profile_comments.ID, profile_comments.comment,
				profile_comments.userid, profile_comments.timestamp,
				profile_comments.profileid, profile_comments.userid,
				users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = profile_comments.userid")
			->limit(5, $page)
			->order_by("profile_comments.ID", "DESC")
			->get("profile_comments");
	}

	public function add_profile_comment($data) 
	{
		$this->db->insert("profile_comments", $data);
	}

	public function get_profile_comment($id) 
	{
		return $this->db->where("ID", $id)->get("profile_comments");
	}

	public function delete_profile_comment($id) 
	{
		$this->db->where("ID", $id)->delete("profile_comments");
	}

	public function get_total_profile_comments($userid) 
	{
		$s = $this->db
			->where("profile_comments.profileid", $userid)
			->select("COUNT(*) as num")
			->get("profile_comments");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function increase_profile_views($userid) 
	{
		$this->db->where("ID", $userid)->set("profile_views", "profile_views+1", FALSE)->update("users");
	}

	public function add_user_data($data) 
	{
		$this->db->insert("user_data", $data);
	}

	public function update_user_data($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_data", $data);
	}

	public function get_user_data($userid) 
	{
		$s = $this->db->where("userid", $userid)->get("user_data");
		if($s->num_rows() == 0) {
			$this->user_model->add_user_data(array(
				"userid" => $userid
				)
			);
			return $this->user_model->get_user_data($userid);
		} else {
			return $s->row();
		}

	}

	public function get_users_with_permissions($permissions) 
	{

		foreach($permissions as $p) {
			$this->db->or_where("user_roles." . $p, 1);
		}

		return $this->db
			->select("users.ID as userid, users.username, users.email, users.first_name,
				users.last_name, users.online_timestamp")
			->join("user_roles", "user_roles.ID = users.user_role")
			->get("users");
	}

	public function get_all_user_groups() 
	{
		return $this->db->get("user_groups");
	}

	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}

	public function get_all_clients_proj($projects, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			)
		);

		$this->db->group_start();
		foreach($projects as $p) {
			$this->db->or_where("project_members.projectid", $p);
		}
		$this->db->group_end();

		return $this->db
			->where("user_roles.client", 1)
			->select("users.ID, users.username, users.first_name, users.last_name,
				users.email, users.avatar, users.online_timestamp,
				user_roles.name as role_name")
			->join("users", "users.ID = project_members.userid")
			->join("user_roles", "user_roles.ID = users.user_role")
			->limit($datatable->length, $datatable->start)
			->get("project_members");
	}

	public function get_all_clients_proj_count($projects) 
	{
		$this->db->group_start();
		foreach($projects as $p) {
			$this->db->where("project_members.projectid", $p);
		}
		$this->db->group_end();

		$s = $this->db
			->where("user_roles.client", 1)
			->select("COUNT(*) as num")
			->join("users", "users.ID = project_members.userid")
			->join("user_roles", "user_roles.ID = users.user_role")
			->get("project_members");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_clients($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			)
		);

		return $this->db
			->where("user_roles.client", 1)
			->select("users.ID, users.username, users.first_name, users.last_name,
				users.email, users.avatar, users.online_timestamp,
				user_roles.name as role_name")
			->join("user_roles", "user_roles.ID = users.user_role", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("users");
	}

	public function get_all_clients_count($userid) 
	{
		$s = $this->db
			->where("user_roles.client", 1)
			->select("COUNT(*) as num")
			->join("user_roles", "user_roles.ID = users.user_role", "left outer")
			->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_all_users($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			)
		);

		return $this->db
			->select("users.ID, users.username, users.first_name, users.last_name,
				users.email, users.avatar, users.online_timestamp,
				user_roles.name as role_name")
			->join("user_roles", "user_roles.ID = users.user_role", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("users");
	}

	public function get_all_users_count() 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_tasks_today($current_timestamp) 
	{
		/* $q = $this->db
			->where("FROM_UNIXTIME(project_tasks.due_date, '%Y-%m-%d') =", $current_timestamp)
			->where("project_tasks.status !=", 3)
			->select("project_tasks.name as title, GROUP_CONCAT(users.username SEPARATOR ',') as user, GROUP_CONCAT(DISTINCT users.team_name SEPARATOR ',') as team, project_tasks.totalPages,project_tasks.totalArticles")
			->join("project_task_members", "project_task_members.taskid = project_tasks.ID")
			->join("users", "users.ID = project_task_members.userid")
			->group_by("project_tasks.name")
			->get("project_tasks");
		return $q;	 */	
		
		$q = $this->db
			->where("FROM_UNIXTIME(project_tasks.due_date, '%Y-%m-%d') =", $current_timestamp)
			->where("project_tasks.status !=", 3)
			->select("project_tasks.name as title, GROUP_CONCAT(users.username SEPARATOR ',') as user, GROUP_CONCAT(DISTINCT users.team_name SEPARATOR ',') as team, project_tasks.totalPages")
			->join("project_task_members", "project_task_members.taskid = project_tasks.ID")
			->join("users", "users.ID = project_task_members.userid")
			->group_by("project_tasks.name")
			->get("project_tasks");
		return $q;	
	}
	
	public function idle_operator() 
	{
		/* $q = $this->db
			->where("project_task_objectives.started", 1)
			->select("project_task_objective_members.userid")
			->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID")
			->group_by("project_task_objective_members.userid")
			->order_by("project_task_objective_members.userid", "asc")
			->get("project_task_objectives");
		return $q;	*/
		
		$q = $this->db
			->where("project_task_objectives.started", 1)
			->select("GROUP_CONCAT(DISTINCT project_task_objective_members.userid SEPARATOR ',') as userid")
			->join("project_task_objective_members", "project_task_objective_members.objectiveid = project_task_objectives.ID")
			->order_by("project_task_objective_members.userid", "asc")
			->get("project_task_objectives");
		return $q;	 
	}
	
	public function user_byid() 
	{
		$q = $this->db
			->select("GROUP_CONCAT(DISTINCT users.ID SEPARATOR ',') as userid")
			->order_by("users.ID", "asc")
			->get("users");
		return $q;
	}

	public function idle_memlist($result) 
	{
		$q = $this->db->select("users.employee_id,users.username,users.email,users.team_name")
			 ->where("users.ID IN (".$result.")",NULL, false)
			 ->where("users.user_role",7)
			 ->order_by("users.ID", "asc")
			 ->get('users'); 
		return $q;
	}
	
	public function load_details() 
	{
		$q = $this->db->select("`projects`.`name` AS project_name,COUNT(`project_tasks`.`ID`) AS title_count,`project_tasks`.unit,FROM_UNIXTIME(`project_tasks`.`due_date`,'%Y-%m-%d') AS due_date,GROUP_CONCAT(`task_status`.`name` ORDER BY `task_status`.`ID` ASC) as title_status")
			 ->where("project_tasks.unit IS NOT NULL",NULL, false)
			 ->where("project_tasks.unit !=",'')
			 ->where("project_tasks.archived", 0)
			 ->where("project_tasks.template", 0)
			 ->join("projects", "projects.ID = project_tasks.projectid")
			 ->join("task_status", "task_status.ID = project_tasks.status")
			 ->group_by("project_tasks.projectid,project_tasks.unit")
			 ->order_by("projects.name", "asc")
			 ->get('project_tasks'); 
		/* echo $this->db->last_query(); */  
		return $q;
	}
	
	/* public function tasks_rework_tbl($monthwise_filter = "") 
	{
	if($monthwise_filter != ''){
		$q = $this->db->select("projects.name as project,
		project_tasks.name as title,project_task_objectives.taskid,project_task_objectives.title as task,
		project_task_objectives.task_status,users.username,users.team_name,
		TIMEDIFF(project_task_objectives.completed_datetime, project_task_objectives.rework_datetime) as duration")
			 ->where("project_task_objectives.complete", 1)
			 ->where("project_task_objectives.task_status", "rework")
			 ->where("MONTH(project_task_objectives.completed_datetime)", $monthwise_filter)
			 ->where("YEAR(project_task_objectives.completed_datetime)", date('Y'))
			 ->join("project_tasks", "project_tasks.ID = project_task_objectives.taskid")
			 ->join("projects", "projects.ID = project_tasks.projectid")
			 ->join("users", "users.ID = project_task_objectives.userid")
			 ->order_by("users.team_name", "asc")
			->get('project_task_objectives'); 
		return $q;
	}
	else{
		$q = $this->db->select("projects.name as project,
		project_tasks.name as title,project_task_objectives.taskid,project_task_objectives.title as task,
		project_task_objectives.task_status,users.username,users.team_name,
		TIMEDIFF(project_task_objectives.completed_datetime, project_task_objectives.rework_datetime) as duration")
			 ->where("project_task_objectives.complete", 1)
			 ->where("project_task_objectives.task_status", "rework")
			 ->join("project_tasks", "project_tasks.ID = project_task_objectives.taskid")
			 ->join("projects", "projects.ID = project_tasks.projectid")
			 ->join("users", "users.ID = project_task_objectives.userid")
			 ->order_by("users.team_name", "asc")
			->get('project_task_objectives'); 
		return $q;	
	}
	} */
	
	public function tasks_rework_tbl($from_date = "",$to_date = "") 
	{
			$this->db->select("projects.name as project,
		project_tasks.name as title,project_task_objectives.taskid,project_task_objectives.title as task,
		project_task_objectives.task_status,users.username,users.team_name,
		TIMEDIFF(project_task_objectives.completed_datetime, project_task_objectives.rework_datetime) as duration");
			$this->db->where("project_task_objectives.complete", 1);
			$this->db->where("project_task_objectives.task_status", "rework");
			if($from_date != '' && $to_date != ''){
				$this->db->where("project_task_objectives.completed_datetime <",$to_date);
				$this->db->where("project_task_objectives.completed_datetime >",$from_date); 
			} else {
				/* $this->db->where("MONTH(project_task_objectives.completed_datetime)", date('m')); 
				$this->db->where("YEAR(project_task_objectives.completed_datetime)", date('Y')); */
			}
			$this->db->join("project_tasks", "project_tasks.ID = project_task_objectives.taskid");
			$this->db->join("projects", "projects.ID = project_tasks.projectid");
			$this->db->join("users", "users.ID = project_task_objectives.userid");
			$this->db->order_by("users.team_name", "asc");
			$query = $this->db->get("project_task_objectives");
			
		/* echo $this->db->last_query(); */ 	
		return $query;
	} 
	
	public function projectrevenue() 
	{
		$q = $this->db->select("projects.ID,projects.name as project_name,GROUP_CONCAT(DISTINCT CASE WHEN users.team_name = '' THEN NULL ELSE users.team_name END ORDER BY users.team_name ASC SEPARATOR ',') as team_name,GROUP_CONCAT(DISTINCT CASE WHEN users.reporting = '' THEN NULL ELSE users.reporting END ORDER BY users.reporting ASC SEPARATOR ',') as reporting,projects.process_name,project_categories.name as category")
			 ->join("project_members", "project_members.projectid = projects.ID")
			 ->join("users", "users.ID = project_members.userid")
			 ->join("project_categories", "project_categories.ID = projects.catid")
			 ->where("projects.catid", 7)
			 ->group_by("projects.ID")
			 ->order_by("projects.ID", "asc")
			 ->get('projects'); 
		/* echo $this->db->last_query();  */
		return $q;
	}
	
	public function titlerevenue($from_date = "",$to_date = "") 
	{
		$this->db->select("project_tasks.*");
			if($from_date != '' && $to_date != ''){
				$this->db->where("FROM_UNIXTIME(project_tasks.due_date) <",$to_date);
				$this->db->where("FROM_UNIXTIME(project_tasks.due_date) >",$from_date); 
			}
			$this->db->order_by("project_tasks.ID", "asc");
			$q = $this->db->get('project_tasks'); 
		return $q;
	}
	
	public function revenue_export($from_date = "",$to_date = "") 
	{
		$this->db->select("projects.name as project_name,project_categories.name as project_status,projects.process_name,
		GROUP_CONCAT(DISTINCT CASE WHEN users.team_name = '' THEN NULL 
		ELSE users.team_name END ORDER BY users.team_name ASC SEPARATOR ',') as team_name,
		GROUP_CONCAT(DISTINCT CASE WHEN users.reporting = '' THEN NULL 
		ELSE users.reporting END ORDER BY users.reporting ASC SEPARATOR ',') as reporting,
		project_tasks.name as title_name,DATE_FORMAT(FROM_UNIXTIME(project_tasks.start_date), '%e/%m/%Y') as start_date,
		DATE_FORMAT(FROM_UNIXTIME(project_tasks.due_date), '%e/%m/%Y') as due_date,project_tasks.unit,
		CASE 
		WHEN project_tasks.parallel = 1 THEN
			CASE WHEN projects.process_name = 'Geofacets Table conversion' OR projects.process_name = 'Geofacets Figure conversion' THEN 
			project_tasks.totalTablesEditable + project_tasks.totalTablesScanned + project_tasks.totalFiguresEditable + project_tasks.totalFiguresScanned + project_tasks.parallel_process_totalTablesEditable + project_tasks.parallel_process_totalTablesScanned + project_tasks.parallel_process_totalFiguresEditable + project_tasks.parallel_process_totalFiguresScanned 
			ELSE 
				CASE 
				WHEN project_tasks.unit = 'Page' THEN project_tasks.totalPages + project_tasks.parallel_process_totalPages
				WHEN project_tasks.unit = 'Article' THEN project_tasks.totalArticles + project_tasks.parallel_process_totalArticles
				WHEN project_tasks.unit = 'table' THEN project_tasks.totalTablesEditable + project_tasks.totalTablesScanned + project_tasks.parallel_process_totalTablesEditable + project_tasks.parallel_process_totalTablesScanned
				WHEN project_tasks.unit = 'figure' THEN project_tasks.totalFiguresEditable + project_tasks.totalFiguresScanned + project_tasks.parallel_process_totalFiguresEditable + project_tasks.parallel_process_totalFiguresScanned
				WHEN project_tasks.unit = 'image' THEN project_tasks.totalImages + project_tasks.parallel_process_totalImages
				WHEN project_tasks.unit = 'Question' THEN 0
				ELSE 0 
				END
			END 
		ELSE
			CASE WHEN projects.process_name = 'Geofacets Table conversion' OR projects.process_name = 'Geofacets Figure conversion' THEN 
			project_tasks.totalTablesEditable + project_tasks.totalTablesScanned + project_tasks.totalFiguresEditable + project_tasks.totalFiguresScanned 
			ELSE 
				CASE 
				WHEN project_tasks.unit = 'Page' THEN project_tasks.totalPages
				WHEN project_tasks.unit = 'Article' THEN project_tasks.totalArticles
				WHEN project_tasks.unit = 'table' THEN project_tasks.totalTablesEditable + project_tasks.totalTablesScanned
				WHEN project_tasks.unit = 'figure' THEN project_tasks.totalFiguresEditable + project_tasks.totalFiguresScanned
				WHEN project_tasks.unit = 'image' THEN project_tasks.totalImages
				WHEN project_tasks.unit = 'Question' THEN 0
				ELSE 0 
				END
			END 
		END
		as unit_total,
		CASE 
		WHEN project_tasks.parallel = 1 THEN
			CASE WHEN projects.process_name = 'Geofacets Table conversion' OR projects.process_name = 'Geofacets Figure conversion' THEN 
			project_tasks.totalTablesPriceEditable + project_tasks.totalTablesPriceScanned + project_tasks.totalFiguresPriceEditable + project_tasks.totalFiguresPriceScanned + project_tasks.parallel_process_totalTablesPriceEditable + project_tasks.parallel_process_totalTablesPriceScanned + project_tasks.parallel_process_totalFiguresPriceEditable + project_tasks.parallel_process_totalFiguresPriceScanned 
			ELSE 
				CASE 
				WHEN project_tasks.unit = 'Page' THEN project_tasks.totalPagesPrice + project_tasks.parallel_process_totalPagesPrice
				WHEN project_tasks.unit = 'Article' THEN project_tasks.totalArticlesPrice + project_tasks.parallel_process_totalArticlesPrice
				WHEN project_tasks.unit = 'table' THEN project_tasks.totalTablesPriceEditable + project_tasks.totalTablesPriceScanned + project_tasks.parallel_process_totalTablesPriceEditable + project_tasks.parallel_process_totalTablesPriceScanned
				WHEN project_tasks.unit = 'figure' THEN project_tasks.totalFiguresPriceEditable + project_tasks.totalFiguresPriceScanned + project_tasks.parallel_process_totalFiguresPriceEditable + project_tasks.parallel_process_totalFiguresPriceScanned
				WHEN project_tasks.unit = 'image' THEN project_tasks.totalImagesPrice + project_tasks.parallel_process_totalImagesPrice
				WHEN project_tasks.unit = 'Question' THEN 0
				ELSE 0 
				END
			END 
		ELSE
			CASE WHEN projects.process_name = 'Geofacets Table conversion' OR projects.process_name = 'Geofacets Figure conversion' THEN 
			project_tasks.totalTablesPriceEditable + project_tasks.totalTablesPriceScanned + project_tasks.totalFiguresPriceEditable + project_tasks.totalFiguresPriceScanned 
			ELSE 
				CASE 
				WHEN project_tasks.unit = 'Page' THEN project_tasks.totalPagesPrice
				WHEN project_tasks.unit = 'Article' THEN project_tasks.totalArticlesPrice
				WHEN project_tasks.unit = 'table' THEN project_tasks.totalTablesPriceEditable + project_tasks.totalTablesPriceScanned
				WHEN project_tasks.unit = 'figure' THEN project_tasks.totalFiguresPriceEditable + project_tasks.totalFiguresPriceScanned
				WHEN project_tasks.unit = 'image' THEN project_tasks.totalImagesPrice
				WHEN project_tasks.unit = 'Question' THEN 0
				ELSE 0 
				END
			END 
		END
		as revenue_total");
		
		
		if($from_date != '' && $to_date != ''){
			$this->db->where("FROM_UNIXTIME(project_tasks.due_date) <",$to_date);
			$this->db->where("FROM_UNIXTIME(project_tasks.due_date) >",$from_date); 
		}
		$this->db->join("project_tasks", "project_tasks.projectid = projects.ID");
		$this->db->join("project_categories", "project_categories.ID = projects.catid");
		$this->db->join("project_members", "project_members.projectid = projects.ID");
		$this->db->join("users", "users.ID = project_members.userid");
		$this->db->where("projects.catid", 7);
		$this->db->group_by("project_tasks.ID");
		$this->db->order_by("projects.ID,project_tasks.ID", "asc");
		$q = $this->db->get("projects"); 
		/* echo $this->db->last_query(); */
		return $q;
	}
	
	public function adminmemberssexport(){
		$this->db->select('users.employee_id,users.username,users.designation,users.team_name,users.email,user_roles.name,users.bloodGroup,users.reporting', FALSE);
		$this->db->join("user_roles", "user_roles.ID = users.user_role");
		$this->db->from('users');
		$this->db->order_by('users.email', 'ASC');
        $query_resultant1 = $this->db->get();
        /* echo $this->db->last_query(); */
		return $query_resultant1;
    }
	
	public function adminunitpricesexport(){
		$this->db->select('project_task_unitprice.*', FALSE);
		$this->db->from('project_task_unitprice');
		$this->db->order_by('project_task_unitprice.ID', 'ASC');
        $query_resultant = $this->db->get();
		return $query_resultant;
    }
}
?>