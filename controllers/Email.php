<?php

require_once('class.phpmailer.php');
require_once('class.smtp.php');

/**
* Using PhpMailer, Open source project
* https://github.com/PHPMailer/PHPMailer
*/

class Email{

	public static function send($email, $body, $subject) {

		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "mapthefourth@gmail.com";
		$mail->Password = "incitemap";
		$mail->SetFrom("incite-g@vt.edu", 'Mapping the Fourth');
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->addReplyTo('incite-g@vt.edu', 'Incite Team');

		$mail->AddAddress($email);

		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
			
		} else {
			return true;
		
		}
		  

	}

}

