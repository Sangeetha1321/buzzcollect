<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("reports_model");
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
			array("admin", "project_admin", "reports_manage", 
			"reports_worker"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index($type = 0) 
	{
		$type = intval($type);
		$this->template->loadData("activeLink", 
			array("reports" => array("general" => 1)));


		if(!isset($_POST['start_date'])) {
			$range1 = date($this->settings->info->date_picker_format, time() - (3600*24*7));
			$range2 = date($this->settings->info->date_picker_format);
		} else {
			$range1 = $this->common->nohtml($this->input->post("start_date"));
			$range2 = $this->common->nohtml($this->input->post("end_date"));
		}

		$dates = $this->common->getDatesFromRange($range1, $range2, $this->settings->info->date_picker_format);
		$results = array();
		$results2 = array();

		if($type == 0) {
			foreach($dates as $date) 
			{
				$count = $this->reports_model->get_tickets_for_day($date['db']);
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			}
		}
		if($type == 1) {
			foreach($dates as $date) 
			{
				$count = $this->reports_model->get_tickets_for_day_closed($date['db']);
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			}
		}
		if($type == 2) {
			foreach($dates as $date) 
			{
				$count = $this->reports_model->get_tickets_for_day($date['db']);
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			}
			foreach($dates as $date) 
			{
				$count = $this->reports_model->get_tickets_for_day_closed($date['db']);
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			}
		}

		if($results) {
			$dates = $results;
		} elseif($results2) {
			$dates = $results2;
		}

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);

		
		$this->template->loadContent("reports/index.php", array(
			"results" => $results,
			"results2" => $results2,
			"dates" => $dates,
			"type" => $type,
			"range1" => $range1,
			"range2" => $range2
			)
		);
	}

	public function time($projectid = 0) 
	{
		if(!$this->settings->info->enable_time) {
			$this->template->error(lang("ctn_1411"));
		}
		$projectid = intval($projectid);
		$all_timers = 0;
		if($this->common->has_permissions(array("admin", "project_admin", "reports_manage"), $this->user)) {
			$all_timers = 1;
		}

		$username = $this->common->nohtml($this->input->post("username"));
		$userid = 0;
		$user = null;
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_52"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		$project = null;
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("error_147"), 
				array("admin", "project_admin", "reports_manage"), // User Roles
				array("admin", "reports"),  // Team Roles
				$projectid
			);
		}

		$this->template->loadData("activeLink", 
			array("reports" => array("time" => 1)));

		if(!isset($_POST['start_date'])) {
			$range1 = date($this->settings->info->date_picker_format, time() - (3600*24*7));
			$range2 = date($this->settings->info->date_picker_format);
		} else {
			$range1 = $this->common->nohtml($this->input->post("start_date"));
			$range2 = $this->common->nohtml($this->input->post("end_date"));
		}

		$dates = $this->common->getDatesFromRange($range1, $range2, $this->settings->info->date_picker_format, "Y-m-d");
		$results = array();
		$results2 = array();

		$projects = array();
		$total_earnt = 0;
		$total_hours = 0;
		$total_timers = 0;
		$total_time = 0;

		foreach($dates as $date) 
		{
			if($all_timers) {
				$time = $this->reports_model->count_hours_date($date['db'], $projectid, $userid);
			} else {
				$time = $this->reports_model->count_hours_date_projects($date['db'], $projectid, $this->user->info->ID, $userid);
			}
			if($time->num_rows() > 0) {
				$user_time = 0;
				$hours = 0;
				foreach($time->result() as $r) {
					$utime = $r->time;
					$user_time += $utime;

					$hour = ($r->time/3600);
					$hours += $hour;

					if(isset($projects[$r->projectid])) {
						$projects[$r->projectid]['times'] += 1;
						$projects[$r->projectid]['hours'] += $utime;
					} else {
						if(!isset($r->name)) {
							$r->name = lang("ctn_1105");
						}
						$projects[$r->projectid]['title'] = $r->name;
						$projects[$r->projectid]['times'] = 1;
						$projects[$r->projectid]['hours'] = $utime;
					}
					$total_time += $utime;
					$total_timers++;

					$earnt = $hour * $r->rate;
					$total_hours += $hour;
					$total_earnt += $earnt;
				}

				$hours = round($hours, 2);

				$results[] = array(
				"date" => $date['display'],
				"count" => $user_time
				);
			} else {
				$results[] = array(
				"date" => $date['display'],
				"count" => 0
				);
			}
		}

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "reports_manage"), $this->user
			)
		) {
			$projects_r = $this->projects_model->get_all_active_projects();
		} else {
			$projects_r = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.reports = 1)");
		}


		$this->template->loadContent("reports/time.php", array(
			"results" => $results,
			"dates" => $results,
			"total_hours" => $total_hours,
			"total_earnt" => $total_earnt,
			"total_timers" => $total_timers,
			"projects_r" => $projects_r,
			"projects" => $projects,
			"project" => $project,
			"user" => $user,
			"projectid" => $projectid,
			"range1" => $range1,
			"range2" => $range2,
			"total_time" => $this->common->get_time_string($this->common->convert_time_raw($total_time))
			)
		);
	}

	public function finance($projectid = 0) 
	{
		$projectid = intval($projectid);
		$all_timers = 0;
		if($this->common->has_permissions(array("admin", "project_admin", "reports_manage"), $this->user)) {
			$all_timers = 1;
		}

		$this->template->loadData("activeLink", 
			array("reports" => array("finance" => 1)));
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);

		$project = null;
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("error_147"), 
				array("admin", "project_admin", "reports_manage"), // User Roles
				array("admin", "reports"),  // Team Roles
				$projectid
			);
		}

		if(!isset($_POST['start_date'])) {
			$range1 = date($this->settings->info->date_picker_format, time() - (3600*24*7));
			$range2 = date($this->settings->info->date_picker_format);
		} else {
			$range1 = $this->common->nohtml($this->input->post("start_date"));
			$range2 = $this->common->nohtml($this->input->post("end_date"));
		}

		$dates = $this->common->getDatesFromRange($range1, $range2, $this->settings->info->date_picker_format, "Y-m-d");
		$results = array();
		$results2 = array();

		$total_revenue = 0;
		$total_expense = 0;

		foreach($dates as $date) 
		{
			if($all_timers) {
				// Revenue
				$count = $this->reports_model->get_finance_sum($date['db'], $projectid, 1);
				$total_revenue += $count;
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
				// Exepenses
				$count = $this->reports_model->get_finance_sum($date['db'], $projectid, 0);
				$total_expense += $count;
				$count *= -1;
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			} else {
				// Revenue
				$count = $this->reports_model->get_finance_sum_projects($date['db'], 
					$this->user->info->ID, $projectid, 1);
				$total_revenue += $count;
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
				// Exepenses
				$count = $this->reports_model->get_finance_sum_projects($date['db'], 
					$this->user->info->ID, $projectid, 0);
				$total_expense += $count;
				$count *= -1;
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					); 
			}
		}

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "reports_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.reports = 1)");
		}

		$this->template->loadContent("reports/finance.php", array(
			"projects" => $projects,
			"projectid" => $projectid,
			"project" => $project,
			"results" => $results,
			"results2" => $results2,
			"dates" => $results,
			"total_revenue" => $total_revenue,
			"total_expense" => $total_expense,
			"range1" => $range1,
			"range2" => $range2
			)
		);
	}

	public function invoices($projectid = 0) 
	{
		$projectid = intval($projectid);
		$all_timers = 0;
		if($this->common->has_permissions(array("admin", "project_admin", "reports_manage"), $this->user)) {
			$all_timers = 1;
		}

		$this->template->loadData("activeLink", 
			array("reports" => array("invoices" => 1)));
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);

		$project = null;
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("error_147"), 
				array("admin", "project_admin", "reports_manage"), // User Roles
				array("admin", "reports"),  // Team Roles
				$projectid
			);
		}

		if(!isset($_POST['start_date'])) {
			$range1 = date($this->settings->info->date_picker_format, time() - (3600*24*7));
			$range2 = date($this->settings->info->date_picker_format);
		} else {
			$range1 = $this->common->nohtml($this->input->post("start_date"));
			$range2 = $this->common->nohtml($this->input->post("end_date"));
		}

		$dates = $this->common->getDatesFromRange($range1, $range2, $this->settings->info->date_picker_format, "Y-m-d");
		$results = array();
		$results2 = array();

		$paid = 0;
		$unpaid = 0;

		foreach($dates as $date) 
		{
			if($all_timers) {
				// paid invoices
				$count = $this->reports_model->get_invoice_sum($date['db'], $projectid, 1);
				$paid += $count;
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
				// Exepenses
				$count = $this->reports_model->get_invoice_sum($date['db'], $projectid, 0);
				$unpaid += $count;
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			} else {
				// paid invoices
				$count = $this->reports_model->get_invoice_sum_projects($date['db'], $this->user->info->ID, $projectid, 1);
				$paid += $count;
				$results[] = array(
					"date" => $date['display'],
					"count" => $count
					);
				// Exepenses
				$count = $this->reports_model->get_invoice_sum_projects($date['db'], $this->user->info->ID, $projectid, 0);
				$unpaid += $count;
				$results2[] = array(
					"date" => $date['display'],
					"count" => $count
					);
			}
		}



		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "reports_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.reports = 1)");
		}

		$this->template->loadContent("reports/invoices.php", array(
			"projects" => $projects,
			"projectid" => $projectid,
			"project" => $project,
			"results" => $results,
			"results2" => $results2,
			"dates" => $results,
			"paid" => $paid,
			"unpaid" => $unpaid,
			"range1" => $range1,
			"range2" => $range2
			)
		);
	}

}

?>