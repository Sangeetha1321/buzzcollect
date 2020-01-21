<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage", "team_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
	}

	public function index($projectid = 0) 
	{
		$this->load->model("projects_model");
		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$projectid = intval($projectid);
		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		
		if($this->common->has_permissions(
			array("admin", "project_admin", "team_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID);
		}

		$roles = $this->team_model->get_team_roles();
		$reporters_list = $this->projects_model->get_immediate_reporters();
		$reporters = json_decode(json_encode($reporters_list), true); 
		$teams_by_groupuser = $this->projects_model->get_teams_by_groupuser();
		$this->template->loadContent("team/index.php", array(
			"roles" => $roles,
			"projects" => $projects,
			"teams" => $teams_by_groupuser,
			"projectid" => $projectid,
			"page" => "index",
			"reporting" => $reporters
			)
		);
	}

	public function team_page($page = "index", $projectid = 0) 
	{
		$projectid = intval($projectid);

		$this->load->library("datatables");

		$this->datatables->set_default_order("project.name", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"project_roles.name" => 0
				 ),
				 2 => array(
				 	"projects.name" => 0
				 ),
				 3 => array(
				 	"users.team_name" => 0
				 ),
				 4 => array(
				 	"users.online_timestamp" => 0
				 )
			)
		);

		
		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->team_model
					->get_members_all_user_count($projectid, $this->user->info->ID)
			);

			$members = $this->team_model->get_members_all_user($projectid, 
				$this->user->info->ID, $this->datatables);
		} elseif($page == "all") {
			if(!$this->common->has_permissions(array("admin", "project_admin", 
				"team_manage"), 
				$this->user)) {
				$this->template->error(lang("error_71"));
			}
			$this->datatables->set_total_rows(
				$this->team_model->get_members_all_count($projectid)
			);

			$members = $this->team_model->get_members_all($projectid, $this->datatables);
		}


		foreach($members->result() as $r) {

			$options = '<a id="team_view" href="'.site_url("team/view/" . $r->userid).'" class="btn btn-primary btn-xs">View</a> ';
			if( $this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) {
				$options .= '<a href="'.site_url("team/user_log/" . $r->userid).'" class="btn btn-info btn-xs">'.lang("ctn_918").'</a> ';
			}
			if( $this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $r)) {
				$options .='<a href="'.site_url("team/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>';
			}
			
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->team_role_name,
				$r->project_name,
				$r->team_name,
				$this->common->get_time_string_simple($this->common->convert_simple_time($r->online_timestamp)),
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function all($projectid = 0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$this->load->model("projects_model");
		$this->template->loadData("activeLink", 
			array("team" => array("all" => 1)));

		$projectid = intval($projectid);


		$projects = $this->projects_model
			->get_all_active_projects();

		$roles = $this->team_model->get_team_roles();
		$reporters_list = $this->projects_model->get_immediate_reporters();
		$reporters = json_decode(json_encode($reporters_list), true);
		$teams_by_groupuser = $this->projects_model->get_teams_by_groupuser();
		$this->template->loadContent("team/index.php", array(
			"roles" => $roles,
			"projects" => $projects,
			"teams" => $teams_by_groupuser,
			"projectid" => $projectid,
			"page" => "all",
			"reporting" => $reporters
			)
		);
	}
	
	/* public function get_users_by_team($projectid = 0) 
	{
		$this->load->model("projects_model");
		$projectid = intval($projectid);
		//$teams_by_groupuser = $this->projects_model->get_teams_by_groupuser();
		$this->template->loadContent("team/get_users.php", array(
			"page" => "all"
			)
		);
	} */
	public function ajaxcall() 
	{
		$team_name = $this->input->post('teamname');
		$this->load->model("projects_model");
		$teams_by_groupuser = $this->projects_model->get_user_by_teamname($team_name);
		echo json_encode($teams_by_groupuser);
		/* $shift_users = $this->machine_model->get_user_list_from_team_name($team_name); 
		echo json_encode($shift_users); */
	}

	public function roles() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("roles" => 1)));

		$roles = $this->team_model->get_team_roles();
		$this->template->loadContent("team/roles.php", array(
			"roles" => $roles
			)
		);
	}

	public function add_team_member() 
	{
		$this->load->model("projects_model");
		/* $username = $this->common->nohtml($this->input->post("username"));  */
		$users = $this->input->post("users");
		$projectid = intval($this->input->post("projectid"));
		$roleid = intval($this->input->post("roleid"));
		$reporting_person = $this->common->nohtml($this->input->post("reporting"));
		// First check to see if user has permission to do this
		/* $username = implode(",",$users); */
		$username = "'" . implode("','", $users) . "'";
		$user = $this->user_model->get_user_by_usersname($username);
		/* $user = $this->user_model->get_user_by_username($username); */
		/* echo $username;
		echo "<pre>";
		print_r($user->result()); die; */
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_190"));
		}
		$user = $user->result();

		// Get project
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check role
		$role = $this->team_model->get_role($roleid);
		if($role->num_rows() == 0) {
			$this->template->error(lang("error_191"));
		}
		$role = $role->row();

		// Get user permission
		$team_member = $this->team_model
			->get_member_of_project($this->user->info->ID, $projectid);
		if($team_member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) 
			{
				$this->template->error(lang("error_192"));
			}
		} else {
			// Check permission (team manager[team], admin[team], admin, project_admin)
			$team = $team_member->row();
			if(!$this->common->has_team_permissions(array("admin", "team"), $team)) {
				if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
					$this->template->error(lang("error_193"));
				}
			}
		}
		foreach($user as $k){
		// Check member isn't already a member of this project
		$user_d = $this->team_model->get_member_of_project($k->ID, $projectid);
		if($user_d->num_rows() > 0) {
			$this->template->error(lang("error_194"));
		}

		// Add member
		$this->team_model->add_member(array(
			"userid" => $k->ID,
			"projectid" => $project->ID,
			"roleid" => $role->ID,
			"reporting" => $reporting_person
			)
		);

		// Send notification of being added to the project
		$this->user_model->add_notification(array(
			"userid" => $k->ID,
			"url" => "projects",
			"timestamp" => time(),
			"message" => lang("ctn_1072") . $project->name,
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"email" => $k->email,
			"username" => $k->username,
			"email_notification" => $k->email_notification
			)
		);

		$this->user_model->increment_field($k->ID, "noti_count", 1);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1073") . " <b>".$k->username.
			"</b> " . lang("ctn_1074"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $project->ID,
			"url" => "team"
			)
		);
		}
		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_94"));
		redirect(site_url("team/index/" . $project->ID));

	}

	public function add_role() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$admin = intval($this->input->post("admin"));
		$team = intval($this->input->post("team"));
		$time = intval($this->input->post("time"));
		$file = intval($this->input->post("file"));
		$task = intval($this->input->post("task"));
		$calendar = intval($this->input->post("calendar"));
		$finance = intval($this->input->post("finance"));
		$notes = intval($this->input->post("notes"));
		$reports = intval($this->input->post("reports"));
		$client = intval($this->input->post("client"));
		$doc = intval($this->input->post("doc"));
		$invoice = intval($this->input->post("invoice"));



		if(empty($name)) $this->template->error(lang("error_195"));

		$this->team_model->add_role(array(
			"name" => $name,
			"admin" => $admin,
			"team" => $team,
			"time" => $time,
			"file" => $file,
			"task" => $task,
			"calendar" => $calendar,
			"finance" => $finance,
			"notes" => $notes,
			"reports" => $reports,
			"client" => $client,
			"doc" => $doc,
			"invoice" => $invoice
			)
		);


		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1075") . " <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "team/roles"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_95"));
		redirect(site_url("team/roles"));
	}

	public function edit_role($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));
		$id = intval($id);
		$role = $this->team_model->get_role($id);
		if($role->num_rows() == 0) $this->template->error(lang("error_191"));

		$this->template->loadContent("team/edit_role.php", array(
			"role" => $role->row()
			)
		);
	}

	public function edit_role_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$role = $this->team_model->get_role($id);
		if($role->num_rows() == 0) $this->template->error(lang("error_191"));

		$name = $this->common->nohtml($this->input->post("name"));
		$admin = intval($this->input->post("admin"));
		$team = intval($this->input->post("team"));
		$time = intval($this->input->post("time"));
		$file = intval($this->input->post("file"));
		$task = intval($this->input->post("task"));
		$calendar = intval($this->input->post("calendar"));
		$finance = intval($this->input->post("finance"));
		$notes = intval($this->input->post("notes"));
		$reports = intval($this->input->post("reports"));
		$client = intval($this->input->post("client"));
		$doc = intval($this->input->post("doc"));
		$invoice = intval($this->input->post("invoice"));


		if(empty($name)) $this->template->error(lang("error_195"));

		$this->team_model->update_role($id, array(
			"name" => $name,
			"admin" => $admin,
			"team" => $team,
			"time" => $time,
			"task" => $task,
			"calendar" => $calendar,
			"finance" => $finance,
			"notes" => $notes,
			"reports" => $reports,
			"client" => $client,
			"file" => $file,
			"doc" => $doc,
			"invoice" => $invoice
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1076") . " <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "team/roles"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_96"));
		redirect(site_url("team/roles"));
	}

	public function delete_role($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", 
			"team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$role = $this->team_model->get_role($id);
		if($role->num_rows() == 0) $this->template->error(lang("error_191"));
		$role = $role->row();

		// Check for members
		$members = $this->team_model->get_members_with_role($id);
		if($members->num_rows() > 0) {
			$this->template->error(lang("error_196"));
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1077") . " <b>".$role->name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "team/roles"
			)
		);

		// Delete
		$this->team_model->delete_role($id);
		$this->session->set_flashdata("globalmsg", 
			lang("success_97"));
		redirect(site_url("team/roles"));
	}

	public function edit_member($id) 
	{
		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));
		$this->load->model("projects_model");
		$id = intval($id);
		$team = $this->team_model->get_team_member($id);
		if($team->num_rows() == 0) 
		{
			$this->template->error(lang("error_197"));
		}
		$team = $team->row();

		// Get user permission
		$team_member = $this->team_model
			->get_member_of_project($this->user->info->ID, $team->projectid);
		if($team_member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) 
			{
				$this->template->error(lang("error_192"));
			}
		} else {
			// Check permission (team manager[team], admin[team], admin, project_admin)
			$team_member = $team_member->row();
			if(!$this->common->has_team_permissions(array("admin", "team"), $team_member)) {
				if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
					$this->template->error(lang("error_193"));
				}
			}
		}

		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "team_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			// Can only add projects to projects they are an admin/team
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.team = 1)");
		}

		$roles = $this->team_model->get_team_roles();
		$reporters_list = $this->projects_model->get_immediate_reporters();
		$reporters = json_decode(json_encode($reporters_list), true); 
		$current_reporter = $this->team_model->get_reporting_person($id);
		$current_report = $current_reporter->row()->reporting;
		$this->template->loadContent("team/edit_member.php", array(
			"team" => $team,
			"roles" => $roles,
			"projects" => $projects,
			"reporting" => $reporters,
			"current_reporter" => $current_report
			)
		);
	}

	public function edit_member_pro($id) 
	{
		$this->load->model("projects_model");
		$id = intval($id);
		$team = $this->team_model->get_team_member($id);
		if($team->num_rows() == 0) 
		{
			$this->template->error(lang("error_197"));
		}
		$team = $team->row();

		// Check permission
		// Get user permission
		$team_member = $this->team_model
			->get_member_of_project($this->user->info->ID, $team->projectid);
		if($team_member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) 
			{
				$this->template->error(lang("error_192"));
			}
		} else {
			// Check permission (team manager[team], admin[team], admin, project_admin)
			$team_member = $team_member->row();
			if(!$this->common->has_team_permissions(array("admin", "team"), $team_member)) {
				if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
					$this->template->error(lang("error_193"));
				}
			}
		}

		// Check new data
		$projectid = intval($this->input->post("projectid"));
		$roleid = intval($this->input->post("roleid"));
		$reporting_person = $this->common->nohtml($this->input->post("reporting"));
		// Get project
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check role
		$role = $this->team_model->get_role($roleid);
		if($role->num_rows() == 0) {
			$this->template->error(lang("error_191"));
		}
		$role = $role->row();

		// Get user permission
		$team_member = $this->team_model
			->get_member_of_project($this->user->info->ID, $projectid);
		if($team_member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) 
			{
				$this->template->error(lang("error_192"));
			}
		} else {
			// Check permission (team manager[team], admin[team], admin, project_admin)
			$team_r = $team_member->row();
			if(!$this->common->has_team_permissions(array("admin", "team"), $team_r)) {
				if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
					$this->template->error(lang("error_193"));
				}
			}
		}

		if($projectid != $team->projectid) {
			// Check member isn't already a member of this project
			$user_d = $this->team_model->get_member_of_project($team->userid, $projectid);
			if($user_d->num_rows() > 0) {
				$this->template->error(lang("error_194"));
			}
		}

		// update member
		$this->team_model->update_team_member($id, array(
			"projectid" => $project->ID,
			"roleid" => $role->ID,
			"reporting" => $reporting_person
			)
		);

		if($projectid != $team->projectid) {
			// Send notification of being added to the project
			$this->user_model->add_notification(array(
				"userid" => $team->userid,
				"url" => "projects",
				"timestamp" => time(),
				"message" => lang("ctn_1072") 
					. $project->name . lang("ctn_1078") . $team->name,
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"email" => $team->email,
				"username" => $team->username,
				"email_notification" => $team->email_notification
				)
			);

			$this->user_model->increment_field($team->userid, "noti_count", 1);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1079") . " <b>".$team->username.
			"</b> " . lang("ctn_1080"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $project->ID,
			"url" => "team"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_98"));
		redirect(site_url("team"));
	}

	public function remove_member($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$this->load->model("projects_model");
		$id = intval($id);
		$team = $this->team_model->get_team_member($id);
		if($team->num_rows() == 0) 
		{
			$this->template->error(lang("error_197"));
		}
		$team = $team->row();

		// Check permission
		// Get user permission
		$team_member = $this->team_model
			->get_member_of_project($this->user->info->ID, $team->projectid);
		if($team_member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) 
			{
				$this->template->error(lang("error_198"));
			}
		} else {
			// Check permission (team manager[team], admin[team], admin, project_admin)
			$team_member = $team_member->row();
			if(!$this->common->has_team_permissions(array("admin", "team"), $team_member)) {
				if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
					$this->template->error(lang("error_199"));
				}
			}
		}

		// Remove
		$this->team_model->delete_member($id);

		// Send notification
		$this->user_model->add_notification(array(
			"userid" => $team->userid,
			"url" => "projects",
			"timestamp" => time(),
			"message" => lang("ctn_1081") . $team->name,
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"email" => $team->email,
			"username" => $team->username,
			"email_notification" => $team->email_notification
			)
		);

		$this->user_model->increment_field($team->userid, "noti_count", 1);


		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1082") . " <b>".$team->username.
			"</b> " . lang("ctn_1080"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $team->projectid,
			"url" => "team"
			)
		);


		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_99"));
		redirect(site_url("team"));

	}

	public function user_log($userid) 
	{
		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->common->check_permissions(
			lang("error_200"), 
			array("admin", "project_admin", "team_manage"), // User Roles
			array(), // Team Roles
			0  
		);

		$userid = intval($userid);

		$this->template->loadContent("team/user_log.php", array(
			"userid" => $userid
			)
		);
	}

	public function user_log_page($userid) 
	{
		$userid = intval($userid);

		$this->load->library("datatables");

		$this->datatables->set_default_order("user_action_log.timestamp", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"projects.name" => 0
				 ),
				 4 => array(
				 	"user_action_log.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->team_model->get_total_user_log_count($userid)
		);
		$logs = $this->team_model->get_user_log($userid, $this->datatables);


		foreach($logs->result() as $r) {

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->message,
				$r->name,
				$r->IP,
				date($this->settings->info->date_format, $r->timestamp)
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function clients() 
	{
		$this->template->loadData("activeLink", 
			array("team" => array("clients" => 1)));

		$this->template->loadContent("team/users.php", array(
			"page" => "clients"
			)
		);
	}

	public function users() 
	{
		$this->template->loadData("activeLink", 
			array("team" => array("all_users" => 1)));

		$this->template->loadContent("team/users.php", array(
			"page" => "all"
			)
		);
	}

	public function user_page($page) 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.username", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"user_roles.name" => 0
				 ),
				 2 => array(
				 	"users.online_timestamp" => 0
				 )
			)
		);

		
		if($page == "clients") {
			if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
				// All available clients for admins
				$this->datatables->set_total_rows(
					$this->user_model
						->get_all_clients_count($this->user->info->ID)
				);

				$members = $this->user_model->get_all_clients($this->user->info->ID, $this->datatables);
			} else {
				// Clients only in the user's project list
				$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID);

				$projects_a = array();

				foreach($projects->result() as $r) {
					$projects_a[] = $r->ID;
				}
				$this->datatables->set_total_rows(
					$this->user_model
						->get_all_clients_proj_count($projects_a)
				);

				$members = $this->user_model->get_all_clients_proj($projects_a, $this->datatables);
			}
		} elseif($page == "all") {
			if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
				$this->template->error("Invalid Permission!");
			}

			$this->datatables->set_total_rows(
					$this->user_model
						->get_all_users_count()
				);

				$members = $this->user_model->get_all_users($this->datatables);
		}


		foreach($members->result() as $r) {

			$options = '<a href="'.site_url("team/view/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a> ';
			if( $this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user) || $this->common->has_team_permissions(array("admin", "team"), $r)) {
				$options .='<a href="'.site_url("team/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("team/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="right" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>';
			}

			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($r->ID);

			$project_string = "";
			foreach($projects->result() as $rr) {
				$project_string .= '<a href="'.site_url("projects/view/" . $rr->ID).'"><img src="'.base_url().$this->settings->info->upload_path_relative . '/' . $rr->image .'" class="project-icon-small" data-toggle="tooltip" data-placement="bottom" title="'.$rr->name.'"></a> ';
			}
			
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->role_name,
				$project_string,
				$this->common->get_time_string_simple($this->common->convert_simple_time($r->online_timestamp)),
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function view($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_52"));
		}
		$user = $user->row();

		// Check we have correct permission
		if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
			// Check user is in their team
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID);

			$projects_a = array();

			foreach($projects->result() as $r) {
				$projects_a[] = $r->ID;
			}
			
			$mem = $this->team_model->check_member_of_projects($projects_a, $userid);
			if($mem->num_rows() ==0) {
				$this->template->error(lang("error_286"));
			}
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$user_data = $this->user_model->get_user_data($this->user->info->ID);

		$this->template->loadContent("team/view.php", array(
			"user" => $user,
			"user_data" => $user_data
			)
		);
	}

	public function load_ajax_details($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_52"));
		}
		$user = $user->row();

		// Check we have correct permission
		if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
			$update = false;
			// Check user is in their team
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID);

			$projects_a = array();

			foreach($projects->result() as $r) {
				$projects_a[] = $r->ID;
			}
			
			$mem = $this->team_model->check_member_of_projects($projects_a, $userid);
			if($mem->num_rows() ==0) {
				$this->template->error(lang("error_286"));
			}
		} else {
			$update = true;
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$user_data = $this->user_model->get_user_data($this->user->info->ID);

		$role = $this->user_model->get_user_role($user->user_role);

		$this->template->loadAjax("team/ajax_details.php", array(
			"user" => $user,
			"user_data" => $user_data,
			"update" => $update,
			"role" => $role
			)
		);
	}

	public function update_user($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_52"));
		}
		$user = $user->row();

		// Check we have correct permission
		if(!$this->common->has_permissions(array("admin", "project_admin", "team_manage"), 
			$this->user)) {
			$this->template->error(lang("error_287"));	
		}

		$address_line_1 = $this->common->nohtml($this->input->post("address_line_1"));
		$address_line_2 = $this->common->nohtml($this->input->post("address_line_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$country = $this->common->nohtml($this->input->post("country"));
		$zipcode = $this->common->nohtml($this->input->post("zipcode"));

		$company_name = $this->common->nohtml($this->input->post("company_name"));
		$phone = $this->common->nohtml($this->input->post("phone"));
		$website = $this->common->nohtml($this->input->post("website"));

		$this->user_model->update_user($userid, array(
			"address_1" => $address_line_1,
			"address_2" => $address_line_2,
			"city" => $city,
			"state" => $state,
			"country" => $country,
			"zipcode" => $zipcode
			)
		);

		$user_data = $this->user_model->get_user_data($this->user->info->ID);

		$this->user_model->update_user_data($user_data->ID, array(
			"company_name" => $company_name,
			"phone" => $phone,
			"website" => $website
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_160"));
		redirect(site_url("team/view/" . $userid));

	}

	public function load_ajax_tasks($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_tasks.php", array(
			"user" => $user,
			"projectid" => $user->ID,
			"u_status" => 0,
			"page" => "assigned_user"
			)
		);
	}

	public function load_ajax_invoices($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		$user_roles = array("admin", "project_admin",
			 "invoice_manage", "invoice_client");
		if(!$this->common->has_permissions($user_roles, $this->user)) {
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_invoices.php", array(
			"user" => $user,
			"page" => "client_user"
			)
		);
	}

	public function load_ajax_timers($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "time_manage", "time_worker"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_timers.php", array(
			"user" => $user,
			"page" => "client"
			)
		);
	}

	public function load_ajax_tickets($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "ticket_manage", "ticket_worker"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_tickets.php", array(
			"user" => $user,
			"page" => "client"
			)
		);
	}

	public function load_ajax_logs($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_logs.php", array(
			"user" => $user,
			"page" => "client"
			)
		);
	}

	public function load_ajax_projects($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_projects.php", array(
			"user" => $user,
			"page" => "client"
			)
		);
	}

	public function load_ajax_notes($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage", "notes_manage"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("team" => array("general" => 1)));

		$this->template->loadAjax("team/ajax_notes.php", array(
			"user" => $user,
			"page" => "client"
			)
		);
	}

	public function email_user($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_52"));
		}
		$user = $user->row();

		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage"), 
			$this->user)) 
		{
			$this->template->errori(lang("error_2"));
		}

		$email_subject = $this->common->nohtml($this->input->post("subject"));
		$body = $this->common->nohtml($this->input->post("email"));

		if(empty($email_subject)) {
			$this->template->error(lang("error_288"));
		}
		if(empty($body)) {
			$this->template->error(lang("error_18"));
		}

		// Email
		$this->common->send_email($email_subject,
			 $body, $user->email);

		$this->session->set_flashdata("globalmsg", lang("success_161"));
		redirect(site_url("team/view/" . $user->ID));
	}

}

?>