<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Settings 
{

	var $info=array();

	var $version = "1.8";

	public function __construct() 
	{
		$CI =& get_instance();
		$site = $CI->db->select("site_name,site_desc,site_email,
			upload_path_relative, upload_path, site_logo, register,
			 disable_captcha, date_format, avatar_upload, file_types,
			 twitter_consumer_key, twitter_consumer_secret, disable_social_login
			 , facebook_app_id, facebook_app_secret, google_client_id,
			 google_client_secret, file_size, paypal_email, paypal_currency,
			 payment_enabled, payment_symbol, global_premium, calendar_type,
			 google_calendar_id, calendar_timezone, google_calendar_api_key,
			 disable_ticket_upload, protocol, protocol_path, protocol_email,
			 protocol_password, protocol_ssl, ticket_title, login_protect,
			 activate_account, fp_currency_symbol,
			 enable_calendar, enable_tasks, enable_files, enable_team,
			 enable_time, enable_tickets, enable_finance, enable_invoices,
			 enable_notes, enable_leads, default_user_role, install,
			 secure_login, enable_reports, date_picker_format, 
			 calendar_picker_format,google_recaptcha_secret, 
			 google_recaptcha_key, google_recaptcha, logo_option,
			 enable_services, layout, cache_time, imap_ticket_string,
			 imap_reply_string, profile_comments, client_user_role,
			 enable_chat, chat_update, avatar_width, avatar_height,
			 resize_avatar, chat_online_client, enable_docs, doc_view_all,
			 dashboard_services")
		->where("ID", 1)
		->get("site_settings");
		
		if($site->num_rows() == 0) {
			$CI->template->error(
				"You are missing the site settings database row."
			);
		} else {
			$this->info = $site->row();
		}
	}

}

?>