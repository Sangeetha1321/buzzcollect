<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Machine extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("machine_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
	}

	public function index() 
	{
		$_SESSION['p_page'] = "index";
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("machine" => array("general" => 1)));

		$machine_details = $this->machine_model->get_machine_details();
		$softwares = $this->machine_model->get_available_softwares();
		$shifts = $this->machine_model->get_available_shifts();
		$team_name = $this->machine_model->get_available_team_name();
		$this->template->loadContent("machine/index.php", array(
			"machine_details" => $machine_details,
			"softwares" => $softwares,
			"team_name" => $team_name,
			"shifts" => $shifts
			)
		);
	}

	public function add_machine() 
	{
		$machine = $this->input->post("machine_no", true);
		$team_name = $this->input->post("team_name", true);
		$softwares = $this->input->post("softwares", true);
		
		/* print_r($softwares);
		exit;  */ 
		$available_softwares = array();
		foreach($softwares as $tar){
			$available_softwares[] .= $tar; 
		} 
		$available_softwares = implode( ',', $available_softwares );
		
		$shifts = $this->input->post("shifts", true);
		
		/* print_r($softwares);
		exit;  */
		$available_shifts = array();
		foreach($shifts as $tar){
			$available_shifts[] .= $tar; 
		} 
		$available_shifts = implode( ',', $available_shifts );
		
		$seat_no = $this->input->post("seat_no", true);
		$head_count = $this->input->post("head_count", true);
		
		if(empty($machine)) $this->template->error(lang("error_303"));
		$this->machine_model->add_machine(array(
			"machine_no" => $machine,
			"team_name" => $team_name,
			"softwares" => $available_softwares,
			"shifts" => $available_shifts,
			"seat_no" => $seat_no,
			"head_count" => $head_count
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_173"));
		redirect(site_url("machine"));
	}

	public function edit_machine($id) 
	{
		$this->template->loadExternal('<script src="'.base_url().'scripts/libraries/jscolor.min.js"></script>');
		/* if(!$this->common->has_permissions(array("admin", "project_admin"), 
			$this->user)) {
			$this->template->error(lang("error_71"));
		} */
		
		$this->template->loadData("activeLink", 
			array("machine" => array("general" => 1)));
			
		$id = intval($id);

		$machine = $this->machine_model->get_machine($id);
		if($machine->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$machine = $machine->row();
		$softwares = $this->machine_model->get_available_softwares();
		$shifts = $this->machine_model->get_available_shifts();
		$team_name = $this->machine_model->get_available_team_name();
		$this->template->loadContent("machine/edit_machine.php", array(
			"machine" => $machine,
			"softwares" => $softwares,
			"team_name" => $team_name,
			"shifts" => $shifts
			)
		);
	}

	public function edit_machine_pro($id) 
	{
		$id = intval($id);
		$machine_details = $this->machine_model->get_machine($id);
		if($machine_details->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$machine_details = $machine_details->row();
		
		$machine = $this->input->post("machine_no", true);
		$team_name = $this->input->post("team_name", true);
		$softwares = $this->input->post("softwares", true);
		
		/* print_r($softwares);
		exit;  */
		$available_softwares = array();
		foreach($softwares as $tar){
			$available_softwares[] .= $tar; 
		} 
		$available_softwares = implode( ',', $available_softwares );
		
		$shifts = $this->input->post("shifts", true);
		
		/* print_r($softwares);
		exit;  */
		$available_shifts = array();
		foreach($shifts as $tar){
			$available_shifts[] .= $tar; 
		} 
		$available_shifts = implode( ',', $available_shifts );
		
		$seat_no = $this->input->post("seat_no", true);
		$head_count = $this->input->post("head_count", true);
		
		if(empty($machine)) $this->template->error(lang("error_303"));

		// Add
		$this->machine_model->update_machine($id, array(
			"machine_no" => $machine,
			"team_name" => $team_name,
			"softwares" => $available_softwares,
			"shifts" => $available_shifts,
			"seat_no" => $seat_no,
			"head_count" => $head_count
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_174"));
		redirect(site_url("machine"));
	}

	public function delete_machine($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$machine = $this->machine_model->get_machine($id);
		if($machine->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$machine = $machine->row();

		$this->machine_model->delete_machine($id);

		$this->session->set_flashdata("globalmsg", 
			lang("success_168"));
		redirect(site_url("machine"));
	}

	public function softwares() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("machine" => array("softwares" => 1)));
			
		$softwares = $this->machine_model->get_available_softwares();
		$this->template->loadContent("machine/softwares.php", array(
			"softwares" => $softwares
			)
		);
	}

	public function add_softwares() 
	{
		$softwares = $this->common->nohtml($this->input->post("softwares"));
		if(empty($softwares)) $this->template->error(lang("error_304"));

		// Add
		$this->machine_model->add_softwares(array(
			"softwares_available" => $softwares
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_170"));
		redirect(site_url("machine/softwares"));
	}

	public function edit_softwares($id) 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		
		$this->template->loadData("activeLink", 
			array("machine" => array("softwares" => 1)));
			
		$id = intval($id);
		$softwares = $this->machine_model->get_softwares($id);
		
		if($softwares->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$softwares = $softwares->row();
		$this->template->loadContent("machine/edit_softwares.php", array(
			"softwares" => $softwares
			)
		);
	}

	public function edit_softwares_pro($id) 
	{
		$id = intval($id);
		$softwares = $this->machine_model->get_softwares($id);
		if($softwares->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$softwares = $softwares->row();

		$available_softwares = $this->common->nohtml($this->input->post("softwares"));
		if(empty($available_softwares)) $this->template->error(lang("error_304"));

		// Add
		$this->machine_model->update_softwares($id, array(
			"softwares_available" => $available_softwares
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_172"));
		redirect(site_url("machine/softwares"));
	}

	public function delete_softwares($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$softwares = $this->machine_model->get_softwares($id);
		if($softwares->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$softwares = $softwares->row();

		$this->machine_model->delete_softwares($id);

		$this->session->set_flashdata("globalmsg", 
			lang("success_171"));
		redirect(site_url("machine/softwares"));
	}

	public function import() 
    {
        set_time_limit(0);
        error_reporting(E_ALL);
		$this->load->model("machine_model");
        try {
            if (isset($_POST["import"])) {
                $filename = $_FILES["file"]["tmp_name"];
				$mimes = array('application/vnd.ms-excel','text/csv');
                if (($_FILES["file"]["size"] > 0) && in_array($_FILES['file']['type'],$mimes)) {
                    $file = fopen($filename, "r");
                    $i = 0;
					$current_date = date("Y-m-d H:i:s");
					$header = fgetcsv($file);
                    while (($importdata = fgetcsv($file)) !== FALSE) {
						$record = array_combine($header, $importdata);
						$data = array(
							'machine_no' => isset($record["machine_no"]) ? $record["machine_no"] : NULL, 
							'team_name' => isset($record["team_name"]) ? $record["team_name"] : NULL,
							'softwares' => isset($record["softwares"]) ? $record["softwares"] : NULL,
							'shifts' => isset($record["shifts"]) ? $record["shifts"] : NULL,
							'seat_no' => isset($record["seat_no"]) ? $record["seat_no"] : NULL,
							'head_count' => isset($record["head_count"]) ? $record["head_count"] : NULL,
							);
						$insert = $this->machine_model->add_machine($data);
                    }

                    fclose($file);
					$this->session->set_flashdata('success', 'Your '. $_FILES['file']['name'] . ' uploaded successfully!');
                    redirect(site_url("machine/index"));
                } else {
                    $this->session->set_flashdata('error', 'Problem in uploading the file!');
                    redirect(site_url("machine/index"));
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
	
	public function export() 
	{
		$this->load->model("machine_model");
		
		$filename = "machine_details_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		
		//$machine_columns = $this->machine_model->get_machine_columns();

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		//fputcsv($fp, $machine_columns);
		
		$machine_details = $this->machine_model->get_machine_details();
		
		
		//fputcsv($fp, $machine_details);
		if ($machine_details->num_rows() > 0) {
			foreach($machine_details->result_array() as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
				//foreach ($row as $line) {
					$line = (array)$row;
					fputcsv($fp, $line);
				//}
			}
		}
		exit;
	}
	
	public function shifts() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("machine" => array("shifts" => 1)));
			
		$shifts = $this->machine_model->get_available_shifts();
		$this->template->loadContent("machine/shifts.php", array(
			"shifts" => $shifts
			)
		);
	}

	public function add_shifts() 
	{
		$shifts = $this->common->nohtml($this->input->post("shifts"));
		$shift_timing = $this->common->nohtml($this->input->post("shift_timing"));
		if(empty($shifts)) $this->template->error(lang("error_305"));
		if(empty($shift_timing)) $this->template->error(lang("error_306"));
		// Add
		$this->machine_model->add_shifts(array(
			"shift_type" => $shifts,
			"shift_timing" => $shift_timing
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_175"));
		redirect(site_url("machine/shifts"));
	}

	public function edit_shifts($id) 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		
		$this->template->loadData("activeLink", 
			array("machine" => array("shifts" => 1)));
			
		$id = intval($id);
		$shifts = $this->machine_model->get_shifts($id);
		
		if($shifts->num_rows() == 0) {
			$this->template->error(lang("error_307"));
		}
		$shifts = $shifts->row();
		$this->template->loadContent("machine/edit_shifts.php", array(
			"shifts" => $shifts
			)
		);
	}

	public function edit_shifts_pro($id) 
	{
		$id = intval($id);
		$shifts = $this->machine_model->get_shifts($id);
		if($shifts->num_rows() == 0) {
			$this->template->error(lang("error_307"));
		}
		$shifts = $shifts->row();

		$shift_type = $this->common->nohtml($this->input->post("shifts"));
		$shift_timing = $this->common->nohtml($this->input->post("shift_timing"));
		if(empty($shift_type)) $this->template->error(lang("error_305"));
		if(empty($shift_timing)) $this->template->error(lang("error_306"));

		// Add
		$this->machine_model->update_shifts($id, array(
			"shift_type" => $shift_type,
			"shift_timing" => $shift_timing
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_177"));
		redirect(site_url("machine/shifts"));
	}

	public function delete_shifts($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$shifts = $this->machine_model->get_shifts($id);
		if($shifts->num_rows() == 0) {
			$this->template->error(lang("error_307"));
		}
		$shifts = $shifts->row();

		$this->machine_model->delete_shifts($id);

		$this->session->set_flashdata("globalmsg", 
			lang("success_176"));
		redirect(site_url("machine/shifts"));
	}
	
	public function team_name() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("team" => array("team_name" => 1)));
			
		$team_name = $this->machine_model->get_available_team_name();
		$this->template->loadContent("machine/team_name.php", array(
			"team_name" => $team_name
			)
		);
	}

	public function add_team_name() 
	{
		$team_name = $this->input->post("team_name");
		//$shift_timing = $this->common->nohtml($this->input->post("shift_timing"));
		if(empty($team_name)) $this->template->error(lang("error_308"));
		//if(empty($shift_timing)) $this->template->error(lang("error_306"));
		// Add
		$this->machine_model->add_team_name(array(
			"team_name" => $team_name
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_178"));
		redirect(site_url("machine/team_name"));
	}

	public function edit_team_name($id) 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		
		$this->template->loadData("activeLink", 
			array("machine" => array("team_name" => 1)));
			
		$id = intval($id);
		$team_name = $this->machine_model->get_team_name($id);
		
		if($team_name->num_rows() == 0) {
			$this->template->error(lang("error_309"));
		}
		$team_name = $team_name->row();
		$this->template->loadContent("machine/edit_team_name.php", array(
			"team_name" => $team_name
			)
		);
	}

	public function edit_team_name_pro($id) 
	{
		$id = intval($id);
		$team_name = $this->machine_model->get_team_name($id);
		if($team_name->num_rows() == 0) {
			$this->template->error(lang("error_309"));
		}
		$team_name = $team_name->row();

		$team_name = $this->common->nohtml($this->input->post("team_name"));
		//$shift_timing = $this->common->nohtml($this->input->post("shift_timing"));
		if(empty($team_name)) $this->template->error(lang("error_308"));
		//if(empty($shift_timing)) $this->template->error(lang("error_306"));

		// Add
		$this->machine_model->update_team_name($id, array(
			"team_name" => $team_name
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_180"));
		redirect(site_url("machine/team_name"));
	}

	public function delete_team_name($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$team_name = $this->machine_model->get_team_name($id);
		if($team_name->num_rows() == 0) {
			$this->template->error(lang("error_309"));
		}
		$team_name = $team_name->row();

		$this->machine_model->delete_team_name($id);

		$this->session->set_flashdata("globalmsg", 
			lang("success_179"));
		redirect(site_url("machine/team_name"));
	}
	
	public function team_users() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("team" => array("team_users" => 1)));
			
		$team_users = $this->machine_model->get_available_team_name();
		$team_users_all_team = $this->machine_model->get_users_of_all_teams();
		$shared_resources = $this->machine_model->get_shared_resources();
		/* $team_list = $this->projects_model->get_team_name(); */
		if(isset($_POST["all_teams"])){
			$all_teams = $_POST["all_teams"];
		}
		else{
			$all_teams = '';
		}
		if(isset($_POST["all_resources"])){
			$all_resources = $_POST["all_resources"];
		}
		else{
			$all_resources = '';
		}
		$allresources = $this->machine_model->all_resources($all_teams);
		/* if(empty($all_resources)) $this->template->error(lang("error_314"));  */
		date_default_timezone_set('Asia/Kolkata');
		$this->machine_model->update_resources($all_resources, array(
			"shared_resource" => $all_teams,
			"shared_from" => time(),
			"shared_delete" => 1
			)
		);
		
		$this->template->loadContent("machine/team_users.php", array(
			"team_users" => $team_users,
			"all_teams" => $all_teams,
			"allresources" => $allresources,
			"all_resources" => $all_resources,
			"shared_resources" => $shared_resources,
			"team_users_all_team" => $team_users_all_team
			)
		);
		/* redirect(site_url("machine/team_users/")); */
		
	}
	
	public function delete_resources($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		/* $softwares = $this->machine_model->get_softwares($id);
		if($softwares->num_rows() == 0) {
			$this->template->error(lang("error_302"));
		}
		$softwares = $softwares->row(); */
		date_default_timezone_set('Asia/Kolkata');
		$this->machine_model->delete_resources($id, array(
			"shared_till" => time(),
			"shared_delete" => 0
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_181"));
		redirect(site_url("machine/team_users")); 
	}
	
	public function shift_users() 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("machine" => array("shift_users" => 1)));
			
		/* $team_name = $this->machine_model->get_available_team_name(); */
		if($this->common->has_permissions(array("admin"), $this->user)) {
			$team_name = $this->machine_model->get_available_team_name();
			$machine_count = $this->machine_model->get_count_machine();
			$mem_teamname = '';
		} else {
			$team_name = $this->machine_model->get_useravailable_team_name($this->user->info->ID);
			if(isset($team_name)){ 
				foreach($team_name->result() as $r) : 
					$mem_teamname = $r->team_name;
				endforeach; 
			$machine_count = $this->machine_model->get_count_machine_byteam($mem_teamname);	
			}
		}		
		$shift_type = $this->machine_model->get_shift_type();	
		/* $team_users = $this->machine_model->get_available_users_by_team();
		$shift_users = $this->machine_model->allocate_users_by_shift(); */
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		/* $sum_shift_allocation = $this->machine_model->get_sum_shift_allocation($week_number_of_year); */
		$sum_shift_allocation = $this->machine_model->get_sum_shift_allocation($week_number_of_year,$mem_teamname);
		
		
		$current_week_shifts = $this->machine_model->current_week_shifts($week_number_of_year);
		$last_week_shifts = $this->machine_model->current_week_shifts($week_number_of_year-1);
		$current_year  = date("Y");
		if($this->common->has_permissions(array("admin"), $this->user)) {
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
		$shift_detail = '';
		} else {
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
		$shift_detail = $this->machine_model->mem_shift_details($mem_teamname,$week_number_of_year,$current_year);
		}
		$shift_allocation = $this->machine_model->get_available_shift_allocation($week_number_of_year);	
		$this->template->loadContent("machine/shift_users.php", array(
			"shift_allocation" => $shift_allocation,
			"current_week_shifts" => $current_week_shifts,
			"last_week_shifts" => $last_week_shifts,
			"shift_details" => $shift_details,
			"shift_detail" => $shift_detail,
			"team_name" => $team_name,
			"shift_type" => $shift_type,
			"current_date" => $current_date,
			"week_number_of_year" => $week_number_of_year,
			"machine_count" => $machine_count,
			"sum_of_shift" => $sum_shift_allocation
			)
		);
	}

	public function add_shift_users() 
	{
		$team_name = $this->common->nohtml($this->input->post("team_name"));
		/* $member_name = $this->common->nohtml($this->input->post("member_name")); */
		$member_name = $this->input->post("member_name"); 
		$shift_type = $this->common->nohtml($this->input->post("shift_type"));
		
		$s1 = $this->input->post("s1", true);
		$s2 = $this->input->post("s2", true);
		$s3 = $this->input->post("s3", true);
		//$current_date = $this->common->nohtml($this->input->post("current_date"));
		$current_date = date("Y-m-d");
		/* [Mon-Sun] $current_date = "2019-07-22"; */
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		if(empty($team_name)) $this->template->error(lang("error_308"));
		if(empty($member_name)) $this->template->error(lang("error_310"));
		if(empty($shift_type)) $this->template->error(lang("error_305"));
		// Add
		foreach($member_name as $mem){
			$this->machine_model->add_shift_users(array(
			"team_name" => $team_name ,
			"member_name" => $mem,
			"shift_type" => $shift_type,
			"week_number_of_year" => $week_number_of_year,
			"s1" => $s1,
			"s2" => $s2,
			"s3" => $s3,
			"created_date" => $current_date
			));
		}

		$this->session->set_flashdata("globalmsg",lang("success_181"));
		redirect(site_url("machine/shift_users"));
	}

	public function edit_shift_for_user() 
	{
		$eteam_name = $this->common->nohtml($this->input->post("eteam_name"));
		$emember_name = $this->common->nohtml($this->input->post("emember_name"));
		/* $emember_name = $this->input->post("emember_name");  */
		$eshift_type = $this->common->nohtml($this->input->post("eshift_type"));
		
		$es1 = $this->input->post("es1", true);
		$es2 = $this->input->post("es2", true);
		$es3 = $this->input->post("es3", true);
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		if(empty($eteam_name)) $this->template->error(lang("error_308"));
		if(empty($emember_name)) $this->template->error(lang("error_310"));
		if(empty($eshift_type)) $this->template->error(lang("error_305"));

		// Update
		/* foreach($emember_name as $mem){ */
			$this->machine_model->edit_shift_for_user($eteam_name,$emember_name,array(
			"team_name" => $eteam_name ,
			"member_name" => $emember_name,
			"shift_type" => $eshift_type,
			"week_number_of_year" => $week_number_of_year,
			"s1" => $es1,
			"s2" => $es2,
			"s3" => $es3,
			"created_date" => $current_date
			));
		/* } */

		$this->session->set_flashdata("globalmsg", 
			lang("success_183"));
		redirect(site_url("machine/shift_users"));
	}
	
	public function edit_shift_users($id) 
	{
		$this->template->loadExternal(
			'<script src="'.base_url().
			'scripts/libraries/jscolor.min.js"></script>'
		);
		
		$this->template->loadData("activeLink", 
			array("machine" => array("shift_users" => 1)));
			
		$id = intval($id);
		
		$shift_allocation = $this->machine_model->edit_available_shift_allocation();	
		
		$shift_type = $this->machine_model->get_shift_type();
		/* $team_users = $this->machine_model->get_available_users_by_team();
		$shift_users = $this->machine_model->allocate_users_by_shift(); */
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		
		if($this->common->has_permissions(array("admin"), $this->user)) {
			$team_name = $this->machine_model->get_available_team_name();
			$machine_count = $this->machine_model->get_count_machine();
			$mem_teamname = '';	
		} else {
			$team_name = $this->machine_model->get_useravailable_team_name($this->user->info->ID);
			if(isset($team_name)){ 
				foreach($team_name->result() as $r) : 
					$mem_teamname = $r->team_name;
				endforeach; 
			$machine_count = $this->machine_model->get_count_machine_byteam($mem_teamname);	
			}
		}
		$sum_shift_allocation = $this->machine_model->get_sum_shift_allocation($week_number_of_year,$mem_teamname);
				
		$shift_users = $this->machine_model->get_shift_users($id);
		
		if($shift_users->num_rows() == 0) {
			$this->template->error(lang("error_311"));
		}
		$shift_users = $shift_users->row();
		$current_year  = date("Y");
		if($this->common->has_permissions(array("admin"), $this->user)) {
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
		$shift_detail = '';
		} else {
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
		$shift_detail = $this->machine_model->mem_shift_details($mem_teamname,$week_number_of_year,$current_year);
		}
		
		$this->template->loadContent("machine/edit_shift_users.php", array(
			"shift_users" => $shift_users,
			"shift_details" => $shift_details,
			"shift_detail" => $shift_detail,
			"shift_users" => $shift_users,
			"shift_allocation" => $shift_allocation,
			"shift_type" => $shift_type,
			"week_number_of_year" => $week_number_of_year,
			"machine_count" => $machine_count,
			"sum_of_shift" => $sum_shift_allocation,
			"current_date" => $current_date
			)
		);
	}

	public function edit_shift_users_pro($id) 
	{
		$id = intval($id);
		$shift_users = $this->machine_model->get_shift_users($id);
		if($shift_users->num_rows() == 0) {
			$this->template->error(lang("error_311"));
		}
		$shift_users = $shift_users->row();

		$team_name = $this->common->nohtml($this->input->post("team_name"));
		$member_name = $this->common->nohtml($this->input->post("member_name"));
		$shift_type = $this->common->nohtml($this->input->post("shift_type"));
		
		$s1 = $this->input->post("s1", true);
		$s2 = $this->input->post("s2", true);
		$s3 = $this->input->post("s3", true);
		//$current_date = $this->common->nohtml($this->input->post("current_date"));
		$current_date = date("Y-m-d");
			$duedt = explode("-", $current_date);
			$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
			$week_number_of_year  = (int)date('W', $date);
			
		/* if(empty($team_name)) $this->template->error(lang("error_308"));
		if(empty($member_name)) $this->template->error(lang("error_310")); */
		if(empty($shift_type)) $this->template->error(lang("error_305"));

		// Add
		$this->machine_model->update_shift_users($id, array(
			/* "team_name" => $team_name ,
			"member_name" => $member_name, */
			"shift_type" => $shift_type,
			"week_number_of_year" => $week_number_of_year,
			"s1" => $s1,
			"s2" => $s2,
			"s3" => $s3,
			"created_date" => $current_date
			)
		);

		$this->session->set_flashdata("globalmsg", 
			lang("success_183"));
		redirect(site_url("machine/shift_users"));
	}

	public function delete_shift_users($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$shift_users = $this->machine_model->get_shift_users($id);
		if($shift_users->num_rows() == 0) {
			$this->template->error(lang("error_311"));
		}
		$shift_users = $shift_users->row();

		$this->machine_model->delete_shift_users($id);

		$this->session->set_flashdata("globalmsg", 
			lang("success_182"));
		redirect(site_url("machine/shift_users"));
	}
	
	public function delete_it($mem, $team, $week, $year, $hash) 
	{
		/* if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		} */
		$this->machine_model->delete_it($mem, $team, $week, $year);
		$this->session->set_flashdata("globalmsg", lang("success_182"));
		redirect(site_url("machine/shift_users"));
	}
	
	public function delete_all_shift_users() 
	{
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$current_year  = date("Y");
		$this->machine_model->delete_all_shift_users($week_number_of_year,$current_year);
		$this->session->set_flashdata("globalmsg",lang("success_182"));
		redirect(site_url("machine/shift_users"));
	}
	
	public function ajaxcall() 
	{
		$team_name = $this->input->post('teamname');
		$shift_users = $this->machine_model->get_user_list_from_team_name($team_name); 
		echo json_encode($shift_users);
	}
	
	public function check_membername() 
	{
		/* $member_name = $this->common->nohtml($this->input->get("member_name", true)); */
		$mem_name = $this->input->get("member_name", true);
		/* $member_name = "'" . implode( "','", $mem_name) . "'";  */
		$member_name = implode(',', $mem_name); 
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		if (!$this->machine_model->check_membername_is_free($member_name,$week_number_of_year)) {
			/* $field_errors = "<b>".$member_name."</b>". lang("ctn_1632"); */
			$field_errors = "Check any of the member". lang("ctn_1632");
		}

		if(empty($field_errors)) {
			//echo json_encode(array("success" => 1));
		} else {
			echo "<div id='member_in_use'>".$field_errors."</div>";
		}

		exit();
	}
	
	public function exportcurrentweekshifts() 
	{
		$this->load->model("machine_model");
		
		$filename = "current_week_shifts_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$current_week_shifts = $this->machine_model->current_week_shifts($week_number_of_year);
		
		/* print_r($current_week_shifts);
		exit(); */
		
		if (count($current_week_shifts) > 0) {
			foreach($current_week_shifts as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		}
		exit;
	}
	
	public function exportlastweekshifts() 
	{
		$this->load->model("machine_model");
		
		$filename = "last_week_shifts_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$last_week_shifts = $this->machine_model->current_week_shifts($week_number_of_year-1);
		
		/* print_r($current_week_shifts);
		exit(); */
		
		if (count($last_week_shifts) > 0) {
			foreach($last_week_shifts as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		}
		exit;
	}
	
	public function exportallshiftdetails() 
	{
		$this->load->model("machine_model");
		
		$filename = "shift_details_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$current_year  = date("Y");
		$shift_details = $this->machine_model->shift_details($week_number_of_year,$current_year);
		
		/* print_r($current_week_shifts);
		exit(); */
		
		if (count($shift_details) > 0) {
			foreach($shift_details as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		}
		exit;
	}
	
	public function exportshiftusers() 
	{
		$this->load->model("machine_model");
		
		$filename = "shift_users_".date("Y-m-d").".csv";
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$current_date = date("Y-m-d");
		$duedt = explode("-", $current_date);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
		$week_number_of_year  = (int)date('W', $date);
		$shifts_users = $this->machine_model->get_available_shift_allocation($week_number_of_year);
		
		/* print_r($current_week_shifts);
		exit(); */
		
		if (count($shifts_users) > 0) {
			foreach($shifts_users as $key => $row) {
				if ($key==0) fputcsv($fp, array_keys((array)$row)); 
					$line = (array)$row;
					fputcsv($fp, $line);
			}
		}
		exit;
	}
	
	public function machine_page() 
	{
		$this->load->library("datatables");
		$this->datatables->set_default_order("machine_allocation.ID", "asc");

		// Set page ordering options that can be used
		 $this->datatables->ordering(
			array(
				 0 => array(
				 	"machine_allocation.ID" => 0
				 ),
				 1 => array(
				 	"machine_allocation.machine_no" => 0
				 ),
				 2 => array(
				 	"machine_allocation.team_name" => 0
				 ),
				 3 => array(
				 	"machine_allocation.softwares" => 0
				 ),
				 4 => array(
				 	"machine_allocation.shifts" => 0
				 ),
				 5 => array(
				 	"machine_allocation.seat_no" => 0
				 ),
				 6 => array(
				 	"machine_allocation.head_count" => 0
				 )
			)
		); 

		$this->datatables->set_total_rows($this->machine_model->get_total_machines_count());
		$mach = $this->machine_model->get_machine_admin($this->datatables);

		foreach($mach->result() as $r) {
			$this->datatables->data[] = array(
				$r->ID,
				$r->machine_no,
				$r->team_name,
				$r->softwares,
				$r->shifts,
				$r->seat_no,
				$r->head_count,
				'<a href="'.site_url("machine/edit_machine/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("machine/delete_machine/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function upload_machine() 
    {
	set_time_limit(0);
	error_reporting(E_ALL); 
	$this->load->model("machine_model");
    try{    
    if (isset($_POST["uploadmac"])) { 
				$path = 'uploads/';
				require_once APPPATH . "third_party\PHPExcel\Classes\PHPExcel.php";
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'xlsx|xls';
				$config['remove_spaces'] = TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);            
				if (!$this->upload->do_upload('uploadmachine')) {
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
                  $inserdata[$i]['machine_no'] = isset($value['A']) ? $value['A'] : NULL;
                  $inserdata[$i]['team_name'] = isset($value['B']) ? $value['B'] : NULL;
                  $inserdata[$i]['softwares'] = isset($value['C']) ? $value['C'] : NULL;
                  $inserdata[$i]['shifts'] = isset($value['D']) ? $value['D'] : NULL;
				  $inserdata[$i]['seat_no'] = isset($value['E']) ? $value['E'] : NULL;
                  $inserdata[$i]['head_count'] = isset($value['F']) ? $value['F'] : NULL;		
                  $i++;
                }                
				$result = $this->machine_model->machdata($inserdata);
                if($result){
                  $this->session->set_flashdata('success', 'Imported successfully');
					redirect(site_url("machine/index"));
                }else{
                  $this->session->set_flashdata('error', 'Problem in uploading the file!');
					redirect(site_url("machine/index"));
                }
			
		} else {
			$this->session->set_flashdata('error', 'Problem in uploading the file!');
			redirect(site_url("machine/index"));
		}
		}
		}
		catch (Exception $e) {
			echo __LINE__ . '-----' . __FILE__ . '-----';
			echo "<pre>";
			print_r($e);
			log_message('error', $e);
			/*  die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' .$e->getMessage());
			 */die; 
		}	
    }	
}

?>
