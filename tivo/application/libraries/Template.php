<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Template
{

	var $cssincludes;
	var $sidebar;
	var $responsive_sidebar;
	var $layout = "";
	var $data = array();
	var $error_layout = 0;
	var $error_view = "error/error.php";
	var $page_title = "";
	var $error_hack = 0;
	var $CI = null;

	public function loadContent($view,$data=array(),$die=0)
	{
		$this->CI =& get_instance();
		$site = array();

		if(empty($this->layout)) {
			$this->set_layout($this->CI->settings->info->layout);
		}

		$site['cssincludes'] = $this->cssincludes;
		foreach($this->data as $k=>$v) {
			$site[$k] = $v;
		}
		foreach($this->data as $k=>$v) {
			$data[$k] = $v;
		}
		if($this->sidebar) {
			$site['sidebar'] = $this->CI->load->view($this->sidebar,$data,true);
		}

		if($this->page_title) {
			$site['page_title'] = $this->page_title;
		}

		if($this->responsive_sidebar) {
			$site['responsive_sidebar'] = $this->CI->load
				->view($this->responsive_sidebar,$data,true);
		}

		$site = $this->set_language($site);

		$data['site'] = $site;

		$site['content'] = $this->CI->load->view($view,$data,true);

		$this->CI->load->view($this->layout, $site);
		if($die) die($this->CI->output->get_output());
	}

	public function loadAjax($view,$data=array(),$die=0) 
	{
		$this->CI =& get_instance();
		$site = array();
		$data['cssincludes'] = $this->cssincludes;
		$data = $this->set_language($data);
		$this->CI->load->view($view,$data);
		if($die) die($this->CI->output->get_output());
	}

	public function loadAjaxReturn($view,$data=array(),$die=0) 
	{
		$this->CI =& get_instance();
		$site = array();
		$data['cssincludes'] = $this->cssincludes;
		return $this->CI->load->view($view,$data, true);
	}

	private function set_language($site) 
	{
		// Lanuggae
		$lang = $this->CI->config->item('language');

		$languages = $this->CI->config->item("available_languages");
		$enable_rtl = 0;
		$datatable_lang = "";
		$fullcalendar_lang = "";
		$datetimepicker = "";
		$datepicker = "";
		if(array_key_exists($lang, $languages)) {
			if(isset($languages[$lang]['rtl_support'])) {
				$enable_rtl = $languages[$lang]['rtl_support']; 
			}
			if(isset($languages[$lang]['datatable_lang'])) {
				$datatable_lang = $languages[$lang]['datatable_lang'];
			}
			if(isset($languages[$lang]['fullcalendar_lang'])) {
				$fullcalendar_lang = $languages[$lang]['fullcalendar_lang'];
			}
			if(isset($languages[$lang]['datetimepicker'])) {
				$datetimepicker = $languages[$lang]['datetimepicker'];
			}
			if(isset($languages[$lang]['datepicker'])) {
				$datepicker = $languages[$lang]['datepicker'];
			}
		}

		$site['enable_rtl'] = $enable_rtl;
		$site['datatable_lang'] = $datatable_lang;
		$site['fullcalendar_lang'] = $fullcalendar_lang;
		$site['datetimepicker'] = $datetimepicker;
		$site['datepicker'] = $datepicker;
		return $site;
	}

	public function set_page_title($title) 
	{
		$this->page_title = $title;
	}

	public function loadSidebar($view) 
	{
		$this->sidebar = $view;
	}

	public function loadResponsiveSidebar($view) 
	{
		$this->responsive_sidebar = $view;
	}

	public function set_error_layout($error) 
	{
		$this->error_layout = $error;
	}

	public function set_error_view($view) 
	{
		$this->error_view = $view;
	}

	public function set_layout($view) 
	{
		$this->layout = $view;
	}

	public function loadData($key, $data) 
	{
		$this->data[$key] = $data;
	}

	public function loadExternal($code) 
	{
		$this->cssincludes = $code;
	}

	public function error($message) 
	{
		if($this->error_hack) {
			$this->errori($message);
		}
		if(!$this->error_layout) {
			$this->loadContent($this->error_view,array(
				"message" => $message),1);
		} else {
			$this->loadContent($this->error_view,array(
				"message" => $message),1);
		}
	}

	public function errori($msg) 
	{
		echo "ERROR: " . $msg;
		exit();
	}

	public function jsonError($msg) 
	{
		echo json_encode(array("error"=>1, "error_msg" => $msg));
		exit();
	}

}

?>
