<?php
class Machine_Model extends CI_Model 
{
	public function get_available_softwares() 
	{
		return $this->db->get("softwares");
	}
	
	public function add_softwares($data) 
	{
		$this->db->insert("softwares", $data);
	}

	public function get_softwares($id) 
	{
		return $this->db->where("ID", $id)->get("softwares");
	}

	public function delete_softwares($id) 
	{
		$this->db->where("ID", $id)->delete("softwares");
	}

	public function update_softwares($id, $data) 
	{
		$this->db->where("ID", $id)->update("softwares", $data);
	}
	
	public function get_available_shifts() 
	{
		return $this->db->get("shifts");
	}
	
	public function add_shifts($data) 
	{
		$this->db->insert("shifts", $data);
	}

	public function get_shifts($id) 
	{
		return $this->db->where("id", $id)->get("shifts");
	}

	public function delete_shifts($id) 
	{
		$this->db->where("id", $id)->delete("shifts");
	}

	public function update_shifts($id, $data) 
	{
		$this->db->where("id", $id)->update("shifts", $data);
	}
	
	public function get_available_team_name() 
	{
		return $this->db->order_by("team_name.team_name", "ASC")->get("team_name");
	}
	
	public function get_useravailable_team_name($userid) 
	{
	 	$s = $this->db
			->select("users.team_name")
			->where("users.ID", $userid)
			->get("users");
			
        return $s; 		
	}
	
	public function get_count_machine() 
	{
		$this->db->from('machine_allocation');
		$query = $this->db->get();
		$rowcount = $query->num_rows();
		return $rowcount;
	}
	
	public function get_count_machine_byteam($team_name) 
	{
		$this->db->from('machine_allocation');
		$this->db->where("machine_allocation.team_name", $team_name);
		$query = $this->db->get();
		$rowcount = $query->num_rows();
		return $rowcount;
	}
	
	public function add_team_name($data) 
	{
		$this->db->insert("team_name", $data);
	}

	public function get_team_name($id) 
	{
		return $this->db->where("id", $id)->get("team_name");
	}

	public function delete_team_name($id) 
	{
		$this->db->where("id", $id)->delete("team_name");
	}

	public function update_team_name($id, $data) 
	{
		$this->db->where("id", $id)->update("team_name", $data);
	}
	
	/* public function get_machine_details() 
	{
		return $this->db->get("machine_allocation");
	} */
	
	public function get_machine_details(){
		$this->db->select('machine_allocation.*', FALSE);
		$this->db->from('machine_allocation');
		$this->db->order_by('machine_allocation.ID', 'ASC');
        $query_resultant = $this->db->get();
		return $query_resultant;
    }
	
	public function add_machine($data) 
	{
		$this->db->insert("machine_allocation", $data);
	}

	public function get_machine($id) 
	{
		return $this->db->where("ID", $id)->get("machine_allocation");
	}

	public function delete_machine($id) 
	{
		$this->db->where("ID", $id)->delete("machine_allocation");
	}

	public function update_machine($id, $data) 
	{
		$this->db->where("ID", $id)->update("machine_allocation", $data);
	}
	
	public function get_available_shift_allocation($weeknumber) 
	{
		$this->db->select("shift_allocation.*");
		$this->db->where("(shift_allocation.week_number_of_year = '$weeknumber') ");
		$this->db->order_by("shift_allocation.team_name,shift_allocation.created_date", "DESC"); 
		$this->db->from("shift_allocation"); 
		$query = $this->db->get();
		/* echo $this->db->last_query(); */
		$result = $query->result_array();
		return $result;
		/* return $this->db->order_by("shift_allocation.team_name DESC", "shift_allocation.created_date ASC")->where("(shift_allocation.week_number_of_year = '$weeknumber') ")->get("shift_allocation")->result_array(); */
	}
	
	public function edit_available_shift_allocation() 
	{
		return $this->db->order_by("shift_allocation.team_name", "DESC")->get("shift_allocation")->result_array();
	}
	
	public function add_shift_users($data) 
	{
		$this->db->insert("shift_allocation", $data);
	}

	public function get_shift_users($id) 
	{
		return $this->db->where("id", $id)->get("shift_allocation");
	}

	public function delete_shift_users($id) 
	{
		$this->db->where("id", $id)->delete("shift_allocation");
	}
	
	public function delete_it($mem, $team, $week, $year) 
	{
		$this->db->where("member_name", $mem)->where("team_name", $team)->where("week_number_of_year", $week)->where("YEAR(created_date)", $year)->delete("shift_allocation");
	}
	
	public function delete_all_shift_users($week_number_of_year,$current_year) 
	{
		$this->db->where("week_number_of_year", $week_number_of_year)->where("YEAR(created_date)", $current_year)->delete("shift_allocation");
	}

	public function update_shift_users($id, $data) 
	{
		$this->db->where("id", $id)->update("shift_allocation", $data);
	}
	
	public function edit_shift_for_user($eteam_name,$emember_name,$data) 
	{
		$this->db->where("team_name", $eteam_name)->where("member_name", $emember_name)->update("shift_allocation", $data);
	}
	
	public function get_user_list_from_team_name($team_name) 
	{
		  $this->db->select("users.username");
		  $this->db->where("users.team_name LIKE '%$team_name%'");
		  $this->db->order_by("users.username", "asc"); 
		  $this->db->from("users"); 
		  $query = $this->db->get();
		  $result = $query->result_array();
		  return $result;
	}
	
	public function get_shift_type() 
	{
		return $this->db->get("shifts");
	}
	
