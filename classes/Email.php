<?php

class Email {

	private $email;

	public function __construct() {
		$this->email = new PHPMailer;

		//Tell PHPMailer to use SMTP
		$this->email->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->email->SMTPDebug = 0;

		//Ask for HTML-friendly debug output
		$this->email->Debugoutput = 'html';

		//Set the hostname of the mail server
		$this->email->Host = 'smtp.gmail.com';

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$this->email->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		$this->email->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		$this->email->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$this->email->Username = "site_admin_email@example.com";

		//Password to use for SMTP authentication
		$this->email->Password = "email_password";

		//Set who the message is to be sent from
		$this->email->setFrom($this->email->Username, 'InferCMS Example Administrator');

	}

	public function general($to, $subject, $body) {
		$this->email->addAddress($to);
		$this->email->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->email->msgHTML($body);

		//Replace the plain text body with one created manually
		$this->email->AltBody = '';

		if (!$this->email->send()) {
			return false; //email failed to send
		} else {
			return true; //successfully sent
		}
	}





}
