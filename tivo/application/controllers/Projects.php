<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");
		$this->load->model("finance_model");
		$this->load->model("task_model");
		$this->load->model("time_model");
		$this->load->model("machine_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium.
		if($this->settings->info->global_premium && 
		($this->user->info->premium_time != -1 &&  $this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
	}

	public function index($catid=0) 
	{
		$_SESSION['p_page'] = "index";
		$this->template->loadExternal('<script src="'.base_url().'scripts/libraries/jscolor.min.js"></script>');
		$this->template->loadData("activeLink", array("projects" => array("general" => 1)));
		$catid = intval($catid);
		$categories = $this->projects_model->get_project_categories();
		$templates = $this->task_model->get_templates_for_all();
		$custom_fields = $this->projects_model->get_custom_fields();
		$pro_publisher = $this->projects_model->get_pro_publisher();
		$pro_process_name = $this->projects_model->get_pro_process_name();
		$pro_stage = $this->projects_model->get_pro_stage();
		$pro_pdf_type = $this->projects_model->get_pro_pdf_type(); 
		$team_name = $this->machine_model->get_available_team_name();
		$projects = $this->projects_model->get_projects_index($catid);
		
		$this->template->loadContent("projects/index.php", array(
			"categories" => $categories,
			"catid" => $catid,
			"page" => "index",
			"templates" => $templates,
			"pro_publisher" => $pro_publisher,
			"pro_process_name" => $pro_process_name,
			"pro_stage" => $pro_stage,
			"pro_pdf_type" => $pro_pdf_type,
			"team_name" => $team_name,
			"projects" => $projects,
			"fields" => $custom_fields
			)
		);
	}

	public function gantt_chart($id) 
	{
		$this->template->loadData("activeLink", 
			array("projects" => array("general" => 1)));
		$this->load->model("task_model");
		$team_member = null;
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			}
			$team_member = $team_member->row();
		}

		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Get all Tasks
		$tasks = $this->task_model->get_all_tasks_no_pagination($id,0,0);
		if($tasks->num_rows() == 0) {
			$this->template->error(lang("error_267"));
		}

		// Find Date Range
		$start_date_range = 0;
		$end_date_range = 0;
		foreach($tasks->result() as $r) {
			if($r->template == 0) {
				if($start_date_range == 0) {
					$start_date_range = $r->start_date;
				}
				if($r->start_date <= $start_date_range) {
					$start_date_range = $r->start_date;
				}

				if($end_date_range == 0) {
					$end_date_range = $r->due_date;
				}

				if($r->due_date > $end_date_range) {
					$end_date_range = $r->due_date;
				}
			}
		}

		$range1 = date($this->settings->info->date_picker_format, 
			$start_date_range);
		$range2 = date($this->settings->info->date_picker_format,
			$end_date_range);

		// Get all dates
		$dates = $this->common->getDatesFromRange($range1, $range2, $this->settings->info->date_picker_format, "Y-m-d");

		// Now find all months and days in month
		$dates_months = array();
		$current_month = 0;
		$days_count = 0;
		$current_year = 0;
		$current_month_d = "";
		foreach($dates as $date) {
			$current_year = date("Y", $date['timestamp']);

			if($current_month == 0) {
				$current_month = date("m", $date['timestamp']);
				$current_month_d = date("F", $date['timestamp']);
			}

			if($current_month != date("m", $date['timestamp'])) {
				$dates_months[] = array(
					"month" => $current_month, 
					"days" => $days_count,
					"year" => $current_year,
					"display" => $current_month_d
				);
				$current_month = date("m", $date['timestamp']);
				$current_month_d = date("F", $date['timestamp']);
				$days_count = 0;
			}
			$days_count++;
		}

		$dates_months[] = array(
			"month" => $current_month, 
			"days" => $days_count,
			"year" => $current_year,
			"display" => $current_month_d
		);


		$this->template->loadContent("projects/new_gantt.php", array(
			"start_date_range" => $start_date_range,
			"end_date_range" => $end_date_range,
			"project" => $project,
			"dates" => $dates,
			"tasks" => $tasks,
			"months" => $dates_months
			)
		);
	}

	public function projects_page($page = "index", $catid) 
	{
		$catid = intval($catid);

		$this->load->library("datatables");

		$this->datatables->set_default_order("projects.ID", "desc");

			/* 	 2 => array(
				 	"projects.frequency" => 0
				 ),

				 3 => array(
				 	"projects.pm" => 0
				 ),				 */
		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"projects.name" => 0
				 ),
				 1 => array(
				 	"projects.catid" => 0
				 ),
				 2 => array(
				 	"projects.process_name" => 0
				 ),
				 3 => array(
				 	"projects.team_name" => 0
				 ), 
				 5 => array(
				 	"projects.user_name" => 0
				 ),
				 6 => array(
				 	"projects.timestamp" => 0
				 )
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->projects_model->get_total_projects_user_all_count($catid, $this->user->info->ID)
			);
			$projects = $this->projects_model->get_projects_user_all($catid, 
			$this->user->info->ID, $this->datatables);
		} elseif($page == "all") {
			if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
				$this->template->error(lang("error_71"));
			}
			$this->datatables->set_total_rows(
				$this->projects_model->get_total_projects_count($catid)
			);
			$projects = $this->projects_model->get_projects($catid, $this->datatables);
		} elseif($page == "client") {
			$userid = intval($this->input->get("userid"));
			$this->datatables->set_total_rows(
				$this->projects_model->get_total_projects_user_all_count($catid, $userid)
			);
			$projects = $this->projects_model->get_projects_user_all($catid, 
			$userid, $this->datatables);
		}

		foreach($projects->result() as $r) {

			$project_name = '<a href="'.site_url("projects/view/" . $r->ID).'">'.$r->name.'</a>';
			if($r->ID == $this->user->info->active_projectid) {
				$project_name .= ' <label class="label label-success">'.lang("ctn_787").'</label>';
			}
			if($r->status == 1) {
				$project_name .= ' <label class="label label-default">'.lang("ctn_778").'</label>';
			}
			
			$longest_duedate = $this->projects_model->get_longest_duedate($r->ID);
			$l_duedate = '';
			foreach($longest_duedate->result() as $duedate) {
				$l_duedate =  $duedate->due_date;
			}
			
			$shortest_startdate = $this->projects_model->get_shortest_startdate($r->ID);
			$s_startdate = '';
			foreach($shortest_startdate->result() as $startdate) {
				$s_startdate =  $startdate->start_date;
			}
			
			$sum_price_project_tasks = $this->projects_model->get_sum_price_project_tasks($r->ID);
			foreach($sum_price_project_tasks->result() as $sum_price) {
				$totalPages =  $sum_price->totalPages;
				$totalPagesPrice =  $sum_price->totalPagesPrice;
				/* $totalArticles =  $sum_price->totalArticles;
				$totalArticlesPrice =  $sum_price->totalArticlesPrice; */
			}
			
			$member_string = '';
			$members = $this->team_model->get_members_for_project($r->ID);
    		$our_user = new STDclass; // For the current user 
			$members_i = 0;
			$len = count($members->result());
    		foreach($members->result() as $member) {
    			if($member->userid == $this->user->info->ID) $our_user = $member;
    			/* $member_string .= '<div class="projects-team-members-simple">
    			'.$this->common->get_user_display(array("username" => $member->username, "avatar" => $member->avatar, "online_timestamp" => $member->online_timestamp)).'</div>'; */
				$member_string .= '<div class="user-box-name" style="width: 100%;"><p class="user-box-username">
				<a href="'.site_url("profile/" . $member->username).'">'.ucfirst($member->username).'</a>';
				if ($members_i != $len - 1) {$member_string .= ',';}
				$member_string .= '</p></div>';
				$members_i++;
    		}

    		/* $options = '<a href="'.site_url("projects/make_active/" . $r->ID).'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_788").'" onclick="return confirm(\''.lang("ctn_1512").'\')"><span class="glyphicon glyphicon-pushpin"></span></a> <a href="'.site_url("projects/view/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_555").'">'.lang("ctn_555").'</a> '; */
			if($r->status == 0) {
			$options = '<a href="'.site_url("projects/make_inactive/" . $r->ID).'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_788").'" onclick="return confirm(\''.lang("ctn_1512").'\')"><span class="glyphicon glyphicon-pushpin"></span></a> <a href="'.site_url("projects/view/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_555").'"><span class="glyphicon glyphicon-eye-open"></span></a> ';
        	}
			else {
			$options = '<a href="'.site_url("projects/make_active/" . $r->ID).'" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_1637").'" onclick="return confirm(\''.lang("ctn_1636").'\')"><span class="glyphicon glyphicon-pushpin"></span></a> <a href="'.site_url("projects/view/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_555").'"><span class="glyphicon glyphicon-eye-open"></span></a> ';
        	}
			if( $this->common->has_permissions(array("admin", "project_admin"), $this->user) || ($this->common->has_team_permissions(array("admin"), $our_user)) ) {
        		/* $options .= '<a href="'.site_url("projects/edit_project/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("projects/delete_project/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_789").'\')" title="'.lang("ctn_57").'"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'; 
        		 */
				 $options .= '<a href="'.site_url("projects/edit_project/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-edit"></span></a>'; 
        	}
			foreach($members->result() as $member) {
    			$user_id[] = $member->userid;
			}
			/* $team_members_by_userid = $this->team_model->get_team_members_by_userid($user_id);
			$team_name = [];
			foreach($team_members_by_userid->result() as $team_members) {
    			$team_name[] .= $team_members->team_name;
			} */
			$unix_timestamp = $r->timestamp;
			$datetime = new DateTime("@$unix_timestamp");
			$date_time_format = $datetime->format('Y-m-d H:i:s');
			$time_zone_from="UTC";
			$time_zone_to='Asia/Kolkata';
			$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));
			$display_date->setTimezone(new DateTimeZone($time_zone_to));
			
				/* $s_startdate,
				$l_duedate,
				$totalPages,
				$totalPagesPrice, */
				/* $r->customer, 
				$r->country,
				'<img src="'.base_url().'/'.$this->settings->info->upload_path_relative.'/'. $r->image.'" width="40" class="user-icon">',
				'<a href="'.base_url().'/'.$this->settings->info->upload_path_relative.'/'. $r->image.'" >$r->image</a>', 
				$r->frequency,
				$r->pm, 
				'<div class="progress" style="height: 15px;">
				  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$r->complete .'" aria-valuemin="0" aria-valuemax="100" style="width: '.$r->complete.'%" title="'.$r->complete.'%" data-toggle="tooltip" data-placement="bottom">
				    <span class="sr-only">'.$r->complete.'% '.lang("ctn_790").'</span>
				  </div>
				</div>',
				$member_string,
				'<a href="'.base_url().'/'.$this->settings->info->upload_path_relative.'/'. $r->image.'" >'.$r->image.'</a>',
				'<button id="upcfg" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#addProModal">'.$r->image.'</button>',
				'<button id="upcfg" class="btn btn-warning btn-xs">'.$r->image.'</button>',
				*/
				if($r->image == 'default.png'){
					$channel_config = '<a data-toggle="modal" class="upcfg" id="'. $r->ID .'" at="" ></a>';
				} else{
					$channel_config = '<a data-toggle="modal" class="upcfg" id="'. $r->ID .'" at="'.base_url().'/'.$this->settings->info->upload_path_relative.'/'. $r->image.'" >'.$r->image.'</a>';
				}
			$this->datatables->data[] = array(
				$project_name,
				'<span class="label label-default" style="background: #'.$r->cat_color.'; color: #000000;">'.$r->catname.'</span>', 
				$r->process_name, 
				$r->team_name,
				$channel_config,
				$r->user_name,
				$display_date->format('d-m-Y H:i:s'),
				$options
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function all($catid=0) 
	{
		$_SESSION['p_page'] = "all";
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("projects" => array("all" => 1)));

		$catid = intval($catid);

		$categories = $this->projects_model->get_project_categories();

		$templates = $this->task_model->get_templates_for_all();

		$custom_fields = $this->projects_model->get_custom_fields();
		$pro_publisher = $this->projects_model->get_pro_publisher();
		$pro_process_name = $this->projects_model->get_pro_process_name();
		$pro_stage = $this->projects_model->get_pro_stage();
		$pro_pdf_type = $this->projects_model->get_pro_pdf_type();
		$team_name = $this->machine_model->get_available_team_name(); 
		$projects = $this->projects_model->get_projects_index($catid);
		
		$this->template->loadContent("projects/index.php", array(
			"categories" => $categories,
			"catid" => $catid,
			"page" => "all",
			"templates" => $templates,
			"pro_publisher" => $pro_publisher,
			"pro_process_name" => $pro_process_name,
			"pro_stage" => $pro_stage,
			"pro_pdf_type" => $pro_pdf_type,
			"team_name" => $team_name,
			"projects" => $projects,
			"fields" => $custom_fields
			)
		);
	}

	public function view($id, $page=0) 
	{
		$this->template->loadData("activeLink", 
			array("projects" => array("general" => 1)));

		$this->load->model("task_model");
		$this->load->model("file_model");
		$id = intval($id);
		$page = intval($page);

		$team_member = null;
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			}
			$team_member = $team_member->row();
		}

		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$members = $this->team_model->get_members_for_project($id);

		$activity = $this->team_model->get_project_log($id, 0, 5);

		$messages = $this->projects_model->get_messages($id, $page);

		$tasks_total = $this->task_model->get_all_tasks_total($id, 0);

		$files = $this->file_model->get_recent_files_by_project($id);

		$fields = $this->projects_model->get_custom_fields_answers($id);
		$cats = $this->projects_model->get_project_categories();
		$time = $this->time_model->count_hours_project($id);
		$hours = 0;
		if($time->num_rows() > 0) {
			foreach($time->result() as $r) {

				$hour = round($r->time/3600,2);
				$hours += $hour;
			}
		}

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("projects/view/" . $id);
		$config['total_rows'] = $this->projects_model
			->get_total_messages($id);
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");
		$this->pagination->initialize($config);

		$this->template->loadContent("projects/view.php", array(
			"project" => $project,
			"team_member" => $team_member,
			"members" => $members,
			"activity" => $activity,
			"messages" => $messages,
			"tasks_total" => $tasks_total,
			"files" => $files,
			"hours" => $hours,
			"categories" => $cats,
			"fields" => $fields
			)
		);

	}

	public function delete_message($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$message = $this->projects_model->get_message($id);
		if($message->num_rows() == 0) {
			$this->template->error(lang("error_188"));
		}
		$message = $message->row();
		if($message->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_2"), 
				array("admin", "project_admin"), // User Roles
				array("admin"),  // Team Roles
				$message->projectid
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1408"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $id,
			"url" => "projects/view/" . $id
			)
		);

		$this->projects_model->delete_message($id);
		$this->session->set_flashdata("globalmsg", lang("success_131"));
		redirect(site_url("projects/view/" . $message->projectid));
	}

	public function add_message($id) 
	{
		$id = intval($id);

		$team_member = null;
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			}
			$team_member = $team_member->row();
		}

		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$message = $this->lib_filter->go($this->input->post("message"));
		if(empty($message)) {
			$this->template->error(lang("error_187"));
		}

		// Add
		$this->projects_model->add_message(array(
			"userid" => $this->user->info->ID,
			"message" => $message,
			"projectid" => $id,
			"timestamp" => time()
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1409"),
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $id,
			"url" => "projects/view/" . $id
			)
		);

		// Notify all team members of project message
		$members = $this->team_model->get_members_for_project($id);
		foreach($members->result() as $r) {
			if($r->userid != $this->user->info->ID) {
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
							"userid" => $r->userid,
							"url" => "projects/view/" . $id,
							"timestamp" => time(),
							"message" => lang("ctn_1513") . $project->name,
							"status" => 0,
							"fromid" => $this->user->info->ID,
							"email" => $r->email,
							"username" => $r->username,
							"email_notification" => $r->email_notification
							)
						);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_92"));
		redirect(site_url("projects/view/" . $id));
	}

	public function cats() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("projects" => array("cats" => 1)));
		$cats = $this->projects_model->get_project_categories();
		$this->template->loadContent("projects/cats.php", array(
			"cats" => $cats
			)
		);
	}

	public function add_category() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$color = $this->common->nohtml($this->input->post("color"));
		if(strlen($color) > 6) {
			$this->template->error(lang("error_148"));
		}
		if(empty($name)) $this->template->error(lang("error_112"));

		// Add
		$this->projects_model->add_category(array(
			"name" => $name,
			"color" => $color
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1042") ." <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "projects/cats"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_51"));
		redirect(site_url("projects/cats"));
	}

	public function edit_cat($id) 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$cat = $this->projects_model->get_category($id);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_149"));
		}
		$cat = $cat->row();
		$this->template->loadContent("projects/edit_cat.php", array(
			"cat" => $cat
			)
		);
	}

	public function edit_cat_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$cat = $this->projects_model->get_category($id);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_149"));
		}
		$cat = $cat->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$color = $this->common->nohtml($this->input->post("color"));
		/* $description = $this->common->nohtml($this->input->post("description")); */
		if(strlen($color) > 6) {
			$this->template->error(lang("error_148"));
		}
		if(empty($name)) $this->template->error(lang("error_112"));
