<?php
/**
 * Contact Form
 * Version 3.2
 */

error_reporting(0);
session_start();
require_once 'config.php';
require_once 'functions.php';

$success = 0;

try {
	$valid_requests = validate_num_requests($file_count, $file_ips);
} catch(Dm3ContactScriptException $e) {
	echo $e->getMessage();
	exit();
}

if (isset($_POST['email']) && $valid_requests) {
	$first_name = '';
	$last_name = '';
	$email = '';
	$phone = '';
	$subject = '';
	$message = '';
    
	$error = array();
	
	// Validate captcha if needed
	if ($security_question && (!isset($_POST['s_q']) || !dm3_is_valid_capcha($_POST['s_q']))) {
		$error['s_q'] = true;
	}
	
	if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
		$first_name = htmlspecialchars($_POST['first_name']);
	} else {
	    if (in_array('first_name', $required_fields)) {
	      $error['first_name'] = true;
	    }
	}
	
	if (isset($_POST['last_name']) && !empty($_POST['last_name'])) {
		$last_name = htmlspecialchars($_POST['last_name']);
	} else {
	    if (in_array('last_name', $required_fields)) {
	      $error['last_name'] = true;
	    }
	}
	
	if (!empty($_POST['email']) && dm3_is_valid_email($_POST['email'])) {
		$email = dm3_escape_for_email($_POST['email']);
	} else {
	    if (in_array('email', $required_fields)) {
	      $error['email'] = true;
	    }
	}
	
	if (isset($_POST['phone'])) {
		$phone = htmlspecialchars($_POST['phone']);
	} else {
	    if (in_array('phone', $required_fields)) {
	      $error['phone'] = true;
	    }
	}
	
	if (isset($_POST['subject'])) {
		$subject = htmlspecialchars(dm3_escape_for_email($_POST['subject']));
	} else {
	    if (in_array('subject', $required_fields)) {
	      $error['subject'] = true;
	    }
	}
	
	if (isset($_POST['message'])) {
		$message = htmlspecialchars($_POST['message']);
	} else {
	    if (in_array('message', $required_fields)) {
	      $error['message'] = true;
	    }
	}

	if (count($error) == 0) {
		// Prepare the email message
		$msg_body = file_get_contents('message.txt');
		$msg_body = str_replace('[first_name]', $first_name, $msg_body);
		$msg_body = str_replace('[last_name]', $last_name, $msg_body);
		$msg_body = str_replace('[email]', $email, $msg_body);
		$msg_body = str_replace('[phone]', $phone, $msg_body);
		$msg_body = str_replace('[subject]', $subject, $msg_body);
		$msg_body = str_replace('[message]', $message, $msg_body);

		// Setup email header
		include_once('phpmailer/class.phpmailer.php');
		$mail = new PHPMailer();


		$mail->CharSet = "UTF-8";
		$mail->IsSMTP(); 								// telling the class to use SMTP
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->Host       = "smtp.mandrillapp.com"; 	// sets the SMTP server
		$mail->Port       = 587;                    	// set the SMTP port for the GMAIL server
		$mail->Username   = "q-team@querys.cl"; 		// SMTP account username
		$mail->Password   = "W9p_BBrThKyrin284Ie36w";   // SMTP account password

		$mail->SetFrom('segonzalez.riffo@gmail.com', 'Romeros Climatización');
		$mail->AddReplyTo("segonzalez.riffo@gmail.com","Romeros Climatización");
		
		$mail->Subject    = $subject;
		$mail->MsgHTML($msg_body);

		$mail->AddAddress($email, $first_name . " " . $last_name);

		if(!$mail->Send()) {
		  	$success = 0;
		  	if (isset($_SESSION['dm3_contact_form_sent'])) {
				unset($_SESSION['dm3_contact_form_sent']);
			}
		} else {
			$success = 1;
			$_SESSION['dm3_contact_form_sent'] = true;
		}

	} else {
		// Output form errors
		$error_text = '';
		
		foreach ($error as $key => $is_error) {
			if ($is_error && isset($error_messages[$key])) {
        if ($is_ajax) {
          $error_text .= $key;
        } else {
          $error_text .= $before_error . $error_messages[$key] . $after_error;
        }
			}
		}
		
		if ($is_ajax == true) {
			echo $error_text;
    }
	}
}

if ($is_ajax && $success) {
	echo $success;
}