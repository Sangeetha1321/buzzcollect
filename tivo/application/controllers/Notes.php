<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("notes_model");
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
			lang("error_143"), 
			array("admin", "project_admin", "notes_manage", 
			"notes_worker"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index($projectid = 0) 
	{
		$_SESSION['n_page'] = "index";
		$this->common->check_permissions(
			lang("error_143"), 
			array("admin", "project_admin", "notes_manage"), // User Roles
			array(), // Team Roles
			0  
		);

		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}
		
		
		$this->template->loadData("activeLink", 
			array("notes" => array("general" => 1)));


		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "notes_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.notes = 1)");
		}

		$this->template->loadContent("notes/index.php", array(
			"projects" => $projects,
			"page" => "index",
			"projectid" => $projectid
			)
		);
	}

	public function your($projectid =0) 
	{
		$_SESSION['n_page'] = "your";

		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}
		
		
		$this->template->loadData("activeLink", 
			array("notes" => array("your" => 1)));

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "notes_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.notes = 1)");
		}


		$this->template->loadContent("notes/index.php", array(
			"projects" => $projects,
			"page" => "your",
			"projectid" => $projectid
			)
		);
	}

	public function personal($projectid =0) 
	{
		$_SESSION['n_page'] = "personal";

		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}
		
		
		$this->template->loadData("activeLink", 
			array("notes" => array("personal" => 1)));

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "notes_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.notes = 1)");
		}


		$this->template->loadContent("notes/index.php", array(
			"projects" => $projects,
			"page" => "personal",
			"projectid" => $projectid
			)
		);
	}


	public function add_note() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$body = $this->lib_filter->go($this->input->post("note"));
		$projectid = intval($this->input->post("projectid"));
		$personal = intval($this->input->post("personal"));
		$pinned = intval($this->input->post("pinned"));
		$type = intval($this->input->post("type"));

		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("error_144"), 
			array("admin", "project_admin", "notes_manage"), // User Roles
			array("admin", "notes"),  // Team Roles
			$projectid
		);

		$noteid = $this->notes_model->add_note(array(
			"title" => $title,
			"body" => $body,
			"projectid" => $projectid,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"last_updated_timestamp" => time(),
			"personal" => $personal,
			"pinned" => $pinned,
			"type" => $type
			)
		);

		if($type == 1) {
			// Get TODO fields
			$todo_count = intval($this->input->post("todo_count"));
			for($i=0;$i<=$todo_count;$i++) {
				$todo = $this->common->nohtml($this->input->post("todo_".$i));
				if(!empty($todo)) {
					$this->notes_model->add_note_todo(array(
						"todo" => $todo,
						"noteid" => $noteid,
						"timestamp" => time()
						)
					);
				}
			}
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1040") . $title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "notes"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_64"));
		if(isset($_SESSION['n_page'])) {
			$page = $this->common->nohtml($_SESSION['n_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("notes/" . $page));
	}

	public function edit_note($id) 
	{
		$this->template->loadData("activeLink", 
			array("notes" => array("general" => 1)));
		
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_146"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		// Get projects
		// If user is Admin, Project-Admin or Finance manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "notes_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.notes = 1)");
		}

		$todos = $this->notes_model->get_note_todos($note->ID);

		$this->template->loadContent("notes/edit_note.php", array(
			"projects" => $projects,
			"note" => $note,
			"todos" => $todos
			)
		);
	}

	public function edit_note_pro($id) 
	{
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_147"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->error(lang("error_147"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$body = $this->lib_filter->go($this->input->post("note"));
		$projectid = intval($this->input->post("projectid"));
		$personal = intval($this->input->post("personal"));
		$pinned = intval($this->input->post("pinned"));
		$type = intval($this->input->post("type"));

		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("error_147"), 
			array("admin", "project_admin", "notes_manage"), // User Roles
			array("admin", "notes"),  // Team Roles
			$projectid
		);

		$this->notes_model->update_note($id, array(
			"title" => $title,
			"body" => $body,
			"projectid" => $projectid,
			"last_updated_timestamp" => time(),
			"last_updated_userid" => $this->user->info->ID,
			"personal" => $personal,
			"pinned" => $pinned,
			"type" => $type
			)
		);

		if($type == 1) {
			// Get previous todos
			$todos = $this->notes_model->get_note_todos($note->ID);
			foreach($todos->result() as $r) {
				$todo = $this->common->nohtml($this->input->post("todo_l_" . $r->ID));
				$status = intval($this->input->post("todo_status_" . $r->ID));

				if($status == 0) {
					$completed = 0;
				} else {
					if($r->status == 0 && $status == 1) { 
						$completed = time();
					} else {
						$completed = $r->completed;
					}
				} 

				if(empty($todo)) {
					$this->notes_model->delete_todo($r->ID);
				} else {
					$this->notes_model->update_todo($r->ID, array(
						"todo" => $todo,
						"status" => $status,
						"completed" => $completed
						)
					);
				}
			}

			// new todos
			// Get TODO fields
			$todo_count = intval($this->input->post("todo_count"));
			for($i=0;$i<=$todo_count;$i++) {
				$todo = $this->common->nohtml($this->input->post("todo_".$i));
				$status = intval($this->input->post("todo_s_" . $i));

				if($status == 0) {
					$completed = 0;
				} else {
					$completed = time();
				}
				if(!empty($todo)) {
					$this->notes_model->add_note_todo(array(
						"todo" => $todo,
						"noteid" => $note->ID,
						"timestamp" => time(),
						"status" => $status,
						"completed" => $completed
						)
					);
				}
			}
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1041") . $title,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "notes"
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_65"));
		if(isset($_SESSION['n_page'])) {
			$page = $this->common->nohtml($_SESSION['n_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("notes/" . $page));
	}

	public function edit_note_ajax($id) 
	{
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_147"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->errori(lang("error_147"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$body = $this->lib_filter->go($this->input->post("note"));
		$projectid = intval($this->input->post("projectid"));

		if(empty($title)) {
			$this->template->error(lang("error_106"));
		}

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$this->common->check_permissions(
			lang("error_147"), 
			array("admin", "project_admin", "notes_manage"), // User Roles
			array("admin", "notes"),  // Team Roles
			$projectid
		);

		$this->notes_model->update_note($id, array(
			"title" => $title,
			"body" => $body,
			"projectid" => $projectid,
			"last_updated_timestamp" => time(),
			"last_updated_userid" => $this->user->info->ID
			)
		);

		echo json_encode(array("success" => 1));
		exit();
	}

	public function delete_note($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_146"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->error(lang("error_147"));
		}

		$this->notes_model->delete_note($id);
		$this->session->set_flashdata("globalmsg", lang("success_66"));

		if(isset($_SESSION['n_page'])) {
			$page = $this->common->nohtml($_SESSION['n_page']);
		} else {
			$page = "index";
		}
		redirect(site_url("notes/" . $page));
	}

	public function view($id) 
	{
		$this->template->loadData("activeLink", 
			array("notes" => array("general" => 1)));
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin", "project_admin",
		 "team_manage", "notes_manage"), 
			$this->user)) {
					$this->template->error(lang("error_147"));
			}
		}

		$todos = $this->notes_model->get_note_todos($note->ID);

		$this->template->loadContent("notes/view_note.php", array(
			"note" => $note,
			"todos" => $todos
			)
		);
	}

	public function notes_page_pro($page="index", $projectid=0) 
	{
		$page = $this->common->nohtml($page);
		$projectid = intval($projectid);

		$this->load->library("datatables");

		$this->datatables->set_default_order("notes.last_updated_timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"notes.title" => 0
				 ),
				 2 => array(
				 	"projects.name" => 0
				 ),
				 3 => array(
				 	"notes.last_updated_timestamp" => 0
				 )
			)
		);

		if($page == "index") {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array(), // Team Roles
				0  
			);
			$this->datatables->set_total_rows(
				$this->notes_model->get_all_notes_total($projectid)
			);

			$notes = $this->notes_model->get_all_notes($projectid ,$this->datatables);
		} elseif($page == "personal") {
			$this->datatables->set_total_rows(
				$this->notes_model
					->get_all_notes_personal_total($this->user->info->ID, $projectid)
			);
			$notes = $this->notes_model
				->get_all_notes_personal($this->user->info->ID, $projectid, $this->datatables);
		} elseif($page == "client") {
			// Project id is userid in this scenario
			$this->datatables->set_total_rows(
				$this->notes_model
					->get_all_notes_personal_total($projectid,0)
			);
			$notes = $this->notes_model
				->get_all_notes_personal($projectid,0, $this->datatables);
		} else {
			$this->datatables->set_total_rows(
				$this->notes_model
					->get_all_notes_project_total($this->user->info->ID, $projectid)
			);
			$notes = $this->notes_model
				->get_all_notes_project($this->user->info->ID, $projectid, $this->datatables);
		}

		foreach($notes->result() as $r) {
			if($r->last_updated_timestamp > 0) {
				$timestamp = date($this->settings->info->date_format, $r->last_updated_timestamp);
			} else {
				$timestamp = date($this->settings->info->date_format, $r->timestamp);
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("avatar" => $r->avatar, "username" => $r->username, "online_timestamp" => $r->online_timestamp)),
				$r->title,
				$r->projectname,
				$timestamp,
				'<a href="'.site_url("notes/view/" . $r->ID).'" class="btn btn-info btn-xs" title="'.lang("ctn_555").'"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-list-alt"></span></a> <a href="'.site_url("notes/edit_note/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_52").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("notes/delete_note/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
				);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_todo_note($id) 
	{
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->error(lang("error_147"));
		}

		// Get TODO fields
		$todo_count = intval($this->input->post("todo_count"));
		for($i=0;$i<=$todo_count;$i++) {
			$todo = $this->common->nohtml($this->input->post("todo_".$i));
			if(!empty($todo)) {
				$this->notes_model->add_note_todo(array(
					"todo" => $todo,
					"noteid" => $note->ID,
					"timestamp" => time()
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_158"));
		redirect(site_url("notes/view/" . $note->ID));

	}

	public function update_todo_item($id, $hash, $dash=0) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$id = intval($id);
		$todo = $this->notes_model->get_todo_item($id);
		if($todo->num_rows() == 0) {
			$this->template->jsonError(lang("error_282"));
		}
		$todo = $todo->row();

		$note = $this->notes_model->get_note($todo->noteid);
		if($note->num_rows() == 0) {
			$this->template->jsonError(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->jsonError(lang("error_147"));
		}

		$value = intval($this->input->get("value"));

		if($value == 0) {
			$completed = 0;
		} else {
			$completed = time();
		}

		$todo->status = $value;
		$todo->completed = $completed;

		$this->notes_model->update_todo($id, array(
			"status" => $value,
			"completed" => $completed
			)
		);

		if($dash == 0) {
			$display = $this->template->loadAjaxReturn("notes/note_todo.php", array(
				"r" => $todo
				)
			);
		} else {
			$display = $this->template->loadAjaxReturn("notes/note_todo_dash.php", array(
				"r" => $todo
				)
			);
		}
		

		echo json_encode(array("success" => 1, "display" => $display));
		exit();
	}

	public function delete_todo_item($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$id = intval($id);
		$todo = $this->notes_model->get_todo_item($id);
		if($todo->num_rows() == 0) {
			$this->template->jsonError(lang("error_282"));
		}
		$todo = $todo->row();

		$note = $this->notes_model->get_note($todo->noteid);
		if($note->num_rows() == 0) {
			$this->template->jsonError(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->jsonError(lang("error_147"));
		}

		$this->notes_model->delete_todo($id);

		echo json_encode(array("success" => 1));
		exit();
	}

	public function add_todo_item($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->jsonError(lang("error_6"));
		}
		$id = intval($id);
		$note = $this->notes_model->get_note($id);
		if($note->num_rows() == 0) {
			$this->template->jsonError(lang("error_145"));
		}
		$note = $note->row();

		if($note->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_143"), 
				array("admin", "project_admin", "notes_manage"), // User Roles
				array("admin"),  // Team Roles
				$note->projectid
			);
		}

		if($note->personal && $note->userid != $this->user->info->ID) {
			$this->template->jsonError(lang("error_147"));
		}

		$todo = $this->common->nohtml($this->input->get("todo"));
		if(!empty($todo)) {
			$this->notes_model->add_note_todo(array(
				"todo" => $todo,
				"noteid" => $note->ID,
				"timestamp" => time()
				)
			);
		}

		echo json_encode(array("success" => 1));
		exit();
		
	}

}

?>