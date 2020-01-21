<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->template->loadData("activeLink", 
			array("home" => array("general" => 1)));
		$this->load->model("user_model");
		$this->load->model("home_model");
		$this->load->model("task_model");
		$this->load->model("notes_model");
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
	}

	public function index()
	{
		$this->load->model("projects_model");
		$this->load->model("time_model");
		$this->load->model("finance_model");
		$this->load->model("tickets_model");
		$this->load->model("team_model");
		$this->load->model("invoices_model");
		$this->load->model("services_model");
		$this->load->model("machine_model");
		$this->load->model("file_model");
		
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>
			<script type="text/javascript" src="'
			.base_url().'scripts/libraries/jquery.animateNumber.min.js" /></script>'
		);

		$stats = $this->home_model->get_home_stats_user($this->user->info->ID);
		if($stats->num_rows() == 0) {
			$stats = $this->get_fresh_results($stats);
			$this->home_model->add_home_stats_user($this->user->info->ID, $stats);
		} else {
			$stats = $stats->row();
			$stats->time_projects = unserialize($stats->time_projects);
			if($stats->timestamp < time() - $this->settings->info->cache_time) {
				$stats = $this->get_fresh_results($stats);
				// Update Row
				$this->home_model->update_home_stats_user($this->user->info->ID, $stats);
			}
		}

		$months = array();
		$total_revenue = 0;
		$current_month = 12;
		$current_year = date("Y");
		// First month
		for($i=11;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$current_year);
			$count = $this->finance_model->get_sum_for_month($this->user->info->ID, $new_month, $current_year,1);
			$total_revenue += $count;
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}
		$income = $months;


		$months = array();
		$current_month = 12;
		$current_year = date("Y");
		$total_expense = 0;
		// First month
		for($i=11;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			
			
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$current_year);
			$count = $this->finance_model->get_sum_for_month($this->user->info->ID, $new_month, $current_year,0);
			$total_expense += $count;
			$count *= -1;
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}
		$expense = $months;

		$profit = $total_revenue + $total_expense;

		$tickets = $this->tickets_model->get_tickets_user_fp($this->user->info->ID, 0,0,0,0,3);

		$activity = $this->team_model->get_all_user_log($this->user->info->ID,0,3);

		$invoices = $this->invoices_model->get_invoices_fp(0, 0, 0, 3);

		$notifications = $this->user_model->get_notifications_all_fp($this->user->info->ID, 0,3);

		// If user is client, let's load their data
		$client_tickets = null;
		if($this->common->has_permissions(array("ticket_client"), 
			$this->user)) 
		{
			$client_tickets = $this->tickets_model->get_client_tickets_fp($this->user->info->ID);
		}
		$client_invoices = null;
		if($this->common->has_permissions(array("invoice_client"), 
			$this->user)) 
		{
			$client_invoices = $this->invoices_model->get_invoices_client_fp($this->user->info->ID);
		}
		$client_projects = null;
		if($this->common->has_permissions(array("admin"), 
			$this->user)) 
		{
			$client_projects = $this->projects_model->get_home_projects_all_no_page();
		}else{
			$client_projects = $this->projects_model->get_home_projects_user_all_no_pagination($this->user->info->ID, "");
		}
		
		$projectstatus_overall = $this->projects_model->projectstatus_overall();
		$project_total = $this->projects_model->project_total();
		$titles_total = $this->projects_model->titles_total();
		/* echo "<pre>";
		print_r($projectwise_status);   */
		
		
		$config = $this->config->item("cookieprefix");
		$load_tn = 0;
		if(isset($_COOKIE[$config . 'dbtn'])) {
			$load_tn = intval($_COOKIE[$config . 'dbtn']);
		} else {
			if( ($this->settings->info->enable_tasks && $this->common->has_permissions(array("admin", "project_admin", "task_worker", "task_manage"), $this->user)) ) {
				$load_tn = 0;
			} elseif ( ($this->settings->info->enable_notes && $this->common->has_permissions(array("admin", "project_admin", "notes_manage", "notes_worker"), $this->user)) ) {
				$load_tn = 1;
			}
		}
		
		if(isset($_POST["from_date"])){
			$from_date = $_POST["from_date"];
		}
		else{
			$from_date = '';
		}
		if(isset($_POST["to_date"])){
			$to_date = $_POST["to_date"];
		}
		else{
			$to_date = date("Y-m-d");
		}
		$default_teamgroup = $this->projects_model->get_default_team_name($this->user->info->ID);
		/*  print_r($default_teamgroup); */
		if(isset($_POST["team_group"])){
			$team_group = $_POST["team_group"];
		}
		else{
			$team_group = $default_teamgroup[0]['team_name'];
		}
		$default_projectgroup = $this->projects_model->getdefault_projectgroup($this->user->info->ID);
		
		if(isset($_POST["project_group"])){
			$project_group = $_POST["project_group"];
		}
		else{
			/* if($this->common->has_permissions(array("admin"), $this->user)) { */
			$project_group = "";  
			/* } else {
				if(!empty($default_projectgroup)){
				$project_group = $default_projectgroup[0]['ID']; 
				}
				else{
				$project_group = "";  
				}
			} */
		}
		/* $data['project_group'] = $project_group;
		$this->export($data); */
		$projectwise_status = $this->projects_model->projectwise_status($project_group);
		$members_daily_productivity = $this->projects_model->get_members_daily_productivity($team_group);
		$members_monthly_productivity = $this->projects_model->get_members_monthly_productivity($from_date,$to_date,$team_group);
		$members_weekly_productivity = $this->projects_model->get_members_weekly_productivity();
		if($this->common->has_permissions(array("admin"), $this->user)) {
		$team_list = $this->projects_model->get_team_name();
		} else {
		$team_list = $this->projects_model->get_user_team_name($this->user->info->ID);
		}
		if($this->common->has_permissions(array("admin"), $this->user)) {
		$project_list = $this->projects_model->get_project_name();
		$projects_list = '';
		} else {
		$project_list = $this->projects_model->get_user_project_name($this->user->info->ID);
		$projects_list = $this->projects_model->get_user_projects_name($this->user->info->ID);
		}
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$current_year  = date("Y");
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
	    $online_count = $this->user_model->get_online_count();
	    $services = $this->services_model->get_all_services();
		$project_status = $this->file_model->monitor_file_status(); 
		$src_status = $this->projects_model->get_project_categories();
		/* $src_status = $this->file_model->monitor_source_status(); */
		
		$this->load->library('session');
		$this->session->set_userdata('pro_group', $project_group);
		
		$this->template->loadContent("home/index.php", array(
			"projects_count" => $stats->projects,
			"tasks_count" => $stats->tasks,
			"time_count" => $stats->time,
			"online_count" => $online_count,
			"members_daily_productivity" => $members_daily_productivity,
			"members_monthly_productivity" => $members_monthly_productivity,
			"members_weekly_productivity" => $members_weekly_productivity,
			"from_date" => $from_date,
			"to_date" => $to_date,
			"team_list" => $team_list,
			"project_list" => $project_list,
			"projects_list" => $projects_list,
			"team_group" => $team_group,
			"project_group" => $project_group,
			"income" => $income,
			"expense" => $expense,
			"total_revenue" => $total_revenue,
			"total_expense" => $total_expense,
			"profit" => $profit,
			"tickets" => $tickets,
			"time_projects" => $stats->time_projects,
			"activity" => $activity,
			"invoices" => $invoices,
			"notifications" => $notifications,
			"client_tickets" => $client_tickets,
			"client_invoices" => $client_invoices,
			"client_projects" => $client_projects,
			"load_tn" => $load_tn,
			"projectwise_status" => $projectwise_status,
			"projectstatus_overall" => $projectstatus_overall,
			"project_total" => $project_total,
			"titles_total" => $titles_total,
			"shift_details" => $shift_details,
			"project_status" => $project_status,
			"src_status" => $src_status,
			"services" => $services
			)
		);
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->projects = $this->projects_model
			->get_total_projects_user_all_count(0, $this->user->info->ID);
		$data->tasks = $this->task_model
			->get_project_tasks_total(0,0,$this->user->info->ID);

		// Get days
		$time_total = 0;
		$projects = array();

		for ($i=6; $i>-1; $i--) {
			$date = date("Y-m-d", strtotime($i." days ago"));
			$time = $this->time_model->count_hours_date($date, 
					$this->user->info->ID);
			if($time->num_rows() > 0) {
				$hours = 0;
				foreach($time->result() as $r) {
					$time_total += $r->time;

					$hour = ($r->time/3600);
					if(isset($projects[$r->projectid])) {
						$projects[$r->projectid]['times'] += 1;
						$projects[$r->projectid]['hours'] += $hour;
					} else {
						if(!isset($r->name)) {
							$r->name = lang("ctn_483");
						}
						$projects[$r->projectid]['title'] = $r->name;
						$projects[$r->projectid]['times'] = 1;
						$projects[$r->projectid]['hours'] = $hour;
					}
				}
			}
		}

		$time_data = $this->common->convert_time_raw($time_total);
		$big = "0";
		if($time_data['days'] > 0) {
	      $big = $time_data['days'];
	      $big .= lang("ctn_1001");
	    } elseif($time_data['hours'] > 0) {
	      $big = $time_data['hours'];
	      $big .= lang("ctn_1002");
	    } elseif($time_data['mins'] > 0) {
	      $big = $time_data['mins'];
	      $big .= lang("ctn_1003");
	    } elseif($time_data['secs'] > 0) {
	      $big = $time_data['secs'];
	      $big .= lang("ctn_1004");
	    }
		$data->time = $big;
		$data->time_projects = $projects;

		return $data;
	}

	public function get_ajax_tasks() 
	{
		if($this->common->has_permissions(array("admin"),$this->user)) {
			$tasks = $this->task_model->get_user_assigned_tasks_all(0, 0,'', 0,3);
		} else {
			$tasks = $this->task_model->get_user_assigned_tasks_fp(0, 0,$this->user->info->ID, 0,3);
		}
		
		

		$config = $this->config->item("cookieprefix");
		$ttl = 3600*24*31;
		setcookie($config . "dbtn", 0, time()+$ttl, "/");

		$this->template->loadAjax("home/ajax_tasks.php", array(
			"tasks" => $tasks
			),0
		);	
	}

	public function get_ajax_notes() 
	{
		$notes = $this->notes_model->get_pinned_notes($this->user->info->ID);

		$config = $this->config->item("cookieprefix");
		$ttl = 3600*24*31;
		setcookie($config . "dbtn", 1, time()+$ttl, "/");

		$this->template->loadAjax("home/ajax_notes.php", array(
			"notes" => $notes
			),0
		);	
	}

	public function change_language() 
	{	

		$languages = $this->config->item("available_languages");
		if(!isset($_COOKIE['language'])) {
			$lang = "";
		} else {
			$lang = $_COOKIE["language"];
		}
		$this->template->loadContent("home/change_language.php", array(
			"languages" => $languages,
			"user_lang" => $lang
			)
		);
	}

	public function change_language_pro() 
	{

		$lang = $this->common->nohtml($this->input->post("language"));
		$languages = $this->config->item("available_languages");
		
		if(!array_key_exists($lang, $languages)) {
			$this->template->error(lang("error_25"));
		}

		setcookie("language", $lang, time()+3600*7, "/");
		$this->session->set_flashdata("globalmsg", lang("success_14"));
		redirect(site_url());
	}

	public function load_notifications() 
	{
		$notifications = $this->user_model
			->get_notifications($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function load_notifications_unread() 
	{
		$notifications = $this->user_model
			->get_notifications_unread($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function read_all_noti($hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		
		$this->user_model->update_user_notifications($this->user->info->ID, array(
			"status" => 1
			)
		);

		$this->user_model->update_user($this->user->info->ID, array(
			"noti_count" => 0
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_135"));
		redirect(site_url("home/notifications"));
	}

	public function load_timers() 
	{
		$this->load->model("time_model");
		$timers = $this->time_model
			->get_active_user_timers($this->user->info->ID);
		$this->template->loadAjax("home/ajax_timers.php", array(
			"timers" => $timers
			),0
		);	
	}

	public function load_projects() 
	{
		$this->load->model("projects_model");
		$projects = $this->projects_model
			->get_projects_user($this->user->info->ID);
		$this->template->loadAjax("home/ajax_projects.php", array(
			"projects" => $projects
			),0
		);	
	}

	public function load_emails() 
	{
		$this->load->model("mail_model");
		$mail = $this->mail_model
			->get_user_mail_recent($this->user->info->ID, 5);
		$this->template->loadAjax("home/ajax_emails.php", array(
			"mail" => $mail
			),0
		);	
	}

	public function load_notification($id)
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$noti = $notification->row();
		if(!$noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 1
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}

		// redirect
		redirect(site_url($noti->url));
	}

	public function get_usernames() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$usernames = $this->user_model->get_usernames($query);
			if($usernames->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($usernames->result() as $r) {
					$array[] = $r->username;
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function notifications() 
	{
		$this->template->loadContent("home/notifications.php", array(
			)
		);	
	}

	public function notification_read($id) 
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$noti = $notification->row();
		if(!$noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 1
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}
		redirect(site_url("home/notifications"));
	}

	public function notification_unread($id) 
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$noti = $notification->row();
		if($noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 0
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count + 1
				)
			);
		}
		redirect(site_url("home/notifications"));
	}

	public function delete_notification($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$noti = $notification->row();
		if($noti->status) {
			
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count + 1
				)
			);
		} else {
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}

		$this->user_model->delete_notification($id);
		$this->session->set_flashdata("globalmsg", lang("success_149"));
		redirect(site_url("home/notifications"));
	}

	public function notifications_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_notifications.timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"user_notifications.timestamp" => 0
				 )
			)
		);
		$this->datatables->set_total_rows(
			$this->user_model
			->get_notifications_all_total($this->user->info->ID)
		);
		$notifications = $this->user_model
			->get_notifications_all($this->user->info->ID, $this->datatables);



		foreach($notifications->result() as $r) {
			$msg = '<a href="'.site_url("profile/" . $r->username).'">'.$r->username.'</a> ' . $r->message;
			$options = '<a href="'.site_url("home/notification_unread/" . $r->ID).'" class="btn btn-default btn-xs">'.lang("ctn_1175").'</a>';
			if($r->status !=1) {
				$msg .=' <label class="label label-danger">'.lang("ctn_584").'</label>';
				$options = '<a href="'.site_url("home/notification_read/" . $r->ID).'" class="btn btn-info btn-xs">'.lang("ctn_1176").'</a>';
			}

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$msg,
				date($this->settings->info->date_format, $r->timestamp),
				$options . ' <a href="'.site_url("home/delete_notification/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a> <a href="'.site_url("home/load_notification/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_585").'</a>'
			);
		}
		echo json_encode($this->datatables->process());
	}
	
	/* public function filterproductivity(){
		if(isset($_POST["from_date"])){
			$from_date = $_POST["from_date"];
		}
		else{
			$from_date = 0;
		}
		if(isset($_POST["to_date"])){
			$to_date = $_POST["to_date"];
		}
		else{
			$to_date = 0;
		}
	$this->template->loadContent("home/index.php", array(
			"from_date" => $from_date,
			"to_date" => $to_date
			)
		);	
	} */
	
	public function export() 
	{
		$this->load->model("projects_model");
		
		$filename = "projectwise_status_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		
		//$machine_columns = $this->machine_model->get_machine_columns();

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		//fputcsv($fp, $machine_columns);
		$pro = $this->session->userdata('pro_group');
		$projectwise_status = $this->projects_model->projectwise_status($pro);
		
		/* print_r($projectwise_status);
		
		exit(); */
		//fputcsv($fp, $machine_details);
		if (count($projectwise_status) > 0) {
			foreach($projectwise_status as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
				//foreach ($row as $line) {
					$line = (array)$row;
					fputcsv($fp, $line);
				//}
			}
		}
		exit;
	}
	
	public function overall() 
	{
		$this->load->model("projects_model");
		
		$filename = "overall_projects_status_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		
		//$machine_columns = $this->machine_model->get_machine_columns();

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		//fputcsv($fp, $machine_columns);
		
		$projectstatus_overall = $this->projects_model->projectstatus_overall();
		
		
		//fputcsv($fp, $machine_details);
		if ($projectstatus_overall->num_rows() > 0) {
			foreach($projectstatus_overall->result_array() as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
				//foreach ($row as $line) {
					$line = (array)$row;
					fputcsv($fp, $line);
				//}
			}
		}
		exit;
	}

}

?>