<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once("PasswordHash.php");

class Common 
{

    public function nohtml($message) 
    {
        $message = trim($message);
        $message = strip_tags($message);
        $message = htmlspecialchars($message, ENT_QUOTES);
        return $message;
    }

	public function encrypt($password) 
    {
        $phpass = new PasswordHash(12, false);
        $hash = $phpass->HashPassword($password);
    	return $hash;
    }

    public function get_user_role($user) 
    {
        if(isset($user->user_role_name)) {
            return $user->user_role_name;
        } else {
            if($user->user_role == -1) {
                return lang("ctn_33");
            } else {
                return lang("ctn_471");
            }
        }
    }

    public function randomPassword() 
    {
    	$letters = array(
            "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q",
            "r","s","t","u","v","w","x","y","z"
        );
    	$pass = "";
    	for($i=0;$i<10;$i++) {
    		shuffle($letters);
    		$letter = $letters[0];
    		if(rand(1,2) == 1) {
	    		$pass .= $letter;
    		} else {
	    		$pass .= strtoupper($letter);
    		}
    		if(rand(1,3)==1) {
    			$pass .= rand(1,9);
    		}
    	}
    	return $pass;
    }

    public function checkAccess($level, $required) 
    {
        $CI =& get_instance();
        if($level < $required) {
            $CI->template->error(
                lang("error_231"). $this->getAccessLevel($required)
                . lang("error_232")
            );
        }
    }

    public function send_email($subject, $body, $emailt, $headers=array(), $debug=0) 
    {
        $CI =& get_instance();
        $CI->load->library('email');

        $CI->email->from($CI->settings->info->site_email, $CI->settings->info->site_name);
        $CI->email->to($emailt);

        $CI->email->subject($subject);
        $CI->email->message($body);

        
        foreach($headers as $key=>$value) {
            $CI->email->set_header($key, $value);
        }

        if($debug) {
            $CI->email->send(false);
            return $CI->email->print_debugger(array('headers','subject','body'));
        } else {
            $CI->email->send();
        }
        
    }

    public function check_mime_type($file) 
    {
        return true;
    }

    public function replace_keywords($array, $message) 
    {
        foreach($array as $k=>$v) {
            $message = str_replace($k, $v, $message);
        }
        return $message;
    }

