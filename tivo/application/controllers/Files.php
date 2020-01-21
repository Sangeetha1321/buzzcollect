<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("file_model");
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
		 "file_manage", "file_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
	}

	public function index($folder_parent = 0, $projectid = -1) 
	{
		$this->template->loadData("activeLink", 
			array("file" => array("general" => 1)));

		$projectid = intval($projectid);
		$folder_parent = intval($folder_parent);
		
		if($projectid == -1) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$folders = array();
		if($folder_parent > 0) {
			// Get folder structure
			$folders = $this->get_folders($folders, $folder_parent);
			$folders = array_reverse($folders);
		}

		if($this->common->has_permissions(
			array("admin", "project_admin", "file_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		}

		$project_status = $this->file_model->monitor_file_status();
		$this->template->loadContent("files/index.php", array(
			"folders" => $folders,
			"folder_parent" => $folder_parent,
			"projectid" => $projectid,
			"projects" => $projects,
			"project_status" => $project_status,
			"page" => "index"
			)
		);
	}

	public function file_page($page, $folder_parent, $projectid = -1, $filter = 0) 
	{
		$folder_parent = intval($folder_parent);
		$projectid = intval($projectid);
		$filter = intval($filter);
		if($projectid == -1) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("project_files.timestamp", "desc");

		/* 0 => array(
				 	"project_files.file_size" => 0
				 ),
				 1 => array(
				 	"project_files.file_type" => 0
				 ), */
		// Set page ordering options that can be used
		$this->datatables->ordering(
			array( 
				 0 => array(
				 	"projects.name" => 0
				 ), 
				 2 => array(
				 	"projects.team_name" => 0
				 ),
				 3 => array(
				 	"project_files.status" => 0
				 ),  
				 4 => array(
				 	"project_files.user_name" => 0
				 ),
				 5 => array(
				 	"project_files.timestamp" => 0
				 ) 
			)
		);

		if($page == "index") {
			if($projectid > 0) {
				$this->datatables->set_total_rows(
					$this->file_model->get_files_total($projectid, 
						$folder_parent, $this->user->info->ID)
				);
				$files = $this->file_model->get_files($projectid, 
					$folder_parent, $this->user->info->ID, $this->datatables);
			} elseif($projectid == 0) {
				$this->datatables->set_total_rows(
					$this->file_model
					->get_files_user_noproject_total($this->user->info->ID, 
							$folder_parent)
				);
				$files = $this->file_model
				->get_files_user_noproject($this->user->info->ID, 
						$folder_parent,$this->datatables);
			} else {
				$this->datatables->set_total_rows(
					$this->file_model
					->get_files_user_projects_total($this->user->info->ID, 
						$folder_parent)
				);
				$files = $this->file_model
				->get_files_user_projects($this->user->info->ID, 
						$folder_parent,$this->datatables);
			}
		} elseif($page == "all") {
			if(!$this->common->has_permissions(array("admin", "project_admin",
			 "file_manage"), 
				$this->user)) 
			{
				$this->template->error(lang("error_71"));
			}
			$this->datatables->set_total_rows(
				$this->file_model
				->get_all_files_total($folder_parent, $projectid, $filter)
			);
			$files = $this->file_model
				->get_all_files($folder_parent, $projectid, $filter, $this->datatables);
		} 

		foreach($files->result() as $r) {
			if(isset($r->project_name)) {
				$project_name = $r->project_name;
			} else {
				$project_name = lang("ctn_46");
			}
			
			$unix_timestamp = $r->timestamp;
			$datetime = new DateTime("@$unix_timestamp");
			$date_time_format = $datetime->format('Y-m-d H:i:s');
			$time_zone_from="UTC";
			$time_zone_to='Asia/Kolkata';
			$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));
			$display_date->setTimezone(new DateTimeZone($time_zone_to));
			
			if($r->folder_flag) {
				$options = '<a href="'.site_url("files/".$page."/" . $r->ID . "/" . $projectid . "/").'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="View File"><span class="glyphicon glyphicon-eye-open"></span></a> ';
				if($r->userid == $this->user->info->ID || ($this->common->has_team_permissions(array("admin"), $r)) || ($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)) ) {
					$options .= '<a href="'.site_url("files/edit_folder/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_507").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-edit"></span></a> <a href="'.site_url("files/delete_folder/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_509").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
				}
					/* 	'',
					lang("ctn_472"), 
					$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
					'<span class="glyphicon glyphicon-folder-close"></span> <a href="'.site_url("files/".$page."/" . $r->ID . "/" . $projectid . "/").'">'.$r->file_name.'</a>',
					$r->broadcast_day_st_time
					*/
				$this->datatables->data[] = array( 
					$project_name,
					$r->format_used,
					$r->team_name,
					'<span class="label label-default" style="background: #'.$r->file_status_color.';">'.$r->file_status.'</span>', 
					$r->user_name,
					$display_date->format('d-m-Y H:i:s'),
					$options
				);
			} else {
				$options = '<a href="'.site_url("files/view_file/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="View File"><span class="glyphicon glyphicon-eye-open"></span></a> ';
				if($r->userid == $this->user->info->ID || ($this->common->has_team_permissions(array("admin"), $r)) || ($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)) ) {
					$options .= '<a href="'.site_url("files/edit_file/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_510").'"><span class="glyphicon glyphicon-edit"></span></a> <a data-toggle="modal" data-target="#addFileModal" id="'. $r->ID .'"  dl="' . $r->ID . '" dlhash="'.$this->security->get_csrf_hash(). '" class="file_del btn btn-danger btn-xs" title="'.lang("ctn_511").'"><span class="glyphicon glyphicon-trash"></span></a>';
				}
				/* $url = base_url().'/'.$this->settings->info->upload_path_relative.'/'.$r->upload_file_name;  */
				$url = 'http://' . $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.'uploadfiles/Input/'.$r->upload_file_name; 
				if(!empty($r->file_url)) {
					$url = $r->file_url;
				}
				if($r->file_status == 'File Deleted'){
					$filestat = '<span class="label label-default" style="background: #'.$r->file_status_color.';color: #000000;">'.$r->file_status.'</span><p>'.$r->del_comment.'</p>';
				} else{
					$filestat = '<span class="label label-default" style="background: #'.$r->file_status_color.';color: #000000;">'.$r->file_status.'</span>';
				}
				/* $r->file_size . " kb",
					$r->file_type,  
					'<a href="'.$url.'" target="_blank">'.$r->file_name.'</a>', */
				$this->datatables->data[] = array( 
					$project_name,
					$r->format_used,
					$r->team_name,
					$filestat,
					$r->user_name,
					$display_date->format('d-m-Y H:i:s'),
					$options
				);
			}
		}
		echo json_encode($this->datatables->process());
		
	}


	public function all($folder_parent = 0, $projectid = -1, $filter = 0) 
	{
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "file_manage"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
		$this->template->loadData("activeLink", 
			array("file" => array("all" => 1)));

		$folder_parent = intval($folder_parent);
		$projectid = intval($projectid);
		$filter = intval($filter);
		if($projectid == -1) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$folders = array();
		if($folder_parent > 0) {
			// Get folder structure
			$folders = $this->get_folders($folders, $folder_parent);
			$folders = array_reverse($folders);
		}
		
		if($this->common->has_permissions(
			array("admin", "project_admin", "file_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		}
		$project_status = $this->file_model->monitor_file_status();
		$this->template->loadContent("files/index.php", array(
			"folders" => $folders,
			"folder_parent" => $folder_parent,
			"projectid" => $projectid,
			"filter" => $filter,
			"project_status" => $project_status,
			"page" => "all",
			"projects" => $projects
			)
		);
	}

	public function monitor($folder_parent = 0, $projectid = -1) 
	{
		$this->template->loadData("activeLink", 
			array("file" => array("monitor" => 1)));

		/* $folder_parent = intval($folder_parent);
		$projectid = intval($projectid);

		if($projectid == -1) {
			if($this->user->info->active_projectid > 0) {
				$projectid = $this->user->info->active_projectid;
			}
		}

		$folders = array();
		if($folder_parent > 0) {
			// Get folder structure
			$folders = $this->get_folders($folders, $folder_parent);
			$folders = array_reverse($folders);
		}
		
		if($this->common->has_permissions(
			array("admin", "project_admin", "file_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		} */
		
		$project_status = $this->file_model->monitor_file_status();

		$this->template->loadContent("files/monitor.php", array(
			"project_status" => $project_status 
			)
		);
	}
	
	public function get_folders($folders, $folder_parent) 
	{
		$folder = $this->file_model->get_folder($folder_parent);
		if($folder->num_rows() == 0) {
			return $folders;
		} else {
			$folder = $folder->row();
			$folders[] = $folder;
			if($folder->folder_parent > 0) {
				return $this->get_folders($folders, $folder->folder_parent);
			} else {
				return $folders;
			}
		}
	}

	public function add_file($folderid = 0) 
	{
		$this->template->loadData("activeLink", array("file" => array("general" => 1)));
		
		$folderid = intval($folderid);
		$default_projectid = 0;
		if($folderid > 0) {
			// Get default project and folder
			$folder = $this->file_model->get_folder($folderid);
			if($folder->num_rows() > 0) {
				$folder = $folder->row();
				$default_projectid = $folder->projectid;
			}
		}

		// If user is Admin, Project-Admin or File manager let them
		// view all projects
		if($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)){
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		}

		$this->template->loadContent("files/add_file.php", array(
			"projects" => $projects,
			"default_projectid" => $default_projectid,
			"folderid" => $folderid
			)
		);
	}

	public function get_folders_for_project() 
	{
		$projectid = intval($this->input->get("projectid"));
		$folderid = intval($this->input->get("folderid"));
		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		} else {
			$projectid = 0;
		}

		// Get folders
		$folders = $this->file_model->get_folders($projectid);
		$this->template->loadAjax("files/ajax_get_folders.php", array(
			"folders" => $folders,
			"folderid" => $folderid
			),1
		);
	}


	public function add_file_process() 
	{
		$this->load->library("upload");

		$name = $this->common->nohtml($this->input->post("name"));
		$user_name = $this->common->nohtml($this->input->post("user_name"));
		$file_url = $this->common->nohtml($this->input->post("file_url"));
		$file_note = $this->lib_filter->go($this->input->post("file_note"));
		$projectid = intval($this->input->post("projectid"));
		$project_name = $this->common->nohtml($this->input->post("project_name"));
		$folderid = intval($this->input->post("folderid"));
		$format_used = $this->common->nohtml($this->input->post("format_used"));
		$broadcast_day_st_time = $this->common->nohtml($this->input->post("broadcast_day_st_time"));
		$status = intval($this->input->post("status")); 
		$period = intval($this->input->post("period")); 
		$startDate = $this->common->nohtml($this->input->post("st_date"));
		$endDate = $this->common->nohtml($this->input->post("end_date"));/* 
		$st_time = $this->common->nohtml($this->input->post("st_time"));
		$end_time = $this->common->nohtml($this->input->post("end_time"));  */
		$st_date = date("Y-m-d H:i:s", strtotime($startDate));
		$end_date = date("Y-m-d H:i:s", strtotime($endDate)); 
		/* 
		if(!empty($st_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->date_picker_format, $st_date);
			$sd_timestamp = $sd->getTimestamp();
		} else {
			$sd_timestamp = time();
		}

		if(!empty($end_date)) {
			$dd = DateTime::createFromFormat($this->settings->info->date_picker_format, $end_date);
			$dd_timestamp = $dd->getTimestamp();
		} else {
			$dd_timestamp = 0;
		} */
		
		if($folderid <=0) {
			$folderid = 0;
		}
		if($projectid <=0) {
			$projectid = 0;
		}

		$folder_flag = intval($this->input->post("folder_flag"));
		$folder_name = $this->common->nohtml($this->input->post("folder_name"));

		if($folder_flag) {
			if(empty($folder_name)) {
				$this->template->error(lang("error_91"));
			}
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		$this->common->check_permissions(
			"Add File", 
			array("admin", "project_admin", "file_manage"), // User Roles
			array("admin", "file"),  // Team Roles
			$projectid
		);

		if($folderid > 0) {
			$folder = $this->file_model->get_folder($folderid);
			if($folder->num_rows() == 0) {
				$this->template->error(lang("error_92"));
			}
			$folder = $folder->row();
			if($folder->projectid != $projectid) {
				$this->template->error(lang("error_93"));
			}
		}
		if($format_used == "pdf" || $format_used == "PDF"){
			$fileuppath = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/pdf/Input';
		}
		else{
			$fileuppath = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/Input';
		}
		$new_name = $name.'_'.$projectid;  
		// Upload
		if(empty($file_url) && !$folder_flag) 
		{
			$this->upload->initialize(array(
			   "upload_path" => $fileuppath,
		       "overwrite" => FALSE,
		       "max_filename" => 1300,
		       "encrypt_name" => FALSE,
			   "file_name" => $new_name,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => $this->settings->info->file_size,
				)
			);

			if ( ! $this->upload->do_upload('userfile'))
            {
                    $error = array('error' => $this->upload->display_errors());

                    $this->template->error(lang("error_94") . "<br /><br />" .
                    	 $this->upload->display_errors());
            }

            $data = $this->upload->data();
			/* print_r($data);
			exit(); */ 
			/* "upload_file_name" => $data['file_name'], */
            $fileid = $this->file_model->add_file(array(
            	"file_name" => $name, 
            	"user_name" => $user_name, 
            	"upload_file_name" => $data['raw_name'].$data['file_ext'],
				"api_input" => 1,
            	"projectid" => $projectid,
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"folder_parent" => $folderid,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"file_type" => $data['file_type'],
            	"extension" => $data['file_ext'],
            	"file_size" => $data['file_size'],
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );

            // File Note
            if(!empty($file_note)) {
            	$this->file_model->add_file_note(array(
            		"fileid" => $fileid,
            		"userid" => $this->user->info->ID,
            		"note" => $file_note,
            		"timestamp" => time()
            		)
            	);
            }
		}
		if(!empty($file_url) && !$folder_flag) {
			$fileid = $this->file_model->add_file(array(
            	"file_name" => $name,
            	"user_name" => $user_name, 
            	"projectid" => $projectid,
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"folder_parent" => $folderid,
            	"file_type" => "External URL",
            	"file_size" => 0,
            	"file_url" => $file_url,
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );

            // File Note
            if(!empty($file_note)) {
            	$this->file_model->add_file_note(array(
            		"fileid" => $fileid,
            		"userid" => $this->user->info->ID,
            		"note" => $file_note,
            		"timestamp" => time()
            		)
            	);
            }
		}
		if($folder_flag) {
			$fileid = $this->file_model->add_file(array(
            	"file_name" => $folder_name,
            	"projectid" => $projectid,
            	"user_name" => $user_name, 
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"file_type" => "Folder",
            	"folder_parent" => $folderid,
            	"folder_flag" => 1,
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );
		}


		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1021"). "<a href='" 
			. site_url("files/view_file/" . $fileid) . "'>".lang("ctn_1022")."</a>" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_42"));
		if($folderid > 0) {
			redirect(site_url("files/index/" . $folderid));
		} else {
			redirect(site_url("files/all"));
		}

	}

	public function edit_file($id, $all=0) 
	{
		$all = intval($all);
		$id = intval($id);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();
		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_97"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		$this->template->loadData("activeLink", 
			array("file" => array("all" => 1)));
		

		// If user is Admin, Project-Admin or File manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "file_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		}

		$folders = null;
		if($file->projectid > 0) {
			$folders = $this->file_model->get_folders($file->projectid);
		}

		$this->template->loadContent("files/edit_file.php", array(
			"file" => $file,
			"projects" => $projects,
			"folders" => $folders,
			"all" => $all
			)
		);

	}

	public function edit_file_process($id, $all=0) 
	{
		$id = intval($id);
		$all = intval($all);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();
		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				"Edit File", 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}


		$this->load->library("upload");

		$name = $this->common->nohtml($this->input->post("name")); 
		$user_name = $this->common->nohtml($this->input->post("user_name")); 
		$file_url = $this->common->nohtml($this->input->post("file_url"));
		$file_note = $this->lib_filter->go($this->input->post("file_note"));
		$projectid = intval($this->input->post("projectid"));
		$project_name = $this->common->nohtml($this->input->post("project_name"));
		$folderid = intval($this->input->post("folderid"));
		$format_used = $this->common->nohtml($this->input->post("format_used"));
		$broadcast_day_st_time = $this->common->nohtml($this->input->post("broadcast_day_st_time"));
		$status = intval($this->input->post("status"));
		$period = intval($this->input->post("period")); 
		$startDate = $this->common->nohtml($this->input->post("st_date"));
		$endDate = $this->common->nohtml($this->input->post("end_date"));/* 
		$st_time = $this->common->nohtml($this->input->post("st_time"));
		$end_time = $this->common->nohtml($this->input->post("end_time")); 
		$st_date = date("Y-m-d", strtotime($startDate));
		$end_date = date("Y-m-d", strtotime($endDate));  */
		$st_date = date("Y-m-d H:i:s", strtotime($startDate));
		$end_date = date("Y-m-d H:i:s", strtotime($endDate)); 
		
		if($folderid <=0) {
			$folderid = 0;
		}
		if($projectid <=0) {
			$projectid = 0;
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		// Get user permissions for the new project we are trying to edit the file to
		if($projectid != $file->projectid) {
			$this->common->check_permissions(
				lang("error_97"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid,
				lang("error_98")
			);
		}

		if($folderid > 0) {
			$folder = $this->file_model->get_folder($folderid);
			if($folder->num_rows() == 0) {
				$this->template->error(lang("error_92"));
			}
			$folder = $folder->row();
			if($folder->projectid != $projectid) {
				$this->template->error(lang("error_93"));
			}
		}
	    /* echo $projectid;
	    echo $project_name;
		exit(); */
		if($format_used == "pdf" || $format_used == "PDF"){
			$fileuppath = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/pdf/Input';
		}
		else{
			$fileuppath = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/Input';
		}
		$new_name = $name.'_'.$projectid; 
		// Upload
		if(empty($file_url) && $_FILES['userfile']['size'] > 0)
		{ 
			$this->upload->initialize(array(
			   "upload_path" => $fileuppath,
		       "overwrite" => FALSE,
		       "max_filename" => 1300,
		       "encrypt_name" => FALSE,
			   "file_name" => $new_name,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => $this->settings->info->file_size,
				)
			);

			if ( ! $this->upload->do_upload('userfile'))
            {
                    $error = array('error' => $this->upload->display_errors());

                    $this->template->error(lang("error_94")."<br /><br />" .
                    	 $this->upload->display_errors());
            }

            $data = $this->upload->data();
			/* print_r($data);
			exit();  */
			/* "upload_file_name" => $data['file_name'], */
			
            $this->file_model->update_file($id, array(
            	"file_name" => $name, 
            	"user_name" => $user_name, 
				"upload_file_name" => $data['raw_name'].$data['file_ext'],
				"api_input" => 1,
            	"projectid" => $projectid,
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"folder_parent" => $folderid,
            	"file_type" => $data['file_type'],
            	"extension" => $data['file_ext'],
            	"file_size" => $data['file_size'],
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );          
		} elseif(!empty($file_url)) {
			$this->file_model->update_file($id, array(
            	"file_name" => $name,
            	"projectid" => $projectid,
            	"user_name" => $user_name, 
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"folder_parent" => $folderid,
            	"file_type" => "External URL",
            	"file_size" => 0,
            	"file_url" => $file_url,
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );
		} else {
			$this->file_model->update_file($id, array(
            	"file_name" => $name,
            	"projectid" => $projectid,
            	"user_name" => $user_name, 
            	"format_used" => $format_used,
            	"broadcast_day_st_time" => $broadcast_day_st_time,
            	"status" => $status,
            	"st_date" => $st_date,
            	"end_date" => $end_date, 
            	"period" => $period,
            	"folder_parent" => $folderid,
            	"userid" => $this->user->info->ID,
            	"timestamp" => time()
            	)
            );
		}

	

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1023") . " <a href='" 
			. site_url("files/view_file/" . $id) . "'>".lang("error_22")."</a>" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_43"));
		if($all == 0) {
			if($folderid > 0) {
				redirect(site_url("files/index/" . $folderid));
			} else {
				redirect(site_url("files/all"));
			}
		} else {
			if($folderid > 0) {
				redirect(site_url("files/all/" . $folderid));
			} else {
				redirect(site_url("files/all"));
			}
		}
	}

	public function edit_folder($id, $all=0) 
	{
		$id = intval($id);
		$all = intval($all);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();
		// make sure not a folder
		if(!$file->folder_flag) {
			$this->template->error(lang("error_99"));
		}

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_97"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		$this->template->loadData("activeLink", 
			array("file" => array("general" => 1)));
		
		// If user is Admin, Project-Admin or File manager let them
		// view all projects
		if($this->common->has_permissions(
			array("admin", "project_admin", "file_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"(pr2.admin = 1 OR pr2.file = 1)");
		}

		$folders = null;
		if($file->projectid > 0) {
			$folders = $this->file_model->get_folders($file->projectid);
		}

		$this->template->loadContent("files/edit_folder.php", array(
			"file" => $file,
			"projects" => $projects,
			"folders" => $folders,
			"all" => $all
			)
		);

	}

	public function edit_folder_process($id, $all) 
	{
		$id = intval($id);
		$all = intval($all);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();
		// make sure not a folder
		if(!$file->folder_flag) {
			$this->template->error(lang("error_99"));
		}

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_97"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}


		$name = $this->common->nohtml($this->input->post("name"));
		$projectid = intval($this->input->post("projectid"));
		$folderid = intval($this->input->post("folderid"));

		if($folderid <=0) {
			$folderid = 0;
		}
		if($projectid <=0) {
			$projectid = 0;
		}

		if($projectid > 0) {
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
		}

		// Get user permissions for the new project we are trying to edit the file to
		if($projectid != $file->projectid) {
			$this->common->check_permissions(
				lang("error_97"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid,
				lang("error_98")
			);
		}
	

		if($folderid > 0) {
			$folder = $this->file_model->get_folder($folderid);
			if($folder->num_rows() == 0) {
				$this->template->error(lang("error_92"));
			}
			$folder = $folder->row();
			if($folder->projectid != $projectid) {
				$this->template->error(lang("error_93"));
			}
		}

	
		$this->file_model->update_file($id, array(
        	"file_name" => $name,
        	"projectid" => $projectid,
        	"folder_parent" => $folderid,
        	"userid" => $this->user->info->ID,
        	"timestamp" => time()
        	)
        );
		

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1024") . " <a href='" 
			. site_url("files/view_file/" . $id) . "'>".lang("ctn_1022")."</a>" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_43"));
		if($all == 0) {
			if($folderid > 0) {
				redirect(site_url("files/index/" . $folderid));
			} else {
				redirect(site_url("files"));
			}
		} else {
			if($folderid > 0) {
				redirect(site_url("files/all/" . $folderid));
			} else {
				redirect(site_url("files/all"));
			}
		}
	}

	public function delete_file($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_227"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		// Delete file
		if(!empty($file->upload_file_name)) {
			/* @unlink($this->settings->info->upload_path . "/" . $file->upload_file_name); */
			@unlink($_SERVER["DOCUMENT_ROOT"] . "/uploadfiles/Input/" . $file->upload_file_name);
		}

		/* $this->file_model->delete_file($id); */ 
		date_default_timezone_set('Asia/Calcutta'); 
		$this->file_model->delete_file($id, array( 
				"del" => 1, 
            	"del_at" => date("Y-m-d H:i:s")
            	)
            ); 

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1025") . " ($file->file_name)" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_44"));
		redirect(site_url("files"));

	}

	public function delete_folder($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_100"));
		}
		$file = $file->row();

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_227"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		// make sure not a folder
		if(!$file->folder_flag) {
			$this->template->error(lang("error_99"));
		}

		$this->file_model->delete_file($id);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1026") . " ($file->folder_name)" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_45"));
		redirect(site_url("files"));

	}

	public function view_file($id) 
	{
		$this->template->loadData("activeLink", 
			array("file" => array("all" => 1)));
		
		$id = intval($id);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();

		// Get user permissions
		$projectid = $file->projectid;
		// For this section we place team_member outside because we 
		// need it for View File view page
		$team_member = $this->team_model
				->get_member_of_project($this->user->info->ID, $projectid);
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_228"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin", "file"),  // Team Roles
				$projectid
			);
		}

		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		$notes = $this->file_model->get_file_notes($id);

		$this->template->loadContent("files/view_file.php", array(
			"file" => $file,
			"notes" => $notes,
			"team_member" => $team_member->row()
			)
		);
	}

	public function delete_file_note($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$note = $this->file_model->get_file_note($id);
		if($note->num_rows() == 0) {
			$this->template->error(lang("error_101"));
		}
		$note = $note->row();

		$this->file_model->delete_file_note($id);
		$projectid = 0;

		// Get user permission
		if($note->userid != $this->user->info->ID) {
			$file = $this->file_model->get_file($note->fileid);
			if($file->num_rows() == 0) {
				$this->template->error(lang("error_101"));
			}
			$file = $file->row();

			$projectid = $file->projectid;
			$this->common->check_permissions(
				lang("error_102"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1027") . " <a href='". 
				site_url("files/view_file/" . $note->fileid) ."'>".lang("ctn_1022")."</a>" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_46"));
		redirect(site_url("files/view_file/" . $note->fileid));
	}

	public function add_file_note($id) 
	{
		$id = intval($id);
		$file = $this->file_model->get_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_101"));
		}
		$file = $file->row();
		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_229"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin", "file"),  // Team Roles
				$projectid
			);
		}

		$note = $this->lib_filter->go($this->input->post("note"));

		if(empty($note)) {
			$this->template->error(lang("error_103"));
		}

		// File Note
    	$this->file_model->add_file_note(array(
    		"fileid" => $id,
    		"userid" => $this->user->info->ID,
    		"note" => $note,
    		"timestamp" => time()
    		)
    	);

    	// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1028") . " <a href='". 
				site_url("files/view_file/" . $id) ."'>".lang("ctn_1022")."</a>" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);
     	
     	// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_47"));
		redirect(site_url("files/view_file/" . $id));
	}
	
	public function ajaxgetformatbroadcast() 
	{
		$sourceid = $this->common->nohtml($this->input->get("sourceid", true));
		$this->load->model("file_model");
		$getfrmsrc = $this->file_model->getfromsource($sourceid);
		$frmsrc = $getfrmsrc->result_array();
		$data = array();  
		foreach($frmsrc as $src){
			$data['format_used'] = $src['process_name'];
			$data['broadcast_day_start_time'] = $src['broadcast_day_start_time'];
			$data['team_name'] = $src['team_name'];
			$data['user_name'] = $src['user_name'];
		}	
		echo json_encode($data);
		exit();	 
	}
	
	
	public function del_comment() 
	{ 
		$dl = intval($this->input->get("dl", true));  
		$rid = intval($this->input->get("rid", true));  
		$dlhash = $this->input->get("dlhash", true);    
		$flcomment = $this->common->nohtml($this->input->get("flcomment"));
		/*  print_r($dl); exit;  */
		$this->load->model("file_model");
		
		if($dlhash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		} 
		
		$file = $this->file_model->get_file($rid);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_95"));
		}
		$file = $file->row();

		// Get user permissions
		$projectid = $file->projectid;
		if($file->userid != $this->user->info->ID) {
			$this->common->check_permissions(
				lang("error_227"), 
				array("admin", "project_admin", "file_manage"), // User Roles
				array("admin"),  // Team Roles
				$projectid
			);
		}

		// make sure not a folder
		if($file->folder_flag) {
			$this->template->error(lang("error_96"));
		}

		// Delete file
		if(!empty($file->upload_file_name)) {
			/* @unlink($this->settings->info->upload_path . "/" . $file->upload_file_name); */
			@unlink($_SERVER["DOCUMENT_ROOT"] . "/uploadfiles/Input/" . $file->upload_file_name);
		}

		/* $this->file_model->delete_file($id); */ 
		date_default_timezone_set('Asia/Calcutta'); 
		$this->file_model->delete_file($rid, array( 
				"del" => 1, 
				"status" => 9, 
				"del_comment" => $flcomment, 
            	"del_at" => date("Y-m-d H:i:s")
            	)
            ); 

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1025") . " ($file->file_name)" ,
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "files"
			)
		);

		// Redirect
		$this->session->set_flashdata("globalmsg", 
			lang("success_44"));
		redirect(site_url("files/all"));
	}
	
	public function exportallfiles() 
	{
		$this->load->model("file_model"); 
		$filename = "file_export_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		
		$file_export = $this->file_model->filessexportall();
		/* print_r($file_export->result()); */
		$items = array();
		foreach($file_export->result() as $r) {	 
			$project_details_ex = [];
			$project_details_ex['source'] = $r->project_name;
			$project_details_ex['file_format'] = $r->format_used;
			$project_details_ex['broadcast_day_start_time'] = $r->broadcast_day_st_time; 
			$project_details_ex['team_name'] = $r->team_name; 
			$project_details_ex['status'] = $r->file_status;
			if($r->status == 9){
				$project_details_ex['delete_comment'] = $r->del_comment;
			} else {
				$project_details_ex['delete_comment'] = '';
			}
			$project_details_ex['reporter'] = $r->username;
			$project_details_ex['created_date'] = $r->created_date;  
			$items[] = $project_details_ex;	
		}
			foreach($items as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			} 
		exit;
	} 
}

?>