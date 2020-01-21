<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("documentation_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");

		if(!$this->settings->info->doc_view_all) {
			if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		}
		
		$this->template->loadData("activeLink", 
			array("doc" => array("general" => 1)));

		$this->template->set_error_view("client/client_error.php");
		$this->template->set_layout("client/client_layout.php");

	}

	public function index($projectid = 0) 
	{
		
		$this->template->loadContent("client/index.php", array(
			)
		);
	}

	public function documentation() 
	{
		if($this->settings->info->doc_view_all) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			// Get projects
			// If user is Admin, Project-Admin or Finance manager let them
			// view all projects
			if($this->common->has_permissions(
				array("admin", "project_admin", "doc_manage"), $this->user
				)
			) {
				$projects = $this->projects_model->get_all_active_projects();
			} else {
				$projects = $this->projects_model
					->get_projects_user_all_no_pagination($this->user->info->ID, 
						"(pr2.admin = 1 OR pr2.doc = 1)");
			}
		}

		$this->template->loadContent("client/projects.php", array(
			"projects" => $projects
			)
		);
	}

	public function view_docs($projectid) 
	{
		$projectid = intval($projectid);

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		if($this->settings->info->doc_view_all) {

		} else {
			// Check user is member of the project
			$this->common->check_permissions(
				lang("error_289"), 
				array("admin", "project_admin", "doc_manage"), // User Roles
				array("admin", "doc"),  // Team Roles
				$projectid
			);
		}

		$documents = $this->documentation_model->get_documents_no_limit_links($projectid);

		$sidebar = $this->load->view("client/doc_sidebar.php",array("project" => $project,
			"documents" => $documents),true);
		
		$this->template->loadContent("client/view.php", array(
			"project" => $project,
			"sidebar" => $sidebar
			)
		);
	}

	public function view_doc($id) 
	{
		$id = intval($id);
		$document = $this->documentation_model->get_document($id);
		if($document->num_rows() == 0) {
			$this->template->error(lang("error_290"));
		}
		$document = $document->row();

		$project = $this->projects_model->get_project($document->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		if($this->settings->info->doc_view_all) {

		} else {
			// Check user is member of the project
			$this->common->check_permissions(
				lang("error_289"), 
				array("admin", "project_admin", "doc_manage"), // User Roles
				array("admin", "doc"),  // Team Roles
				$project->ID
			);
		}


		if($document->link_documentid > 0) {
			$files = $this->documentation_model->get_files($document->link_documentid);
		} else {
			$files = $this->documentation_model->get_files($id);
		}


		$documents = $this->documentation_model->get_documents_no_limit_links($document->projectid);

		$sidebar = $this->load->view("client/doc_sidebar.php",array("project" => $project,
			"documents" => $documents),true);

		$this->template->loadContent("client/document.php", array(
			"project" => $project,
			"document" => $document,
			"files" => $files,
			"sidebar" => $sidebar
			)
		);
	}

}

?>