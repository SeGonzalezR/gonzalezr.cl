<html>
	<head>
		<title>PHPMailer - SMTP basic test with authentication</title>
	</head>
<body>
<?php

date_default_timezone_set('America/Toronto');

require_once('class.phpmailer.php');

$mail = new PHPMailer();

$body = "<h1>TEST MAIL from mandrillapp<h1>";

$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 2;                     		// enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  		// enable SMTP authentication
$mail->Host       = "smtp.mandrillapp.com"; 	// sets the SMTP server
$mail->Port       = 587;                    	// set the SMTP port for the GMAIL server
$mail->Username   = "q-team@querys.cl"; 		// SMTP account username
$mail->Password   = "W9p_BBrThKyrin284Ie36w";   // SMTP account password

$mail->SetFrom('contacto@romerosclima.cl', 'First Last');
$mail->AddReplyTo("contacto@romerosclima.cl","First Last");
$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);

$address = "fepezoabarca@gmail.com";
$mail->AddAddress($address, "Sebastian");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