    public function convert_time($timestamp) 
    {
        $time = $timestamp - time();
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs
        );
    }

    public function convert_time_raw($timestamp) 
    {
        $time = $timestamp;
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs
        );
    }

    public function get_time_string($time) 
    {
        if(isset($time['days']) && 
            ($time['days'] > 1 || $time['days'] == 0)) {
            $days = lang("ctn_294");
        } else {
            $days = lang("ctn_295");
        }
        if(isset($time['hours']) && 
            ($time['hours'] > 1 || $time['hours'] == 0)) {
            $hours = lang("ctn_296");
        } else {
            $hours = lang("ctn_297");
        }
        if(isset($time['mins']) && 
            ($time['mins'] > 1 || $time['mins'] == 0)) {
            $mins = lang("ctn_298");
        } else {
            $mins = lang("ctn_299");
        }
        if(isset($time['secs']) && 
            ($time['secs'] > 1 || $time['secs'] == 0)) {
            $secs = lang("ctn_300");
        } else {
            $secs = lang("ctn_301");
        }

        // Create string
        $timeleft = "";
        if(isset($time['days']) && $time['days'] > 0) {
            $timeleft = $time['days'] . " " . $days;
        }

        if(isset($time['hours']) && $time['hours'] > 0) {
            if(!empty($timeleft)) {
                if(!isset($time['mins'])) {
                    $timeleft .= " ".lang("ctn_302")." " . $time['hours'] . " " 
                    . $hours;
                } else {
                    $timeleft .= ", " . $time['hours'] . " " . $hours;
                }
            } else {
                $timeleft .= $time['hours'] . " " . $hours;
            }
        }

        if(isset($time['mins']) && $time['mins'] > 0) {
            if(!empty($timeleft)) {
                if(!isset($time['secs'])) {
                    $timeleft .= " ".lang("ctn_302")." " . $time['mins'] . " " 
                    . $mins;
                } else {
                    $timeleft .= ", " . $time['mins'] . " " . $mins;
                }
            } else {
                $timeleft .= $time['mins'] . " " . $mins;
            }
        }

        if(isset($time['secs']) && $time['secs'] > 0) {
            if(!empty($timeleft)) {
                $timeleft .= " ".lang("ctn_302")." " . $time['secs'] . " " 
                . $secs;
            } else {
                $timeleft .= $time['secs'] . " " . $secs;
            }
        }

        return $timeleft;
    }

    public function convert_simple_time($time) 
    {
        $o_time = $time;
        $time = time() - $time;
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs,
            "timestamp" => $o_time
        );
    }

    public function get_time_string_simple($time) 
    {
        $CI =& get_instance();
        if(isset($time['days']) && 
            ($time['days'] > 1 || $time['days'] == 0)) {
            $days = lang("ctn_294");
        } else {
            $days = lang("ctn_295");
        }
        if(isset($time['hours']) && 
            ($time['hours'] > 1 || $time['hours'] == 0)) {
            $hours = lang("ctn_296");
        } else {
            $hours = lang("ctn_297");
        }
        if(isset($time['mins']) && 
            ($time['mins'] > 1 || $time['mins'] == 0)) {
            $mins = lang("ctn_298");
        } else {
            $mins = lang("ctn_299");
        }
        if(isset($time['secs']) && 
            ($time['secs'] > 1 || $time['secs'] == 0)) {
            $secs = lang("ctn_300");
        } else {
            $secs = lang("ctn_301");
        }

        if($time['days'] > 7) {
            return date($CI->settings->info->date_format, $time['timestamp']);
        } else {
            if($time['days'] > 0) {
                return $time['days'] . " " . $days . " " . lang("ctn_1515");
            } elseif($time['hours'] > 0) {
                return $time['hours'] . " " . $hours . " " . lang("ctn_1515");
            } elseif($time['mins'] > 0) {
                return $time['mins'] . " " . $mins . " " . lang("ctn_1515");
            } elseif($time['secs'] > 0) {
                return $time['secs'] . " " . $secs . " " . lang("ctn_1515");
            } else {
                return "0 " . lang("ctn_300") . " " . lang("ctn_1515");
            }
        }
    }

    public function has_permissions($required, $user) 
    {
        if(!isset($user->info->user_role_id)) return 0;
        foreach($required as $permission) {
            if(isset($user->info->{$permission}) && $user->info->{$permission}) return 1;
        }
        return 0;
    }

    public function has_team_permissions($required, $team) 
    {
        if(!isset($team)) return 0;
        if(count($required) == 0) return 1;
        foreach($required as $permission) {
            if(isset($team->{$permission}) && $team->{$permission}) return 1;
        }
        return 0;
    }

    public function check_permissions($action_string, $user_roles, $team_roles, 
        $projectid, $error_message = "", $error_function="error", $return=0) {
        $CI =& get_instance();
        $team_member = $CI->team_model
            ->get_member_of_project($CI->user->info->ID, $projectid);
        // If not member of team, check user_role permissions
        if($team_member->num_rows() == 0) {
            if(!$this->has_permissions($user_roles, $CI->user)) 
            {
                if(!empty($error_message)) {
                    if($return) {
                        return $error_message;
                    } else {
                        $CI->template->{$error_function}($error_message);
                    }
                } else {
                    if($projectid == 0) {
                        if($return) {
                            return lang("error_233") . $action_string . lang("error_234");
                        } else {
                            $CI->template->{$error_function}(lang("error_233") . $action_string . lang("error_234"));
                        }
                    } else {
                        if($return) {
                            return lang("error_235") . $action_string . lang("error_236");
                        } else {
                            $CI->template->{$error_function}(lang("error_235") . $action_string . lang("error_236"));
                        }
                    }
                }
            }
        } else {
            // Check permission team role: admin pr 
            // Check permission user_role: admin, project_admin, file_manager)
            $team = $team_member->row();
            if(!$this->has_team_permissions($team_roles, $team)) {
                if(!$this->has_permissions($user_roles, $CI->user)) {
                    if(!empty($error_message)) {
                        if($return) {
                            return $error_message;
                        } else {
                            $CI->template->{$error_function}($error_message);
                        }
                    } else {
                        if($return) {
                            return lang("error_237") . $action_string . ".";
                        } else {
                            $CI->template->{$error_function}(lang("error_237") . $action_string . ".");
                        }
                    }
                }
            }
        }
    }

    public function get_user_display($data) 
    {
        if(empty($data['username'])) return "";
        if(isset($data['online_timestamp']) > 0) {
            if($data['online_timestamp'] > time() - (60*15)) {
                $class = "online-dot-user";
                $title = lang("ctn_1106");
            } else {
                $class = "offline-dot-user";
                $title = lang("ctn_1107");
            }
        } else {
            $class = "online-dot-user";
        }

        $name = "";
        if(isset($data['first_name']) && isset($data['last_name'])) {
            $name = $data['first_name'] . " " . $data['last_name'];
        }
        $CI =& get_instance();
        $html = '<div class="user-box-avatar">
                <div class="'.$class.'" data-toggle="tooltip" data-placement="bottom" title="'.$title.'"></div>
                <a href="'.site_url("profile/" . $data['username']).'"><img src="'. base_url() . $CI->settings->info->upload_path_relative .'/'. $data['avatar'] .'" title="'.$data['username'].'" data-toggle="tooltip" data-placement="right" /></a>
                </div>';
        if($name) {
            $html .='<div class="user-box-name"><p>'.$name.'</p><p class="user-box-username">@<a href="'.site_url("profile/" . $data['username']).'">'.$data['username'].'</a></p></div>';
        }
        return $html;
    }

    /*
      START = The start date
      END = The End Date
      FORMAT = The format that both the start and end date are in. It's also used as the display format.
      FORMAT_DB = The format that the date is stored in the database as.
    */
    public function getDatesFromRange($start, $end, $format = 'Y-m-d', $format_db = "d-n-Y") 
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = DateTime::createFromFormat($format, $end);

        $realEnd->add($interval);

        $period = new DatePeriod(DateTime::createFromFormat($format, $start), $interval, $realEnd);

        foreach($period as $date) { 
            $array[] = array("display" => $date->format($format), "db" => $date->format($format_db), "timestamp" => $date->getTimestamp(), "obj" => $date); 
        }

        return $array;
    }

    public function date_php_to_jquery($date)
    {
        $formats = array(
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'yy',
            'y' => 'y',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => ''
        );
        $str = "";
        for($i=0;$i<strlen($date);$i++) {
            $flag= false;
            foreach($formats as $php=>$jquery) {
                if($date[$i] == $php) {
                    $str .= $jquery;
                    $flag = true;
                }
            }
            if(!$flag) {
                $str .= $date[$i];
            }
        }
        return $str;  
    }

    public function date_php_to_momentjs($date)
    {
        $formats = array(
            'd' => 'DD', // 01-31
            'D' => '',  // mon-sun
            'j' => 'D',  // 1-31
            'l' => '', // Monday-Sunday
            'N' => '',   // day of the week number [1-7]
            'S' => 'o',   // English suffix e.g st, nd, rd
            'w' => '',   // day of the week number [0-6]
            'z' => '',  // day of the year 0-365
            // Week
            'W' => '',   // week number
            // Month
            'F' => 'MMMM', // January - December
            'm' => 'MM', // 01-12
            'M' => 'MMM',  // Jan - Dec
            'n' => 'M',  // 1-12
            't' => '',   // days in a month i.e 28,31
            // Year
            'L' => '',   // Leap year 1 or 0
            'o' => '',   // iso year
            'Y' => 'YYYY', // 1999-2003
            'y' => 'YY',  // 99-03
            // Time
            'a' => 'a',   // am or pm
            'A' => 'A',   // AM or PM
            'B' => '',   // swatch internet time 000-999
            'g' => 'h',   // 1-12 hour format
            'G' => 'H',   // 1-24 hour format
            'h' => 'hh',   // 01-12 hour format
            'H' => 'HH',   // 01-24 hour format
            'i' => 'mm',   // 00-59 minutes
            's' => 'ss',   // 00-59 seconds
            'u' => 'SSS'    // micro seconds
        );
        $str = "";
        for($i=0;$i<strlen($date);$i++) {
            $flag= false;
            foreach($formats as $php=>$jquery) {
                if($date[$i] == $php) {
                    $str .= $jquery;
                    $flag = true;
                }
            }
            if(!$flag) {
                $str .= $date[$i];
            }
        }
        return $str;  
    }

}

?>
