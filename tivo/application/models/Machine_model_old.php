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
		return $this->db->get("team_name");
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
	
	public function get_machine_details() 
	{
		return $this->db->get("machine_allocation");
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
	
	public function get_available_shift_allocation() 
	{
	
		return $this->db->order_by("shift_allocation.team_name", "ASC")->get("shift_allocation");
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

	public function update_shift_users($id, $data) 
	{
		$this->db->where("id", $id)->update("shift_allocation", $data);
	}
	
	public function get_user_list_from_team_name($team_name) 
	{
		  $this->db->select("users.first_name");
		  $this->db->where("users.team_name LIKE '%$team_name%'");
		  $this->db->order_by("users.first_name", "asc"); 
		  $this->db->from("users"); 
		  $query = $this->db->get();
		  $result = $query->result_array();
		  return $result;
	}
}	
?>