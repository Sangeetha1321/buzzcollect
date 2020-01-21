<?php

class File_Model extends CI_Model 
{
	public function get_files($projectid, $folder_parent,$userid, $datatable) 
	{
		$this->db->order_by("project_files.folder_flag", "DESC");
		$datatable->db_order();

		$datatable->db_search(array(
			"project_files.file_name",
			"project_files.file_type",
			"project_files.format_used",
			"project_files.broadcast_day_st_time",
			"users.username",
			"task_status.name",
			"projects.name",
			"project_files.user_name"
			)
		);

		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name,
				project_files.file_url,project_files.user_name,
				project_files.folder_parent,
				project_files.format_used,
				project_files.broadcast_day_st_time,
				project_files.del_comment,
				project_files.timestamp,
				projects.name as project_name,
				task_status.name as file_status,
				task_status.color as file_status_color,
				users.username, users.avatar, users.online_timestamp,
				pr2.admin, pr2.file")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_members as pm2", "pm2.projectid = project_files.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->group_start()
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.file = 1)") 
			->where("project_files.folder_parent", $folder_parent)
			->where("project_files.projectid", $projectid)
			->group_end()
			->order_by("project_files.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_files");
	}

	public function get_files_total($projectid, $folder_parent, $userid) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_files.projectid")
			->join("project_members as pm2", "pm2.projectid = project_files.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.file = 1)") 
			->where("project_files.folder_parent", $folder_parent)
			->where("project_files.projectid", $projectid)
			->get("project_files");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_files_user_projects($userid, $folder_parent, $datatable) 
	{
		$this->db->order_by("project_files.folder_flag", "DESC");
		$datatable->db_order();

		$datatable->db_search(array(
			"project_files.file_name",
			"project_files.file_type",
			"project_files.format_used",
			"project_files.broadcast_day_st_time",
			"users.username",
			"task_status.name",
			"projects.name",
			"project_files.user_name"
			)
		);
		$s = $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name, 
				project_files.file_url,project_files.user_name,
				project_files.folder_parent,
				project_files.format_used,
				project_files.broadcast_day_st_time,
				project_files.del_comment,
				projects.name as project_name,
				project_files.timestamp,
				task_status.name as file_status,
				task_status.color as file_status_color,
				users.username, users.avatar, users.online_timestamp,
				pr2.admin, pr2.file")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_members as pm2", "pm2.projectid = project_files.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->group_start()
			->group_start()
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->where("(project_files.folder_parent", $folder_parent)
			->where("(pm2.userid", $userid) 
			->where("(pr2.admin = 1 OR pr2.file = 1)))")
			->group_end()
			->or_group_start()
			->or_where("project_files.projectid", 0)
			->where("project_files.folder_parent", $folder_parent)
			->where("project_files.userid", $userid)
			->group_end()
			->group_end()
			->order_by("project_files.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_files");
		
		return $s;
	}

	public function get_files_user_noproject($userid, $folder_parent, $datatable) 
	{
		$this->db->where("project_files.projectid", 0);
		$this->db->order_by("project_files.folder_flag", "DESC");

		$datatable->db_order();

		$datatable->db_search(array(
			"project_files.file_name",
			"project_files.file_type",
			"project_files.format_used",
			"project_files.broadcast_day_st_time",
			"users.username",
			"task_status.name",
			"projects.name",
			"project_files.user_name"
			)
		);
		
		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name,project_files.user_name,
				project_files.file_url,
				project_files.timestamp,
				project_files.folder_parent,
				project_files.format_used,
				project_files.broadcast_day_st_time,
				project_files.del_comment,
				task_status.name as file_status,
				task_status.color as file_status_color,
				projects.name as project_name,
				users.username, users.avatar, users.online_timestamp")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->group_start()
			->where("project_files.userid", $userid) 
			->where("project_files.folder_parent", $folder_parent)
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->group_end()
			->order_by("project_files.ID", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_files");
	}

	public function get_files_user_noproject_total($userid, $folder_parent) 
	{
		$this->db->where("project_files.projectid", 0);
		
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->where("project_files.userid", $userid) 
			->where("project_files.folder_parent", $folder_parent)
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->get("project_files");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_files_user_projects_total($userid, $folder_parent) 
	{
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_members as pm2", "pm2.projectid = project_files.projectid", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->where("(project_files.folder_parent", $folder_parent)
			->where("(pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.file = 1)))")
			->group_end()
			->or_group_start()
			->or_where("project_files.projectid", 0)
			->where("project_files.folder_parent", $folder_parent)
			->where("project_files.userid", $userid) 
			->group_end()
			->get("project_files");
		
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}


	public function get_all_files($folder_parent, $projectid, $filter, $datatable) 
	{
		if($projectid != -1) {
			$this->db->where("project_files.projectid", $projectid);
		}
		if($filter != 0) {
			$this->db->where("project_files.status", $filter);
		}
		/* $this->db->order_by("project_files.folder_flag", "DESC"); */

		$datatable->db_order();

		$datatable->db_search(array(
			"project_files.file_name",
			"project_files.file_type",
			"project_files.format_used",
			"project_files.broadcast_day_st_time",
			"users.username",
			"projects.team_name",
			"task_status.name",
			"projects.name",
			"project_files.user_name"
			)
		);
		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name,
				project_files.file_url,project_files.user_name,
				project_files.folder_parent,
				project_files.format_used,
				project_files.broadcast_day_st_time,
				project_files.del_comment,
				project_files.timestamp,
				projects.name as project_name,
				task_status.name as file_status,
				task_status.color as file_status_color,
				users.username, projects.team_name, users.avatar, users.online_timestamp")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->group_start()
			->where("project_files.folder_parent", $folder_parent)
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->group_end() 
			->where("(project_files.status != 9 OR project_files.del != 1)")
			->order_by("project_files.timestamp", "DESC")
			->limit($datatable->length, $datatable->start)
			->get("project_files");
	}

	public function get_all_files_total($folder_parent, $projectid, $filter) 
	{
		if($projectid != -1) {
			$this->db->where("project_files.projectid", $projectid);
		}
		if($filter != 0) {
			$this->db->where("project_files.status", $filter);
		}
		$s = $this->db
			->select("COUNT(*) as num")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->where("project_files.folder_parent", $folder_parent) 
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->where("(project_files.status != 9 OR project_files.del != 1)")
			->get("project_files");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_file($id) 
	{
		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name, project_files.timestamp,
				project_files.folder_parent, project_files.projectid,
				project_files.del_comment,
				project_files.file_url,project_files.format_used,project_files.broadcast_day_st_time,
				project_files.status,projects.name as project_name,task_status.name as file_status,
				task_status.color as file_status_color,project_files.period,
				project_files.st_date,project_files.st_time,project_files.end_date,project_files.end_time,
				users.username, users.avatar, users.online_timestamp,
				folder.ID as folderid, folder.folder_name as parent_folder_name")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_files as folder", "folder.ID = project_files.folder_parent", "left outer")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->where("project_files.ID", $id)
			->get("project_files");
	}

	public function get_files_by_project($projectid, $filename) 
	{
		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name, project_files.timestamp,
				project_files.folder_parent, project_files.projectid,
				project_files.file_url,
				projects.name as project_name,
				users.username, users.avatar, users.online_timestamp,
				folder.ID as folderid, folder.folder_name as parent_folder_name")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_files as folder", "folder.ID = project_files.folder_parent", "left outer")
			->where("(project_files.projectid", $projectid)
			->or_where("project_files.projectid = 0)")
			->where("project_files.folder_flag", 0)
			->like("project_files.file_name", $filename)
			->get("project_files");
	}

	public function get_folder($folderid) 
	{
		return $this->db
			->where("ID", $folderid)->where("folder_flag", 1)
			->get("project_files");
	}

	public function get_folders($projectid) 
	{
		return $this->db->where("projectid", $projectid)
			->where("folder_flag", 1)
			->get("project_files");
	}

	public function add_file($data) 
	{
		$this->db->insert("project_files", $data);
		return $this->db->insert_id();
	}

	public function update_file($id, $data) 
	{
		$this->db->where("ID", $id)->update("project_files", $data);
	}

	public function delete_file($id, $data) 
	{
		/* $this->db->where("ID", $id)->delete("project_files"); */
		$this->db->where("ID", $id)->update("project_files", $data);
	}

	public function get_file_notes($fileid) 
	{
		return $this->db
			->where("project_file_notes.fileid", $fileid)
			->select("project_file_notes.ID, project_file_notes.note,
				project_file_notes.timestamp,
				users.ID as userid, users.username, users.avatar,
				users.online_timestamp")
			->join("users", "users.ID = project_file_notes.userid")
			->get("project_file_notes");
	}

	public function get_file_note($id) 
	{
		return $this->db->where("ID", $id)->get("project_file_notes");
	}

	public function delete_file_note($id) 
	{
		$this->db->where("ID", $id)->delete("project_file_notes");
	}

	public function add_file_note($data) 
	{
		$this->db->insert("project_file_notes", $data);
	}

	public function get_recent_files_by_project($projectid) 
	{
		return $this->db
			->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name, project_files.timestamp,
				project_files.folder_parent, project_files.projectid,
				project_files.file_url,
				projects.name as project_name,
				users.username, users.avatar, users.online_timestamp,
				folder.ID as folderid, folder.folder_name as parent_folder_name")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("project_files as folder", "folder.ID = project_files.folder_parent", "left outer")
			->where("(project_files.projectid", $projectid)
			->or_where("project_files.projectid = 0)")
			->where("project_files.folder_flag", 0)
			->limit(5)
			->order_by("project_files.ID", "DESC")
			->get("project_files");
	}

	public function getfromsource($src_id) 
	{
		return $this->db
			->where("projects.ID", $src_id)
			->select("projects.process_name,projects.broadcast_day_start_time,projects.user_name,projects.team_name")
			->get("projects");
	}
	
	public function monitor_file_status() 
	{ 
		return $this->db
			->select("COUNT(project_files.status) AS status_count,task_status.name AS status_name, task_status.color AS status_color, task_status.ID")
			->join("project_files", "project_files.status = task_status.ID", "left outer") 
			->where('task_status.ID !=', 9)
			->group_by('task_status.name')
			->order_by('task_status.ID', 'asc')
			->get("task_status");
	}
	
	public function monitor_source_status() 
	{ 
		return $this->db
			->select("COUNT(project_categories.name) AS status_count,project_categories.name AS status_name, project_categories.color AS status_color")
			->join("project_categories", "project_categories.ID = projects.catid", "left outer") 
			->group_by('project_categories.name')
			->order_by('project_categories.ID', 'asc')
			->get("projects");
	}
	
	public function getfromcomt($dl) 
	{
		return $this->db
			->where("projects.image", $dl)
			->select("projects.ID,projects.image")
			->get("projects");
	}
	
	public function filessexportall(){
		return $this->db->select("project_files.ID, project_files.userid, 
				project_files.folder_flag, project_files.file_name, 
				project_files.extension, project_files.file_size,
				project_files.file_type, project_files.folder_name,
				project_files.upload_file_name,
				project_files.file_url,project_files.user_name,
				project_files.folder_parent,
				project_files.format_used,
				project_files.broadcast_day_st_time,
				project_files.del_comment,
				project_files.timestamp,project_files.del_comment,project_files.status,
				DATE_FORMAT(FROM_UNIXTIME(project_files.timestamp), '%d-%m-%Y %h:%i:%s') as created_date,
				projects.name as project_name,
				task_status.name as file_status,
				task_status.color as file_status_color,
				users.username, projects.team_name, users.avatar, users.online_timestamp")
			->join("projects", "projects.ID = project_files.projectid", "left outer")
			->join("users", "users.ID = project_files.userid")
			->join("task_status", "task_status.ID = project_files.status", "left outer")
			->group_start()
			->where("project_files.folder_parent", 0)
			->where("(projects.status = 0 OR projects.status IS NULL)")
			->group_end()  
			->order_by("projects.name", "ASC")
			->get("project_files"); 
    }
}


?>