/* "description" => $description, */
		// Add
		$this->projects_model->update_category($id, array(
			"name" => $name,
			"color" => $color
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1043") . " <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "projects/cats"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_53"));
		redirect(site_url("projects/cats"));
	}

	public function delete_cat($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$cat = $this->projects_model->get_category($id);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_149"));
		}
		$cat = $cat->row();

		$this->projects_model->delete_category($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1044") . " <b>".$cat->name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => 0,
			"url" => "projects/cats"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_52"));
		redirect(site_url("projects/cats"));
	}

	public function add_project() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin", "project_worker"), $this->user)) {
			$this->template->error(lang("error_71"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$description = $this->lib_filter->go($this->input->post("description"));
		$catid = intval($this->input->post("catid"));
		$tivo = intval($this->input->post("tivo"));
		$calendar_id = $this->common->nohtml($this->input->post("calendar_id"));
		$calendar_color = $this->common->nohtml($this->input->post("calendar_color"));
		$templates_toadd = $this->input->post("templates");
		$complete = intval($this->input->post("complete"));
		$complete_sync = intval($this->input->post("complete_sync"));
		$customer = $this->common->nohtml($this->input->post("customer"));
		$frequency = $this->common->nohtml($this->input->post("frequency"));
		$acronym = $this->common->nohtml($this->input->post("acronym"));
		$process_name = $this->common->nohtml($this->input->post("process_name"));
		/* $country = $this->common->nohtml($this->input->post("country")); */
		$geofacets_process_name = $this->common->nohtml($this->input->post("geofacets_process_name"));
		$stage = $this->common->nohtml($this->input->post("stage"));
		$pdfType = $this->common->nohtml($this->input->post("pdfType"));
		$geofacets_pdfType = $this->common->nohtml($this->input->post("geofacets_pdfType"));
		$pm = $this->common->nohtml($this->input->post("pm"));
		$teams = $this->input->post("team_name");
		$team_name = implode("','", $teams);
		$user_name = $this->common->nohtml($this->input->post("user_name"));
		$broadcast_day_st_time = $this->common->nohtml($this->input->post("broadcast_day_st_time"));
		
		if(empty($name)) $this->template->error(lang("error_150"));
		$cat = $this->projects_model->get_category($catid);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_149"));
		}
		
		$configpath = 'D:\xampp\htdocs\uploadfiles\ConfigFiles\\';
		
		/* "allowed_types" => $this->settings->info->file_types, 
		"upload_path" => $this->settings->info->upload_path, */
		$this->load->library("upload");
		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $configpath,
			       "overwrite" => FALSE,
			       "max_filename" => 1300,
			       "encrypt_name" => FALSE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "xls|xlsx|xml|cfg",
			       "max_size" => $this->settings->info->file_size,
			       "max_width" => 150,
			       "max_height" => 150
			    ));

			    /* if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    } */
				if ( ! $this->upload->do_upload('userfile'))
				{
						$error = array('error' => $this->upload->display_errors());

						$this->template->error(lang("error_94") . "<br /><br />" .
							 $this->upload->display_errors());
				}
			    $data = $this->upload->data();
			    $image = $data['file_name'];
			 
		} else {
			$image= "default.png";
		}

		$fields = $this->projects_model->get_custom_fields();

		// Custom Fields
		// Process fields
		$answers = array();
		$fail = "";
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$fail = lang("error_158") . $r->name;
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$fail = lang("error_158") . $r->name;
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		if(!empty($fail)) {
			$this->template->error($fail);
		}

		$templates= array();
		if($templates_toadd) {
			foreach($templates_toadd as $templateid) {
				$templateid = intval($templateid);
				if($templateid > 0) {
					// Check task template
					$template = $this->task_model->get_task($templateid);
					if($template->num_rows() == 0) {
						$this->template->error(lang("error_283"));
					}
					$template = $template->row();
					if(!$template->template) {
						$this->template->error(lang("error_284") . $template->name);
					}
					if($template->template_projectid > 0) {
						$this->template->error(lang("error_285") . $template->name);
					}

					$templates[] = $templateid;
				}
			}
		}

		// Add Project
		$projectid = $this->projects_model->add_project(array(
			"name" => $name,
			"description" => $description,
			"catid" => $catid,
			"userid" => $this->user->info->ID,
			"customer" => $customer,
			"frequency" => $frequency,
			"acronym" => $acronym,
			"process_name" => $process_name, 
			"geofacets_process_name" => $geofacets_process_name,
			"stage" => $stage,
			"pdfType" => $pdfType,
			"geofacets_pdfType" => $geofacets_pdfType,
			"pm" => $pm,
			"team_name" => $team_name,
			"user_name" => $user_name,
			"broadcast_day_start_time" => $broadcast_day_st_time,
			"timestamp" => time(),
			"image" => $image,
			"calendar_id" => $calendar_id,
			"calendar_color" => $calendar_color,
			"complete" => $complete,
			"complete_sync" => $complete_sync,
			"tivo" => $tivo
			)
		);

		// Add Team Members
		$this->team_model->add_member(array(
			"projectid" => $projectid,
			"userid" => $this->user->info->ID,
			"roleid" => 1
			)
		);

		// Create tasks from templates
		foreach($templates as $tid) {
			$this->create_task($tid, $projectid);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1045"). " <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "projects"
			)
		);

		// CUstom Fields
		// Add Custom Fields data
		foreach($answers as $answer) {
			$this->projects_model->add_custom_field_value(array(
				"projectid" => $projectid,
				"fieldid" => $answer['fieldid'],
				"value" => $answer['answer']
				)
			);
		}


		$this->session->set_flashdata("globalmsg", 
			lang("success_67"));
		redirect(site_url("projects/all"));
	}

	private function create_task($taskid, $projectid) 
	{
		$taskid = intval($taskid);
		$projectid = intval($projectid);
		$task = $this->task_model->get_task($taskid);
		if($task->num_rows() == 0) {
			$this->template->error(lang("error_166"));
		}
		$task = $task->row();

		if($task->template == 0) {
			$this->template->error(lang("error_284"));
		}

		// Options
		$import_objectives = 1;
		$import_task_members = 1;
		$import_team = 0;
		$import_files = 1;
		$import_messages = 1;

		// Create task time.
		$name = $task->name;
		$desc = $task->description;
		$start_date = $task->start_date;
		$due_date = $task->due_date;
		$status = $task->status;
		$assign = $task->assign;

		$template_start_days = $task->template_start_days;
		$template_due_days = $task->template_due_days;

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

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

	}

	public function delete_project($id, $hash) 
	{
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			} else {
				$team = $team_member->row();
				if(!$this->common->has_team_permissions(array("admin"), $team)) {
					$this->template->error(lang("error_151"));
				}
			}
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Delete
		$this->projects_model->delete_project($id);

		// Delete finances
		$this->finance_model->delete_finance_project($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1046") . " <b>".$project->name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $project->ID,
			"url" => "projects"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_68"));
		if(isset($_SESSION['p_page'])) {
			$page = $this->common->nohtml($_SESSION['p_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("projects/" . $page));
	}

	public function edit_project($id) 
	{
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			} else {
				$team = $team_member->row();
				if(!$this->common->has_team_permissions(array("admin"), $team)) {
					$this->template->error(lang("error_151"));
				}
			}
		}
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		
		$this->load->model("projects_model");
		$id = intval($id);
		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}

		$fields = $this->projects_model->get_custom_fields_answers($id);

		$cats = $this->projects_model->get_project_categories();
		$pro_publisher = $this->projects_model->get_pro_publisher();
		$pro_process_name = $this->projects_model->get_pro_process_name();
		$pro_stage = $this->projects_model->get_pro_stage();
		$pro_pdf_type = $this->projects_model->get_pro_pdf_type();
		$team_name = $this->machine_model->get_available_team_name();
		
		$this->template->loadContent("projects/edit.php", array(
			"categories" => $cats,
			"pro_publisher" => $pro_publisher,
			"pro_process_name" => $pro_process_name,
			"pro_stage" => $pro_stage,
			"pro_pdf_type" => $pro_pdf_type,
			"team_name" => $team_name,
			"project" => $project->row(),
			"fields" => $fields
			)
		);
	}

	public function edit_project_pro($id) 
	{
		// Get user permission
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
			$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $id);
			if($team_member->num_rows() == 0) {
					$this->template->error(lang("error_71"));
			} else {
				$team = $team_member->row();
				if(!$this->common->has_team_permissions(array("admin"), $team)) {
					$this->template->error(lang("error_151"));
				}
			}
		}
		$id = intval($id);
		$project = $this->projects_model->get_project($id);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$description = $this->lib_filter->go($this->input->post("description"));
		$catid = intval($this->input->post("catid"));
		$status = intval($this->input->post("status"));
		$calendar_id = $this->common->nohtml($this->input->post("calendar_id"));
		$calendar_color = $this->common->nohtml($this->input->post("calendar_color"));

		$complete = intval($this->input->post("complete"));
		$complete_sync = intval($this->input->post("complete_sync"));

		$customer = $this->common->nohtml($this->input->post("customer"));
		$frequency = $this->common->nohtml($this->input->post("frequency"));
		/* $country = $this->common->nohtml($this->input->post("country")); */
		$acronym = $this->common->nohtml($this->input->post("acronym"));
		$process_name = $this->common->nohtml($this->input->post("process_name"));
		$geofacets_process_name = $this->common->nohtml($this->input->post("geofacets_process_name"));
		$pm = $this->common->nohtml($this->input->post("pm")); 
		$teams = $this->input->post("team_name");
		$team_name = implode("','", $teams);
		$user_name = $this->common->nohtml($this->input->post("user_name"));
		$broadcast_day_st_time = $this->common->nohtml($this->input->post("broadcast_day_st_time"));
		$stage = $this->common->nohtml($this->input->post("stage"));
		$pdfType = $this->common->nohtml($this->input->post("pdfType"));
		$geofacets_pdfType = $this->common->nohtml($this->input->post("geofacets_pdfType"));
		
		if($status != 0 && $status != 1) {
			$this->template->error(lang("error_152"));
		}

		if(empty($name)) $this->template->error(lang("error_150"));

		$cat = $this->projects_model->get_category($catid);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_149"));
		}

		// Custom Fields
		$fields = $this->projects_model->get_custom_fields_answers($id);
		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->name);
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->name);
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		$this->load->library("upload");
		$configpath = 'D:\xampp\htdocs\uploadfiles\ConfigFiles\\';
		
		/* "allowed_types" => $this->settings->info->file_types, 
		"upload_path" => $this->settings->info->upload_path, */
		
		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $configpath,
			       "overwrite" => FALSE,
			       "max_filename" => 1300,
			       "encrypt_name" => FALSE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "xls|xlsx|xml|cfg",
			       "max_size" => $this->settings->info->file_size,
			       "max_width" => 150,
			       "max_height" => 150
			    ));

			    /* if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    } */
				if ( ! $this->upload->do_upload('userfile'))
				{
						$error = array('error' => $this->upload->display_errors());

						$this->template->error(lang("error_94") . "<br /><br />" .
							 $this->upload->display_errors());
				}

			    $data = $this->upload->data();

			    $image = $data['file_name'];
			
		} else {
			$image= $project->image;
		}

		if($complete_sync) {
			// Get all tasks
			$this->load->model("task_model");
			$tasks = $this->task_model->get_all_project_tasks($project->ID);
			$total = $tasks->num_rows() * 100;
			$complete = 0;
			foreach($tasks->result() as $r) {
				$complete += $r->complete;
			}

			$complete = @intval(($complete/$total) * 100);
		}
		
		// Update Project
		$this->projects_model->update_project($project->ID, array(
			"name" => $name,
			"description" => $description,
			"catid" => $catid,
			"userid" => $this->user->info->ID,
			"customer" => $customer,
			"frequency" => $frequency, 
			"acronym" => $acronym,
			"process_name" => $process_name,
			"geofacets_process_name" => $geofacets_process_name,
			"stage" => $stage,
			"pdfType" => $pdfType,
			"geofacets_pdfType" => $geofacets_pdfType,
			"pm" => $pm,
			"team_name" => $team_name,
			"user_name" => $user_name,
			"broadcast_day_start_time" => $broadcast_day_st_time,
			"timestamp" => time(),
			"image" => $image,
			"status" => $status,
			"calendar_id" => $calendar_id,
			"calendar_color" => $calendar_color,
			"complete" => $complete,
			"complete_sync" => $complete_sync
			)
		);
		// Update CF
		// Add Custom Fields data
		foreach($answers as $answer) {
			// Check if field exists
			$field = $this->projects_model->get_project_cf($answer['fieldid'], $project->ID);
			if($field->num_rows() == 0) {
				$this->projects_model->add_custom_field_value(array(
					"projectid" => $project->ID,
					"fieldid" => $answer['fieldid'],
					"value" => $answer['answer']
					)
				);
			} else {
				$this->projects_model->update_custom_field_value($answer['fieldid'], 
					$project->ID, $answer['answer']);
			}
		}


		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1047") . " <b>".$name.
			"</b>.",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $project->ID,
			"url" => "projects"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_69"));
		if(isset($_SESSION['p_page'])) {
			$page = $this->common->nohtml($_SESSION['p_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("projects/" . $page));
	}

	public function make_active($id) 
	{
		$id = intval($id);
		if($id > 0) {
			$project = $this->projects_model->get_project($id);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			// Active if user is admin only or a member of the project
			if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
				// Check if user is a member
				$member = $this->team_model
					->get_member_of_project($this->user->info->ID, $project->ID);
				if($member->num_rows() == 0) {
					$this->template->error(lang("error_153"));
				}
			}

			/* $this->user_model->update_user($this->user->info->ID, array(
				"active_projectid" => $project->ID
				)
			); */
			$this->user_model->update_project_stat($project->ID, array(
				"status" => 0
				)
			);
			$msg = lang("success_182");
		} else {
			$msg = lang("success_71");
			/* $this->user_model->update_user($this->user->info->ID, array(
				"active_projectid" => 0
				)
			); */
			$this->user_model->update_project_stat($project->ID, array(
				"status" => 1
				)
			);
		}

		$this->session->set_flashdata("globalmsg", $msg);
		if(isset($_SESSION['p_page'])) {
			$page = $this->common->nohtml($_SESSION['p_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("projects/" . $page));
	}
	
	public function make_inactive($id) 
	{
		$id = intval($id);
		if($id > 0) {
			$project = $this->projects_model->get_project($id);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			// Active if user is admin only or a member of the project
			if(!$this->common->has_permissions(array("admin", "project_admin"), 
				$this->user)) {
				// Check if user is a member
				$member = $this->team_model
					->get_member_of_project($this->user->info->ID, $project->ID);
				if($member->num_rows() == 0) {
					$this->template->error(lang("error_153"));
				}
			}

			/* $this->user_model->update_user($this->user->info->ID, array(
				"active_projectid" => $project->ID
				)
			); */
			$this->user_model->update_project_stat($project->ID, array(
				"status" => 1
				)
			);
			$msg = lang("success_70");
		} else {
			$msg = lang("success_71");
			/* $this->user_model->update_user($this->user->info->ID, array(
				"active_projectid" => 0
				)
			); */
			$this->user_model->update_project_stat($project->ID, array(
				"status" => 0
				)
			);
		}

		$this->session->set_flashdata("globalmsg", $msg);
		if(isset($_SESSION['p_page'])) {
			$page = $this->common->nohtml($_SESSION['p_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("projects/" . $page));
	}

	public function custom_fields() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}

		$this->template->loadData("activeLink", 
			array("projects" => array("custom_fields" => 1)));
		$custom_fields  = $this->projects_model->get_custom_fields();
		$this->template->loadContent("projects/custom_fields.php", array(
			"fields" => $custom_fields
			)
		);
	}

	public function add_custom_field() 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));

		if(empty($name)) {
			$this->template->error(lang("error_246"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_247"));
		}

		// Add
		$this->projects_model->add_custom_field(array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"help_text" => $help_text
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_126"));
		redirect(site_url("projects/custom_fields"));
	}

	public function delete_custom_field($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$field = $this->projects_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}

		$this->projects_model->delete_custom_field($id);
		$this->session->set_flashdata("globalmsg", lang("success_128"));
		redirect(site_url("projects/custom_fields"));
	}

	public function edit_custom_field($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$field = $this->projects_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}
		$field = $field->row();

		$this->template->loadData("activeLink", 
			array("projects" => array("custom_fields" => 1)));
		$custom_fields  = $this->projects_model->get_custom_fields();
		$this->template->loadContent("projects/edit_custom_field.php", array(
			"field" => $field
			)
		);
	}

	public function edit_custom_field_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		}
		$id = intval($id);
		$field = $this->projects_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}
		$field = $field->row();

		$this->template->loadData("activeLink", 
			array("projects" => array("custom_fields" => 1)));

		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));

		if(empty($name)) {
			$this->template->error(lang("error_246"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_247"));
		}

		// Add
		$this->projects_model->update_custom_field($id, array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"help_text" => $help_text
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_127"));
		redirect(site_url("projects/custom_fields"));
	}
	
	public function import() 
    {
        set_time_limit(0);
        error_reporting(E_ALL);
		$this->load->model("projects_model");
        try {
            if (isset($_POST["import"])) {
                $filename = $_FILES["file"]["tmp_name"];
				$mimes = array('application/vnd.ms-excel','text/csv', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip');
                if (($_FILES["file"]["size"] > 0) && in_array($_FILES['file']['type'],$mimes)) {
                    $file = fopen($filename, "r");
					$header = fgetcsv($file);
                    while (($importdata = fgetcsv($file)) !== FALSE) {
					$record = array_combine($header, $importdata);	
							$data = array(
                                'name' => isset($record["name"]) ? $record["name"] : NULL, 
                                'image' => 'default.png', 
								'userid' => $this->user->info->ID,
								'description' => isset($record["description"]) ? $record["description"] : NULL,
								'catid' => 6,
								'customer' => isset($record["customer"]) ? $record["customer"] : NULL, 
								'publisher' => isset($record["publisher"]) ? $record["publisher"] : NULL, 
								'acronym' => isset($record["acronym"]) ? $record["acronym"] : NULL, 
								'process_name' => isset($record["process_name"]) ? $record["process_name"] : NULL, 
								'geofacets_process_name' => isset($record["geofacets_process_name"]) ? $record["geofacets_process_name"] : NULL, 
								'stage' => isset($record["stage"]) ? $record["stage"] : NULL, 
								'pdfType' => isset($record["pdfType"]) ? $record["pdfType"] : NULL, 
								'geofacets_pdfType' => isset($record["geofacets_pdfType"]) ? $record["geofacets_pdfType"] : NULL, 
								'pm' => isset($record["pm"]) ? $record["pm"] : NULL, 
								'timestamp' => time(),
								'status' => 0,
								'complete' => 0,
								'complete_sync' => 1
								);
                            $insert = $this->projects_model->add_project($data);
							
							$this->team_model->add_member(array(
								"projectid" => $insert,
								"userid" => $this->user->info->ID,
								"roleid" => 1
								)
							); 

                    }
                    fclose($file);
					$this->session->set_flashdata('success', 'Your '. $_FILES['file']['name'] . ' uploaded successfully!');
                    redirect(site_url("projects/index"));
                } else {
                    $this->session->set_flashdata('error', 'Problem in uploading the file!');
                    redirect(site_url("projects/index"));
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
	
	public function check_project_name() 
	{
		$name = $this->common->nohtml($this->input->get("projectname", true));
		$this->load->model("projects_model");
		$field_errors = array();

		if (strlen($name) < 3) {
			$field_errors['name'] = lang("error_14");
		}

		/* if (!preg_match("/^[a-z0-9_]+$/i", $name)) {
			$field_errors['name'] = lang("error_15");
		} */

		if (!$this->projects_model->check_projectname_is_free($name)) {
			$field_errors['name'] = "$name " . lang("ctn_243");
		}

		
		if(empty($field_errors)) {
			echo json_encode(array("success" => 1));
		} else {
			echo json_encode(array("field_errors" => 1,"fieldErrors" => $field_errors));
		}

		exit();
	} 
	
	/* public function check_upcfg() 
	{
		$cfgfile = $this->input->get("cfgfile", true); 
		$cfgat = $this->input->get("cfgat", true); 
		
		$this->load->model("projects_model");
		$getfrmcfg = $this->projects_model->getfromcfg($cfgfile);
		$frmcfg = $getfrmcfg->result_array();
		
	
		$fileContents = file_get_contents($cfgat); 
		$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
		$fileContents = trim(str_replace('"', "'", $fileContents)); 
		$simpleXml = simplexml_load_string($fileContents);
		$json = json_encode($simpleXml);
		$myArray = json_decode(json_encode($simpleXml), true); 
		  
		$foo = array();		
		$arrayLength = count($myArray['item']);
		for ($i = 0; $i <=$arrayLength; $i++) {
			if(isset($myArray['item'][$i]['colName'])){
				if(!(is_array($myArray['item'][$i]['colName']))){
					$foo['colname'][] = $myArray['item'][$i]['colName'];
				}
			}
			if(isset($myArray['item'][$i]['TivoElement'])){
				if(!(is_array($myArray['item'][$i]['TivoElement']))){
					$foo['tivoelement'][] = $myArray['item'][$i]['TivoElement'];
				}
			}
			if(isset($myArray['item'][$i]['TivoAttribute'])){
				if(!(is_array($myArray['item'][$i]['TivoAttribute']))){
					$foo['tivoattribute'][] = $myArray['item'][$i]['TivoAttribute'];
				}
			}  
			if(isset($myArray['item'][$i]['EventDescription'])){
				if(!(is_array($myArray['item'][$i]['EventDescription']))){
					$foo['eventdescription'][] = $myArray['item'][$i]['EventDescription'];
				}
			} 
			if(isset($myArray['item'][$i]['CustomValue'])){
				if(!(is_array($myArray['item'][$i]['CustomValue']))){
					$foo['customvalue'][] = $myArray['item'][$i]['CustomValue'];
				}
			} 
			if(isset($myArray['item'][$i]['DateTimeFormat'])){
				if(!(is_array($myArray['item'][$i]['DateTimeFormat']))){
					$foo['datetimeformat'][] = $myArray['item'][$i]['DateTimeFormat'];
				}
			} 			
		}
		
		$var1 = '<b>colName=</b> ' . join(',', $foo['colname']).'<br>';
		$var2 = '<b>TivoElement=</b> ' . join(',', $foo['tivoelement']).'<br>';
		$var3 = '<b>TivoAttribute=</b> ' . join(',', $foo['tivoattribute']).'<br>';
		$var4 = '<b>EventDescription=</b> ' . join(',', $foo['eventdescription']).'<br>';
		$var5 = '<b>CustomValue=</b> ' . join(',', $foo['customvalue']).'<br>';
		$var6 = '<b>DateTimeFormat=</b> ' . join(',', $foo['datetimeformat']).'<br>';
		$var = $var1.$var2.$var3.$var4.$var5.$var6;
		
		echo json_encode($var); 
		exit();	 
	} */ 
	
	public function check_upcfg() 
	{
		/* $cfgfile = $this->common->nohtml($this->input->get("cfgfile", true)); */
		$cfgfile = $this->input->get("cfgfile", true); 
		$cfgat = $this->input->get("cfgat", true); 
		$rid = intval($this->input->get("rid", true));  
		
		$this->load->model("projects_model");
		$projectimg = $this->projects_model->getprojectsimage($rid);
		$frmcfgimg = $projectimg->result_array();
		/* print_r($frmcfgimg); */
		foreach($frmcfgimg as $cfgimg){
			/* $linkupload = base_url().'/'.$this->settings->info->upload_path_relative.'/'. $cfgimg['image'];
			 */
			$linkupload = 'http://localhost:81/uploadfiles/ConfigFiles/'. $cfgimg['image'];
		} 
		/* echo $linkupload;
		exit; */
		
		/* $fileContents = file_get_contents($cfgat); */ 
		$fileContents = file_get_contents($linkupload); 
		$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
		$fileContents = trim(str_replace('"', "'", $fileContents)); 
		$simpleXml = simplexml_load_string($fileContents);
		$json = json_encode($simpleXml);
		$myArray = json_decode(json_encode($simpleXml), true); 
		/*  echo "<pre>";
		print_r($myArray['item']);
		echo "</pre>";  */  
		
		$tab = '';
		$tab .= '<table id="cfg">';
		$tab .= '<tr>
					<th><b>colName</b></th>
					<th><b>TivoElement</b></th>
					<th><b>TivoAttribute</b></th>
					<th><b>EventDescription</b></th>
					<th><b>CustomValue</b></th>
					<th><b>DateTimeFormat</b></th>
				 </tr>';
		
		
		$foo = array();		
		$arrayLength = count($myArray['item']);
		for ($i = 0; $i <$arrayLength; $i++) {
			$tab .= '<tr>';
			if(isset($myArray['item'][$i]['colName'])){
				if(!(is_array($myArray['item'][$i]['colName']))){
					$foo['colname'][] = $myArray['item'][$i]['colName'];
					$tab .= '<td>'.$myArray['item'][$i]['colName'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			} else{
					$tab .= '<td> </td>';
			}
			if(isset($myArray['item'][$i]['TivoElement'])){
				if(!(is_array($myArray['item'][$i]['TivoElement']))){
					$foo['tivoelement'][] = $myArray['item'][$i]['TivoElement'];
					$tab .= '<td>'.$myArray['item'][$i]['TivoElement'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			} else{
					$tab .= '<td> </td>';
			}
			if(isset($myArray['item'][$i]['TivoAttribute'])){
				if(!(is_array($myArray['item'][$i]['TivoAttribute']))){
					$foo['tivoattribute'][] = $myArray['item'][$i]['TivoAttribute'];
					$tab .= '<td>'.$myArray['item'][$i]['TivoAttribute'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			} else{
					$tab .= '<td> </td>';
			}
			if(isset($myArray['item'][$i]['EventDescription'])){
				if(!(is_array($myArray['item'][$i]['EventDescription']))){
					$foo['eventdescription'][] = $myArray['item'][$i]['EventDescription'];
					$tab .= '<td>'.$myArray['item'][$i]['EventDescription'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			} else{
					$tab .= '<td> </td>';
			}
			if(isset($myArray['item'][$i]['CustomValue'])){
				if(!(is_array($myArray['item'][$i]['CustomValue']))){
					$foo['customvalue'][] = $myArray['item'][$i]['CustomValue'];
					$tab .= '<td>'.$myArray['item'][$i]['CustomValue'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			}  else{
					$tab .= '<td> </td>';
			}
			if(isset($myArray['item'][$i]['DateTimeFormat'])){
				if(!(is_array($myArray['item'][$i]['DateTimeFormat']))){
					$foo['datetimeformat'][] = $myArray['item'][$i]['DateTimeFormat'];
					$tab .= '<td>'.$myArray['item'][$i]['DateTimeFormat'].'</td>';
				} else{
					$tab .= '<td> </td>';
				}
			} else{
					$tab .= '<td> </td>';
			} 			
		$tab .= '</tr>';
		}
		
		$tab .= '</table>';
		
		/* $var1 = '<b>colName=</b> ' . join(',', $foo['colname']).'<br>';
		$var2 = '<b>TivoElement=</b> ' . join(',', $foo['tivoelement']).'<br>';
		$var3 = '<b>TivoAttribute=</b> ' . join(',', $foo['tivoattribute']).'<br>';
		$var4 = '<b>EventDescription=</b> ' . join(',', $foo['eventdescription']).'<br>';
		$var5 = '<b>CustomValue=</b> ' . join(',', $foo['customvalue']).'<br>';
		$var6 = '<b>DateTimeFormat=</b> ' . join(',', $foo['datetimeformat']).'<br>';
		$var = $var1.$var2.$var3.$var4.$var5.$var6; */
		
		echo json_encode($tab); 
		exit();	 
	}
	
	public function exportallprojects($page = "index") 
	{
		$this->load->model("projects_model");
		$this->load->model("team_model");
		$filename = "projects_export_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		if($page == "index") {
			$project_export = $this->projects_model->projectssexport($this->user->info->ID);
		} elseif($page == "all") {
			$project_export = $this->projects_model->projectssexportall();
		} elseif($page == "client") {
			$userid = intval($this->input->get("userid"));
			$project_export = $this->projects_model->projectssexport($userid);
		}
		/* print_r($project_export->result()); */
		$items = array();
		foreach($project_export->result() as $r) {			
			/* $longest_duedate = $this->projects_model->get_longest_duedate($r->ID);
			$l_duedate = '';
			foreach($longest_duedate->result() as $duedate) {
				$l_duedate =  $duedate->due_date;
			}
			
			$shortest_startdate = $this->projects_model->get_shortest_startdate($r->ID);
			$s_startdate = '';
			foreach($shortest_startdate->result() as $startdate) {
				$s_startdate =  $startdate->start_date;
			}
			
			$sum_price_project_tasks = $this->projects_model->get_sum_price_project_tasks($r->ID);
			foreach($sum_price_project_tasks->result() as $sum_price) {
				$totalPages =  $sum_price->totalPages;
				$totalPagesPrice =  $sum_price->totalPagesPrice; 
			} */
			/* $member_string = '';
			$members = $this->team_model->get_members_for_project($r->ID);
    		$our_user = new STDclass;
			$members_i = 0;
			$len = count($members->result());
    		foreach($members->result() as $member) {
    			if($member->userid == $this->user->info->ID) $our_user = $member;
    			$member_string .= ucfirst($member->username);
				if ($members_i != $len - 1) {$member_string .= ',';}
				$members_i++;
    		}
			foreach($members->result() as $member) {
    			$user_id[] = $member->userid;
			}
			$team_members_by_userid = $this->team_model->get_team_members_by_userid($user_id);
			$team_name = '';
			$team_i = 0;
			$team_len = count($team_members_by_userid->result());
			foreach($team_members_by_userid->result() as $team_members) {
				$team_name .= ucfirst($team_members->team_name);
				if ($team_i != $team_len - 1) {$team_name .= ',';}
				$team_i++;
			} */
			
				$project_details_ex = [];
				$project_details_ex['source_id'] = $r->source_id;
				$project_details_ex['source_name'] = $r->source_name;
				$project_details_ex['transition_status'] = $r->transition_status; 
				$project_details_ex['file_format'] = $r->format_used;
				$project_details_ex['team_name'] = $r->team_name;
				$project_details_ex['broadcast_day_start_time'] = $r->broadcast_day_start_time;
				$project_details_ex['channel_config'] = $r->channel_config;
				$project_details_ex['reporter'] = $r->user_name;
				/* $project_details_ex['s_startdate'] = $s_startdate;
				$project_details_ex['l_duedate'] = $l_duedate;
				$project_details_ex['totalPages'] = $totalPages;
				$project_details_ex['totalPagesPrice'] = $totalPagesPrice;
				$project_details_ex['team'] = $team_name;
				$project_details_ex['users'] = $member_string;
				$project_details_ex['progress'] = $r->progress_percent; */
				$project_details_ex['created_date'] = $r->created_date;  
			
			 $items[] = $project_details_ex;
			
		}
		/* print_r($items); die;  */
		//if ($items->num_rows() > 0) {
			foreach($items as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		//}
		exit;
	}
	
	public function upload_projects() 
    {
		set_time_limit(0);
		error_reporting(E_ALL);  
		$this->load->model("projects_model");
		try{
			if (isset($_POST["uploadpro"])) { 
				$path = 'uploads/';
				require_once APPPATH . "third_party\PHPExcel\Classes\PHPExcel.php";
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'xlsx|xls';
				$config['remove_spaces'] = TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);            
				if (!$this->upload->do_upload('uploadprojects')) {
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
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                $flag = true;
                $i=0;
                foreach ($allDataInSheet as $value) {
                  if($flag){
                    $flag =false;
                    continue;
                  }
								
                  $inserdata['name'] = $value['A'];
                  $inserdata['image'] = 'default.png';
                  $inserdata['userid'] = $this->user->info->ID;
                  $inserdata['description'] = $value['B'];
                  $inserdata['catid'] = 6;
                  $inserdata['customer'] = $value['C'];
                  $inserdata['publisher'] = $value['D'];
				  $inserdata['acronym'] = $value['E'];
                  $inserdata['process_name'] = $value['F'];
                  $inserdata['geofacets_process_name'] = $value['G'];    
                  $inserdata['stage'] = $value['H'];    
                  $inserdata['pdfType'] = $value['I'];    
                  $inserdata['geofacets_pdfType'] = $value['J'];    
                  $inserdata['pm'] = $value['K'];    
                  $inserdata['timestamp'] = time();    
                  $inserdata['status'] = 0;    
                  $inserdata['complete'] = 0;    
                  $inserdata['complete_sync'] = 1;    
                  $i++;
                                
				$result = $this->projects_model->importprojectsdata($inserdata);
				
				$this->team_model->add_member(array(
					"projectid" => $result,
					"userid" => $this->user->info->ID,
					"roleid" => 1
					)
				);  
                if($result){
					$this->session->set_flashdata('success', 'Imported successfully');
					redirect(site_url("projects/index"));
                }else{
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("projects/index"));
                }
				}
				} else {
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("projects/index"));
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
	
	public function upload_tivo_projects() 
    {
		set_time_limit(0);
		error_reporting(E_ALL);  
		$this->load->model("projects_model");
		try{
			if (isset($_POST["uploadtivopro"])) { 
				$path = 'uploads/';
				require_once APPPATH . "third_party\PHPExcel\Classes\PHPExcel.php";
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'xlsx|xls';
				$config['remove_spaces'] = TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);            
				if (!$this->upload->do_upload('uploadtivoprojects')) {
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
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                $flag = true;
                $i=0;
                foreach ($allDataInSheet as $value) {
                  if($flag){
                    $flag =false;
                    continue;
                  }
								
                  $inserdata['customer'] = $value['A'];
                  $inserdata['name'] = $value['B'];
                  $inserdata['image'] = 'default.png';
                  $inserdata['userid'] = $this->user->info->ID;
                  $inserdata['description'] = $value['C'];
                  $inserdata['catid'] = 6;
                  $inserdata['frequency'] = $value['D']; 
                  $inserdata['process_name'] = $value['E'];  
                  $inserdata['pm'] = $value['F'];    
                  $inserdata['country '] = $value['G'];    
                  $inserdata['timestamp'] = time();    
                  $inserdata['status'] = 0;    
                  $inserdata['complete'] = 0;    
                  $inserdata['complete_sync'] = 1;    
                  $inserdata['tivo'] = 1;    
                  $inserdata['calendar_id'] = '';    
                  $inserdata['calendar_color'] = '';  
                  $i++;
                                
				$result = $this->projects_model->importprojectsdata($inserdata);
				
				$this->team_model->add_member(array(
					"projectid" => $result,
					"userid" => $this->user->info->ID,
					"roleid" => 1
					)
				);  
                if($result){
					$this->session->set_flashdata('success', 'Imported successfully');
					redirect(site_url("projects/index"));
                }else{
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("projects/index"));
                } 
				}
				} else {
					$this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("projects/index"));
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
	
	public function import_tivo_projects() 
    {
        set_time_limit(0);
        error_reporting(E_ALL);
		$this->load->model("projects_model");
        try {
            if (isset($_POST["import_tivo_projects"])) {
                $filename = $_FILES["file"]["tmp_name"];
				$mimes = array('application/vnd.ms-excel','text/csv', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip');
                if (($_FILES["file"]["size"] > 0) && in_array($_FILES['file']['type'],$mimes)) {
                    $file = fopen($filename, "r");
					$header = fgetcsv($file);
					/* 
					'image' => 'default.png',  
					'description' => isset($record["long_name"]) ? $record["long_name"] : NULL, 
					'frequency' => isset($record["frequency"]) ? $record["frequency"] : NULL, 
												
					*/
                    while (($importdata = fgetcsv($file)) !== FALSE) {
					$record = array_combine($header, $importdata);	
							$data = array(
                                'customer' => isset($record["source_id"]) ? $record["source_id"] : NULL, 
                                'name' => isset($record["source_name"]) ? $record["source_name"] : NULL, 
								'userid' => $this->user->info->ID,
								'catid' => isset($record["transition_status"]) ? $record["transition_status"] : NULL,
								'process_name' => isset($record["format_used"]) ? $record["format_used"] : NULL,
								'team_name' => isset($record["team_name"]) ? $record["team_name"] : NULL,
								'user_name' => isset($record["user_name"]) ? $record["user_name"] : NULL,
								'broadcast_day_start_time' => isset($record["broadcast_day_start_time"]) ? $record["broadcast_day_start_time"] : NULL, 
								'timestamp' => time(),
								'status' => 0,
								'complete' => 0,
								'complete_sync' => 0,
								'image' => 'default.png',
								'tivo' => 1
								);
                            $insert = $this->projects_model->add_project($data);
							
							$this->team_model->add_member(array(
								"projectid" => $insert,
								"userid" => $this->user->info->ID,
								"roleid" => 1
								)
							); 

                    }
                    fclose($file);
					$this->session->set_flashdata('success', 'Your '. $_FILES['file']['name'] . ' uploaded successfully!');
                    redirect(site_url("projects/index"));
                } else {
                    $this->session->set_flashdata('error', 'Problem in uploading the file!');
                    redirect(site_url("projects/index"));
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
	
	public function import_source_auto_populate() 
    {
        set_time_limit(0);
        error_reporting(E_ALL);
		$this->load->model("projects_model");
        try {
            if (isset($_POST["import_auto_populate"])) {
                $filename = $_FILES["file"]["tmp_name"];
				$mimes = array('application/vnd.ms-excel','text/csv', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip');
                if (($_FILES["file"]["size"] > 0) && in_array($_FILES['file']['type'],$mimes)) {
                    $file = fopen($filename, "r");
					$header = fgetcsv($file); 
                    while (($importdata = fgetcsv($file)) !== FALSE) {
					$record = array_combine($header, $importdata);	
							$data = array(
                                'source_id' => isset($record["source_id"]) ? $record["source_id"] : NULL, 
                                'source_name' => isset($record["source_name"]) ? $record["source_name"] : NULL,    
								'long_name' => isset($record["long_name"]) ? $record["long_name"] : NULL, 
								'scheduling_team' => isset($record["scheduling_team"]) ? $record["scheduling_team"] : NULL 
								);
                            $insert = $this->projects_model->add_source_autopopulate($data); 
                    }
                    fclose($file);
					$this->session->set_flashdata('success', 'Your '. $_FILES['file']['name'] . ' uploaded successfully!');
                    redirect(site_url("projects/index"));
                } else {
                    $this->session->set_flashdata('error', 'Problem in uploading the file!');
                    redirect(site_url("projects/index"));
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
	
	public function ajaxcall() 
	{
		$source_name = $this->input->post('sourcename');
		$this->load->model("projects_model");
		$srcdetails_by_sourcename = $this->projects_model->get_srcdetails_by_sourcename($source_name);
		echo json_encode($srcdetails_by_sourcename);
		/* $shift_users = $this->machine_model->get_user_list_from_team_name($team_name); 
		echo json_encode($shift_users); */
	}
}

?>