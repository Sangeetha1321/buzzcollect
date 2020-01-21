<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funds extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("funds_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("funds" => array("general" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		$this->template->loadContent("funds/index.php", array(
			)
		);
	}

	public function plans() 
	{
		$this->template->loadData("activeLink", 
			array("funds" => array("plans" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		$plans = $this->funds_model->get_plans();
		$this->template->loadContent("funds/plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function buy_plan($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$plan = $this->funds_model->get_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));
		$plan = $plan->row();

		// Check user has dolla
		if($this->user->info->points < $plan->cost) {
			$this->template->error(lang("error_62"));
		}

		if($this->user->info->premium_time == -1) {
			$this->template->error(lang("error_63"));
		}

		if($plan->days > 0) {
			$premium_time = $this->user->info->premium_time;
			$time_added = (24*3600) * $plan->days;

			// Check to see if user currently has time.
			if($premium_time > time()) {
				// If plan does not equal current one, then we reset 
				// the timer 
				if($this->user->info->premium_planid != $plan->ID) {
					$premium_time = time() + $time_added;
				} else {
					$premium_time = $premium_time + $time_added;
				}
			} else {
				$premium_time = time() + $time_added;
			}
		} else {
			// Unlimited Time modifier
			$premium_time = -1;
		}

		$this->user->info->points = $this->user->info->points - $plan->cost;

		$this->user_model->update_user($this->user->info->ID, array(
			"premium_time" => $premium_time,
			"points" => $this->user->info->points,
			"premium_planid" => $plan->ID
			)
		);

		$this->funds_model->update_plan($id, array(
			"sales" => $plan->sales + 1
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_28"));
		redirect(site_url("funds/plans"));
	}

}

?>