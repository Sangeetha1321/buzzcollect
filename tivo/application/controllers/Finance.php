<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("finance_model");
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
		$this->common->check_permissions(
			lang("error_104"), 
			array("admin", "project_admin", "finance_manage", 
			"finance_worker"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index($projectid=0) 
	{
		$this->template->loadData("activeLink", 
			array("finance" => array("general" => 1)));

		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "finance_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.finance = 1)");
		}

		$this->template->loadContent("finance/index.php", array(
			"page" => "index",
			"projects" => $projects,
			"projectid" => $projectid
			)
		);
	}

	public function finance_page($page = "index", $projectid = 0) 
	{
		$projectid = intval($projectid);

		$this->load->library("datatables");

		$this->datatables->set_default_order("finance.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"finance.title" => 0
				 ),
				 1 => array(
				 	"finance.amount" => 0
				 ),
				 2 => array(
				 	"projects.name" => 0
				 ),
				 3 => array(
				 	"finance_categories.name" => 0
				 ),
				 5 => array(
				 	"finance.timestamp" => 0
				 )
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->finance_model
					->get_finances_total($this->user->info->ID, $projectid)
			);
			$finances = $this->finance_model->get_finances(
				$this->user->info->ID, $projectid, $this->datatables);
		} elseif($page == "all") {
			$this->common->check_permissions(
				lang("error_105"), 
				array("admin", "project_admin", "finance_manage"), // User Roles
				array(),
				0  // Team Roles
			);
			$this->datatables->set_total_rows(
				$this->finance_model
					->get_all_finances_total($projectid)
			);
			$finances = $this->finance_model->get_all_finances($projectid, 
				$this->datatables);
		}

		foreach($finances->result() as $r) {
			if($r->amount > 0) {
				$fcl = "finance-positive";
			} elseif($r->amount < 0) {
				$fcl = "finance-negative";
			} else {
				$fcl = "";
			}
			$amount = '<span class="'.$fcl.'">'.number_format($r->amount,2).'</span>';

			$this->datatables->data[] = array(
				$r->title,
				$amount,
				$r->projectname,
				$r->catname,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="'.site_url("finance/edit_finance/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("finance/delete_finance/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function all($projectid = 0) 
	{
		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		$this->common->check_permissions(
			lang("error_105"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(),
			0  // Team Roles
		);
		$this->template->loadData("activeLink", 
			array("finance" => array("all" => 1)));

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "finance_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.finance = 1)");
		}

		$this->template->loadContent("finance/index.php", array(
			"page" => "all",
			"projects" => $projects,
			"projectid" => $projectid
			)
		);
	}

	public function add_finance() 
	{
		$this->template->loadData("activeLink", 
			array("finance" => array("general" => 1)));
		$categories = $this->finance_model->get_categories();

		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "finance_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.finance = 1)");
		}

		$this->template->loadContent("finance/add.php", array(
			"categories" => $categories,
			"projects" => $projects
			)
		);
	}

	public function add_finance_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$notes = $this->lib_filter->go($this->input->post("notes"));
		$catid = intval($this->input->post("catid"));
		$projectid = intval($this->input->post("projectid"));
		$amount = floatval($this->input->post("amount"));
		$date = $this->common->nohtml($this->input->post("date"));

		$days = intval($this->input->post("days"));
		$type = intval($this->input->post("type"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$status = intval($this->input->post("status"));
		
		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		if($catid > 0) {
			$cat = $this->finance_model->get_category($catid);
			if($cat->num_rows() == 0) {
				$this->template->error(lang("error_107"));
			}
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		if(!empty($date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}
		

		$this->common->check_permissions(
			lang("error_108"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array("admin", "finance"),  // Team Roles
			$projectid
		);

		// Add
		$this->finance_model->add_finance(array(
			"title" => $title,
			"notes" => $notes,
			"categoryid" => $catid,
			"projectid" => $projectid,
			"userid" => $this->user->info->ID,
			"amount" => $amount,
			"timestamp" => $sd_timestamp,
			"month" => date("n",$sd_timestamp),
			"year" => date("Y",$sd_timestamp),
			"time_date" => date("Y-m-d",$sd_timestamp)
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1029") . $title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "finance"
			)
		);

		if($days > 0) {
			// reoccurring

			if($type == 1) {
				$days = $days;
			} elseif($type == 2) {
				$days = $days * 7;
			} elseif($type == 3) {
				$days = $days * 30;
			} elseif($type == 4) {
				$days = $days * 365;
			}

			if(!empty($start_date)) {
				$sdt = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
				$sdt_timestamp = $sdt->getTimestamp();
			} else {
				$this->template->error(lang("error_121"));
			}

			if(!empty($end_date)) {
				$ed = DateTime::createFromFormat($this->settings->info->date_picker_format, $end_date);
				$ed_timestamp = $ed->getTimestamp();
			} else {
				$ed_timestamp = 0;
			}

			if($status < 0 || $status > 2) {
				$this->template->error(lang("error_122"));
			}


			// Add
			$this->finance_model->add_finance(array(
				"title" => $title,
				"notes" => $notes,
				"categoryid" => $catid,
				"projectid" => $projectid,
				"userid" => $this->user->info->ID,
				"amount" => $amount,
				"timestamp" => $sd_timestamp,
				"month" => date("n",$sd_timestamp),
				"year" => date("Y",$sd_timestamp),
				"time_date" => date("Y-m-d",$sd_timestamp),
				"reoccur" => 1,
				"reoccur_days" => $days,
				"start_date" => $sdt_timestamp,
				"end_date" => $ed_timestamp,
				"reoccur_status" => $status,
				"last_occurence" => time()
				)
			);
		}

		$this->session->set_flashdata("globalmsg", 
			lang("success_48"));
		redirect(site_url("finance"));
	}

	public function edit_finance($id) 
	{
		$id = intval($id);
		$finance = $this->finance_model->get_finance($id);
		if($finance->num_rows() == 0) {
			$this->template->error(lang("error_109"));
		}
		$finance = $finance->row();

		$this->template->loadData("activeLink", 
			array("finance" => array("general" => 1)));
		$categories = $this->finance_model->get_categories();

		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "finance_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.finance = 1)");
		}

		$this->template->loadContent("finance/edit_finance.php", array(
			"categories" => $categories,
			"projects" => $projects,
			"finance" => $finance
			)
		);
	}

	public function edit_finance_pro($id) 
	{
		$id = intval($id);
		$finance = $this->finance_model->get_finance($id);
		if($finance->num_rows() == 0) {
			$this->template->error(lang("error_109"));
		}
		$finance = $finance->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$notes = $this->lib_filter->go($this->input->post("notes"));
		$catid = intval($this->input->post("catid"));
		$projectid = intval($this->input->post("projectid"));
		$amount = floatval($this->input->post("amount"));
		$date = $this->common->nohtml($this->input->post("date"));

		$days = intval($this->input->post("days"));
		$type = intval($this->input->post("type"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$status = intval($this->input->post("status"));

		if(!empty($date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}
		
		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		if($catid > 0) {
			$cat = $this->finance_model->get_category($catid);
			if($cat->num_rows() == 0) {
				$this->template->error(lang("error_107"));
			}
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("error_110"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array("admin", "finance"),  // Team Roles
			$projectid
		);


		if(!empty($start_date)) {
			$sdt = DateTime::createFromFormat($this->settings->info->date_picker_format, $start_date);
			$sdt_timestamp = $sdt->getTimestamp();
		} else {
			$this->template->error(lang("error_121"));
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->date_picker_format, $end_date);
			$ed_timestamp = $ed->getTimestamp();
		} else {
			$ed_timestamp = 0;
		}

		if($status < 0 || $status > 2) {
			$this->template->error(lang("error_122"));
		}

		$reoccur = 0;
		if($days > 0) {
			$reoccur = 1;
		}

		if($type == 1) {
				$days = $days;
			} elseif($type == 2) {
				$days = $days * 7;
			} elseif($type == 3) {
				$days = $days * 30;
			} elseif($type == 4) {
				$days = $days * 365;
			}

			// update
			$this->finance_model->update_finance($id, array(
				"title" => $title,
				"notes" => $notes,
				"categoryid" => $catid,
				"projectid" => $projectid,
				"amount" => $amount,
				"timestamp" => $sd_timestamp,
				"month" => date("n",$sd_timestamp),
				"year" => date("Y",$sd_timestamp),
				"time_date" => date("Y-m-d",$sd_timestamp),
				"reoccur" => $reoccur,
				"reoccur_days" => $days,
				"start_date" => $sdt_timestamp,
				"end_date" => $ed_timestamp,
				"reoccur_status" => $status,
				"last_occurence" => time()
				)
			);
		
		

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1030"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "finance/edit_finance/" . $id
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_49"));
		redirect(site_url("finance"));
	}

	public function delete_finance($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$finance = $this->finance_model->get_finance($id);
		if($finance->num_rows() == 0) {
			$this->template->error(lang("error_109"));
		}
		$finance = $finance->row();
		if($finance->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_230"), 
				array("admin", "project_admin", "finance_manage"), // User Roles
				array("admin"),  // Team Roles
				$finance->projectid
			);
		}

		$this->finance_model->delete_finance($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" =>  lang("ctn_1031") . $finance->title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $finance->projectid,
			"url" => "finance"
			)
		);
		$this->session->set_flashdata("globalmsg", 
			lang("success_50"));
		redirect(site_url("finance"));
	}

	public function categories() 
	{
		$this->template->loadData("activeLink", 
			array("finance" => array("cats" => 1)));
		$this->common->check_permissions(
			lang("error_111"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(), // Team Roles
			0  
		);
		
		$categories = $this->finance_model->get_categories();
		$this->template->loadContent("finance/categories.php", array(
			"categories" => $categories
			)
		);
	}

	public function add_category_pro() 
	{
		$this->common->check_permissions(
			lang("error_111"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(), // Team Roles
			0  
		);

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_112"));
		}

		$this->finance_model->add_category(array(
			"name" => $name,
			"description" => $desc
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1032") . $name ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "finance/categories"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_51"));
		redirect(site_url("finance/categories"));
	}

	public function delete_category($id, $hash) 
	{
		$this->common->check_permissions(
			lang("error_111"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(), // Team Roles
			0  
		);
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->finance_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_107"));
		}
		$category = $category->row();

		$this->finance_model->delete_category($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1033") . $category->name ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "finance/categories"
			)
		);
		$this->session->set_flashdata("globalmsg", 
			lang("success_52"));
		redirect(site_url("finance/categories"));
	}

	public function edit_category($id) 
	{
		$this->common->check_permissions(
			lang("error_111"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(), // Team Roles
			0  
		);
		
		$this->template->loadData("activeLink", 
			array("finance" => array("categories" => 1)));
		$id = intval($id);
		$category = $this->finance_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_107"));
		}
		$category = $category->row();

		$this->template->loadContent("finance/edit_category.php", array(
			"category" => $category
			)
		);
	}

	public function edit_category_pro($id) 
	{
		$this->common->check_permissions(
			lang("error_111"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(), // Team Roles
			0  
		);
		$id = intval($id);
		$category = $this->finance_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_107"));
		}
		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_112"));
		}

		$this->finance_model->update_category($id, array(
			"name" => $name,
			"description" => $desc
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1034") . $name ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "finance/categories"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_53"));
		redirect(site_url("finance/categories"));
	}

	public function reoccuring($projectid = 0) 
	{
		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		$this->common->check_permissions(
			lang("error_105"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(),
			0  // Team Roles
		);
		$this->template->loadData("activeLink", 
			array("finance" => array("reoccuring" => 1)));

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "finance_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.finance = 1)");
		}

		$this->template->loadContent("finance/reoccuring.php", array(
			"page" => "reoccuring",
			"projects" => $projects,
			"projectid" => $projectid
			)
		);
	}

	public function reoccur_finance_page($projectid=0) 
	{
		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		$this->common->check_permissions(
			lang("error_105"), 
			array("admin", "project_admin", "finance_manage"), // User Roles
			array(),
			0  // Team Roles
		);
		$this->template->loadData("activeLink", 
			array("finance" => array("reoccuring" => 1)));

		$this->load->library("datatables");

		$this->datatables->set_default_order("finance.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"finance.title" => 0
				 ),
				 1 => array(
				 	"finance.amount" => 0
				 ),
				 2 => array(
				 	"projects.name" => 0
				 ),
				 3 => array(
				 	"finance.reoccur_status" => 0
				 ),
				 4 => array(
				 	"finance.last_occurence" => 0
				 ),
				 5 => array(
				 	"finance.end_date" => 0
				 )
			)
		);

		
			$this->datatables->set_total_rows(
				$this->finance_model
					->get_reoccur_finances_total($projectid)
			);
			$finances = $this->finance_model->get_reoccur_finances($projectid, 
				$this->datatables);
		

		foreach($finances->result() as $r) {
			if($r->amount > 0) {
				$fcl = "finance-positive";
			} elseif($r->amount < 0) {
				$fcl = "finance-negative";
			} else {
				$fcl = "";
			}

			if($r->reoccur_status == 0) {
				$status = lang("ctn_647");
			} elseif($r->reoccur_status == 1) {
				$status = lang("ctn_648");
			} elseif($r->reoccur_status == 2) {
				$status = lang("ctn_649");
			}

			$amount = '<span class="'.$fcl.'">'.number_format($r->amount,2).'</span>';

			if($r->end_date > 0) {
				$end_date = date($this->settings->info->date_format, $r->end_date);
			} else {
				$end_date = lang("ctn_1569");
			}

			$this->datatables->data[] = array(
				$r->title,
				$amount,
				$r->projectname,
				$status,
				date($this->settings->info->date_format, $r->last_occurence),
				$end_date,
				'<a href="'.site_url("finance/edit_finance/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("finance/delete_finance/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

}

?>