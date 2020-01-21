<?php

class Reports_Model extends CI_Model 
{
	public function get_tickets_for_day($date) 
	{

		$s = $this->db->select("COUNT(*) as num")->where("ticket_date", $date)
			->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_tickets_for_day_closed($date) 
	{

		$s = $this->db->select("COUNT(*) as num")->where("close_ticket_date", $date)
			->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_tickets_for_month_closed($month, $year) 
	{
		$string = "-" . $month . "-" . $year;

		$s = $this->db->select("COUNT(*) as num")->like("close_ticket_date", $string)
			->get("tickets");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function count_hours_date($date, $projectid, $userid=0) {
		if($userid > 0) {
			$this->db->where("user_timers.userid", $userid);
		}
		if($projectid > 0) {
			$this->db->where("user_timers.projectid", $projectid);
		}
		
		return $this->db
			->where("user_timers.date_stamp", $date)
			->select("(user_timers.end_time - user_timers.start_time) as time, user_timers.rate, user_timers.projectid, projects.name")
			->where("end_time >", 0)
			->join("projects", "projects.ID = user_timers.projectid", "left outer")
			->get("user_timers");
	}

	public function count_hours_date_projects($date, $projectid, $cur_userid, $userid=0) {
		if($userid > 0) {
			$this->db->where("user_timers.userid", $userid);
		}
		if($projectid > 0) {
			$this->db->where("user_timers.projectid", $projectid);
		}
		
		return $this->db
			->where("user_timers.date_stamp", $date)
			->select("(user_timers.end_time - user_timers.start_time) as time, user_timers.rate, user_timers.projectid, projects.name")
			->where("end_time >", 0)
			->join("projects", "projects.ID = user_timers.projectid")
			->join("project_members as pm2", "pm2.projectid = projects.ID", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $cur_userid)
			->where("(pr2.admin = 1 OR pr2.reports = 1)")
			->group_end()
			->get("user_timers");
	}

	public function get_finance_sum($date, $projectid, $type) 
	{
		if($type) {
			$this->db->where("finance.amount >", 0);
		} else {
			$this->db->where("finance.amount <", 0);
		}

		if($projectid > 0) {
			$this->db->where("finance.projectid", $projectid);
		}
		$s = $this->db
			->where("finance.time_date", $date)
			->select("SUM(finance.amount) as num")
			->get("finance");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_finance_sum_projects($date, $userid, $projectid, $type) 
	{
		if($type) {
			$this->db->where("finance.amount >", 0);
		} else {
			$this->db->where("finance.amount <", 0);
		}

		if($projectid > 0) {
			$this->db->where("finance.projectid", $projectid);
		}
		$s = $this->db
			->where("finance.time_date", $date)
			->select("SUM(finance.amount) as num")
			->join("projects", "projects.ID = finance.projectid")
			->join("project_members as pm2", "pm2.projectid = projects.ID", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.reports = 1)")
			->group_end()
			->get("finance");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_invoice_sum($date, $projectid, $type) 
	{
		if($type) {
			$this->db->where("invoices.time_date", $date);
		} else {
			$this->db->where("invoices.time_date_paid", $date);
		}

		if($projectid > 0) {
			$this->db->where("invoices.projectid", $projectid);
		}
		$s = $this->db
			->select("SUM(invoices.total) as num")
			->get("invoices");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_invoice_sum_projects($date, $userid, $projectid, $type) 
	{
		if($type) {
			$this->db->where("invoices.time_date", $date);
		} else {
			$this->db->where("invoices.time_date_paid", $date);
		}

		if($projectid > 0) {
			$this->db->where("invoices.projectid", $projectid);
		}
		$s = $this->db
			->select("SUM(invoices.total) as num")
			->join("projects", "projects.ID = invoices.projectid")
			->join("project_members as pm2", "pm2.projectid = projects.ID", "left outer")
			->join("project_roles as pr2", "pr2.ID = pm2.roleid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->where("(pr2.admin = 1 OR pr2.reports = 1)")
			->group_end()
			->get("invoices");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
}

?>