<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documentation extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("documentation_model");
		$this->load->model("projects_model");
		$this->load->model("team_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("doc" => array("general" => 1)));

		if(!$this->common->has_permissions(array(
			"admin","project_admin", "doc_manage", "doc_worker"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
	}

	public function index($projectid = 0) 
	{
		$this->template->loadData("activeLink", 
			array("doc" => array("general" => 1)));


		$projectid = intval($projectid);

		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
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
					"(pr2.admin = 1 OR pr2.doc = 1)");
		}

		$project = null;
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$this->common->check_permissions(
				lang("error_289"), 
				array("admin", "project_admin", "doc_manage"), // User Roles
				array("admin", "doc"),  // Team Roles
				$projectid
			);
		}

		$this->template->loadContent("documentation/index.php", array(
			"projects" => $projects,
			"project" => $project
			)
		);
	}

	public function documentation_page($projectid = 0) 
	{
		$projectid = intval($projectid);

		if($projectid > 0) {
			$this->common->check_permissions(
				lang("error_289"), 
				array("admin", "project_admin", "doc_manage"), // User Roles
				array("admin", "doc"),  // Team Roles
				$projectid
			);
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("documents.last_updated", "DESC");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"documents.title" => 0
				 ),
				 1 => array(
				 	"projects.name" => 0
				 ),
				 2 => array(
				 	"documents.last_updated" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->documentation_model
				->get_documents_total($projectid)
		);
		$docs = $this->documentation_model->get_documents($projectid, $this->datatables);
		

		foreach($docs->result() as $r) {

			$title = $r->title;
			if($r->link_documentid > 0) {
				$title .= ' <a href="'.site_url("documentation/edit_document/" . $r->link_documentid).'">'.lang("ctn_1550").': ['. $r->link_title .']</a>';
			}
			
			$this->datatables->data[] = array(
				$title,
				$r->project_name,
				date($this->settings->info->date_format, $r->last_updated),
				'<a href="'.site_url("client/view_doc/" . $r->ID).'" class="btn btn-info btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("documentation/edit_document/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("documentation/delete_document/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit_document($id) 
	{
		$this->template->loadData("activeLink", 
			array("doc" => array("general" => 1)));

		$id = intval($id);
		$document = $this->documentation_model->get_document($id);
		if($document->num_rows() == 0) {
			$this->template->error(lang("error_290"));
		}
		$document = $document->row();

		$files = $this->documentation_model->get_files($document->ID);

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

		$linked = null;
		if($document->link_documentid > 0) {
			$doc = $this->documentation_model->get_document($document->link_documentid);
			if($doc->num_rows() > 0) {
				$linked = $doc->row();
			}
		}
		
		$this->template->loadContent("documentation/edit_document.php", array(
			"projects" => $projects,
			"document" => $document,
			"files" => $files,
			"linked" => $linked
			)
		);
	}

	public function edit_document_pro($id) 
	{
		$id = intval($id);
		$document = $this->documentation_model->get_document($id);
		if($document->num_rows() == 0) {
			$this->template->error(lang("error_290"));
		}
		$document = $document->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$document = $this->lib_filter->go($this->input->post("document"));
		$projectid = intval($this->input->post("projectid"));

		$link_documentid = intval(
			$this->input->post("link_documentid"));

		if(empty($name)) {
			$this->template->error(lang("error_242"));
		}

		$this->common->check_permissions(
			lang("error_291"), 
			array("admin", "project_admin", "doc_manage"), // User Roles
			array("admin", "doc"),  // Team Roles
			$projectid
		);

		if($link_documentid > 0) {
			$document_link = $this->documentation_model->get_document($link_documentid);
			if($document_link->num_rows() == 0) {
				$this->template->error(lang("error_292"));
			}
			$dl = $document_link->row();
			if($dl->link_documentid >0) {
				$this->template->error(lang("error_293"));
			}
			if($link_documentid == $id) {
				$this->template->error(lang("error_294"));
			}
		}

		$this->documentation_model->update_document($id, array(
			"title" => $name,
			"document" => $document,
			"projectid" => $projectid,
			"userid" => $this->user->info->ID,
			"last_updated" => time(),
			"link_documentid" => $link_documentid
			)
		);

		$documentid = $id;

		$file_count = intval($this->input->post("file_count"));
		for($i=1;$i<=$file_count;$i++) {
			if(isset($_FILES['userfile_' . $i]) && !empty($_FILES['userfile_' . $i]['tmp_name']) ) 
			{
				$this->load->library("upload");
				$this->upload->initialize(array(
				   "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => $this->settings->info->file_types,
			       "max_size" => $this->settings->info->file_size,
					)
				);

				if ( ! $this->upload->do_upload('userfile_' . $i))
	            {
	                    $this->template->error(lang("error_21") . "<br /><br />" .
	                    	 $this->upload->display_errors());
	            }

	            $data = $this->upload->data();
	            $name = $this->common
	            	->nohtml(trim($data['orig_name']));

	            $this->documentation_model->add_file(array(
	            	"file_name" => $data['file_name'],
	            	"name" => $name,
	            	"documentid" => $documentid,
	            	"file_type" => $data['file_type'],
	            	"extension" => $data['file_ext'],
	            	"file_size" => $data['file_size'],
	            	"userid" => $this->user->info->ID,
	            	"timestamp" => time()
	            	)
	            );
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_163"));
		redirect(site_url("documentation"));
	}

	public function delete_document($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$document = $this->documentation_model->get_document($id);
		if($document->num_rows() == 0) {
			$this->template->error(lang("error_136"));
		}
		$document = $document->row();

		$this->documentation_model->delete_document($id);

		// Reset all linked documents to this document?
		$linked_documents = $this->documentation_model->get_linked_documents($document->ID);
		foreach($linked_documents->result() as $r) {
			// Reset
			$this->documentation_model->update_document($r->ID, array(
				"link_documentid" => 0
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_164"));
		redirect(site_url("documentation"));
	}

	public function delete_file($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$file = $this->documentation_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_141"));
		}
		$file = $file->row();

		$this->documentation_model->delete_file($id);
		$this->session->set_flashdata("globalmsg", lang("success_165"));
		redirect(site_url("documentation/edit_document/" . $file->documentid));
	}

	public function order($projectid = 0 ) {
		$this->template->loadData("activeLink", 
			array("doc" => array("order" => 1)));


		$projectid = intval($projectid);
		if($projectid == 0) {
			$projectid = $this->user->info->active_projectid;
		}

		if($projectid == 0) {
			// Select project
			
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


			$this->template->loadContent("documentation/order_select.php", array(
			"projects" => $projects
				)
			);
		} else {
			$this->common->check_permissions(
				lang("error_291"), 
				array("admin", "project_admin", "doc_manage"), // User Roles
				array("admin", "doc"),  // Team Roles
				$projectid
			);

			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();

			$documents = $this->documentation_model->get_documents_order($projectid);
			$this->template->loadContent("documentation/order.php", array(
				"project" => $project,
				"documents" => $documents
				)
			);
		}
	}

	public function add() 
	{
		$this->template->loadData("activeLink", 
			array("documentation" => array("general" => 1)));

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
		
		$this->template->loadContent("documentation/add.php", array(
			"projects" => $projects
			)
		);
	}

	public function add_pro() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$document = $this->lib_filter->go($this->input->post("document"));
		$projectid = intval($this->input->post("projectid"));

		$link_documentid = intval(
			$this->input->post("link_documentid"));

		if(empty($name)) {
			$this->template->error(lang("error_295"));
		}

		$this->common->check_permissions(
			lang("error_291"), 
			array("admin", "project_admin", "doc_manage"), // User Roles
			array("admin", "doc"),  // Team Roles
			$projectid
		);

		if($link_documentid > 0) {
			$document_link = $this->documentation_model->get_document($link_documentid);
			if($document_link->num_rows() == 0) {
				$this->template->error(lang("error_292"));
			}
			$dl = $document_link->row();
			if($dl->link_documentid >0) {
				$this->template->error(lang("error_293"));
			}
		}

		$documentid = $this->documentation_model->add_document(array(
			"title" => $name,
			"document" => $document,
			"projectid" => $projectid,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"last_updated" => time(),
			"link_documentid" => $link_documentid
			)
		);

		$file_count = intval($this->input->post("file_count"));
		for($i=1;$i<=$file_count;$i++) {
			if(isset($_FILES['userfile_' . $i]) && !empty($_FILES['userfile_' . $i]['tmp_name']) ) 
			{
				$this->load->library("upload");
				$this->upload->initialize(array(
				   "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => $this->settings->info->file_types,
			       "max_size" => $this->settings->info->file_size,
					)
				);

				if ( ! $this->upload->do_upload('userfile_' . $i))
	            {
	                    $this->template->error(lang("error_21") . "<br /><br />" .
	                    	 $this->upload->display_errors());
	            }

	            $data = $this->upload->data();
	            $name = $this->common
	            	->nohtml(trim($data['orig_name']));

	            $this->documentation_model->add_file(array(
	            	"file_name" => $data['file_name'],
	            	"name" => $name,
	            	"documentid" => $documentid,
	            	"file_type" => $data['file_type'],
	            	"extension" => $data['file_ext'],
	            	"file_size" => $data['file_size'],
	            	"userid" => $this->user->info->ID,
	            	"timestamp" => time()
	            	)
	            );
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_166"));
		redirect(site_url("documentation"));
	}

	public function get_documents($projectid) 
	{
		$projectid = intval($projectid);
		$documents = $this->documentation_model->get_documents_no_limit($projectid);
		$this->template->loadAjax("documentation/ajax_documents_list.php", array(
			"documents" => $documents,
			)
		);
	}

	public function projects() 
	{
		$this->template->loadData("activeLink", 
			array("doc" => array("projects" => 1)));
		
		$this->template->loadContent("documentation/projects.php", array(
			)
		);
	}

	public function project_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("projects.ID", "DESC");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"projects.name" => 0
				 )
			)
		);

		if($this->common->has_permissions(array(
			"admin","project_admin", "doc_manage"), $this->user)) {
			$page = "all";
		} else {
			$page = "index";
		}

		$catid = 0;

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->projects_model
				->get_total_projects_user_all_count($catid, $this->user->info->ID)
			);
			$projects = $this->projects_model->get_projects_user_all($catid, 
			$this->user->info->ID, $this->datatables);
		} elseif($page == "all") {
			if(!$this->common->has_permissions(array("admin", "project_admin", "doc_manage"), 
				$this->user)) {
				$this->template->error(lang("error_71"));
			}
			$this->datatables->set_total_rows(
				$this->projects_model
					->get_total_projects_count($catid)
			);
			$projects = $this->projects_model
				->get_projects($catid, $this->datatables);
		}
		

		foreach($projects->result() as $r) {
			
			$this->datatables->data[] = array(
				'<img src="'.base_url(). $this->settings->info->upload_path_relative . "/" . $r->image .'" width="30">',
				$r->name,
				'<a href="'.site_url("client/view_docs/" . $r->ID).'" class="btn btn-default btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("documentation/pdf/" . $r->ID).'" class="btn btn-default btn-xs">'.lang("ctn_666").'</a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function pdf($projectid) 
	{
		$this->common->check_permissions(
			lang("error_291"), 
			array("admin", "project_admin", "doc_manage"), // User Roles
			array("admin", "doc"),  // Team Roles
			$projectid
		);
		$projectid = intval($projectid);

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$documents = $this->documentation_model->get_documents_no_limit_links($projectid);

		
		// PDF Cover
		ob_start();
		$this->template->loadAjax("documentation/pdf_cover.php", array(
			"project" => $project,
			)
		);
		$cover = ob_get_contents();
		ob_end_clean();


		require_once APPPATH . 'third_party/mpdf/vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf(array(
			"mode" => "UTF-8"
			)
		);

		if(empty($project->name)) {
			$footer = "{PAGENO}";
		} else {
			$footer = $project->name . " - " . '{PAGENO}';
		}

		$mpdf->WriteHTML($cover);
		$mpdf->TOCpagebreakByArray(array(
			"paging" => true,
			"links" => true,
			"toc-preHTML" => "<h1 name='top'>".lang("ctn_1528")."</h1>",
			"toc-odd-footer-value" => "off"

			)
		);

		// find last page
		$last_doc = 0;
		foreach($documents->result() as $d) 
		{
			$last_doc = $d->ID;
		}

		$mpdf->setFooter($footer);
		foreach($documents->result() as $document) 
		{
			$mpdf->TOC_Entry($document->title,0);
			ob_start();
			$this->template->loadAjax("documentation/pdf_document.php", array(
				"document" => $document
				)
			);
			$out = ob_get_contents();
			ob_end_clean();

			$mpdf->WriteHTML($out);

			if($document->ID != $last_doc) {
				$mpdf->addPage();
			}
		}


		//$mpdf->setFooter("");

		$mpdf->Output();

	}

	public function update_order($projectid) 
	{
		$projectid = intval($projectid);
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		$documents = $this->documentation_model->get_documents_order($projectid);
		foreach($documents->result() as $r) {
			$position = $this->get_position($_GET['document'], $r->ID);
			$this->documentation_model->update_document($r->ID, array(
				"sort_no" => $position
				)
			);
		}
		echo 1;
		exit();
	}

	private function get_position($array, $id) 
	{
		$i=0;
		if(!is_array($array)) return 0;
		foreach($array as $order) {
			if($order == $id) {
				return $i;
			}
			$i++;
		}
		return 0;
	}

}

?>