<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['useragent'] = 'CodeIgniter';
$config['protocol'] = 'smtp';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = '172.24.136.153';
//$config['smtp_host'] = 'smtp-mail.outlook.com';
$config['SMTPAuth'] = TRUE;
$config['smtp_user'] = 'v.sangeetha@spi-global.com';
$config['smtp_pass'] = 'Spisan13212830';
$config['SMTPSecure'] = 'tls'; //ssl
$config['smtp_port'] = 587; //25
$config['smtp_timeout'] = 5	;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'UTF-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;

/* $config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n"; */




 
/* $config['useragent'] = 'PHPMailer';
$config['protocol'] = 'smtp';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'mailpdy.spi-global.com';
//$config['smtp_host'] = '172.24.137.139';
$config['SMTPAuth'] = TRUE;
$config['smtp_user'] = 'v.sangeetha@spi-global.com';
$config['smtp_pass'] = 'San13212830';
$config['SMTPSecure'] = 'ssl';
$config['smtp_port'] = 25; //465
$config['smtp_timeout'] = 5	;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'UTF-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200; */

/*

/* $config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE; */
/* 
$config['useragent'] = 'CodeIgniter';
$config['protocol'] = 'smtp';
//$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 465; 
$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;
*/

?>