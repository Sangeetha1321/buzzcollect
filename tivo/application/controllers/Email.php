<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {

	function sendEmail()
	 { $this->load->library('email');
	   $this->email->from('k.ilavazhuthi@spi-global.com'); //change it
	   $this->email->to('v.sangeetha@spi-global.com'); //change it
	   $subject = 'Test subject';
       $body = 'Hi there, <strong>Carl</strong> here.<br/> This is our email body.';
	   $this->email->subject($subject);
	   $this->email->message($body);
	   if ($this->email->send())
	   {
		  $data['success'] = 'Yes';
	   }
	   else
	   {
		  $data['success'] = 'No';
		  $data['error'] = $this->email->print_debugger(array(
			 'headers'
		  ));
	   }

	   echo "<pre>";
	   print_r($data);
	   echo "</pre>";
	}
        /* function __construct(){
            parent::__construct();
            $this->load->library('PHPMailer');
        }
        function send_email() {
            $response = false;
            $mail = new PHPMailer();
            $subject = 'Test subject';
            $body = 'Hi there, <strong>Carl</strong> here.<br/> This is our email body.';
            $email = 'v.sangeetha@spi-global.com';


            $mail->CharSet = 'UTF-8';
            $mail->SetFrom('k.ilavazhuthi@spi-global.com','Ilavazhuthi K');

            //You could either add recepient name or just the email address.
            $mail->AddAddress($email,"Sangeetha V");
            $mail->AddAddress($email);

            //Address to which recipient will reply
            $mail->addReplyTo("reply@yourdomain.com","Reply");
            $mail->addCC("cc@example.com");
            $mail->addBCC("bcc@example.com");

            //Add a file attachment
            $mail->addAttachment("file.txt", "File.txt");        
            $mail->addAttachment("images/profile.png"); //Filename is optional

            //You could send the body as an HTML or a plain text
            $mail->IsHTML(true);

            $mail->Subject = $subject;
            $mail->Body = $body;

            //Send email via SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth   = true; 
            $mail->SMTPSecure = "ssl";  //tls
            $mail->Host       = "mailpdy.spi-global.com";
            $mail->Port       = 25; //you could use port 25, 587, 465 for googlemail
            $mail->Username   = "k.ilavazhuthi@spi-global.com";
            $mail->Password   = "****";

            if(!$mail->send()){
                $response['message'] = 'Oops! Something went wrong while trying to send your email.';
            }
            else{
                $response['message'] = 'Email has been sent successfully!';
            }
            echo json_encode($response);
        } */
    }