	public function get_sum_shift_allocation($weeknumber,$mem_teamname) 
	{
		//$this->db->select_sum('s1','s2');
		$this->db->select('SUM(s1) as sum_s1 , SUM(s2) as sum_s2 , SUM(s3) as sum_s3', FALSE);
		$this->db->from('shift_allocation');
		$this->db->where("(week_number_of_year = '$weeknumber') "); 
		$this->db->where("(team_name = '$mem_teamname') "); 
		$query = $this->db->get();
		/* echo $this->db->last_query();
		die; */
		$result = $query->result_array();
		return $result;
		/* return $query->row()->s1; */
	}
	
	public function current_week_shifts($weeknumber) 
	{
		$this->db->select("shift_allocation.shift_type,GROUP_CONCAT(DISTINCT shift_allocation.member_name ORDER BY shift_allocation.id ASC SEPARATOR',') as member_name,GROUP_CONCAT(shift_allocation.team_name ORDER BY shift_allocation.id ASC SEPARATOR',') as team_name"); 
		$this->db->from("shift_allocation");
		$this->db->join("shifts", "shifts.shift_type = shift_allocation.shift_type");
		$this->db->where("(week_number_of_year = '$weeknumber') "); 
		$this->db->group_by("shift_allocation.shift_type");
		$this->db->order_by("shifts.id","ASC");
		$query = $this->db->get(); 
		$result = $query->result_array(); 
		return $result;
	}
	
	public function shift_details($weeknumber,$current_year) 
	{
		$this->db->select("shift_allocation.shift_type,GROUP_CONCAT(DISTINCT shift_allocation.member_name ORDER BY shift_allocation.id ASC SEPARATOR',') as member_name,GROUP_CONCAT(shift_allocation.team_name ORDER BY shift_allocation.id ASC SEPARATOR',') as team_name,shifts.shift_timing"); 
		$this->db->from("shift_allocation");
		$this->db->join("shifts", "shifts.shift_type = shift_allocation.shift_type");
		$this->db->where("(week_number_of_year = '$weeknumber') ");
		$this->db->where("YEAR(shift_allocation.created_date)", $current_year);
		$this->db->group_by("shift_allocation.shift_type");
		$this->db->order_by("shifts.id","ASC");
		$query = $this->db->get(); 
		/* echo $this->db->last_query(); */
		$result = $query->result_array(); 
		return $result;
	}
	
	public function mem_shift_details($mem_teamname,$weeknumber,$current_year) 
	{
		$this->db->select("shift_allocation.week_number_of_year,shift_allocation.created_date,shift_allocation.shift_type,GROUP_CONCAT(DISTINCT shift_allocation.member_name ORDER BY shift_allocation.id ASC SEPARATOR',') as member_name,GROUP_CONCAT(shift_allocation.team_name ORDER BY shift_allocation.id ASC SEPARATOR',') as team_name,shifts.shift_timing"); 
		$this->db->from("shift_allocation");
		$this->db->join("shifts", "shifts.shift_type = shift_allocation.shift_type");
		$this->db->where("shift_allocation.team_name", $mem_teamname);
		$this->db->where("(shift_allocation.week_number_of_year = '$weeknumber') ");
		$this->db->where("YEAR(shift_allocation.created_date)", $current_year);
		$this->db->group_by("shift_allocation.shift_type");
		$this->db->order_by("shifts.id","ASC");
		$query = $this->db->get(); 
		/* echo $this->db->last_query();  */
		$result = $query->result_array(); 
		return $result;
	}
	
	public function check_membername_is_free($member_name,$weeknumber) 
	{
		$mem = explode(',', $member_name);
		$s=$this->db->where_in("member_name", $mem)->where("(week_number_of_year = '$weeknumber') ")->get("shift_allocation");
		if($s->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function get_users_of_all_teams() 
	{
		return $this->db
			->select("users.employee_id,users.username,users.team_name")
			->order_by("users.team_name", "ASC")
			->get("users");
	}
	
	public function all_resources($all_teams) 
	{
		//$this->db->select_sum('s1','s2');
		$this->db->select('users.ID,users.username', FALSE);
		$this->db->from('users');
		$this->db->where("(users.team_name != '$all_teams') ");
		$query = $this->db->get();
		/* echo $this->db->last_query();
		die; */
		$result = $query->result_array();
		return $result;
		/* return $query->row()->s1; */
	}
	
	public function update_resources($allsharedresources,$data) 
	{
		$this->db->where("ID", $allsharedresources)->update("users", $data);
		/* echo $this->db->last_query(); */
	}
	
	public function get_shared_resources() 
	{
		$this->db->select('ID,team_name,shared_resource,username,avatar,online_timestamp,shared_from', FALSE);
		$this->db->from('users');
		$this->db->where("(shared_resource != '') ");
		$this->db->where("(shared_delete != 0) ");
		$this->db->order_by("shared_resource", "ASC");
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	public function delete_resources($allsharedresources,$data) 
	{
		$this->db->where("ID", $allsharedresources)->update("users", $data);
		/* echo $this->db->last_query(); */
	}
	
	public function get_total_machines_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("machine_allocation");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	
	public function get_machine_admin($datatable) 
	{
		$datatable->db_order();
		$datatable->db_search(array(
			"machine_allocation.machine_no",
			"machine_allocation.team_name",
			"machine_allocation.softwares",
			"machine_allocation.shifts",
			"machine_allocation.seat_no",
			"machine_allocation.head_count"
			)
		);

		return $this->db->select("machine_allocation.*")
		->limit($datatable->length, $datatable->start)
		->get("machine_allocation");
	}
	
	public function machdata($data) 
	{
		$res = $this->db->insert_batch("machine_allocation", $data);
		if($res){
            return TRUE;
        }else{
            return FALSE;
        }
	}
}
?>