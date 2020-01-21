<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("calendar_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");

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
		 "calendar_manage", "calendar_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
	}

	public function index($projectid = null) 
	{
		$this->template->loadData("activeLink", 
			array("calendar" => array("general" => 1)));

		if($projectid !== null) {
			$projectid = intval($projectid);
		}

		$this->template->loadExternal(
			'<link rel="stylesheet" href="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.css" />
			<script src="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.full.min.js"></script>
			<link rel="stylesheet" href="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.css" />
			<script src="'.base_url().'scripts/libraries/fullcalendar/lib/moment.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/gcal.js"></script>
			<link rel="stylesheet" href="'.base_url().'styles/calendar.css" />'
		);

		if($this->settings->info->calendar_type == 1) {
			// Google Calendar. Let's authorize them first.
			$client = $this->authorise_google_api("calendar");

			if($projectid === null) {
				// Get projects
				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.calendar = 1)");
				$calendar_ids = array();
				foreach($projects->result() as $r) {
					if(!empty($r->calendar_id)) {
						$calendar_ids[] = array(
							"calendar_id" => $r->calendar_id, 
							"color" => $r->calendar_color, 
							"project_name" => $r->name, 
							"projectid" => $r->ID
						);
					}
				}
			} else {
				// Get a specific project calendar
				if($projectid > 0) {
					$project = $this->projects_model->get_project($projectid);
					if($project->num_rows() == 0) {
						$this->template->error(lang("error_72"));
					}
					$project = $project->row();

					$this->common->check_permissions(
						lang("error_73"), 
						array("admin", "project_admin", "calendar_manage"), // User Roles
						array("admin", "calendar"),  // Team Roles
						$projectid
					);
					$calendar_ids = array();
					$calendar_ids[] = array(
							"calendar_id" => $project->calendar_id, 
							"color" => $project->calendar_color, 
							"project_name" => $project->name, 
							"projectid" => $project->ID
					);
				} else {
					$calendar_ids = array();
					// Global Calendar get (does automatically in the view)
				}
				

				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.calendar = 1)");
			}

			$this->template->loadContent("calendar/google_index.php", array(
				"calendar_ids" => $calendar_ids,
				"projects" => $projects,
				"projectid" => $projectid,
				"page" => "index"
				)
			);
		} else {


			if($projectid === null) {
				// Get projects
				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.calendar = 1)");
				$calendar_ids = array();
				foreach($projects->result() as $r) {
						$calendar_ids[] = array(
							"id" => $r->ID, 
							"color" => $r->calendar_color
						);
				}
			} else {
				// Get a specific project calendar
				if($projectid > 0) {
					$project = $this->projects_model->get_project($projectid);
					if($project->num_rows() == 0) {
						$this->template->error(lang("error_72"));
					}
					$project = $project->row();

					$this->common->check_permissions(
						lang("error_73"), 
						array("admin", "project_admin", "calendar_manage"), // User Roles
						array("admin", "calendar"),  // Team Roles
						$projectid
					);
					$calendar_ids = array();
					$calendar_ids[] = array(
							"color" => $project->calendar_color, 
							"project_name" => $project->name, 
							"id" => $project->ID
					);
				} else {
					$calendar_ids = array();
					// Global Calendar get (does automatically in the view)
				}
				

				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.calendar = 1)");
			}
			$this->template->loadContent("calendar/index.php", array(
				"projects" => $projects,
				"calendar_ids" => $calendar_ids,
				"projectid" => $projectid,
				"page" => "index"
				)
			);
		}
	}

	public function all($projectid = null) 
	{
		if(!$this->common->has_permissions(
			array("admin", "project_admin", "calendar_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("calendar" => array("all" => 1)));

		if($projectid !== null) {
			$projectid = intval($projectid);
		}

		$this->template->loadExternal(
			'<link rel="stylesheet" href="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.css" />
			<script src="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.full.min.js"></script>
			<link rel="stylesheet" href="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.css" />
			<script src="'.base_url().'scripts/libraries/fullcalendar/lib/moment.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/gcal.js"></script>
			<link rel="stylesheet" href="'.base_url().'styles/calendar.css" />'
		);

		if($this->settings->info->calendar_type == 1) {
			// Google Calendar. Let's authorize them first.
			$client = $this->authorise_google_api("calendar");


			if($projectid === null) {
				// Get projects
				$projects = $this->projects_model->get_all_active_projects();
				$calendar_ids = array();
				foreach($projects->result() as $r) {
					if(!empty($r->calendar_id)) {
						$calendar_ids[] = array(
							"calendar_id" => $r->calendar_id, 
							"color" => $r->calendar_color, 
							"project_name" => $r->name, 
							"projectid" => $r->ID
						);
					}
				}
			} else {
				// Get a specific project calendar
				if($projectid > 0) {
					$project = $this->projects_model->get_project($projectid);
					if($project->num_rows() == 0) {
						$this->template->error(lang("error_72"));
					}
					$project = $project->row();

					$calendar_ids = array();
					$calendar_ids[] = array(
							"calendar_id" => $project->calendar_id, 
							"color" => $project->calendar_color, 
							"project_name" => $project->name, 
							"projectid" => $project->ID
					);
				} else {
					$calendar_ids = array();
					// Global Calendar get (does automatically in the view)
				}
				

				$projects = $this->projects_model->get_all_active_projects();
			}

			$this->template->loadContent("calendar/google_index.php", array(
				"calendar_ids" => $calendar_ids,
				"projects" => $projects,
				"projectid" => $projectid,
				"page" => "all"
				)
			);
		} else {

			if($projectid === null) {
				// Get projects
				$projects = $this->projects_model->get_all_active_projects();
				$calendar_ids = array();
				foreach($projects->result() as $r) {
						$calendar_ids[] = array(
							"id" => $r->ID, 
							"color" => $r->calendar_color
						);
				}
			} else {
				// Get a specific project calendar
				if($projectid > 0) {
					$project = $this->projects_model->get_project($projectid);
					if($project->num_rows() == 0) {
						$this->template->error(lang("error_72"));
					}
					$project = $project->row();

					$calendar_ids = array();
					$calendar_ids[] = array(
							"color" => $project->calendar_color, 
							"project_name" => $project->name, 
							"id" => $project->ID
					);
				} else {
					$calendar_ids = array();
					// Global Calendar get (does automatically in the view)
				}
				

				$projects = $this->projects_model->get_all_active_projects();
			}
			$this->template->loadContent("calendar/index.php", array(
				"projects" => $projects,
				"calendar_ids" => $calendar_ids,
				"projectid" => $projectid,
				"page" => "all"
				)
			);
		}
	}

	public function get_site_events() 
	{
		$start = $this->common->nohtml($this->input->get("start"));
		$end = $this->common->nohtml($this->input->get("end"));
		$projectid = intval($this->input->get("projectid"));

		$startdt = new DateTime('now'); // setup a local datetime
		$startdt->setTimestamp($start); // Set the date based on timestamp
		$format = $startdt->format('Y-m-d H:i:s');

		$enddt = new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$format2 = $enddt->format('Y-m-d H:i:s');


		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			$color = "#" . $project->calendar_color;
			$project_name = $project->name;
		} else {
			// Global calendar
			$color = "#1caaf3";
			$project_name = lang("ctn_1018");
		}

		$events = $this->calendar_model->get_events($format, 
			$format2, $projectid);
		$data_events = array();
		foreach($events->result() as $r) { 
			$task_name = "";

			if($r->taskid > 0) {
				// Get task
				$this->load->model("task_model");
				$task = $this->task_model->get_task($r->taskid);
				if($task->num_rows() > 0) {
					$task = $task->row();
					$task_name = $task->name;
				}
			}

			$data_events[] = array(
				"id" => $r->ID,
				"title" => $r->title,
				"description" => $r->description,
				"end" => $r->end,
				"start" => $r->start,
				"color" => $color,
				"projectid" => $projectid,
				"project_name" => $project_name,
				"taskid" => $r->taskid,
				"task_name" => $task_name
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	public function add_site_event() 
	{
		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$projectid = intval($this->input->post("projectid"));

		$projectid = intval($this->input->post("projectid"));
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$this->common->check_permissions(
				lang("error_74"), 
				array("admin", "project_admin", "calendar_manage"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		} else {
			$this->common->check_permissions(
				lang("error_74"), 
				array("admin", "project_admin", "calendar_manage", 
					"calendar_worker"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		}
		


		if(empty($name)) {
			$this->template->error(lang("error_75"));
		}

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
			$start_date = $sd->format('Y-m-d H:i:s');
			$start_date_timestamp = $sd->getTimestamp();
		} else {
			$start_date = date("Y-m-d H:i:s", time());
			$start_date_timestamp = time();
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
			$end_date = $ed->format('Y-m-d H:i:s');
			$end_date_timestamp = $ed->getTimestamp();
		} else {
			$end_date = date("Y-m-d H:i:s", time());
			$end_date_timestamp = time();
		}

		$this->calendar_model->add_event(array(
			"title" => $name,
			"description" => $desc,
			"start" => $start_date,
			"end" => $end_date,
			"userid" => $this->user->info->ID,
			"projectid" => $projectid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_38"));
		redirect(site_url("calendar"));
	}

	public function update_site_event() 
	{
		$eventid = intval($this->input->post("eventid"));
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		if($event->projectid > 0) {
			$project = $this->projects_model->get_project($event->projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$this->common->check_permissions(
				lang("error_77"), 
				array("admin", "project_admin", "calendar_manage"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$event->projectid
			);
		} else {
			$this->common->check_permissions(
				lang("error_77"), 
				array("admin", "project_admin", "calendar_manage", 
					"calendar_worker"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$event->projectid
			);
		}

		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$delete = intval($this->input->post("delete"));

		if(!$delete) {
			if(empty($name)) {
				$this->template->error(lang("error_75"));
			}

			if(!empty($start_date)) {
				$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
				$start_date = $sd->format('Y-m-d H:i:s');
				$start_date_timestamp = $sd->getTimestamp();
			} else {
				$start_date = date("Y-m-d\TH:i:s", time());
				$start_date_timestamp = time();
			}

			if(!empty($end_date)) {
				$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
				$end_date = $ed->format('Y-m-d H:i:s');
				$end_date_timestamp = $ed->getTimestamp();
			} else {
				$this->template->error(lang("error_78"));
			}

			$this->calendar_model->update_event($eventid, array(
				"title" => $name,
				"description" => $desc,
				"start" => $start_date,
				"end" => $end_date,
				)
			);
			$this->session->set_flashdata("globalmsg", 
				lang("success_39"));
		} else {
			$this->calendar_model->delete_event($eventid);
			$this->session->set_flashdata("globalmsg", 
				lang("success_40"));
		}
		redirect(site_url("calendar"));

	}

	public function add_google_event() 
	{

		$client = $this->authorise_google_api("calendar");

		$projectid = intval($this->input->post("projectid"));
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			$calendarId = $project->calendar_id;

			$this->common->check_permissions(
				lang("error_79"), 
				array("admin", "project_admin", "calendar_manage"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		} else {
			// Global calendar
			$calendarId = $this->settings->info->google_calendar_id;

			$this->common->check_permissions(
				lang("error_79"), 
				array("admin", "project_admin", "calendar_manage", 
					"calendar_worker"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		}
		

		// Fail safe
		if(!$client) { 
			$this->template->error(lang("error_80"));
		}

		// Connect to calendar
		if(empty($calendarId)) {
			$this->template->error(lang("error_81"));
		}
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

		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));

		if(empty($name)) {
			$this->template->error(lang("error_75"));
		}

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
			$start_date = $sd->format('Y-m-d\TH:i:s');
			$start_date_timestamp = $sd->getTimestamp();
		} else {
			$start_date = date("Y-m-d\TH:i:s", time());
			$start_date_timestamp = time();
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
			$end_date = $ed->format('Y-m-d\TH:i:s');
			$end_date_timestamp = $ed->getTimestamp();
		} else {
			$end_date = date("Y-m-d\TH:i:s", time());
			$end_date_timestamp = time();
		}

		if($start_date > $end_date) {
			$this->template->error(lang("error_84"));
		}


		/* insert into google calendar */
		$event = new Google_Service_Calendar_Event(array(
		  'summary' => $name,
		  'location' => '',
		  'description' => $desc,
		  'start' => array(
		    'dateTime' => $start_date,
		    'timeZone' => $timezone,
		  ),
		  'end' => array(
		    'dateTime' => $end_date,
		    'timeZone' => $timezone,
		  )
		));
		try {
			$event = $cal->events->insert($calendarId, $event);
		} catch(Exception $e) {
			$this->template->error(lang("error_82") . "<br /><br />" .
				$e->getMessage());
		}
		$this->session->set_flashdata("globalmsg", 
			lang("success_41"));
		redirect(site_url("calendar"));

	}

	public function update_google_event() 
	{
		$client = $this->authorise_google_api("calendar");
		$event_calendar_id = $this->common
			->nohtml($this->input->post("event_calendar_id"));

		// Get project by calendar_id
		$project = $this->projects_model
			->get_project_calendarid($event_calendar_id);
		if($project->num_rows() == 0) {
			// Check for global calendar
			if($event_calendar_id != $this->settings->info->google_calendar_id) {
				$this->template->error(lang("error_85"));
			}
			$calendarId = $this->settings->info->google_calendar_id;
			$projectid = 0;

			$this->common->check_permissions(
				lang("error_77"), 
				array("admin", "project_admin", "calendar_manage"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		} else {
			$project = $project->row();
			$projectid = $project->ID;
			$calendarId = $project->calendar_id;

			$this->common->check_permissions(
				lang("error_77"), 
				array("admin", "project_admin", "calendar_manage", 
					"calendar_worker"), // User Roles
				array("admin", "calendar"),  // Team Roles
				$projectid
			);
		}

		// Fail safe
		if(!$client) { 
			$this->template->error(lang("error_80"));
		}

		// Connect to calendar
		if(empty($calendarId)) {
			$this->template->error(lang("error_81"));
		}
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

		$eventid = $this->common->nohtml($this->input->post("eventid"));
		if(empty($eventid)) {
			$this->template->error(lang("error_86"));
		}

		try {
			$event = $cal->events->get($calendarId, $eventid);
		} catch(Exception $e) {
			$this->template->error(lang("error_87") . "<br /><br />" . $e->getMessage());
		}
		if(!$event) {
			$this->template->error(lang("error_88"));
		}

		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$delete = intval($this->input->post("delete"));

		if(!$delete) {
			if(empty($name)) {
				$this->template->error(lang("error_75"));
			}

			if(!empty($start_date)) {
				$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
				$start_date = $sd->format('Y-m-d\TH:i:s');
				$start_date_timestamp = $sd->getTimestamp();
			} else {
				$start_date = date("Y-m-d\TH:i:s", time());
				$start_date_timestamp = time();
			}

			if(!empty($end_date)) {
				$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
				$end_date = $ed->format('Y-m-d\TH:i:s');
				$end_date_timestamp = $ed->getTimestamp();
			} else {
				$this->template->error(lang("error_78"));
			}

			$eventnew = new Google_Service_Calendar_Event(array(
			  'summary' => $name,
			  'location' => '',
			  'description' => $desc,
			  'start' => array(
			    'dateTime' => $start_date,
			    'timeZone' => $timezone,
			  ),
			  'end' => array(
			    'dateTime' => $end_date,
			    'timeZone' => $timezone,
			  )
			));


			// Update event
			try {
				$updatedEvent = $cal->events->update($calendarId, 
					$event->getId(), $eventnew);
			} catch(Exception $e) {
				$this->template->error(lang("error_89") . "<br /><br />" 
					. $e->getMessage());
			}
			$this->session->set_flashdata("globalmsg", 
				lang("success_39"));
		} else {
			try {
				$cal->events->delete($calendarId, $event->getId());
			} catch(Exception $e) {
				$this->template->error(lang("error_90") . "<br /><br />" 
					. $e->getMessage());
			}
			$this->session->set_flashdata("globalmsg", 
				lang("success_40"));
		}
		redirect(site_url("calendar"));

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
			$authUrl = $client->createAuthUrl();
		    redirect($authUrl);
		}
	}

}

?>