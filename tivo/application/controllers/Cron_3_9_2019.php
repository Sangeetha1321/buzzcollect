<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller 
{
	var $debug;

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");
		$this->load->model("invoices_model");
		$this->load->model("home_model");
		$this->load->model("finance_model");
	}

	public function finance() 
	{
		$current_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y") . " 00:00:00");
		$debug = "";
		$finance = $this->finance_model->get_reoccur_finances_all();
		foreach($finance->result() as $r) {
			if($r->last_occurence > 0 && $r->next_occurence > 0) {
				$start_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $r->next_occurence) . " 00:00:00");
				if($start_date->getTimestamp() == $current_date->getTimestamp()) {
					// Create new invoice.
					if(!$this->new_finance($r)) {
						continue;
					}
				}
			} else {
				// Check start date is today
				$start_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $r->start_date) . " 00:00:00");
				if($current_date->getTimestamp() >= $start_date->getTimestamp()) {
					// Create new invoice.
					if(!$this->new_finance($r)) {
						continue;
					}
				}
			}
		}
		echo $this->debug;
		exit();
	}

	private function new_finance($finance) 
	{
		// Just recreate the finance record
		// Add
		$this->finance_model->add_finance(array(
			"title" => $finance->title,
			"notes" => $finance->notes,
			"categoryid" => $finance->categoryid,
			"projectid" => $finance->projectid,
			"userid" => $finance->userid,
			"amount" => $finance->amount,
			"timestamp" => time(),
			"month" => date("n"),
			"year" => date("Y"),
			"time_date" => date("Y-m-d")
			)
		);

		// Calculate next occurence
		$current_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y") . " 00:00:00");
		$amount = $finance->reoccur_days;
		$day = 3600*24;
		
		// Days 
		$next = $current_date->getTimestamp() + ( $day * $amount );
		

		if($finance->end_date > 0) {
			// Check to make sure end date isn't exceeded
			$end_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $finance->end_date) . " 00:00:00");

			if($end_date->getTimestamp() < $next) {
				$next = 0;
			}
		}

		// Update Reoccur Invoice
		$this->finance_model->update_finance($finance->ID, array(
			"last_occurence" => time(),
			"next_occurence" => $next
			)
		);
	}

	public function invoices() 
	{
		$current_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y") . " 00:00:00");
		$debug = "";
		$invoices = $this->invoices_model->get_reoccuring_invoices_all();
		foreach($invoices->result() as $r) {
			if($r->last_occurence > 0) {
				$start_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $r->next_occurence) . " 00:00:00");
				if($start_date->getTimestamp() == $current_date->getTimestamp()) {
					// Create new invoice.
					if(!$this->new_invoice($r)) {
						continue;
					}
				}
			} else {
				// Check start date is today
				$start_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $r->start_date) . " 00:00:00");
				if($current_date->getTimestamp() >= $start_date->getTimestamp()) {
					// Create new invoice.
					if(!$this->new_invoice($r)) {
						continue;
					}
				}
			}
		}
		echo $this->debug;
		exit();
	}

	public function invoices_overdue() 
	{
		// Get all invoices that are overdue and email the clients
		$invoices = $this->invoices_model->get_overdue_invoices(time());
		foreach($invoices->result() as $r) {

			if(!empty($r->guest_email)) {
				$client_username = $r->guest_name;
				$client_email = $r->guest_email;
			} else {
				$client_username = $r->client_username;
				$client_email = $r->client_email;
			}

			// Get template
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("invoice_reminder", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $client_username,
				"[SITE_URL]" => site_url(),
				"[INVOICE_URL]" => 
					site_url("invoices/view/" . $r->ID . "/" . $r->hash),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $client_email);
		}
		exit();
	}
	
	public function taskoverdue() 
	{
		/* error_reporting(-1);
		ini_set('display_errors', 'On'); */
		$default_usergroups = $this->invoices_model->get_users_from_default_groups();
		/* $client_email = $default_usergroups->result_array();   */
		$client_email = array
		(
			0 => array
				(
					"email" => 'v.sangeetha@spi-global.com',
					"username" => 'mail1'
				),
				
			1 => array
				(
					"email" => 'v.sangeetha2@spi-global.com',
					"username" => 'mail2'
				)
		);    
		if(!isset($_COOKIE['language'])) {
			$lang = $this->config->item("language");
		} else {
			$lang = $this->common->nohtml($_COOKIE["language"]);
		}
		$task_overdue = date('Y-m-d', time()); 
		$tasksoverdue = $this->invoices_model->get_overdue_tasks($task_overdue); 
		$task_alert_tomorrow = time() + 24*60*60;
		$task_alert = date('Y-m-d', $task_alert_tomorrow); 
		$tasksalert = $this->invoices_model->get_tasks_alert($task_alert); 
		$toa = [];
		foreach($tasksoverdue->result() as $r) {
			$toa[] = site_url("tasks/view/" . $r->ID);
		}
		$tasksoverdue_links = "<pre>".implode(",\n",$toa)."</pre>";
		$ta = [];
		foreach($tasksalert->result() as $tar) {
			$ta[] = site_url("tasks/view/" . $tar->ID);
		}
		$tasksalert_links = "<pre>".implode(",\n",$ta)."</pre>";
		/* $taskstoday = $this->invoices_model->get_tasks_today(time());  */
		$email_template = $this->home_model->get_email_template_hook("tasks_overdue", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
		$email_template = $email_template->row();
			foreach ($client_email as $ce) {
				$email_template->message = $this->common->replace_keywords(array(
					"[NAME]" => $ce['username'],
					"[SITE_URL]" => site_url(),
					"[TASKS_OVERDUE_URL]" => $tasksoverdue_links,
					"[TASKS_ALERT_URL]" => $tasksalert_links,
					"[SITE_NAME]" =>  $this->settings->info->site_name
					),$email_template->message);
					
				$this->common->send_email($email_template->title, $email_template->message, $ce['email']);				
				sleep(25); 
			}
		exit(); 
	}

	private function new_invoice($r) 
	{
		$user = $this->user_model->get_user_by_id($r->clientid);
		if($user->num_rows() == 0) {
			$this->debug .="This invoice has an invalid client assigned: " . $r->ID . "<br />";
			return null;
		}
		$user = $user->row();

		// Get invoice template
		$invoice = $this->invoices_model->get_invoice($r->templateid);
		if($invoice->num_rows() == 0) {
			$this->debug .= "This invoice doesn't have a valid template: " . $r->ID . "<br />";
			return null;
		}
		$invoice = $invoice->row();

		// Get invoice items
		$items = $this->invoices_model->get_invoice_items($r->templateid);
		$sub_total=0;
		foreach($items->result() as $item) {
			$quantity = $item->quantity;
			$amount = $item->amount;
			
			$sub_total += $amount*$quantity;
		}
		$total = $sub_total;

		// Tax
		$tax_name_1 = $invoice->tax_name_1;
		$tax_rate_1 = $invoice->tax_rate_1;
		$tax_name_2 = $invoice->tax_name_2;
		$tax_rate_2 = $invoice->tax_rate_2;

		if($tax_rate_1>0) {
			$extra = floatval($sub_total/100*$tax_rate_1);
			$total = $total + $extra;
		}
		if($tax_rate_2>0) {
			$extra = floatval($sub_total/100*$tax_rate_2);
			$total = $total + $extra;
		}

		// Invoice hash
		$hash = sha1(rand(1,100000) . $invoice->title . time());

		// Invoice ID
		$invoice_tmp_id = $invoice->invoice_id;
		// Get last 4 digits
		if (preg_match('#(\d+)$#', $invoice_tmp_id, $matches)) {
			$num = intval($matches[1]);
			$pad = strlen($matches[1]);
			$num++;
			$num = str_pad($num, $pad, '0', STR_PAD_LEFT);
			$invoice_tmp_id = 
				substr($invoice_tmp_id, 0, strlen($invoice_tmp_id)-$pad);
			$invoice_tmp_id = $invoice_tmp_id . $num;
		} else {
			$invoice_tmp_id = $invoice_tmp_id . "_0001";
		}

		// Process
		$invoiceid = $this->invoices_model->add_invoice(array(
			"invoice_id" => $invoice_tmp_id,
			"title" => $invoice->title,
			"notes" => $invoice->notes,
			"userid" => $r->userid, // Person who made the occuring invoice
			"status" => $invoice->status,
			"clientid" => $user->ID,
			"projectid" => $invoice->projectid,
			"currencyid" => $invoice->currencyid,
			"timestamp" => time(),
			"due_date" => time(),
			"tax_name_1" => $invoice->tax_name_1,
			"tax_rate_1" => $invoice->tax_rate_1,
			"tax_name_2" => $invoice->tax_name_2,
			"tax_rate_2" => $invoice->tax_rate_2,
			"total" => $total,
			"hash" => $hash,
			"time_date" => date("Y-m-d"),
			"paying_accountid" => $invoice->paying_accountid,
			"guest_name" => $invoice->guest_name,
			"guest_email" => $invoice->guest_email,
			)
		);

		// Add invoice items
		foreach($items->result() as $item) {
			$quantity = $item->quantity;
			$amount = $item->amount;
			$name = $item->name;
			
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $name,
				"quantity" => $quantity,
				"amount" => $amount
				)
			);
			
		}

		// Send notification
		$this->user_model->increment_field($user->ID, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $user->ID,
			"url" => "invoices/view/" . $invoiceid . "/" . $hash,
			"timestamp" => time(),
			"message" => lang("ctn_1019"),
			"status" => 0,
			"fromid" => $r->userid,
			"email" => $user->email,
			"username" => $user->username,
			"email_notification" => $user->email_notification
			)
		);

		// Calculate next occurence
		$current_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y") . " 00:00:00");
		$amount = $r->amount;
		$amount_time = $r->amount_time;
		$day = 3600*24;
		$week = ((3600*24) *7);
		$month = ((3600*24) * 30);
		$year = ((3600*24) * 365);
		if($amount_time == 0) {
			// Days 
			$next = $current_date->getTimestamp() + ( $day * $amount );
		} elseif($amount_time == 1) {
			// Weeks
			$next = $current_date->getTimestamp() + ( $week * $amount );
		} elseif($amount_time == 2) {
			// Months
			$next = $current_date->getTimestamp() + ( $month * $amount);
		} elseif($amount_time == 3) {
			// Year
			$next = $current_date->getTimestamp() + ( $year * $amount);
		}

		if($r->end_date > 0) {
			// Check to make sure end date isn't exceeded
			$end_date = DateTime::createFromFormat("m/d/Y h:i:s", date("m/d/Y", $r->end_date) . " 00:00:00");

			if($end_date->getTimestamp() < $next) {
				$next = 0;
			}
		}

		// Update Reoccur Invoice
		$this->invoices_model->update_reoccuring_invoice($r->ID, array(
			"last_occurence" => time(),
			"next_occurence" => $next
			)
		);

		// Update invoice id so we know to increment it next time
		$this->invoices_model->update_invoice($r->templateid, array(
			"invoice_id" => $invoice_tmp_id
			)
		);

		$this->debug .= "Invoice was created";
	}

	public function ticket_replies() 
	{
		$enable_debug = 0;
		$debug = "";
		$this->load->model("tickets_model");
		include(APPPATH . "/libraries/IMap.php");

		$imapPath = $this->settings->info->protocol_path;
		$username = $this->settings->info->protocol_email;
		$password = $this->settings->info->protocol_password;

		if($this->settings->info->protocol ==1) {
			$protocol = "imap";
		} elseif($this->settings->info->protocol == 2) {
			$protocol = "pop3";
		}
		if($this->settings->info->protocol_ssl) {
			$ssl = "/ssl";
		} else {
			$ssl = "";
		}

		$host = $this->settings->info->protocol_path . "/" . $protocol . $ssl;

		$imap = new IMap("{" .$host. "}INBOX", $username, $password);
		$emails = $imap->search(array(
			"subject" => $this->settings->info->ticket_title . " [ID:",
			"unseen" => 1
			)
		);

		if($emails) {
			$debug .="Count: " . count($emails);
			foreach($emails as $mail) {
				$header = $imap->get_header_info($mail);
				$message = $imap->getmsg($mail);
				if(isset($message['htmlmsg']) && !empty($message['htmlmsg'])) {
					$body = $message['htmlmsg'];
				} elseif(isset($message['plainmsg']) && !empty($message['plainmsg'])) {
					$body = $message['plainmsg'];
				} else {
					$body = "";
				}

				$header->subject = mb_decode_mimeheader($header->subject);

				// Now we need to extract ticket id.
				$pos = strpos($body, $this->settings->info->imap_ticket_string);
				if($pos === false) {
					// New ticket creation
					$debug .="Unable to find ticket id.";

					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				} else {
					$ticketid = $this->get_ticket_id($body);
				}


				// Strip old text from body
				$body = strstr($body, $this->settings->info->imap_reply_string, true);

				// GMAIL SUPPORT
				$body = $imap->extract_gmail_message($body);
				$body = $imap->extract_outlook_message($body);


				$body = strip_tags($body, "<br><p>");

				// Look up a ticket in our system
				$ticket = $this->tickets_model->get_ticket($ticketid);
				if($ticket->num_rows() == 0) {
					$debug .="NO Ticket";
					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				}
				$ticket = $ticket->row();
				if(isset($ticket->client_email) && !empty($ticket->client_email)) {
					$email = $ticket->client_email;
				} else {
					$email = "";
				}

				if(strcasecmp($email, $header->from) == 0) {
					// Match
					// Post ticket reply
					// Add
					$replyid = $this->tickets_model->add_ticket_reply(array(
						"ticketid" => $ticketid,
						"userid" => $ticket->userid,
						"body" => $body,
						"timestamp" => time(),
						)
					);

					// Update last reply
					$this->tickets_model->update_ticket($ticket->ID, array(
						"last_reply_userid" => $ticket->userid,
						"last_reply_timestamp" => time()
						)
					);
					$debug .="Message added";
					$imap->mark_as_read($mail);

					// Notification
					// Alert assigned user of new reply
					$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $ticket->assignedid,
						"url" => "tickets/view/" . $ticket->ID,
						"timestamp" => time(),
						"message" => lang("ctn_1020"),
						"status" => 0,
						"fromid" => $ticket->userid,
						"username" => $ticket->assigned_username,
						"email" => $ticket->assigned_email,
						"email_notification" => $ticket->assigned_email_notification
						)
					);
				} else {
					$debug .="From email does not match ticket db.";
					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				}
			}
		}

		if($enable_debug) {
			echo "DEBUG OUTPUT: <br />";
			echo $debug;
		}

		exit();
	}

	private function get_ticket_id($body) 
	{
		$ticket = trim(strstr($body, $this->settings->info->imap_ticket_string));
		$ticketid = substr($ticket, 
			strlen($this->settings->info->imap_ticket_string),
			strlen($ticket)
		);
		$ticketid = intval(strstr(trim($ticketid), " ", true));
		return $ticketid;
	}

	public function reminder() 
	{
		// Get all tasks
		$this->load->model("task_model");

		$users = array();

		// Get all uncompleted tasks
		$tasks = $this->task_model->get_all_uncompleted_tasks();
		foreach($tasks->result() as $r) {
			// Get due date
			$due_date = $r->due_date;

			// Check due date is within 5 days
			if(time() + (24*3600*5) > $due_date && $r->status != 3 && $r->status != 5) {
				// Add as an alert
				
				// Get all users belonging to task
				$task_members = $this->task_model->get_task_members($r->ID);
				foreach($task_members->result() as $u) {
					if(isset($users[$u->userid])) {
						if(isset($users[$u->userid]['high_tasks'])) {
							$users[$u->userid]['high_tasks'][] = $r;
						} else {
							$users[$u->userid]['high_tasks'] = array($r);
						}
					} else {
						$users[$u->userid] = array(
							"high_tasks" => array($r)
						);
					}
				}
			}
		}

		$title = $this->settings->info->site_name . " " . lang("ctn_1398");

		// Loop through all users
		foreach($users as $k => $v) {
			$body = "";
			$userid = $k;
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() > 0) {
				$user = $user->row();
				//echo "User: " . $user->username . " " . $user->email . "<br /><br />";
				$body .= lang("ctn_1088") . " " . $user->username . ",<br /><br />";
				$body .= lang("ctn_1399") . "<br /><br />";


				// Show tasks
				foreach($v['high_tasks'] as $task) {
					//echo"Task: " . $task->name . "<br />";
					$body .= '<a href="'.site_url("tasks/view/" . $task->ID).'">' . $task->name . '</a> - '.lang("ctn_547").': ' . date($this->settings->info->date_format, $task->due_date) . "<br /><br />";
				}

				$body .= lang("ctn_1400") . " <a href='".site_url()."'>".$this->settings->info->site_name."</a>.";

				// Send email
				$this->common->send_email($title, $body, $user->email);
			}
			//echo "<hr>";
		}

		exit();
		
	}

	public function reminder_calendar() 
	{
		// Get all events
		$this->load->model("calendar_model");
		$this->load->model("team_model");

		$start = time();
		$end = time() + (24 * 3600 * 7);


		$startdt = new DateTime('now'); // setup a local datetime
		$startdt->setTimestamp($start); // Set the date based on timestamp
		$format = $startdt->format('Y-m-d H:i:s');

		$enddt = new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$format2 = $enddt->format('Y-m-d H:i:s');

		$events = $this->calendar_model->get_all_events($format, $format2);


		$users = array();
		foreach($events->result() as $r) {
			if($r->projectid > 0) {

				// Get all project members
				$members = $this->team_model->get_members_for_project_roles($r->projectid, 
					array(
						"admin",
						"calendar"
					)
				);
				foreach($members->result() as $user) {
					if(isset($users[$user->userid])) {
						if(isset($users[$user->userid]['events'])) {
							$users[$user->userid]['events'][] = $r;
						} else {
							$users[$user->userid]['events'] = array($r);
						}
					} else {
						$users[$user->userid] = array(
							"events" => array($r)
						);
					}
				}
			} else {
				// All Members with Admin/Project/Calendar Manager/Calendar Worker
				$members = $this->user_model->get_users_with_permissions(array(
					"admin",
					"admin_members",
					"admin_payment",
					"admin_settings",
					"calendar_manage",
					"calendar_worker",
					"project_admin"
					)
				);


				foreach($members->result() as $user) {
					if(isset($users[$user->userid])) {
						if(isset($users[$user->userid]['events'])) {
							$users[$user->userid]['events'][] = $r;
						} else {
							$users[$user->userid]['events'] = array($r);
						}
					} else {
						$users[$user->userid] = array(
							"events" => array($r)
						);
					}
				}

			}
		}

		$title = $this->settings->info->site_name . " " . lang("ctn_1401");

		// Loop through all users
		foreach($users as $k => $v) {
			$body = "";
			$userid = $k;
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() > 0) {
				$user = $user->row();
				//echo "User: " . $user->username . " " . $user->email . "<br /><br />";
				$body .= lang("ctn_1088") . " " . $user->username . ",<br /><br />";
				$body .= lang("ctn_1402") . "<br /><br />";


				// Show Events
				foreach($v['events'] as $event) {
					try{
						$ed = DateTime::createFromFormat('Y-m-d H:i:s', $event->start);
						$timestamp = $ed->getTimestamp();
					} catch (Exception $e) {
						$timestamp = time();
					} 
					//echo"Event: " . $event->title . "<br />";
					$body .= '<a href="'.site_url("calendar") .'">' . $event->title . '</a> - '.lang("ctn_1403").': ' . date($this->settings->info->date_format, $timestamp) . "<br /><br />";
				}

				$body .= lang("ctn_1400") . " <a href='".site_url()."'>".$this->settings->info->site_name."</a>.";

				// Send email
				$this->common->send_email($title, $body, $user->email);
			}
			//echo "<hr>";
		}


	}

	public function reminder_tickets() 
	{
		$this->load->model("tickets_model");
		// Get all tickets which are waiting for a response/open.
		$tickets = $this->tickets_model->get_open_tickets();
		echo "Tickets: " . $tickets->num_rows();
		$users = array();
		foreach($tickets->result() as $r) {
			// get assigned user
			if($r->assignedid > 0) {
				if(isset($users[$r->assignedid])) {
					if(isset($users[$r->assignedid]['tickets'])) {
						$users[$r->assignedid]['tickets'][] = $r;
					} else {
						$users[$r->assignedid]['tickets'] = array($r);
					}
				} else {
					$users[$r->assignedid] = array(
						"tickets" => array($r)
					);
				}
			}
		}


		$title = $this->settings->info->site_name . " " . lang("ctn_1405");

		// Loop through all users
		foreach($users as $k => $v) {
			$body = "";
			$userid = $k;
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() > 0) {
				$user = $user->row();
				//echo "User: " . $user->username . " " . $user->email . "<br /><br />";
				$body .= lang("ctn_1088") . " " . $user->username . ",<br /><br />";
				$body .= lang("ctn_1404") . "<br /><br />";


				// Show Events
				foreach($v['tickets'] as $ticket) {
					
					//echo"Ticket: " . $ticket->title . "<br />";
					$body .= '<a href="'.site_url("tickets/view/" . $ticket->ID) .'">' . $ticket->title . '</a><br /><br />';
				}

				$body .= lang("ctn_1400") . " <a href='".site_url()."'>".$this->settings->info->site_name."</a>.";

				// Send email
				$this->common->send_email($title, $body, $user->email);
			}
			//echo "<hr>";
		}


	}

}