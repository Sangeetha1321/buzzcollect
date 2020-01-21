<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("task_model");
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
		 "task_manage", "task_worker", "task_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
	}

	public function index($projectid = 0, $status=0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("task" => array("general" => 1)));

		$projectid = intval($projectid);
		$status = intval($status);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$project_list = $this->task_model->get_projectname();
		if(isset($_POST["project_lr"])){
			$project_lr = $_POST["project_lr"];
		}
		else{
			$project_lr = "";  
		}
		$this->load->library('session');
		$this->session->set_userdata('pro_ls', $project_lr);
		
		
		if(isset($_POST["filter_start_date"])){
			$filter_start_date = $_POST["filter_start_date"];
		}
		else{
			$filter_start_date = '';
		}
		if(isset($_POST["filter_end_date"])){
			$filter_end_date = $_POST["filter_end_date"];
		}
		else{
			/* $filter_end_date = date("Y-m-d"); */
			$filter_end_date = '';
		}
		$this->session->set_userdata('filter_start', $filter_start_date);
		$this->session->set_userdata('filter_end', $filter_end_date);
		
		$this->template->loadContent("tasks/index.php", array(
			"u_status" => $status,
			"projectid" => $projectid,
			"projects" => $projects,
			"project_list" => $project_list,
			"project_lr" => $project_lr,
			"filter_start_date" => $filter_start_date,
			"filter_end_date" => $filter_end_date,
			"page" => "index"
			)
		);
	}

	public function tasks_page($page = "index", $projectid=0, $u_status =0) 
	{
		$projectid = intval($projectid);
		$u_status = intval($u_status);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("project_tasks.due_date", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"project_tasks.jobid" => 0
				 ),
				 1 => array(
				 	"project_tasks.name" => 0
				 ),
				 2 => array(
				 	"project_tasks.status" => 0
				 ),
				 3 => array(
				 	"projects.name" => 0
				 ),
				 4 => array(
				 	"project_tasks.complete" => 0
				 ),
				 5 => array(
				 	"project_tasks.start_date" => 0
				 ),
				 6 => array(
				 	"project_tasks.due_date" => 0
				 ),
				 7 => array(
				 	"users.username" => 0
				 ),
				 8 => array(
				 	"users.team_name" => 0
				 )
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->task_model
					->get_project_tasks_total($projectid, $u_status, $this->user->info->ID)
			);

			$tasks = $this->task_model->get_project_tasks($projectid, $u_status,
				$this->user->info->ID, $this->datatables);
			
		} elseif($page == "assigned") {
			$this->datatables->set_total_rows(
				$this->task_model
				->get_user_assigned_tasks_total($projectid, $u_status, $this->user->info->ID)
			);
			$tasks = $this->task_model->get_user_assigned_tasks($projectid, $u_status,
				$this->user->info->ID, $this->datatables);
		} elseif($page == "assigned_user") {
			// Projectid in this case equals userid
			$this->datatables->set_total_rows(
				$this->task_model
				->get_user_assigned_tasks_total(0, $u_status, $projectid)
			);

			$tasks = $this->task_model->get_user_assigned_tasks(0, $u_status,
				$projectid, $this->datatables);
		} elseif($page == "all") {
			$this->common->check_permissions(
				lang("error_162"), 
				array("admin", "project_admin", "task_manage"), // User Roles
				array(),
				0  // Team Roles
			);
			$this->datatables->set_total_rows(
				$this->task_model
				->get_all_tasks_total($projectid, $u_status)
			);

			$tasks = $this->task_model->get_all_tasks($projectid, $u_status, $this->datatables);
		} elseif($page == "archived") {
			$this->common->check_permissions(
				lang("error_162"), 
				array("admin", "project_admin", "task_manage"), // User Roles
				array(),
				0  // Team Roles
			);
			$this->datatables->set_total_rows(
				$this->task_model
				->get_all_tasks_total($projectid, $u_status, 1)
			);

			$tasks = $this->task_model->get_all_tasks($projectid, $u_status, $this->datatables, 1);
		} elseif($page == "client") {
			$this->datatables->set_total_rows(
				$this->task_model
					->get_project_tasks_total($projectid, $u_status, $this->user->info->ID)
			);

			$tasks = $this->task_model->get_project_tasks($projectid, $u_status,
				$this->user->info->ID, $this->datatables);
		}			
		foreach($tasks->result() as $r) {
			if($r->status == 1) {
				$status = "<label class='label label-info'>".lang("ctn_830")."</label>";
			} elseif($r->status == 2) {
				$status = "<label class='label label-primary'>".lang("ctn_831")."</label>";
			} elseif($r->status == 3) {
				$status = "<label class='label label-success'>".lang("ctn_832")."</label>";
			} elseif($r->status == 4) {
				$status = "<label class='label label-warning'>".lang("ctn_833")."</label>";
			} elseif($r->status == 5) {
				$status = "<label class='label label-danger'>".lang("ctn_834")."</label>";
			}

			if($page == "client") {
				$options = '<a href="'.site_url("tasks/view/" . $r->ID) .'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a>';
			} else {
				$options = '<a href="'.site_url("tasks/view/" . $r->ID) .'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a href="'.site_url("tasks/edit_task/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tasks/delete_task/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			}
			
			/* $teams_users = $this->task_model->get_teams_and_users($r->ID);
			$member_string = '';
			$teams_users_i = 0;
			$len = count($teams_users->result());
			$userid = [];
			foreach($teams_users->result() as $tu) {
				$userid[] = $tu->userid; 
				$member_string .= '<div class="user-box-name" style="width: 100%;"><p class="user-box-username">
				<a href="'.site_url("profile/" . $tu->username).'">'.ucfirst($tu->username).'</a>';
				if ($teams_users_i != $len - 1) {$member_string .= ',';}
				$member_string .= '</p></div>';
				$teams_users_i++;
			}  
			$team_members_by_userid = $this->team_model->get_team_members_by_userid($userid);
			$team_name = [];
			foreach($team_members_by_userid->result() as $team_members) {
    			$team_name[] .= $team_members->team_name;
			} */
			if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)){            	
				$this->datatables->data[] = array(
					'<a href="'.site_url("tasks/view/" . $r->ID) .'">'.$r->jobid.'</a>',
					'<a href="'.site_url("tasks/view/" . $r->ID) .'">'.$r->name.'</a>',
					$status,
					'<a href="'.site_url("tasks/".$page."/" . $r->projectid . "/" . $u_status).'">'.$r->project_name.'</a>',
					'<div class="progress" style="height: 15px;">
						  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$r->complete.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$r->complete.'%" title="'.$r->complete .'%" data-toggle="tooltip" data-placement="bottom">
							<span class="sr-only">'.$r->complete.'% '.lang("ctn_790").'</span>
						  </div>
					</div>',				
					$r->start_date_formatted,
					$r->due_date_formatted,
					implode(',', array_unique(explode(', ', $r->team_name))),
					$r->username,
					$options
				);
			}
			else{
				$this->datatables->data[] = array(
					$r->jobid,
					'<a href="'.site_url("tasks/view/" . $r->ID) .'">'.$r->name.'</a>',
					$status,
					$r->project_name,
					'<div class="progress" style="height: 15px;">
						  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$r->complete.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$r->complete.'%" title="'.$r->complete .'%" data-toggle="tooltip" data-placement="bottom">
							<span class="sr-only">'.$r->complete.'% '.lang("ctn_790").'</span>
						  </div>
					</div>',				
					$r->start_date_formatted,
					$r->due_date_formatted,
					implode(',', array_unique(explode(', ', $r->team_name))),
					$r->username,
					$options
				);
			
			}
		}

		echo json_encode($this->datatables->process());

	}

	public function assigned($projectid =0, $status=0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("task" => array("your" => 1)));

		$status = intval($status);
		$projectid = intval($projectid);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model->get_projects_user_all_no_pagination($this->user->info->ID, "(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$project_list = $this->task_model->get_projectname();
		if(isset($_POST["project_lr"])){
			$project_lr = $_POST["project_lr"];
		}
		else{
			$project_lr = "";  
		}
		$this->load->library('session');
		$this->session->set_userdata('pro_ls', $project_lr);
		
		$this->template->loadContent("tasks/index.php", array(
			"u_status" => $status,
			"projectid" => $projectid,
			"projects" => $projects,
			"project_list" => $project_list,
			"project_lr" => $project_lr,
			"page" => "assigned"
			)
		);
	}

	public function archived($projectid=0, $status=0, $page =0) 
	{
		$this->template->loadData("activeLink", 
			array("task" => array("archived" => 1)));

		$this->common->check_permissions(
			lang("error_162"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array(),
			0  // Team Roles
		);

		$page = intval($page);
		$status = intval($status);
		$projectid = intval($projectid);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$project_list = $this->task_model->get_projectname();
		if(isset($_POST["project_lr"])){
			$project_lr = $_POST["project_lr"];
		}
		else{
			$project_lr = "";  
		}
		$this->load->library('session');
		$this->session->set_userdata('pro_ls', $project_lr);
		
		$this->template->loadContent("tasks/index.php", array(
			"projectid" => $projectid,
			"projects" => $projects,
			"project_list" => $project_list,
			"project_lr" => $project_lr,
			"u_status" => $status,
			"page" => "archived"
			)
		);
	}

	public function all($projectid=0, $status=0, $page =0) 
	{
		$this->template->loadData("activeLink", 
			array("task" => array("all" => 1)));

		$this->common->check_permissions(
			lang("error_162"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array(),
			0  // Team Roles
		);

		$page = intval($page);
		$status = intval($status);
		$projectid = intval($projectid);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$project_list = $this->task_model->get_projectname();
		if(isset($_POST["project_lr"])){
			$project_lr = $_POST["project_lr"];
		}
		else{
			$project_lr = "";  
		}
		$this->load->library('session');
		$this->session->set_userdata('pro_ls', $project_lr);
		
		$this->template->loadContent("tasks/index.php", array(
			"projectid" => $projectid,
			"projects" => $projects,
			"project_list" => $project_list,
			"project_lr" => $project_lr,
			"u_status" => $status,
			"page" => "all"
			)
		);
	}

	public function client($projectid=0, $status=0) 
	{
		$this->template->loadData("activeLink", 
			array("task" => array("client" => 1)));

		$this->common->check_permissions(
			lang("error_162"), 
			array("admin", "project_admin", "task_client"), // User Roles
			array("client"),
			$projectid
		);

		$status = intval($status);
		$projectid = intval($projectid);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"pr2.client = 1");
		}
		
		$this->template->loadContent("tasks/index.php", array(
			"projectid" => $projectid,
			"projects" => $projects,
			"u_status" => $status,
			"page" => "client"
			)
		);
	}

	public function get_team_members($projectid) 
	{
		$projectid = intval($projectid);
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$this->common->check_permissions(
			lang("error_165"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$projectid
		);

		$team = $this->team_model->get_members_for_project($projectid);

		$this->template->loadAjax("tasks/ajax_team.php", array(
			"team" => $team
			), 1
		);
	}

	public function add() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("task" => array("general" => 1)));


		// If user is Admin, Project-Admin or File manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$pro_unit = $this->projects_model->get_pro_unit();
		$team_list = $this->projects_model->get_team_name();
		$this->template->loadContent("tasks/add.php", array(
			"projects" => $projects,
			"team_list" => $team_list,
			"pro_unit" => $pro_unit
			)
		);
	}

	public function add_task_process() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$last_taskid = $this->projects_model->get_last_taskid();
		
		foreach($last_taskid->result() as $last_val){
			$last_tskid = ($last_val->ID)+1;
		}
		
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$input = $this->common->nohtml($this->input->post("input"));
		$output = $this->common->nohtml($this->input->post("output"));
		$complexity = $this->common->nohtml($this->input->post("complexity"));
		$unit = $this->common->nohtml($this->input->post("unit"));
		$geofacets_unit = $this->common->nohtml($this->input->post("geofacets_unit"));
		$geofacets_vendor_unit = $this->common->nohtml($this->input->post("geofacets_vendor_unit"));
		$geofacets_parallel_process_unit = $this->common->nohtml($this->input->post("geofacets_parallel_process_unit"));
		$isbn = $this->common->nohtml($this->input->post("isbn"));
		$eisbn = $this->common->nohtml($this->input->post("eisbn"));
		$volume_issue = $this->common->nohtml($this->input->post("volume_issue"));
		
		$unitPricePerPage = round($this->input->post("unitPricePerPage"),2);
		$unitPricePerImage = round($this->input->post("unitPricePerImage"),2);
		$unitPricePerArticle = round($this->input->post("unitPricePerArticle"),2);
		$unitPricePerTableEditable = round($this->input->post("unitPricePerTableEditable"),2);
		$unitPricePerTableScanned = round($this->input->post("unitPricePerTableScanned"),2);
		$unitPricePerFigureEditable = round($this->input->post("unitPricePerFigureEditable"),2);
		$unitPricePerFigureScanned = round($this->input->post("unitPricePerFigureScanned"),2);
		$totalPages = intval($this->input->post("totalPages"));
		$totalImages = intval($this->input->post("totalImages"));
		$totalArticles = intval($this->input->post("totalArticles"));
		$totalTablesEditable = intval($this->input->post("totalTablesEditable"));
		$totalTablesScanned = intval($this->input->post("totalTablesScanned"));
		$totalFiguresEditable = intval($this->input->post("totalFiguresEditable"));
		$totalFiguresScanned = intval($this->input->post("totalFiguresScanned"));
		$totalPagesPrice = round($this->input->post("totalPagesPrice"),2);
		$totalImagesPrice = round($this->input->post("totalImagesPrice"),2);
		$totalArticlesPrice = round($this->input->post("totalArticlesPrice"),2);
		$totalTablesPriceEditable = round($this->input->post("totalTablesPriceEditable"),2);
		$totalTablesPriceScanned = round($this->input->post("totalTablesPriceScanned"),2);
		$totalFiguresPriceEditable = round($this->input->post("totalFiguresPriceEditable"),2);
		$totalFiguresPriceScanned = round($this->input->post("totalFiguresPriceScanned"),2);
		$projectid = intval($this->input->post("projectid"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$due_date = $this->common->nohtml($this->input->post("due_date"));
		$status = intval($this->input->post("status"));
		$assign = intval($this->input->post("assign"));
		$template_option = intval($this->input->post("template_option"));
		$template_start_days = intval($this->input->post("template_start_days"));
		$template_due_days = intval($this->input->post("template_due_days"));
		$calendar_event = intval($this->input->post("calendar_event"));
		$project_name = $this->common->nohtml($this->input->post("project_name"));
		$jobid = strtolower(substr($project_name, 0, 3)).$last_tskid;
		
		$vendor_assignment = intval($this->input->post("vendor_assignment"));
		if($vendor_assignment == 1) {
			$vendor_name = $this->common->nohtml($this->input->post("vendor_name"));
			$vendor_process_name = $this->common->nohtml($this->input->post("vendor_process_name"));
			$vendor_unit = $this->common->nohtml($this->input->post("vendor_unit"));
			$vendor_unitPricePerPage = round($this->input->post("vendor_unitPricePerPage"),2);
			$vendor_unitPricePerImage = round($this->input->post("vendor_unitPricePerImage"),2);
			$vendor_unitPricePerArticle = round($this->input->post("vendor_unitPricePerArticle"),2);
			$vendor_unitPricePerTableEditable = round($this->input->post("vendor_unitPricePerTableEditable"),2);
			$vendor_unitPricePerTableScanned = round($this->input->post("vendor_unitPricePerTableScanned"),2);
			$vendor_unitPricePerFigureEditable = round($this->input->post("vendor_unitPricePerFigureEditable"),2);
			$vendor_unitPricePerFigureScanned = round($this->input->post("vendor_unitPricePerFigureScanned"),2);
			$vendor_totalPages = intval($this->input->post("vendor_totalPages"));
			$vendor_totalImages = intval($this->input->post("vendor_totalImages"));
			$vendor_totalArticles = intval($this->input->post("vendor_totalArticles"));
			$vendor_totalTablesEditable = intval($this->input->post("vendor_totalTablesEditable"));
			$vendor_totalTablesScanned = intval($this->input->post("vendor_totalTablesScanned"));
			$vendor_totalFiguresEditable = intval($this->input->post("vendor_totalFiguresEditable"));
			$vendor_totalFiguresScanned = intval($this->input->post("vendor_totalFiguresScanned"));
			$vendor_totalPagesPrice = round($this->input->post("vendor_totalPagesPrice"),2);
			$vendor_totalImagesPrice = round($this->input->post("vendor_totalImagesPrice"),2);
			$vendor_totalArticlesPrice = round($this->input->post("vendor_totalArticlesPrice"),2);
			$vendor_totalTablesPriceEditable = round($this->input->post("vendor_totalTablesPriceEditable"),2);
			$vendor_totalTablesPriceScanned = round($this->input->post("vendor_totalTablesPriceScanned"),2);
			$vendor_totalFiguresPriceEditable = round($this->input->post("vendor_totalFiguresPriceEditable"),2);
			$vendor_totalFiguresPriceScanned = round($this->input->post("vendor_totalFiguresPriceScanned"),2);
			$vendor_start_date = $this->common->nohtml($this->input->post("vendor_start_date"));
			$vendor_due_date = $this->common->nohtml($this->input->post("vendor_due_date"));
			$vendor_status = intval($this->input->post("vendor_status"));
		} else {
			$vendor_name = NULL;
			$vendor_process_name = NULL;
			$vendor_unit = NULL;
			$vendor_unitPricePerPage = '0.00';
			$vendor_unitPricePerImage = '0.00';
			$vendor_unitPricePerArticle = '0.00';
			$vendor_unitPricePerTableEditable = '0.00';
			$vendor_unitPricePerTableScanned = '0.00';
			$vendor_unitPricePerFigureEditable = '0.00';
			$vendor_unitPricePerFigureScanned = '0.00';
			$vendor_totalPages = 0;
			$vendor_totalImages = 0;
			$vendor_totalArticles = 0;
			$vendor_totalTablesEditable = 0;
			$vendor_totalTablesScanned = 0;
			$vendor_totalFiguresEditable = 0;
			$vendor_totalFiguresScanned = 0;
			$vendor_totalPagesPrice = '0.00';
			$vendor_totalImagesPrice = '0.00';
			$vendor_totalArticlesPrice = '0.00';
			$vendor_totalTablesPriceEditable = '0.00';
			$vendor_totalTablesPriceScanned = '0.00';
			$vendor_totalFiguresPriceEditable = '0.00';
			$vendor_totalFiguresPriceScanned = '0.00';
			$vendor_start_date = 0;
			$vendor_due_date = 0;
			$vendor_status = 0;		
		}
		
		$parallel = intval($this->input->post("parallel"));
		if($parallel == 1) {
			$parallel_process_name = $this->common->nohtml($this->input->post("parallel_process_name"));
			$parallel_process_team = $this->common->nohtml($this->input->post("parallel_process_team"));
			$cost_applicable = $this->common->nohtml($this->input->post("cost_applicable"));
			if($cost_applicable == 1) {
				$parallel_process_unit = $this->common->nohtml($this->input->post("parallel_process_unit"));
				$parallel_process_unitPricePerPage = round($this->input->post("parallel_process_unitPricePerPage"),2);
				$parallel_process_unitPricePerImage = round($this->input->post("parallel_process_unitPricePerImage"),2);
				$parallel_process_unitPricePerArticle = round($this->input->post("parallel_process_unitPricePerArticle"),2);
				$parallel_process_unitPricePerTableEditable = round($this->input->post("parallel_process_unitPricePerTableEditable"),2);
				$parallel_process_unitPricePerTableScanned = round($this->input->post("parallel_process_unitPricePerTableScanned"),2);
				$parallel_process_unitPricePerFigureEditable = round($this->input->post("parallel_process_unitPricePerFigureEditable"),2);
				$parallel_process_unitPricePerFigureScanned = round($this->input->post("parallel_process_unitPricePerFigureScanned"),2);
				$parallel_process_totalPages = intval($this->input->post("parallel_process_totalPages"));
				$parallel_process_totalImages = intval($this->input->post("parallel_process_totalImages"));
				$parallel_process_totalArticles = intval($this->input->post("parallel_process_totalArticles"));
				$parallel_process_totalTablesEditable = intval($this->input->post("parallel_process_totalTablesEditable"));
				$parallel_process_totalTablesScanned = intval($this->input->post("parallel_process_totalTablesScanned"));
				$parallel_process_totalFiguresEditable = intval($this->input->post("parallel_process_totalFiguresEditable"));
				$parallel_process_totalFiguresScanned = intval($this->input->post("parallel_process_totalFiguresScanned"));
				$parallel_process_totalPagesPrice = round($this->input->post("parallel_process_totalPagesPrice"),2);
				$parallel_process_totalImagesPrice = round($this->input->post("parallel_process_totalImagesPrice"),2);
				$parallel_process_totalArticlesPrice = round($this->input->post("parallel_process_totalArticlesPrice"),2);
				$parallel_process_totalTablesPriceEditable = round($this->input->post("parallel_process_totalTablesPriceEditable"),2);
				$parallel_process_totalTablesPriceScanned = round($this->input->post("parallel_process_totalTablesPriceScanned"),2);
				$parallel_process_totalFiguresPriceEditable = round($this->input->post("parallel_process_totalFiguresPriceEditable"),2);
				$parallel_process_totalFiguresPriceScanned = round($this->input->post("parallel_process_totalFiguresPriceScanned"),2);
			}
			else{
				$parallel_process_unit = NULL;
				$parallel_process_unitPricePerPage = '0.00';
				$parallel_process_unitPricePerImage = '0.00';
				$parallel_process_unitPricePerArticle = '0.00';
				$parallel_process_unitPricePerTableEditable = '0.00';
				$parallel_process_unitPricePerTableScanned = '0.00';
				$parallel_process_unitPricePerFigureEditable = '0.00';
				$parallel_process_unitPricePerFigureScanned = '0.00';
				$parallel_process_totalPages = 0;
				$parallel_process_totalImages = 0;
				$parallel_process_totalArticles = 0;
				$parallel_process_totalTablesEditable = 0;
				$parallel_process_totalTablesScanned = 0;
				$parallel_process_totalFiguresEditable = 0;
				$parallel_process_totalFiguresScanned = 0;
				$parallel_process_totalPagesPrice = '0.00';
				$parallel_process_totalImagesPrice = '0.00';
				$parallel_process_totalArticlesPrice = '0.00';
				$parallel_process_totalTablesPriceEditable = '0.00';
				$parallel_process_totalTablesPriceScanned = '0.00';
				$parallel_process_totalFiguresPriceEditable = '0.00';
				$parallel_process_totalFiguresPriceScanned = '0.00';
			}
		} else {
			$parallel_process_name = NULL;
			$parallel_process_team = NULL;
			$parallel_process_unit = NULL;
			$parallel_process_unitPricePerPage = '0.00';
			$parallel_process_unitPricePerImage = '0.00';
			$parallel_process_unitPricePerArticle = '0.00';
			$parallel_process_unitPricePerTableEditable = '0.00';
			$parallel_process_unitPricePerTableScanned = '0.00';
			$parallel_process_unitPricePerFigureEditable = '0.00';
			$parallel_process_unitPricePerFigureScanned = '0.00';
			$parallel_process_totalPages = 0;
			$parallel_process_totalImages = 0;
			$parallel_process_totalArticles = 0;
			$parallel_process_totalTablesEditable = 0;
			$parallel_process_totalTablesScanned = 0;
			$parallel_process_totalFiguresEditable = 0;
			$parallel_process_totalFiguresScanned = 0;
			$parallel_process_totalPagesPrice = '0.00';
			$parallel_process_totalImagesPrice = '0.00';
			$parallel_process_totalArticlesPrice = '0.00';
			$parallel_process_totalTablesPriceEditable = '0.00';
			$parallel_process_totalTablesPriceScanned = '0.00';
			$parallel_process_totalFiguresPriceEditable = '0.00';
			$parallel_process_totalFiguresPriceScanned = '0.00';	
		}
		/*  echo $project_name.'<br>';
		echo $last_tskid.'<br>';
		echo $jobid.'<br>';
		exit;  */
		if(empty($name)) {
			$this->template->error(lang("error_163"));
		}
		if(empty($start_date)) {
			$this->template->error(lang("error_312"));
		}
		if(empty($due_date)) {
			$this->template->error(lang("error_313"));
		}

		if($status < 1 || $status > 5) {
			$this->template->error(lang("error_164"));
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("error_165"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$projectid
		);

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}

		if(!empty($due_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $due_date);
			$dd_timestamp = $dd->getTimestamp();
			$dd_calendar = $dd->format('Y-m-d H:i:s');
			$google_end_date = $dd->format('Y-m-d\TH:i:s');
		} else {
			$dd_timestamp = 0;
		}
		
		if(!empty($vendor_start_date)) {
			$sd_ven = DateTime::createFromFormat($this->settings->info->date_picker_format, $vendor_start_date);
			$vendor_sd_timestamp = $sd_ven->getTimestamp();
		} else {
			$vendor_sd_timestamp = time();
		}

		if(!empty($vendor_due_date)) {
			$dd_ven = DateTime::createFromFormat($this->settings->info->date_picker_format, $vendor_due_date);
			$vendor_dd_timestamp = $dd_ven->getTimestamp();/* 
			$dd_calendar = $dd_ven->format('Y-m-d H:i:s');
			$google_end_date = $dd_ven->format('Y-m-d\TH:i:s'); */
		} else {
			$vendor_dd_timestamp = 0;
		}

		$users_toadd = $this->input->post("users");
		$users= array();
		if($users_toadd) {
			foreach($users_toadd as $uid) {
				$uid = intval($uid);
				if($uid > 0) {
					$user = $this->team_model->get_member_of_project($uid, $projectid);
					if($user->num_rows() == 0) {
						$this->template->error(lang("error_171"));
					}
				}
				$users[] = $uid;
			}
		}

		$template_projectid = 0;
		$template = 0;
		if($template_option > 0) {
			$template = 1;
		}
		if($template_option == 1) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage"), 
			$this->user)) {
				$this->template->error(lang("error_71"));
			}
		}
		if($template_option == 2) {
			$template_projectid = $projectid;
		}

		$taskid = $this->task_model->add_task(array(
			"name" => $name,
			"description" => $desc,
			"jobid" => $jobid,
			"input" => $input,
			"output" => $output,
			"complexity" => $complexity,
			"unit" => $unit,
			"geofacets_unit" => $geofacets_unit,
			"geofacets_vendor_unit" => $geofacets_vendor_unit,
			"geofacets_parallel_process_unit" => $geofacets_parallel_process_unit,
			"isbn" => $isbn,
			"eisbn" => $eisbn,
			"volume_issue" => $volume_issue,
			"unitPricePerPage" => $unitPricePerPage,
			"unitPricePerImage" => $unitPricePerImage,
			"unitPricePerArticle" => $unitPricePerArticle,
			"unitPricePerTableEditable" => $unitPricePerTableEditable,
			"unitPricePerTableScanned" => $unitPricePerTableScanned,
			"unitPricePerFigureEditable" => $unitPricePerFigureEditable,
			"unitPricePerFigureScanned" => $unitPricePerFigureScanned,
			"totalPages" => $totalPages,
			"totalImages" => $totalImages,
			"totalArticles" => $totalArticles,
			"totalTablesEditable" => $totalTablesEditable,
			"totalTablesScanned" => $totalTablesScanned,
			"totalFiguresEditable" => $totalFiguresEditable,
			"totalFiguresScanned" => $totalFiguresScanned,
			"totalPagesPrice" => $totalPagesPrice,
			"totalImagesPrice" => $totalImagesPrice,
			"totalArticlesPrice" => $totalArticlesPrice,
			"totalTablesPriceEditable" => $totalTablesPriceEditable,
			"totalTablesPriceScanned" => $totalTablesPriceScanned,
			"totalFiguresPriceEditable" => $totalFiguresPriceEditable,
			"totalFiguresPriceScanned" => $totalFiguresPriceScanned,
			"projectid" => $projectid,
			"start_date" => $sd_timestamp,
			"due_date" => $dd_timestamp,
			"status" => $status,
			"userid" => $this->user->info->ID,
			"template" => $template,
			"template_projectid" => $template_projectid,
			"template_start_days" => $template_start_days,
			"template_due_days" => $template_due_days,
			"vendor_assignment" => $vendor_assignment,
			"vendor_name" => $vendor_name,
			"vendor_process_name" => $vendor_process_name,
			"vendor_unit" => $vendor_unit,
			"vendor_unitPricePerPage" => $vendor_unitPricePerPage,
			"vendor_unitPricePerImage" => $vendor_unitPricePerImage,
			"vendor_unitPricePerArticle" => $vendor_unitPricePerArticle,
			"vendor_totalPages" => $vendor_totalPages,
			"vendor_totalImages" => $vendor_totalImages,
			"vendor_totalArticles" => $vendor_totalArticles,
			"vendor_totalPagesPrice" => $vendor_totalPagesPrice,
			"vendor_totalImagesPrice" => $vendor_totalImagesPrice,
			"vendor_totalArticlesPrice" => $vendor_totalArticlesPrice,
			"vendor_unitPricePerTableEditable" => $vendor_unitPricePerTableEditable,
			"vendor_unitPricePerTableScanned" => $vendor_unitPricePerTableScanned,
			"vendor_unitPricePerFigureEditable" => $vendor_unitPricePerFigureEditable,
			"vendor_unitPricePerFigureScanned" => $vendor_unitPricePerFigureScanned,
			"vendor_totalTablesEditable" => $vendor_totalTablesEditable,
			"vendor_totalTablesScanned" => $vendor_totalTablesScanned,
			"vendor_totalFiguresEditable" => $vendor_totalFiguresEditable,
			"vendor_totalFiguresScanned" => $vendor_totalFiguresScanned,
			"vendor_totalTablesPriceEditable" => $vendor_totalTablesPriceEditable,
			"vendor_totalTablesPriceScanned" => $vendor_totalTablesPriceScanned,
			"vendor_totalFiguresPriceEditable" => $vendor_totalFiguresPriceEditable,
			"vendor_totalFiguresPriceScanned" => $vendor_totalFiguresPriceScanned,
			"vendor_start_date" => $vendor_sd_timestamp,
			"vendor_due_date" => $vendor_dd_timestamp,
			"vendor_status" => $vendor_status,
			"parallel" => $parallel,
			"cost_applicable" => $cost_applicable,
			"parallel_process_name" => $parallel_process_name,
			"parallel_process_team" => $parallel_process_team,
			"parallel_process_unit" => $parallel_process_unit,
			"parallel_process_unitPricePerPage" => $parallel_process_unitPricePerPage,
			"parallel_process_unitPricePerImage" => $parallel_process_unitPricePerImage,
			"parallel_process_unitPricePerArticle" => $parallel_process_unitPricePerArticle,
			"parallel_process_totalPages" => $parallel_process_totalPages,
			"parallel_process_totalImages" => $parallel_process_totalImages,
			"parallel_process_totalArticles" => $parallel_process_totalArticles,
			"parallel_process_totalPagesPrice" => $parallel_process_totalPagesPrice,
			"parallel_process_totalImagesPrice" => $parallel_process_totalImagesPrice,
			"parallel_process_totalArticlesPrice" => $parallel_process_totalArticlesPrice,
			"parallel_process_unitPricePerTableEditable" => $parallel_process_unitPricePerTableEditable,
			"parallel_process_unitPricePerTableScanned" => $parallel_process_unitPricePerTableScanned,
			"parallel_process_unitPricePerFigureEditable" => $parallel_process_unitPricePerFigureEditable,
			"parallel_process_unitPricePerFigureScanned" => $parallel_process_unitPricePerFigureScanned,
			"parallel_process_totalTablesEditable" => $parallel_process_totalTablesEditable,
			"parallel_process_totalTablesScanned" => $parallel_process_totalTablesScanned,
			"parallel_process_totalFiguresEditable" => $parallel_process_totalFiguresEditable,
			"parallel_process_totalFiguresScanned" => $parallel_process_totalFiguresScanned,
			"parallel_process_totalTablesPriceEditable" => $parallel_process_totalTablesPriceEditable,
			"parallel_process_totalTablesPriceScanned" => $parallel_process_totalTablesPriceScanned,
			"parallel_process_totalFiguresPriceEditable" => $parallel_process_totalFiguresPriceEditable,
			"parallel_process_totalFiguresPriceScanned" => $parallel_process_totalFiguresPriceScanned
			)
		);

		if($assign) {
			// Add member
			$this->task_model->add_task_member(array(
				"taskid" => $taskid,
				"userid" => $this->user->info->ID
				)
			);
		}

		foreach($users as $user) {
			if($user == $this->user->info->ID && $assign) continue;
			$this->task_model->add_task_member(array(
				"taskid" => $taskid,
				"userid" => $user
				)
			);
		}

		if($template == 0 && $calendar_event && $dd_timestamp > 0) {
			// Create Calendar Event
			$this->load->model("calendar_model");

			if($this->settings->info->calendar_type == 1) {
				// Google Calendar
				$client = $this->authorise_google_api("calendar");

				if($client) {
					$calendarId = $this->settings->info->google_calendar_id;
					if(!empty($project->calendar_id)) {
						$calendarId = $project->calendar_id;
					}

					
					if(!empty($calendarId)) {
						
						$timezone = $this->settings->info->calendar_timezone;
						try {
							$cal = new Google_Service_Calendar($client);
							$calendarListEntry = $cal->calendarList->get($calendarId);
						} catch(Exception $e) {
							$this->template->error(lang("error_82") . "<br /><br />" 
								. $e->getMessage());
						}

						if(!$calendarListEntry->getSummary()) {
							$this->template->error(lang("error_83"));
						}

						/* insert into google calendar */
						$event = new Google_Service_Calendar_Event(array(
						  'summary' => $name,
						  'location' => '',
						  'description' => '<a href="'.site_url("tasks/view/" . $taskid).'">'.$name.'</a>',
						  'start' => array(
						    'dateTime' => $google_end_date,
						    'timeZone' => $timezone,
						  ),
						  'end' => array(
						    'dateTime' => $google_end_date,
						    'timeZone' => $timezone,
						  )
						));
						try {
							$event = $cal->events->insert($calendarId, $event);
						} catch(Exception $e) {
							$this->template->error(lang("error_82") . "<br /><br />" .
								$e->getMessage());
						}

					}
				}
			} else {
				// Site calendar
				$this->calendar_model->add_event(array(
					"title" => $name,
					"description" => strip_tags($desc),
					"start" => $dd_calendar,
					"end" => $dd_calendar,
					"userid" => $this->user->info->ID,
					"projectid" => $projectid,
					"taskid" => $taskid
					)
				);
			}
		}

		if($template == 0) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1056"). $name,
				$this->user->info->ID
			);

			if($project->complete_sync) {
				// Get all tasks
				$tasks = $this->task_model->get_all_project_tasks($project->ID);
				$total = $tasks->num_rows() * 100;
				$complete = 0;
				foreach($tasks->result() as $r) {
					$complete += $r->complete;
				}

				$complete = @intval(($complete/$total) * 100);
				$this->projects_model->update_project($project->ID, array(
					"complete" => $complete
					)
				);
			}

			// Log
			$this->user_model->add_user_log(array(
				"userid" => $this->user->info->ID,
				"message" => lang("ctn_1050") . $name . lang("ctn_1051") . $project->name,
				"timestamp" => time(),
				"IP" => $_SERVER['REMOTE_ADDR'],
				"projectid" => $projectid,
				"url" => "tasks/view_task/" . $taskid,
				"taskid" => $taskid
				)
			);

			// Redirect
			$this->session->set_flashdata("globalmsg", 
				lang("success_81"));
			redirect(site_url("tasks"));
		} else {
			// Redirect
			$this->session->set_flashdata("globalmsg", lang("success_159"));
			redirect(site_url("tasks/templates"));
		}

	}

	public function delete_task($taskid, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		if($task->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_167"), 
				array("admin", "project_admin", "task_manage"), // User Roles
				array("admin"),  // Team Roles
				$task->projectid
			);
		}

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$this->task_model->delete_task($taskid);

		if($project->complete_sync) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Notify
		$this->notifiy_task_members(
			$taskid, 
			lang("ctn_1255")."[".$task->name."] " . lang("ctn_1256"), 
			$this->user->info->ID
		);

		

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1052") .  $task->name,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks",
			"taskid" => $task->ID
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_82"));
		redirect(site_url("tasks"));
	}

	public function edit_task($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin","task_manage", "task_worker"),$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", array("task" => array("general" => 1)));
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		if($task->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("ctn_1053"), 
				array("admin", "project_admin", "task_manage"), // User Roles
				array("admin"),  // Team Roles
				$task->projectid
			);
		}

		$this->template->loadData("activeLink",array("task" => array("general" => 1)));


		// If user is Admin, Project-Admin or File manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model->get_projects_user_all_no_pagination($this->user->info->ID,"(pr2.admin = 1 OR pr2.task = 1)");
		}
		
		$pro_unit = $this->projects_model->get_pro_unit();
		$team_list = $this->projects_model->get_team_name();
		$this->template->loadContent("tasks/edit_task.php", array(
			"task" => $task,
			"projects" => $projects,
			"team_list" => $team_list,
			"pro_unit" => $pro_unit
			)
		);
	}

	public function edit_task_pro($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		if($task->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("ctn_1053"), 
				array("admin", "project_admin", "task_manage"), // User Roles
				array("admin"),  // Team Roles
				$task->projectid
			);
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$jobid = $this->common->nohtml($this->input->post("jobid"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$input = $this->common->nohtml($this->input->post("input"));
		$output = $this->common->nohtml($this->input->post("output"));
		$complexity = $this->common->nohtml($this->input->post("complexity"));
		$unit = $this->common->nohtml($this->input->post("unit"));
		$geofacets_unit = $this->common->nohtml($this->input->post("geofacets_unit"));
		$geofacets_vendor_unit = $this->common->nohtml($this->input->post("geofacets_vendor_unit"));
		$geofacets_parallel_process_unit = $this->common->nohtml($this->input->post("geofacets_parallel_process_unit"));
		$isbn = $this->common->nohtml($this->input->post("isbn"));
		$eisbn = $this->common->nohtml($this->input->post("eisbn"));
		$volume_issue = $this->common->nohtml($this->input->post("volume_issue"));		
		$unitPricePerPage = round($this->input->post("unitPricePerPage"),2);
		$unitPricePerImage = round($this->input->post("unitPricePerImage"),2);
		$unitPricePerArticle = round($this->input->post("unitPricePerArticle"),2);
		$unitPricePerTableEditable = round($this->input->post("unitPricePerTableEditable"),2);
		$unitPricePerTableScanned = round($this->input->post("unitPricePerTableScanned"),2);
		$unitPricePerFigureEditable = round($this->input->post("unitPricePerFigureEditable"),2);
		$unitPricePerFigureScanned = round($this->input->post("unitPricePerFigureScanned"),2);
		$totalPages = intval($this->input->post("totalPages"));
		$totalImages = intval($this->input->post("totalImages"));
		$totalArticles = intval($this->input->post("totalArticles"));
		$totalTablesEditable = intval($this->input->post("totalTablesEditable"));
		$totalTablesScanned = intval($this->input->post("totalTablesScanned"));
		$totalFiguresEditable = intval($this->input->post("totalFiguresEditable"));
		$totalFiguresScanned = intval($this->input->post("totalFiguresScanned"));
		$totalPagesPrice = round($this->input->post("totalPagesPrice"),2);
		$totalImagesPrice = round($this->input->post("totalImagesPrice"),2);
		$totalArticlesPrice = round($this->input->post("totalArticlesPrice"),2);
		$totalTablesPriceEditable = round($this->input->post("totalTablesPriceEditable"),2);
		$totalTablesPriceScanned = round($this->input->post("totalTablesPriceScanned"),2);
		$totalFiguresPriceEditable = round($this->input->post("totalFiguresPriceEditable"),2);
		$totalFiguresPriceScanned = round($this->input->post("totalFiguresPriceScanned"),2);
		$projectid = intval($this->input->post("projectid"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$due_date = $this->common->nohtml($this->input->post("due_date"));
		$status = intval($this->input->post("status"));

		$template_option = intval($this->input->post("template_option"));
		$template_start_days = intval($this->input->post("template_start_days"));
		$template_due_days = intval($this->input->post("template_due_days"));

		$archived = intval($this->input->post("archived"));
		
		$vendor_assignment = intval($this->input->post("vendor_assignment"));
		if($vendor_assignment == 1) {
			$vendor_name = $this->common->nohtml($this->input->post("vendor_name"));
			$vendor_process_name = $this->common->nohtml($this->input->post("vendor_process_name"));
			$vendor_unit = $this->common->nohtml($this->input->post("vendor_unit"));
			$vendor_unitPricePerPage = round($this->input->post("vendor_unitPricePerPage"),2);
			$vendor_unitPricePerImage = round($this->input->post("vendor_unitPricePerImage"),2);
			$vendor_unitPricePerArticle = round($this->input->post("vendor_unitPricePerArticle"),2);
			$vendor_unitPricePerTableEditable = round($this->input->post("vendor_unitPricePerTableEditable"),2);
			$vendor_unitPricePerTableScanned = round($this->input->post("vendor_unitPricePerTableScanned"),2);
			$vendor_unitPricePerFigureEditable = round($this->input->post("vendor_unitPricePerFigureEditable"),2);
			$vendor_unitPricePerFigureScanned = round($this->input->post("vendor_unitPricePerFigureScanned"),2);
			$vendor_totalPages = intval($this->input->post("vendor_totalPages"));
			$vendor_totalImages = intval($this->input->post("vendor_totalImages"));
			$vendor_totalArticles = intval($this->input->post("vendor_totalArticles"));
			$vendor_totalTablesEditable = intval($this->input->post("vendor_totalTablesEditable"));
			$vendor_totalTablesScanned = intval($this->input->post("vendor_totalTablesScanned"));
			$vendor_totalFiguresEditable = intval($this->input->post("vendor_totalFiguresEditable"));
			$vendor_totalFiguresScanned = intval($this->input->post("vendor_totalFiguresScanned"));
			$vendor_totalPagesPrice = round($this->input->post("vendor_totalPagesPrice"),2);
			$vendor_totalImagesPrice = round($this->input->post("vendor_totalImagesPrice"),2);
			$vendor_totalArticlesPrice = round($this->input->post("vendor_totalArticlesPrice"),2);
			$vendor_totalTablesPriceEditable = round($this->input->post("vendor_totalTablesPriceEditable"),2);
			$vendor_totalTablesPriceScanned = round($this->input->post("vendor_totalTablesPriceScanned"),2);
			$vendor_totalFiguresPriceEditable = round($this->input->post("vendor_totalFiguresPriceEditable"),2);
			$vendor_totalFiguresPriceScanned = round($this->input->post("vendor_totalFiguresPriceScanned"),2);
			$vendor_start_date = $this->common->nohtml($this->input->post("vendor_start_date"));
			$vendor_due_date = $this->common->nohtml($this->input->post("vendor_due_date"));
			$vendor_status = intval($this->input->post("vendor_status"));
		} else {
			$vendor_name = NULL;
			$vendor_process_name = NULL;
			$vendor_unit = NULL;
			$vendor_unitPricePerPage = '0.00';
			$vendor_unitPricePerImage = '0.00';
			$vendor_unitPricePerArticle = '0.00';
			$vendor_unitPricePerTableEditable = '0.00';
			$vendor_unitPricePerTableScanned = '0.00';
			$vendor_unitPricePerFigureEditable = '0.00';
			$vendor_unitPricePerFigureScanned = '0.00';
			$vendor_totalPages = 0;
			$vendor_totalImages = 0;
			$vendor_totalArticles = 0;
			$vendor_totalTablesEditable = 0;
			$vendor_totalTablesScanned = 0;
			$vendor_totalFiguresEditable = 0;
			$vendor_totalFiguresScanned = 0;
			$vendor_totalPagesPrice = '0.00';
			$vendor_totalImagesPrice = '0.00';
			$vendor_totalArticlesPrice = '0.00';
			$vendor_totalTablesPriceEditable = '0.00';
			$vendor_totalTablesPriceScanned = '0.00';
			$vendor_totalFiguresPriceEditable = '0.00';
			$vendor_totalFiguresPriceScanned = '0.00';
			$vendor_start_date = 0;
			$vendor_due_date = 0;
			$vendor_status = 0;		
		}
		
		$parallel = intval($this->input->post("parallel"));
		if($parallel == 1) {
			$parallel_process_name = $this->common->nohtml($this->input->post("parallel_process_name"));
			$parallel_process_team = $this->common->nohtml($this->input->post("parallel_process_team"));
			$cost_applicable = $this->common->nohtml($this->input->post("cost_applicable"));
			if($cost_applicable == 1) {
				$parallel_process_unit = $this->common->nohtml($this->input->post("parallel_process_unit"));
				$parallel_process_unitPricePerPage = round($this->input->post("parallel_process_unitPricePerPage"),2);
				$parallel_process_unitPricePerImage = round($this->input->post("parallel_process_unitPricePerImage"),2);
				$parallel_process_unitPricePerArticle = round($this->input->post("parallel_process_unitPricePerArticle"),2);
				$parallel_process_unitPricePerTableEditable = round($this->input->post("parallel_process_unitPricePerTableEditable"),2);
				$parallel_process_unitPricePerTableScanned = round($this->input->post("parallel_process_unitPricePerTableScanned"),2);
				$parallel_process_unitPricePerFigureEditable = round($this->input->post("parallel_process_unitPricePerFigureEditable"),2);
				$parallel_process_unitPricePerFigureScanned = round($this->input->post("parallel_process_unitPricePerFigureScanned"),2);
				$parallel_process_totalPages = intval($this->input->post("parallel_process_totalPages"));
				$parallel_process_totalImages = intval($this->input->post("parallel_process_totalImages"));
				$parallel_process_totalArticles = intval($this->input->post("parallel_process_totalArticles"));
				$parallel_process_totalTablesEditable = intval($this->input->post("parallel_process_totalTablesEditable"));
				$parallel_process_totalTablesScanned = intval($this->input->post("parallel_process_totalTablesScanned"));
				$parallel_process_totalFiguresEditable = intval($this->input->post("parallel_process_totalFiguresEditable"));
				$parallel_process_totalFiguresScanned = intval($this->input->post("parallel_process_totalFiguresScanned"));
				$parallel_process_totalPagesPrice = round($this->input->post("parallel_process_totalPagesPrice"),2);
				$parallel_process_totalImagesPrice = round($this->input->post("parallel_process_totalImagesPrice"),2);
				$parallel_process_totalArticlesPrice = round($this->input->post("parallel_process_totalArticlesPrice"),2);
				$parallel_process_totalTablesPriceEditable = round($this->input->post("parallel_process_totalTablesPriceEditable"),2);
				$parallel_process_totalTablesPriceScanned = round($this->input->post("parallel_process_totalTablesPriceScanned"),2);
				$parallel_process_totalFiguresPriceEditable = round($this->input->post("parallel_process_totalFiguresPriceEditable"),2);
				$parallel_process_totalFiguresPriceScanned = round($this->input->post("parallel_process_totalFiguresPriceScanned"),2);
			}
			else{
				$parallel_process_unit = NULL;
				$parallel_process_unitPricePerPage = '0.00';
				$parallel_process_unitPricePerImage = '0.00';
				$parallel_process_unitPricePerArticle = '0.00';
				$parallel_process_unitPricePerTableEditable = '0.00';
				$parallel_process_unitPricePerTableScanned = '0.00';
				$parallel_process_unitPricePerFigureEditable = '0.00';
				$parallel_process_unitPricePerFigureScanned = '0.00';
				$parallel_process_totalPages = 0;
				$parallel_process_totalImages = 0;
				$parallel_process_totalArticles = 0;
				$parallel_process_totalTablesEditable = 0;
				$parallel_process_totalTablesScanned = 0;
				$parallel_process_totalFiguresEditable = 0;
				$parallel_process_totalFiguresScanned = 0;
				$parallel_process_totalPagesPrice = '0.00';
				$parallel_process_totalImagesPrice = '0.00';
				$parallel_process_totalArticlesPrice = '0.00';
				$parallel_process_totalTablesPriceEditable = '0.00';
				$parallel_process_totalTablesPriceScanned = '0.00';
				$parallel_process_totalFiguresPriceEditable = '0.00';
				$parallel_process_totalFiguresPriceScanned = '0.00';
			}
		} else {
			$parallel_process_name = NULL;
			$parallel_process_team = NULL;
			$parallel_process_unit = NULL;
			$parallel_process_unitPricePerPage = '0.00';
			$parallel_process_unitPricePerImage = '0.00';
			$parallel_process_unitPricePerArticle = '0.00';
			$parallel_process_unitPricePerTableEditable = '0.00';
			$parallel_process_unitPricePerTableScanned = '0.00';
			$parallel_process_unitPricePerFigureEditable = '0.00';
			$parallel_process_unitPricePerFigureScanned = '0.00';
			$parallel_process_totalPages = 0;
			$parallel_process_totalImages = 0;
			$parallel_process_totalArticles = 0;
			$parallel_process_totalTablesEditable = 0;
			$parallel_process_totalTablesScanned = 0;
			$parallel_process_totalFiguresEditable = 0;
			$parallel_process_totalFiguresScanned = 0;
			$parallel_process_totalPagesPrice = '0.00';
			$parallel_process_totalImagesPrice = '0.00';
			$parallel_process_totalArticlesPrice = '0.00';
			$parallel_process_totalTablesPriceEditable = '0.00';
			$parallel_process_totalTablesPriceScanned = '0.00';
			$parallel_process_totalFiguresPriceEditable = '0.00';
			$parallel_process_totalFiguresPriceScanned = '0.00';	
		}

		if(empty($name)) {
			$this->template->error(lang("error_163"));
		}

		if($status < 1 || $status > 5) {
			$this->template->error(lang("error_164"));
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("ctn_1053"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$projectid
		);

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}

		if(!empty($due_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $due_date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = 0;
		}
		
		if(!empty($vendor_start_date)) {
			$sd_ven = DateTime::createFromFormat($this->settings->info->date_picker_format, $vendor_start_date);
			$vendor_sd_timestamp = $sd_ven->getTimestamp();
		} else {
			$vendor_sd_timestamp = time();
		}

		if(!empty($vendor_due_date)) {
			$dd_ven = DateTime::createFromFormat($this->settings->info->date_picker_format, $vendor_due_date);
			$vendor_dd_timestamp = $dd_ven->getTimestamp();/* 
			$dd_calendar = $dd_ven->format('Y-m-d H:i:s');
			$google_end_date = $dd_ven->format('Y-m-d\TH:i:s'); */
		} else {
			$vendor_dd_timestamp = 0;
		}
		
		if($task->status != $status) {
			if($status == 1) {
				$statusmsg = lang("ctn_830");
			} elseif($status == 2) {
				$statusmsg = lang("ctn_831");
			} elseif($status == 3) {
				$statusmsg = lang("ctn_832");
			} elseif($status == 4) {
				$statusmsg = lang("ctn_833");
			} elseif($status == 5) {
				$statusmsg = lang("ctn_834");
			}
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1257") . "[".$name."] " . lang("ctn_1258") . " 
				 <strong>" . $statusmsg ."</strong>", 
				$this->user->info->ID
			);
		}

		$template_projectid = 0;
		$template = 0;
		if($template_option > 0) {
			$template = 1;
		}
		if($template_option == 1) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage"), 
			$this->user)) {
				$this->template->error(lang("error_71"));
			}
		}
		if($template_option == 2) {
			$template_projectid = $projectid;
		}

		$this->task_model->update_task($taskid, array(
			"name" => $name,
			"jobid" => $jobid,
			"description" => $desc,
			"projectid" => $projectid,
			"input" => $input,
			"output" => $output,
			"complexity" => $complexity,
			"unit" => $unit,
			"geofacets_unit" => $geofacets_unit,
			"geofacets_vendor_unit" => $geofacets_vendor_unit,
			"geofacets_parallel_process_unit" => $geofacets_parallel_process_unit,
			"isbn" => $isbn,
			"eisbn" => $eisbn,
			"volume_issue" => $volume_issue,
			"unitPricePerPage" => $unitPricePerPage,
			"unitPricePerImage" => $unitPricePerImage,
			"unitPricePerArticle" => $unitPricePerArticle,
			"unitPricePerTableEditable" => $unitPricePerTableEditable,
			"unitPricePerTableScanned" => $unitPricePerTableScanned,
			"unitPricePerFigureEditable" => $unitPricePerFigureEditable,
			"unitPricePerFigureScanned" => $unitPricePerFigureScanned,
			"totalPages" => $totalPages,
			"totalImages" => $totalImages,
			"totalArticles" => $totalArticles,
			"totalTablesEditable" => $totalTablesEditable,
			"totalTablesScanned" => $totalTablesScanned,
			"totalFiguresEditable" => $totalFiguresEditable,
			"totalFiguresScanned" => $totalFiguresScanned,
			"totalPagesPrice" => $totalPagesPrice,
			"totalImagesPrice" => $totalImagesPrice,
			"totalArticlesPrice" => $totalArticlesPrice,
			"totalTablesPriceEditable" => $totalTablesPriceEditable,
			"totalTablesPriceScanned" => $totalTablesPriceScanned,
			"totalFiguresPriceEditable" => $totalFiguresPriceEditable,
			"totalFiguresPriceScanned" => $totalFiguresPriceScanned,
			"start_date" => $sd_timestamp,
			"due_date" => $dd_timestamp,
			"status" => $status,
			"archived" => $archived,
			"template" => $template,
			"template_start_days" => $template_start_days,
			"template_due_days" => $template_due_days,
			"vendor_assignment" => $vendor_assignment,
			"vendor_name" => $vendor_name,
			"vendor_process_name" => $vendor_process_name,
			"vendor_unit" => $vendor_unit,
			"vendor_unitPricePerPage" => $vendor_unitPricePerPage,
			"vendor_unitPricePerImage" => $vendor_unitPricePerImage,
			"vendor_unitPricePerArticle" => $vendor_unitPricePerArticle,
			"vendor_totalPages" => $vendor_totalPages,
			"vendor_totalImages" => $vendor_totalImages,
			"vendor_totalArticles" => $vendor_totalArticles,
			"vendor_totalPagesPrice" => $vendor_totalPagesPrice,
			"vendor_totalImagesPrice" => $vendor_totalImagesPrice,
			"vendor_totalArticlesPrice" => $vendor_totalArticlesPrice,
			"vendor_unitPricePerTableEditable" => $vendor_unitPricePerTableEditable,
			"vendor_unitPricePerTableScanned" => $vendor_unitPricePerTableScanned,
			"vendor_unitPricePerFigureEditable" => $vendor_unitPricePerFigureEditable,
			"vendor_unitPricePerFigureScanned" => $vendor_unitPricePerFigureScanned,
			"vendor_totalTablesEditable" => $vendor_totalTablesEditable,
			"vendor_totalTablesScanned" => $vendor_totalTablesScanned,
			"vendor_totalFiguresEditable" => $vendor_totalFiguresEditable,
			"vendor_totalFiguresScanned" => $vendor_totalFiguresScanned,
			"vendor_totalTablesPriceEditable" => $vendor_totalTablesPriceEditable,
			"vendor_totalTablesPriceScanned" => $vendor_totalTablesPriceScanned,
			"vendor_totalFiguresPriceEditable" => $vendor_totalFiguresPriceEditable,
			"vendor_totalFiguresPriceScanned" => $vendor_totalFiguresPriceScanned,
			"vendor_start_date" => $vendor_sd_timestamp,
			"vendor_due_date" => $vendor_dd_timestamp,
			"vendor_status" => $vendor_status,
			"parallel" => $parallel,
			"cost_applicable" => $cost_applicable,
			"parallel_process_name" => $parallel_process_name,
			"parallel_process_team" => $parallel_process_team,
			"parallel_process_unit" => $parallel_process_unit,
			"parallel_process_unitPricePerPage" => $parallel_process_unitPricePerPage,
			"parallel_process_unitPricePerImage" => $parallel_process_unitPricePerImage,
			"parallel_process_unitPricePerArticle" => $parallel_process_unitPricePerArticle,
			"parallel_process_totalPages" => $parallel_process_totalPages,
			"parallel_process_totalImages" => $parallel_process_totalImages,
			"parallel_process_totalArticles" => $parallel_process_totalArticles,
			"parallel_process_totalPagesPrice" => $parallel_process_totalPagesPrice,
			"parallel_process_totalImagesPrice" => $parallel_process_totalImagesPrice,
			"parallel_process_totalArticlesPrice" => $parallel_process_totalArticlesPrice,
			"parallel_process_unitPricePerTableEditable" => $parallel_process_unitPricePerTableEditable,
			"parallel_process_unitPricePerTableScanned" => $parallel_process_unitPricePerTableScanned,
			"parallel_process_unitPricePerFigureEditable" => $parallel_process_unitPricePerFigureEditable,
			"parallel_process_unitPricePerFigureScanned" => $parallel_process_unitPricePerFigureScanned,
			"parallel_process_totalTablesEditable" => $parallel_process_totalTablesEditable,
			"parallel_process_totalTablesScanned" => $parallel_process_totalTablesScanned,
			"parallel_process_totalFiguresEditable" => $parallel_process_totalFiguresEditable,
			"parallel_process_totalFiguresScanned" => $parallel_process_totalFiguresScanned,
			"parallel_process_totalTablesPriceEditable" => $parallel_process_totalTablesPriceEditable,
			"parallel_process_totalTablesPriceScanned" => $parallel_process_totalTablesPriceScanned,
			"parallel_process_totalFiguresPriceEditable" => $parallel_process_totalFiguresPriceEditable,
			"parallel_process_totalFiguresPriceScanned" => $parallel_process_totalFiguresPriceScanned
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1054") . $name . lang("ctn_1051") .$project->name,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "tasks/view/" . $taskid,
			"taskid" => $taskid
			)
		);

		// Redirect
		if($template == 0) {
			$this->session->set_flashdata("globalmsg", 
				lang("success_83"));
			redirect(site_url("tasks"));
		} else {
			$this->session->set_flashdata("globalmsg", 
				lang("success_83"));
			redirect(site_url("tasks/templates"));
		}
	}

	public function view($taskid, $page=0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker", "task_client"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("task" => array("general" => 1)));
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>
			<script src="'.base_url().'scripts/custom/tasks.js">
			</script>'
		);
		$taskid = intval($taskid);
		$page = intval($page);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		if($task->template) {
			$this->template->loadData("activeLink", 
			array("task" => array("templates" => 1)));
		}

		// Permissions
		$this->common->check_permissions(
			lang("error_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task", "client"),  // Team Roles
			$task->projectid
		);

		$members = $this->team_model->get_members_for_project($task->projectid);
		$task_members = $this->task_model->get_task_members($taskid);
		$productivity_members = $this->task_model->get_productivity_members($taskid);
		$objectives = $this->task_model->get_task_objectives($taskid);
		$files = $this->task_model->get_attached_files($taskid);
		$messages = $this->task_model->get_task_messages($taskid, $page);
		$actions = $this->task_model->get_activity_log($taskid);
		$dependencies = $this->task_model->get_dependencies($taskid);
		$tasks = $this->task_model->get_all_tasks_no_pagination($task->projectid,0,0);

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("tasks/view/" . $taskid);
		$config['total_rows'] = $this->task_model->get_task_messages_total($taskid);
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");
		$this->pagination->initialize($config);


		// Time stats
		$this->load->model("time_model");
		// Get days
		$last_dates = array();
		$total_hours = 0;
		$total_earnt = 0;
		$total_timers = 0;
		$projects = array();
		$days = 6;

		for ($i=$days; $i>-1; $i--) {
			$date = date("Y-m-d", strtotime($i." days ago"));
			$time = $this->time_model->count_hours_date_task($date, $taskid);
			if($time->num_rows() > 0) {
				$hours = 0;
				foreach($time->result() as $r) {
					$hour = ($r->time/3600);
					$hours += $hour;
					$earnt = $hour * $r->rate;
					$total_hours += $hour;
					$total_earnt += $earnt;
					$total_timers++;
				}

				$hours = round($hours, 2);

				$hour = array(
					"date" => $date,
					"hours" => $hours
				);
			    $last_dates[] = $hour;
			} else {
				$hour = array(
					"date" => $date,
					"hours" => 0
				);
			    $last_dates[] = $hour;
			}
		}

		$this->template->loadContent("tasks/view_task.php", array(
			"task" => $task,
			"members" => $members,
			"task_members" => $task_members,
			"productivity_members" => $productivity_members,
			"objectives" => $objectives,
			"files" => $files,
			"messages" => $messages,
			"actions" => $actions,
			"last_dates" => $last_dates,
			"dependencies" => $dependencies,
			"tasks" => $tasks
			)
		);
		
	}

	public function add_task_dependency($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}

		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("ctn_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid,
			"",
			"error"
		);

		// Get dependency
		$second_taskid = intval($this->input->post("taskid"));
		$task2 = $this->task_model->get_task($second_taskid);
		if($task2->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task2 = $task2->row();

		if($task2->projectid != $task->projectid) 
		{
			$this->template->error("These tasks are not in the same project!");
		}

		// Add
		$this->task_model->add_task_dependency(array(
			"taskid_primary" => $taskid,
			"taskid_secondary" => $second_taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			"The task dependency was added!");
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function remove_dependency($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		$id = intval($id);
		$dependency = $this->task_model->get_task_dependency($id);
		if($dependency->num_rows() == 0) {
			$this->template->error("Invalid dependency");
		}
		$dependency = $dependency->row();

		$this->task_model->delete_task_dependency($id);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			"The task dependency was deleted!");
		redirect(site_url("tasks/view/" . $dependency->taskid_primary));
	}

	public function update_details() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->jsonError(lang("error_71"));
		}
		$taskid = intval($this->input->get("taskid"));
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->jsonError(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("ctn_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid,
			"",
			"jsonError"
		);

		$start_date = $this->common->nohtml($this->input->get("start_date"));
		$due_date = $this->common->nohtml($this->input->get("due_date"));

		$complete = intval($this->input->get("complete"));
		$sync = intval($this->input->get("sync"));

		if($complete < 0 || $complete > 100) $complete = 0;
		if($sync < 0 || $sync > 1) $sync = 1;

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}

		if(!empty($due_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $due_date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = 0;
		}

		if($sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			} 
		}

		if($complete >= 100) {
			$status = 3;
		} else {
			if($task->status == 3) {
				$status = 2;
			} else {
				$status = $task->status;
			}
		}

		if($task->status != $status) {
			if($status == 1) {
				$statusmsg = lang("ctn_830");
			} elseif($status == 2) {
				$statusmsg = lang("ctn_831");
			} elseif($status == 3) {
				$statusmsg = lang("ctn_832");
			} elseif($status == 4) {
				$statusmsg = lang("ctn_833");
			} elseif($status == 5) {
				$statusmsg = lang("ctn_834");
			}
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1257") . "[".$task->name."] " . lang("ctn_1258") . " 
				 <strong>" . $statusmsg ."</strong>", 
				$this->user->info->ID
			);
		}

		$this->task_model->update_task($taskid, array(
			"start_date" => $sd_timestamp,
			"due_date" => $dd_timestamp,
			"complete" => $complete,
			"complete_sync" => $sync,
			"status" => $status
			)
		);
		$complete_a = $complete;
		if($project->complete_sync) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" =>  lang("ctn_1054") .$task->name. lang("ctn_1051") .$project->name,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $taskid,
			"taskid" => $taskid
			)
		);

		echo json_encode(array("success" => 1, "complete" => $complete_a));
		exit();
	}

	public function change_status() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->jsonError(lang("error_71"));
		}
		$taskid = intval($this->input->get("taskid"));
		$status = intval($this->input->get("status"));

		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->jsonError(lang("error_166"));
		}
		$task = $task->row();

		if($status < 1 || $status > 5) {
			$this->template->jsonError(lang("error_164"));
		}

		// Permissions
		$this->common->check_permissions(
			lang("error_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid,
			"",
			"jsonError"
		);

		$this->task_model->update_task($taskid, array(
			"status" => $status
			)
		);

		if($task->status != $status) {
			if($status == 1) {
				$statusmsg = lang("ctn_830");
			} elseif($status == 2) {
				$statusmsg = lang("ctn_831");
			} elseif($status == 3) {
				$statusmsg = lang("ctn_832");
			} elseif($status == 4) {
				$statusmsg = lang("ctn_833");
			} elseif($status == 5) {
				$statusmsg = lang("ctn_834");
			}

			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1257") . "[".$task->name."] " . lang("ctn_1258") . " 
				 <strong>" . $statusmsg ."</strong>", 
				$this->user->info->ID
			);
		}

		echo json_encode(array("success" => 1));
		exit();
	}

	public function remind_user() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->jsonError(lang("error_71"));
		}
		$id = intval($this->input->get("id"));
		if($_GET['hash'] != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}

		// Get task member
		$member = $this->task_model->get_task_member_id($id);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_169"));
		}
		$member = $member->row();

		// Check permission
		// Permissions
		$this->common->check_permissions(
			lang("error_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$member->projectid,
			"",
			"jsonError"
		);

		// Good
		// Send notification of being added to the task
		$this->user_model->increment_field($member->userid, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $member->userid,
			"url" => "tasks/view/" . $member->taskid,
			"timestamp" => time(),
			"message" => lang("ctn_1055") . $member->username,
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"taskid" => $member->taskid,
			"email" => $member->email,
			"username" => $member->username,
			"email_notification" => $member->email_notification
			)
		);

		echo json_encode(array("success" => 1));
		exit();
	}

	public function add_task_member($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_170"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$userid = $this->input->post("userid");
		
		
		foreach ($userid as $val){
		// Check user is member of team
		$member = $this->team_model->get_member_of_project($val, $task->projectid);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$member = $member->row();

		// Check they're not already a member
		$taskmember = $this->task_model->get_task_member($val, $taskid);
		if($taskmember->num_rows() > 0) {
			$this->template->error(lang("error_172"));
		}

		// Add member
		
			$this->task_model->add_task_member(array(
				"taskid" => $taskid,
				"userid" => $val
				)
			);
		
		

		// Send notification of being added to the task
		$this->user_model->increment_field($val, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $val,
			"url" => "tasks/view/" . $taskid,
			"timestamp" => time(),
			"message" => lang("ctn_1056"). $task->name,
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"email" => $member->email,
			"username" => $member->username,
			"email_notification" => $member->email_notification
			)
		);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1057") . " <b>".$member->username.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_84"));
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function remove_member($userid, $taskid, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$taskid = intval($taskid);
		$userid = intval($userid);

		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_173"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		// Check they're not already a member
		$taskmember = $this->task_model->get_task_member($userid, $taskid);
		if($taskmember->num_rows() == 0) {
			$this->template->error(lang("error_174"));
		}
		$taskmember = $taskmember->row();

		// Remove Member
		$this->task_model->remove_member($userid, $taskid);

		// Send notification of being added to the task
		$this->user_model->increment_field($userid, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $userid,
			"url" => "tasks/view/" . $taskid,
			"timestamp" => time(),
			"message" => lang("ctn_1059") . $task->name,
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"email" => $taskmember->email,
			"username" => $taskmember->username,
			"email_notification" => $taskmember->email_notification
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1060") . " <b>".$taskmember->username.
			"</b> ".lang("ctn_1061")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_85"));
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function add_task_objective($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_175"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$title = $this->common->nohtml($this->input->post("title"));
		$subtask_get_unit = $this->common->nohtml($this->input->post("subtask_get_unit"));
		$subtask_get_complex = $this->common->nohtml($this->input->post("subtask_get_complex"));
		$subtask_target = $this->input->post("subtask_target");
		
		$desc = $this->lib_filter->go($this->input->post("description"));

		if(empty($title)) {
			$this->template->error(lang("error_176"));
		}
		if($subtask_get_unit == 'Page'){
		$objectiveid = $this->task_model->add_objective(array(
			"title" => $title,
			"description" => $desc,
			"unit" => $subtask_get_unit,
			"complexity" => $subtask_get_complex,
			"totalPages" => $subtask_target,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"taskid" => $taskid
			)
		);
		}else{
		$objectiveid = $this->task_model->add_objective(array(
			"title" => $title,
			"description" => $desc,
			"unit" => $subtask_get_unit,
			"complexity" => $subtask_get_complex,
			"totalArticles" => $subtask_target,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"taskid" => $taskid
			)
		);
		}

		// Get userr
		$task_members = $this->task_model->get_task_members($taskid);
		foreach($task_members->result() as $r) {
			if(isset($_POST['user_' . $r->ID])) {
				// Add user to objective
				$this->task_model->add_objective_member($objectiveid, $r->userid);


				if(!$task->template) {
					// Notify
					// Send notification of being added to the task
					$this->user_model->increment_field($r->userid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $r->userid,
						"url" => "tasks/view/" . $taskid,
						"timestamp" => time(),
						"message" =>  lang("ctn_1062") . $title,
						"status" => 0,
						"fromid" => $this->user->info->ID,
						"email" => $r->email,
						"username" => $r->username,
						"email_notification" => $r->email_notification
						)
					);
				}
			}
		}

		if($task->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}

			if($complete >= 100) {
				$status = 3;
			} else {
				if($task->status == 3) {
					$status = 2;
				} else {
					$status = $task->status;
				}
			}
			$this->task_model->update_task($taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$task->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1063") . " <b>".$title.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1259")."[".$title."] ".lang("ctn_1260") .":
				 <strong>" . $task->name ."</strong>", 
				$this->user->info->ID
			);
		}

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_86"));
		redirect(site_url("tasks/view/" . $taskid));

	}
	
	public function add_default_task_objective($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$subtask_complexity = $this->common->nohtml($this->input->post("subtask_complexity"));
		$subtask_unit = $this->common->nohtml($this->input->post("subtask_unit"));
		/* $totalPages = intval($this->input->post("totalPages"));
		$totalArticles = intval($this->input->post("totalArticles")); */
		
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_175"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$objectiveid = $this->task_model->add_default_objective($this->user->info->ID,time(),$taskid,$subtask_complexity,$subtask_unit);

		// Get userr
		$task_members = $this->task_model->get_task_members($taskid);
		foreach($task_members->result() as $r) {
			if(isset($_POST['user_' . $r->ID])) {
				// Add user to objective
				$this->task_model->add_objective_member($objectiveid, $r->userid);


				if(!$task->template) {
					// Notify
					// Send notification of being added to the task
					$this->user_model->increment_field($r->userid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $r->userid,
						"url" => "tasks/view/" . $taskid,
						"timestamp" => time(),
						"message" =>  lang("ctn_1062") . $title,
						"status" => 0,
						"fromid" => $this->user->info->ID,
						"email" => $r->email,
						"username" => $r->username,
						"email_notification" => $r->email_notification
						)
					);
				}
			}
		}

		if($task->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}

			if($complete >= 100) {
				$status = 3;
			} else {
				if($task->status == 3) {
					$status = 2;
				} else {
					$status = $task->status;
				}
			}
			$this->task_model->update_task($taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$task->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1063") . " <b>".$title.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1259")."[".$title."] ".lang("ctn_1260") .":
				 <strong>" . $task->name ."</strong>", 
				$this->user->info->ID
			);
		}

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_86"));
		redirect(site_url("tasks/view/" . $taskid));

	}
	
	public function add_wtx_task_objective($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$subtask_complexity = $this->common->nohtml($this->input->post("subtask_complexity"));
		$subtask_unit = $this->common->nohtml($this->input->post("subtask_unit"));
		
		/* $totalPages = intval($this->input->post("totalPages"));
		$totalArticles = intval($this->input->post("totalArticles")); */
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_175"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$objectiveid = $this->task_model->add_wtx_objective($this->user->info->ID,time(),$taskid,$subtask_complexity,$subtask_unit);

		// Get userr
		$task_members = $this->task_model->get_task_members($taskid);
		foreach($task_members->result() as $r) {
			if(isset($_POST['user_' . $r->ID])) {
				// Add user to objective
				$this->task_model->add_objective_member($objectiveid, $r->userid);


				if(!$task->template) {
					// Notify
					// Send notification of being added to the task
					$this->user_model->increment_field($r->userid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $r->userid,
						"url" => "tasks/view/" . $taskid,
						"timestamp" => time(),
						"message" =>  lang("ctn_1062") . $title,
						"status" => 0,
						"fromid" => $this->user->info->ID,
						"email" => $r->email,
						"username" => $r->username,
						"email_notification" => $r->email_notification
						)
					);
				}
			}
		}

		if($task->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}

			if($complete >= 100) {
				$status = 3;
			} else {
				if($task->status == 3) {
					$status = 2;
				} else {
					$status = $task->status;
				}
			}
			$this->task_model->update_task($taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$task->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1063") . " <b>".$title.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1259")."[".$title."] ".lang("ctn_1260") .":
				 <strong>" . $task->name ."</strong>", 
				$this->user->info->ID
			);
		}

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_86"));
		redirect(site_url("tasks/view/" . $taskid));

	}
	
	public function add_xtx_task_objective($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$subtask_complexity = $this->common->nohtml($this->input->post("subtask_complexity"));
		$subtask_unit = $this->common->nohtml($this->input->post("subtask_unit"));
		/* $totalPages = intval($this->input->post("totalPages"));
		$totalArticles = intval($this->input->post("totalArticles")); */
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$project = $this->projects_model->get_project($task->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_175"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$objectiveid = $this->task_model->add_xtx_objective($this->user->info->ID,time(),$taskid,$subtask_complexity,$subtask_unit);

		// Get userr
		$task_members = $this->task_model->get_task_members($taskid);
		foreach($task_members->result() as $r) {
			if(isset($_POST['user_' . $r->ID])) {
				// Add user to objective
				$this->task_model->add_objective_member($objectiveid, $r->userid);


				if(!$task->template) {
					// Notify
					// Send notification of being added to the task
					$this->user_model->increment_field($r->userid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $r->userid,
						"url" => "tasks/view/" . $taskid,
						"timestamp" => time(),
						"message" =>  lang("ctn_1062") . $title,
						"status" => 0,
						"fromid" => $this->user->info->ID,
						"email" => $r->email,
						"username" => $r->username,
						"email_notification" => $r->email_notification
						)
					);
				}
			}
		}

		if($task->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}

			if($complete >= 100) {
				$status = 3;
			} else {
				if($task->status == 3) {
					$status = 2;
				} else {
					$status = $task->status;
				}
			}
			$this->task_model->update_task($taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$task->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1063") . " <b>".$title.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1259")."[".$title."] ".lang("ctn_1260") .":
				 <strong>" . $task->name ."</strong>", 
				$this->user->info->ID
			);
		}

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_86"));
		redirect(site_url("tasks/view/" . $taskid));

	}

	public function complete_objective($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$objective = $this->task_model->get_task_objective($id);
		if($objective->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$objective = $objective->row();

		$project = $this->projects_model->get_project($objective->projectid);
		if($project->num_rows() == 0) {
			$this->template->jsonError(lang("error_72"));
		}
		$project = $project->row();

		// Check
		// Permissions
		$this->common->check_permissions(
			lang("error_178"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$objective->projectid
		);

		$this->task_model->update_objective($id, array(
			"complete" => 1
			)
		);

		if($objective->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($objective->taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}
			if($complete >= 100) {
				$status = 3;
			} else {
				if($objective->status == 3) {
					$status = 2;
				} else {
					$status = $objective->status;
				}
			}

			if($objective->status != $status) {
				if($status == 1) {
					$statusmsg = lang("ctn_830");
				} elseif($status == 2) {
					$statusmsg = lang("ctn_831");
				} elseif($status == 3) {
					$statusmsg = lang("ctn_832");
				} elseif($status == 4) {
					$statusmsg = lang("ctn_833");
				} elseif($status == 5) {
					$statusmsg = lang("ctn_834");
				}

				if(!$objective->template) {
					// Notify
					$this->notifiy_task_members(
						$objective->taskid, 
						lang("ctn_1257") . "[".$objective->name."] ".lang("ctn_1258")." 
						 <strong>" . $statusmsg ."</strong>", 
						$this->user->info->ID
					);
				}
			}

			$this->task_model->update_task($objective->taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$objective->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1064") . " <b>".$objective->title.
			"</b>" . lang("ctn_1065"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $objective->projectid,
			"url" => "tasks/view/" . $objective->taskid,
			"taskid" => $objective->taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_87"));
		redirect(site_url("tasks/view/" . $objective->taskid));
	}

	public function delete_objective($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$objective = $this->task_model->get_task_objective($id);
		if($objective->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$objective = $objective->row();

		$project = $this->projects_model->get_project($objective->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check
		// Permissions
		$this->common->check_permissions(
			lang("error_179"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$objective->projectid
		);

		$this->task_model->delete_objective($id);
		$this->task_model->delete_objective_members($id);

		if($objective->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($objective->taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}
			if($complete >= 100) {
				$status = 3;
			} else {
				if($objective->status == 3) {
					$status = 2;
				} else {
					$status = $objective->status;
				}
			}

			if($objective->status != $status) {
				if($status == 1) {
					$statusmsg = lang("ctn_830");
				} elseif($status == 2) {
					$statusmsg = lang("ctn_831");
				} elseif($status == 3) {
					$statusmsg = lang("ctn_832");
				} elseif($status == 4) {
					$statusmsg = lang("ctn_833");
				} elseif($status == 5) {
					$statusmsg = lang("ctn_834");
				}

				if(!$objective->template) {
					// Notify
					$this->notifiy_task_members(
						$objective->taskid, 
						lang("ctn_1257") . "[".$objective->name."] ".lang("ctn_1258")." 
						 <strong>" . $statusmsg ."</strong>", 
						$this->user->info->ID
					);
				}
			}
			$this->task_model->update_task($objective->taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$objective->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1066") . " <b>".$objective->title.
			"</b>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $objective->projectid,
			"url" => "tasks/view/" . $objective->taskid,
			"taskid" => $objective->taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_88"));
		redirect(site_url("tasks/view/" . $objective->taskid));
	}

	public function edit_objective($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$objective = $this->task_model->get_task_objective($id);
		if($objective->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$objective = $objective->row();
		// Check
		// Permissions
		$this->common->check_permissions(
			lang("error_180"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$objective->projectid
		);

		$task_members = $this->task_model->get_task_members($objective->taskid);

		$objective_members = $this->task_model->get_task_objective_members($id);

		$objective_members_ids = array();
		foreach($objective_members->result() as $r) {
			$objective_members_ids[] = $r->userid;
		}

		$this->template->loadAjax("tasks/edit_objective.php", array(
			"objective" => $objective,
			"task_members" => $task_members,
			"objective_members_ids" => $objective_members_ids
			),1
		);

	}

	public function edit_objective_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$objective = $this->task_model->get_task_objective($id);
		if($objective->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$objective = $objective->row();

		$project = $this->projects_model->get_project($objective->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check
		// Permissions
		$this->common->check_permissions(
			lang("error_180"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$objective->projectid
		);

		$title = $this->common->nohtml($this->input->post("title"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$target_complete = $this->input->post("subtask_edit_target_complete");
		
		$target_percent = $this->input->post("subtask_edit_target_percentage");
		
		if(empty($title)) {
			$this->template->error(lang("error_176"));
		}
		$complete = $this->input->post("complete");
		if($complete == 1){
			$completed_datetime = $this->input->post("completed_datetime");
			$completed_date = $this->input->post("completed_date");
			$completed_time = $this->input->post("completed_time"); 
		}
		if($complete != 1){
			$onhold_date = $this->input->post("onhold_date");
			$onhold_time = $this->input->post("onhold_time");
		}
		
		$started = intval($this->input->post("started"));
		$started = $this->input->post("started");
		if(($complete == 1) && ($this->input->post("hldrwip") != 'rework')){
			$hldrwip = '';
		}else{
		$hldrwip = $this->input->post("hldrwip");
		if($this->input->post("hldrwip") == 'rework'){
			$rework_datetime = $this->input->post("rework_datetime");
		}
		}
		if($hldrwip != ''){
		if($started == 1){
			$start_date = $this->input->post("start_date");
			$start_time = $this->input->post("start_time");
			$this->task_model->update_objective($id, array(
			"title" => $title,
			"description" => $desc,
			"productivity_count" => $target_complete,
			"productivity_percentage" => $target_percent,
			"started" => $started,
			"start_date" => $start_date,
			"start_time" => $start_time,
			"onhold_date" => $onhold_date,
			"onhold_time" => $onhold_time,
			"rework_datetime" => $rework_datetime,
			"task_status" => $hldrwip
			)
			);
		}
		$this->task_model->update_objective($id, array(
			"title" => $title,
			"description" => $desc,
			"productivity_count" => $target_complete,
			"productivity_percentage" => $target_percent,
			"complete" => $complete,
			"completed_datetime" => $completed_datetime,
			"completed_date" => $completed_date,
			"completed_time" => $completed_time,
			"onhold_date" => $onhold_date,
			"onhold_time" => $onhold_time,
			"rework_datetime" => $rework_datetime,
			"task_status" => $hldrwip
			)
			);
		
		}
		else{
		if($started == 1){
			$start_date = $this->input->post("start_date");
			$start_time = $this->input->post("start_time");
			$this->task_model->update_objective($id, array(
			"title" => $title,
			"description" => $desc,
			"productivity_count" => $target_complete,
			"productivity_percentage" => $target_percent,
			"started" => $started,
			"start_date" => $start_date,
			"start_time" => $start_time
			)
			);
		}
		$this->task_model->update_objective($id, array(
			"title" => $title,
			"description" => $desc,
			"productivity_count" => $target_complete,
			"productivity_percentage" => $target_percent,
			"complete" => $complete,
			"completed_datetime" => $completed_datetime,
			"completed_date" => $completed_date,
			"completed_time" => $completed_time
			)
			);
		} 
		$this->task_model->delete_objective_members($id);

		// Get userr
		$task_members = $this->task_model->get_task_members($objective->taskid);
		foreach($task_members->result() as $r) {
			if(isset($_POST['user_' . $r->ID])) {
				// Add user to objective
				$this->task_model->add_objective_member($id, $r->userid);
			}
		}

		if($objective->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($objective->taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}
			if($complete >= 100) {
				$status = 3;
			} else {
				if($objective->status == 3) {
					$status = 2;
				} else {
					$status = $objective->status;
				}
			}

			if($objective->status != $status) {
				if($status == 1) {
					$statusmsg = lang("ctn_830");
				} elseif($status == 2) {
					$statusmsg = lang("ctn_831");
				} elseif($status == 3) {
					$statusmsg = lang("ctn_832");
				} elseif($status == 4) {
					$statusmsg = lang("ctn_833");
				} elseif($status == 5) {
					$statusmsg = lang("ctn_834");
				}

				if(!$objective->template) {
					// Notify
					$this->notifiy_task_members(
						$objective->taskid, 
						lang("ctn_1257") . "[".$objective->name."] ".lang("ctn_1258")." 
						 <strong>" . $statusmsg ."</strong>", 
						$this->user->info->ID
					);
				}
			}
			$this->task_model->update_task($objective->taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}
		
		$objective_started = $this->task_model->get_task_objectives($objective->taskid);
		foreach($objective_started->result() as $osr) {
			if($osr->complete != 1 && $osr->started == 1)
			{
				$status = 2;
			}
		}
		if($status == 2) {
		$this->task_model->update_task($objective->taskid, array(
				"status" => $status
				)
		);
		}
		
		if($project->complete_sync && !$objective->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}


		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1067") . " <b>".$title.
			"</b>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $objective->projectid,
			"url" => "tasks/view/" . $objective->taskid,
			"taskid" => $objective->taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_89"));
		redirect(site_url("tasks/view/" . $objective->taskid));
	}

	public function get_files($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$query = $this->common->nohtml($this->input->get("query"));

		// Look up files in the file manager for this project + no project

		if(!empty($query)) {
			$this->load->model("file_model");
			$files = $this->file_model->get_files_by_project($task->projectid, $query);
			if($files->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($files->result() as $r) {
					$array[] = array("label" => $r->file_name . $r->extension, "value" => $r->ID);
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function add_file($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->load->model("file_model");
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("ctn_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$fileid = intval($this->input->post("file_search_id"));
		$file = $this->file_model->get_file($fileid);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();

		if($file->projectid > 0) {
			if($file->projectid != $task->projectid) {
				$this->template->error(lang("error_181"));
			}
		}

		if($file->folder_flag != 0) {
			$this->template->error(lang("error_182"));
		}

		// Check it's not already attached
		$attached = $this->task_model->get_attached_file($fileid, $taskid);
		if($attached->num_rows() > 0) {
			$this->template->error(lang("error_183"));
		}

		// Attach
		$this->task_model->add_file(array(
			"fileid" => $fileid,
			"taskid" => $taskid
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1068"). " <b>".$file->file_name.
			"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1068"). " <b>".$file->file_name.
				"</b> ".lang("ctn_1058")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
				. $task->name . "</a>", 
				$this->user->info->ID
			);
		}


		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_90"));
		redirect(site_url("tasks/view/" . $taskid));

	}

	public function remove_file($taskid, $id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->load->model("file_model");
		$taskid = intval($taskid);
		$id = intval($id);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_184"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$file = $this->task_model->get_attached_file_id($id, $taskid);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_185"));
		}
		$file  = $file->row();

		$this->task_model->delete_file($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1069") . " <b>".$file->file_name.
			"</b> ".lang("ctn_1061")." <a href='".site_url("tasks/view/" . $task->ID)."'>" 
			. $task->name . "</a>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view/" . $task->ID,
			"taskid" => $task->ID
			)
		);


		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_91"));
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function add_message($taskid) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		// Permissions
		$this->common->check_permissions(
			lang("error_186"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$task->projectid
		);

		$message = $this->lib_filter->go($this->input->post("message"));
		if(empty($message)) {
			$this->template->error(lang("error_187"));
		}

		$this->task_model->add_message(array(
			"userid" => $this->user->info->ID,
			"message" => $message,
			"timestamp" => time(),
			"taskid" => $taskid
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1070"). " {$task->name}",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view_task/" . $taskid,
			"taskid" => $taskid
			)
		);

		if(!$task->template) {
			// Notify
			$this->notifiy_task_members(
				$taskid, 
				lang("ctn_1261") . ":
				 <strong>" . $task->name ."</strong>", 
				$this->user->info->ID
			);
		}

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_92"));
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function delete_message($taskid, $id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$taskid = intval($taskid);
		$id = intval($id);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		$message = $this->task_model->get_message($id, $taskid);
		if($message->num_rows() == 0) {
			$this->template->error(lang("error_188"));
		}

		$this->task_model->delete_message($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1071") . " {$task->name}",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $task->projectid,
			"url" => "tasks/view_task/" . $taskid,
			"taskid" => $taskid
			)
		);
		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_93"));
		redirect(site_url("tasks/view/" . $taskid));
	}

	public function view_activity($taskid, $page=0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$taskid = intval($taskid);
		$page = intval($page);

		$activity = $this->task_model->get_task_activity($taskid, $page);
		if($activity->num_rows() == 0) {
			$this->template->error(lang("error_189"));
		}

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("tasks/view_activity/" . $taskid);
		$config['total_rows'] = $this->task_model
			->get_task_activity_total($taskid);
		$config['per_page'] = 15;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");
		$this->pagination->initialize($config);

		$this->template->loadContent("tasks/view_task_activity.php", array(
			"actions" => $activity
			)
		);
	}

	private function notifiy_task_members($taskid, $msg, $fromid) 
	{
		$members = $this->task_model->get_task_members($taskid);

		foreach($members->result() as $r) {
			//if($r->userid != $fromid) {
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->userid,
					"url" => "tasks/view/" . $taskid,
					"timestamp" => time(),
					"message" => $msg,
					"status" => 0,
					"fromid" => $fromid,
					"email" => $r->email,
					"username" => $r->username,
					"email_notification" => $r->email_notification
					)
				);
			//}
		}
		return true;
	}

	public function templates($projectid = 0, $status=0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("task" => array("templates" => 1)));

		$projectid = intval($projectid);
		$status = intval($status);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "task_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.task = 1)");
		}

		$this->template->loadContent("tasks/templates.php", array(
			"projects" => $projects,
			"page" => "templates",
			"projectid" => $projectid,
			"u_status" => $status
			)
		);
	}

	public function templates_page($projectid=0, $u_status =0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$projectid = intval($projectid);
		$u_status = intval($u_status);

		// if no project, set active
		if($projectid == 0) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("project_tasks.due_date", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"project_tasks.name" => 0
				 ),
				 1 => array(
				 	"project_tasks.status" => 0
				 ),
				 2 => array(
				 	"projects.name" => 0
				 ),
				 3 => array(
				 	"project_tasks.complete" => 0
				 ),
				 4 => array(
				 	"project_tasks.due_date" => 0
				 )
			)
		);

		

		$this->datatables->set_total_rows(
			$this->task_model
				->get_project_task_templates_total($projectid, $u_status, $this->user->info->ID)
		);

		$tasks = $this->task_model->get_project_task_templates($projectid, $u_status,
			$this->user->info->ID, $this->datatables);
		

		foreach($tasks->result() as $r) {
			if($r->status == 1) {
				$status = "<label class='label label-info'>".lang("ctn_830")."</label>";
			} elseif($r->status == 2) {
				$status = "<label class='label label-primary'>".lang("ctn_831")."</label>";
			} elseif($r->status == 3) {
				$status = "<label class='label label-success'>".lang("ctn_832")."</label>";
			} elseif($r->status == 4) {
				$status = "<label class='label label-warning'>".lang("ctn_833")."</label>";
			} elseif($r->status == 5) {
				$status = "<label class='label label-danger'>".lang("ctn_834")."</label>";
			}

			if($r->template_projectid == 0) {
				$template_type = "All Projects";
			} else {
				$template_type = '<a href="'.site_url("tasks/templates/" . $r->projectid . "/" . $u_status).'">'.$r->project_name.'</a>';
			}

			
			$options = '<button class="btn btn-success btn-xs" onclick="create_task('.$r->ID.')">Create</button> <a href="'.site_url("tasks/view/" . $r->ID) .'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a href="'.site_url("tasks/edit_task/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tasks/delete_task/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			
			$this->datatables->data[] = array(
				'<a href="'.site_url("tasks/view/" . $r->ID) .'">'.$r->name.'</a>',
				$status,
				'<div class="progress" style="height: 15px;">
					  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$r->complete.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$r->complete.'%" title="'.$r->complete .'%" data-toggle="tooltip" data-placement="bottom">
					    <span class="sr-only">'.$r->complete.'% '.lang("ctn_790").'</span>
					  </div>
				</div>',
				$template_type,
				$options

			);
		}

		echo json_encode($this->datatables->process());

	}

	public function create_template($taskid) 
	{
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		if($task->template == 0) {
			$this->template->error(lang("error_284"));
		}

		if($task->template_projectid > 0) {
			// Fixed project
			// Else
			$projects = $this->projects_model->get_project($task->template_projectid);
		} else {
			if($this->common->has_permissions(
				array("admin", "project_admin", "task_manage"), $this->user
				)
			) {
				$projects = $this->projects_model->get_all_active_projects();
			} else {
				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.task = 1)");
			}
		}

		$this->template->loadAjax("tasks/create_template.php", array(
			"task" => $task,
			"projects" => $projects
			), 0
		);
	}

	public function template_process($taskid) 
	{
		$taskid = intval($taskid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		if($task->template == 0) {
			$this->template->error(lang("error_284"));
		}

		// Permissions
		$this->common->check_permissions(
			lang("error_168"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task", "client"),  // Team Roles
			$task->projectid
		);

		// Options
		$import_objectives = intval($this->input->post("import_objectives"));
		$import_task_members = intval($this->input->post("import_task_members"));
		$import_team = intval($this->input->post("import_team"));
		$import_files = intval($this->input->post("import_files"));
		$import_messages = intval($this->input->post("import_messages"));
		$projectid = intval($this->input->post("projectid"));

		// Create task time.
		$name = $task->name;
		$desc = $task->description;
		$start_date = $task->start_date;
		$due_date = $task->due_date;
		$status = $task->status;
		/* $assign = $task->assign; */
		$template_start_days = $task->template_start_days;
		$template_due_days = $task->template_due_days;

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check user has permission
		$this->common->check_permissions(
			lang("error_165"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$projectid
		);

		$day_time = 3600*24;
		$sd_timestamp = time() + ($task->template_start_days * $day_time);
		$dd_timestamp = time() + ($task->template_due_days * $day_time);


		$taskid = $this->task_model->add_task(array(
			"name" => $name,
			"description" => $desc,
			"projectid" => $projectid,
			"start_date" => $sd_timestamp,
			"due_date" => $dd_timestamp,
			"status" => $status,
			"userid" => $this->user->info->ID,
			"complete_sync" => $task->complete_sync,
			"complete" => $task->complete
			)
		);

		// Add Task dependencies
		if($import_objectives) {
			$objectives = $this->task_model->get_task_objectives($task->ID);
			foreach($objectives->result() as $r) {
				$objectiveid = $this->task_model->add_objective(array(
					"title" => $r->title,
					"description" => $r->description,
					"userid" => $this->user->info->ID,
					"timestamp" => time(),
					"taskid" => $taskid
					)
				);

				// Get assigned objective members
				$members = $this->task_model->get_task_objective_members($r->ID);
				foreach($members->result() as $rr) {
					$member = $this->team_model->get_member_of_project($rr->userid, $projectid);
					if($member->num_rows() == 0) {
						continue;
					}
					$member = $member->row();

					$this->task_model->add_objective_member($objectiveid, $rr->userid);
				}
			}

		}

		if($import_task_members) {
			// This can only happen if the members of the current task 
			// are also members of the project that we are creating the template for
			$task_members = $this->task_model->get_task_members($task->ID);
			foreach($task_members->result() as $r) {
				// Check member is on the new project
				// Check user is member of team
				$member = $this->team_model->get_member_of_project($r->userid, $projectid);
				if($member->num_rows() == 0) {
					continue;
				}
				$member = $member->row();
				// Add

				// Add member
				$this->task_model->add_task_member(array(
					"taskid" => $taskid,
					"userid" => $r->userid
					)
				);

				// Send notification of being added to the task
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->userid,
					"url" => "tasks/view/" . $taskid,
					"timestamp" => time(),
					"message" => lang("ctn_1056"). $task->name,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"email" => $member->email,
					"username" => $member->username,
					"email_notification" => $member->email_notification
					)
				);

			}
		}

		if($import_messages) {
			$task_messages = $this->task_model->get_task_messages_all($task->ID);
			foreach($task_messages->result() as $r) {
				$this->task_model->add_message(array(
					"userid" => $r->userid,
					"message" => $r->message,
					"timestamp" => time(),
					"taskid" => $taskid
					)
				);
			}
		}

		if($import_files) {
			$files = $this->task_model->get_attached_files($task->ID);
			foreach($files->result() as $r) {
				// Check file is available to project
				if($r->projectid > 0) {
					if($r->projectid != $projectid) {
						continue;
					}
				}

				// Attach
				$this->task_model->add_file(array(
					"fileid" => $r->fileid,
					"taskid" => $taskid
					)
				);
			}
		}

		if($import_team) {
			// Get project team and add them
			$members = $this->team_model->get_members_for_project_roles($projectid, array("admin", "team"));
			foreach($members->result() as $r) {
				// Add member
				$this->task_model->add_task_member(array(
					"taskid" => $taskid,
					"userid" => $r->userid
					)
				);

				// Send notification of being added to the task
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->userid,
					"url" => "tasks/view/" . $taskid,
					"timestamp" => time(),
					"message" => lang("ctn_1056"). $task->name,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"email" => $r->email,
					"username" => $r->username,
					"email_notification" => $r->email_notification
					)
				);
			}
		}
			

		if($project->complete_sync) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1050") . $name . lang("ctn_1051") . $project->name,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "tasks/view_task/" . $taskid,
			"taskid" => $taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_81"));
		redirect(site_url("tasks"));
		
	}

	private function authorise_google_api($redirect_url) 
	{
		// Get Keys
		if(empty($this->settings->info->google_client_id) || 
			empty($this->settings->info->google_client_secret)) {
			$this->template->error(lang("error_31"));
		}

		require_once APPPATH . 'third_party/Google/autoload.php';
		$client = new Google_Client();
		$client->setApplicationName('framework');
		$client->setClientId($this->settings->info->google_client_id);
		$client->setClientSecret($this->settings->info->google_client_secret);
		$client->setRedirectUri(site_url($redirect_url));
		$client->setScopes(array(
			'https://www.googleapis.com/auth/plus.login',
			'https://www.googleapis.com/auth/plus.me', 
			'https://www.googleapis.com/auth/userinfo.email', 
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/calendar'
			)
		);

		$oauth2 = new Google_Auth_OAuth2($client);

		if (isset($_GET['code'])) {
			$client->authenticate($_GET['code']);
			$_SESSION['google_token'] = $client->getAccessToken();
			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
			return;
		}

		if (isset($_SESSION['google_token'])) {
			$client->setAccessToken($_SESSION['google_token']);
		}
		$provider = "google";

		if($client->isAccessTokenExpired()) {
		    $authUrl = $client->createAuthUrl();
		    redirect($authUrl);
		}

		if ($client->getAccessToken()) {
			// We now have access to google events.
			// Let's say hello
			return $client;
		} else {
			return null;
		}
	}
	
	public function import() 
    {
        set_time_limit(0);
        error_reporting(E_ALL);
		$this->load->model("task_model");
        try {
            if (isset($_POST["import"])) {
                $filename = $_FILES["file"]["tmp_name"];
				$mimes = array('application/vnd.ms-excel','text/csv');
                if (($_FILES["file"]["size"] > 0) && in_array($_FILES['file']['type'],$mimes)) {
                    $file = fopen($filename, "r");
                    $i = 0;
					$current_date = date("Y-m-d H:i:s");
                    //while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
					$header = fgetcsv($file);
					$pro = $this->session->userdata('pro_ls'); /* $record["projectid"] */
					/* $projectwise_status = $this->task_model->projectwise_status($pro); */
					$this->load->model("projects_model");
					$pro_namebyid = $this->projects_model->get_pro_namebyid($pro);	
					$project_name = $pro_namebyid->result_array();
					$pro_name = $project_name[0]['name'];
					$last_taskid = $this->projects_model->get_last_taskid();	
					foreach($last_taskid->result() as $last_val){
						$last_tskid = ($last_val->ID)+1;
					}
					
                    while (($importdata = fgetcsv($file)) !== FALSE) {
					$record = array_combine($header, $importdata);	
                       // if($i > 0) {
							$data = array(
								'name' => isset($record["name"]) ? $record["name"] : NULL, 
								'input' => isset($record["input"]) ? $record["input"] : NULL, 
								'output' => isset($record["output"]) ? $record["output"] : NULL, 
								'complexity' => isset($record["complexity"]) ? $record["complexity"] : NULL, 
								'unit' => isset($record["unit"]) ? $record["unit"] : NULL, 
								'isbn' => isset($record["isbn"]) ? $record["isbn"] : NULL, 
								'eisbn' => isset($record["eisbn"]) ? $record["eisbn"] : NULL, 
								'volume_issue' => isset($record["volume_issue"]) ? $record["volume_issue"] : NULL, 
								'unitPricePerPage' => isset($record["unitPricePerPage"]) ? round($record["unitPricePerPage"],2) : '0.00', 
								'unitPricePerArticle' => isset($record["unitPricePerArticle"]) ? round($record["unitPricePerArticle"],2) : '0.00', 
								'totalPages' => isset($record["totalPages"]) ? $record["totalPages"] : 0, 
								'totalArticles' => isset($record["totalArticles"]) ? $record["totalArticles"] : 0, 
								'totalPagesPrice' => isset($record["totalPagesPrice"]) ? round($record["totalPagesPrice"],2) : '0.00', 
								'totalArticlesPrice' => isset($record["totalArticlesPrice"]) ? round($record["totalArticlesPrice"],2) : '0.00', 
								'projectid' => isset($pro) ? $pro : NULL, 
								'start_date' => isset($record["start_date"]) ? strtotime($record["start_date"]) : NULL, 
								'due_date' => isset($record["due_date"]) ? strtotime($record["due_date"]) : NULL, 
								'status' => 1, 
								'userid' => $this->user->info->ID,
								'jobid' => strtolower(substr($pro_name, 0, 3)).$last_tskid,
								'vendor_assignment' => isset($record["vendor_assignment"]) ? $record["vendor_assignment"] : 0, 
								'vendor_name' => isset($record["vendor_name"]) ? $record["vendor_name"] : NULL, 
								'vendor_process_name' => isset($record["vendor_process_name"]) ? $record["vendor_process_name"] : NULL, 
								'vendor_unit' => isset($record["vendor_unit"]) ? $record["vendor_unit"] : NULL, 
								'vendor_unitPricePerPage' => isset($record["vendor_unitPricePerPage"]) ? round($record["vendor_unitPricePerPage"],2) : '0.00', 
								'vendor_unitPricePerArticle' => isset($record["vendor_unitPricePerArticle"]) ? round($record["vendor_unitPricePerArticle"],2) : '0.00', 
								'vendor_totalPages' => isset($record["vendor_totalPages"]) ? $record["vendor_totalPages"] : 0, 
								'vendor_totalArticles' => isset($record["vendor_totalArticles"]) ? $record["vendor_totalArticles"] : 0, 
								'vendor_totalPagesPrice' => isset($record["vendor_totalPagesPrice"]) ? round($record["vendor_totalPagesPrice"],2) : '0.00', 
								'vendor_totalArticlesPrice' => isset($record["vendor_totalArticlesPrice"]) ? round($record["vendor_totalArticlesPrice"],2) : '0.00', 
								'vendor_start_date' => isset($record["vendor_start_date"]) ? strtotime($record["vendor_start_date"]) : 0, 
								'vendor_due_date' => isset($record["vendor_due_date"]) ? strtotime($record["vendor_due_date"]) : 0, 
								'vendor_status' => 1 
								);
                            $insert = $this->task_model->add_task($data);		
		
							$this->task_model->add_task_member(array(
								"taskid" => $last_tskid,
								"userid" => $this->user->info->ID
								)
							);
                        // }
                       // $i++; 
					   $last_tskid++;
                    }

                    fclose($file);
					$this->session->set_flashdata('success', 'Your '. $_FILES['file']['name'] . ' uploaded successfully!');
                    redirect(site_url("tasks/index"));
                } else {
                    $this->session->set_flashdata('error', 'Problem in uploading the file!');
                    redirect(site_url("tasks/index"));
                }
            }
        } catch (Exception $e) {
            echo __LINE__ . '-----' . __FILE__ . '-----';
            echo "<pre>";
            print_r($e);
            log_message('error', $e);
            die; 
        }

    }
	
	public function upload_title() 
    {
		set_time_limit(0);
		error_reporting(E_ALL);  
		$this->load->model("task_model");
		try{
			if (isset($_POST["import"])) { 
				$path = 'uploads/';
				require_once APPPATH . "third_party\PHPExcel\Classes\PHPExcel.php";
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'xlsx|xls';
				$config['remove_spaces'] = TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);            
				if (!$this->upload->do_upload('file')) {
					$error = array('error' => $this->upload->display_errors());
				} else {
					$data = array('upload_data' => $this->upload->data());
				}
				if(empty($error)){
				  if (!empty($data['upload_data']['file_name'])) {
					$import_xls_file = $data['upload_data']['file_name'];
				} else {
					$import_xls_file = 0;
				}
				$inputFileName = $path . $import_xls_file;
				
				
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
				
				$pro = $this->session->userdata('pro_ls'); /* $record["projectid"] */
				/* $projectwise_status = $this->task_model->projectwise_status($pro); */
				$this->load->model("projects_model");
				$pro_namebyid = $this->projects_model->get_pro_namebyid($pro);	
				$project_name = $pro_namebyid->result_array();
				$pro_name = $project_name[0]['name'];
				$last_taskid = $this->projects_model->get_last_taskid();	
				foreach($last_taskid->result() as $last_val){
					$last_tskid = ($last_val->ID)+1;
				}
					
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                $flag = true;
                $i=0;
                foreach ($allDataInSheet as $value) {
					if($flag){
					$flag =false;
					continue;
					}
   
					$inserdata[$i]['name'] = isset($value['A']) ? $value['A'] : NULL;
					$inserdata[$i]['isbn'] = isset($value['B']) ? $value['B'] : NULL;
					$inserdata[$i]['eisbn'] = isset($value['C']) ? $value['C'] : NULL;
					$inserdata[$i]['volume_issue'] = isset($value['D']) ? $value['D'] : NULL;
					$inserdata[$i]['input'] = isset($value['E']) ? $value['E'] : NULL;
					$inserdata[$i]['output'] = isset($value['F']) ? $value['F'] : NULL;
					$inserdata[$i]['projectid'] = isset($pro) ? $pro : NULL;
					$inserdata[$i]['unit'] = isset($value['G']) ? $value['G'] : NULL;
					$inserdata[$i]['complexity'] = isset($value['H']) ? $value['H'] : NULL;
					$inserdata[$i]['unitPricePerPage'] =  isset($value['I']) ? round($value['I'],2) : '0.00';
					$inserdata[$i]['unitPricePerArticle'] = isset($value['J']) ? round($value['J'],2) : '0.00';
					$inserdata[$i]['totalPages'] = isset($value['K']) ? $value['K'] : 0;
					$inserdata[$i]['totalArticles'] = isset($value['L']) ? $value['L'] : 0;
					$inserdata[$i]['totalPagesPrice'] = isset($value['M']) ? round($value['M'],2) : '0.00';
					$inserdata[$i]['totalArticlesPrice'] = isset($value['N']) ? round($value['N'],2) : '0.00';
					$inserdata[$i]['start_date'] = isset($value['O']) ? strtotime($value['O']) : NULL;
					$inserdata[$i]['due_date'] = isset($value['P']) ? strtotime($value['P']) : NULL;
					$inserdata[$i]['status'] = 1;
					$inserdata[$i]['userid'] = $this->user->info->ID;
					$inserdata[$i]['jobid'] = strtolower(substr($pro_name, 0, 3)).$last_tskid;  			
					
					$this->task_model->add_task_bulk_member(array(
								"taskid" => $last_tskid,
								"userid" => $this->user->info->ID
								)
							);  
					$i++;
					$last_tskid++;	
					
				}
				$result = $this->task_model->importtasksdata($inserdata);
				if($result){
					$this->session->set_flashdata('success', 'Imported successfully');
					redirect(site_url("tasks/index"));
					}else{
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("tasks/index"));
					}
				} else {
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("tasks/index"));
				}
			}
		}
		catch (Exception $e) {
			echo __LINE__ . '-----' . __FILE__ . '-----';
			echo "<pre>";
			print_r($e);
			log_message('error', $e);
			/* die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' .$e->getMessage());  */ 
			die; 
		}
    }
	
	public function check_task_details() 
	{
		$name = $this->common->nohtml($this->input->get("taskname", true));
		$isbn = $this->common->nohtml($this->input->get("isbn", true));
		$eisbn = $this->common->nohtml($this->input->get("eisbn", true));
		$this->load->model("task_model");
		$field_errors = array();

		if (strlen($name) < 3) {
			$field_errors['name'] = lang("error_14");
		}

		if (!preg_match("/^[a-z0-9_]+$/i", $name)) {
			$field_errors['name'] = lang("error_15");
		}

		if (!$this->task_model->check_task_details_is_free($name,$isbn,$eisbn)) {
			$field_errors['name'] = "$name " . lang("ctn_243");
		}

		
		if(empty($field_errors)) {
			echo json_encode(array("success" => 1));
		} else {
			echo json_encode(array("field_errors" => 1,"fieldErrors" => $field_errors));
		}

		exit();
	}
	
	public function export() 
	{
		$this->load->model("task_model");
		
		$filename = "title_export_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$pro = $this->session->userdata('pro_ls');
		$filter_start = $this->session->userdata('filter_start');
		$filter_end = $this->session->userdata('filter_end');
		$tasklistsexport = $this->task_model->tasklistsexport($pro,$this->user->info->ID,$filter_start,$filter_end);
		/* print_r($tasklistsexport);
		die; */
		
		if ($tasklistsexport->num_rows() > 0) {
			foreach($tasklistsexport->result_array() as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		}
		exit;
	}
	
	public function ajaxgetunitprice() 
	{
		$complexity = $this->common->nohtml($this->input->get("complexity", true));
		$unit = $this->common->nohtml($this->input->get("unit", true));
		if(isset($_GET['geofacets_unit'])) {
			$geofacets_unit = $this->common->nohtml($this->input->get("geofacets_unit", true));
		}
		$projectid = $this->common->nohtml($this->input->get("projectid", true));
		$this->load->model("task_model");
		$getfrmpro = $this->task_model->getfromprojects($projectid);
		$frmpro = $getfrmpro->result_array();
		$geofacets_process_name = '';
		foreach($frmpro as $pro){
			$publisher = $pro['publisher'];
			$process_name = $pro['process_name'];
			if(isset($pro['geofacets_process_name'])) {
				$geofacets_process_name = $pro['geofacets_process_name'];
				$geofacets_pdfType = $pro['geofacets_pdfType']; 
			}
			$stage = $pro['stage'];
			$pdfType = $pro['pdfType']; 
		}
		if($process_name == "Geofacets Table conversion" || $process_name == "Geofacets Figure conversion"){
			$geo_tbl_editable_unitpriceresult = $this->task_model->getunitprice($complexity,'table',$publisher,'Geofacets Table conversion',$stage,'Editable');
			$geo_tbl_scanned_unitpriceresult = $this->task_model->getunitprice($complexity,'table',$publisher,'Geofacets Table conversion',$stage,'Scanned');
			$data = array();  
			$unitprice_geo_tbl_editable = $geo_tbl_editable_unitpriceresult->result_array();
			/* echo $stage; 
			print_r($unitprice_geo_tbl_editable);  */
			foreach($unitprice_geo_tbl_editable as $geo_tbl_editable_price){
				$data['unitprice_geo_tbl_editable'] = $geo_tbl_editable_price['unitprice'];
			}	
			$unitprice_geo_tbl_scanned = $geo_tbl_scanned_unitpriceresult->result_array();
			foreach($unitprice_geo_tbl_scanned as $geo_tbl_scanned_price){
				$data['unitprice_geo_tbl_scanned'] = $geo_tbl_scanned_price['unitprice'];
			}			
			
			$geo_fig_editable_unitpriceresult = $this->task_model->getunitprice($complexity,'figure',$publisher,'Geofacets Figure conversion',$stage,'Editable');
			$geo_fig_scanned_unitpriceresult = $this->task_model->getunitprice($complexity,'figure',$publisher,'Geofacets Figure conversion',$stage,'Scanned');
			
			$unitprice_geo_fig_editable = $geo_fig_editable_unitpriceresult->result_array();			
			$unitprice_geo_fig_scanned = $geo_fig_scanned_unitpriceresult->result_array();
			foreach($unitprice_geo_fig_editable as $geo_fig_editable_price){
				$data['unitprice_geo_fig_editable'] = $geo_fig_editable_price['unitprice'];
			}	
			foreach($unitprice_geo_fig_scanned as $geo_fig_scanned_price){
				$data['unitprice_geo_fig_scanned'] = $geo_fig_scanned_price['unitprice'];
			}
			if($process_name == "Geofacets Table conversion"){
				$data['unitval'] = 'table';
			}
			else{
				$data['unitval'] = 'figure';
			}
		}
		else{
			$unitpriceresult = $this->task_model->getunitprice($complexity,$unit,$publisher,$process_name,$stage,$pdfType);
			$unitprice = $unitpriceresult->result_array();
			$data = array();  
			$data['unitval'] = $unit;			
			foreach($unitprice as $price){
				$data['unitprice'] = $price['unitprice'];
			} 	
		}
		
		/*
			if($process_name == ''){
			$unitpriceproresult = $this->task_model->getunitprice($complexity,$unit,$publisher,$geofacets_process_name,$stage,$pdfType);
			}
			if($geofacets_pdfType != '' ){
			$unitpricepdfresult = $this->task_model->getunitprice($complexity,$unit,$publisher,$process_name,$stage,$geofacets_pdfType);
			}
			if($geofacets_process_name != '' &&	$geofacets_pdfType != '' ){
			$unitpricepropdfresult = $this->task_model->getunitprice($complexity,$unit,$publisher,$geofacets_process_name,$stage,$geofacets_pdfType);
			} 
		*/
		echo json_encode($data);
		exit();	 
	}
	
	/* public function deleteselectedTasks()
    {
		$tasks = $this->input->post('chk_id');
		if(isset($tasks))
		{
			$result = $this->task_model->deleteselectedTasks($tasks);
		}
		
		if ($result > 0) {
				echo(json_encode(array('status'=>TRUE))); 
				$msg = "Deleted Successfully";
				redirect('tasks/index/?msg='.$msg);
			}
			else { 
				echo(json_encode(array('status'=>FALSE)));
				redirect('tasks/index');				
			}
	
    } */
	
	public function deleteselectedTasks($hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "task_manage", "task_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$tasks = $this->input->post('chk_id');
		$objective = $this->task_model->get_task_objective_mutiple($tasks);
		
		if($objective->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$objective = $objective->row();
		
		$project = $this->projects_model->get_project($objective->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Check
		// Permissions
		$this->common->check_permissions(
			lang("error_179"), 
			array("admin", "project_admin", "task_manage"), // User Roles
			array("admin", "task"),  // Team Roles
			$objective->projectid
		);

		$this->task_model->deleteselectedTasks($tasks);
		$this->task_model->delete_objective_members_multiple($tasks);

		if($objective->complete_sync) {
			// Count total objectives complete
			$objectives = $this->task_model->get_task_objectives($objective->taskid);
			$complete =0;
			$total = $objectives->num_rows();
			if($total > 0) {
				foreach($objectives->result() as $r) {
					if($r->complete)
					{
						$complete++;
					}
				}
				// Get percentage
				$complete = @intval(($complete/$total) * 100);
			}
			if($complete >= 100) {
				$status = 3;
			} else {
				if($objective->status == 3) {
					$status = 2;
				} else {
					$status = $objective->status;
				}
			}

			if($objective->status != $status) {
				if($status == 1) {
					$statusmsg = lang("ctn_830");
				} elseif($status == 2) {
					$statusmsg = lang("ctn_831");
				} elseif($status == 3) {
					$statusmsg = lang("ctn_832");
				} elseif($status == 4) {
					$statusmsg = lang("ctn_833");
				} elseif($status == 5) {
					$statusmsg = lang("ctn_834");
				}

				if(!$objective->template) {
					// Notify
					$this->notifiy_task_members(
						$objective->taskid, 
						lang("ctn_1257") . "[".$objective->name."] ".lang("ctn_1258")." 
						 <strong>" . $statusmsg ."</strong>", 
						$this->user->info->ID
					);
				}
			}
			$this->task_model->update_task($objective->taskid, array(
				"complete" => $complete,
				"status" => $status
				)
			);
		}

		if($project->complete_sync && !$objective->template) {
			// Get all tasks
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
			$this->projects_model->update_project($project->ID, array(
				"complete" => $complete
				)
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1066") . " <b>".$objective->title.
			"</b>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $objective->projectid,
			"url" => "tasks/view/" . $objective->taskid,
			"taskid" => $objective->taskid
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_88"));
		redirect(site_url("tasks/view/" . $objective->taskid));
	}
	
	public function ajaxgetdetails() 
	{
		$projectid = $this->common->nohtml($this->input->get("projectid", true));
		$this->load->model("task_model");
		$getfrmpro = $this->task_model->getfromprojects($projectid);
		$frmpro = $getfrmpro->result_array();
		foreach($frmpro as $pro){
			$publisher = $pro['publisher'];
			$process_name = $pro['process_name']; 
		}
		if($process_name == "Geofacets Table conversion" || $process_name == "Geofacets Figure conversion"){
			$data = array(); 
			$data['publisher'] = $publisher;
			$data['process_name'] = $process_name;
			$data['projectid'] = $projectid;
		}
		echo json_encode($data);
		exit();	 
	}
}

?>