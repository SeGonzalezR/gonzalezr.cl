<?php

$site_email = 'sebastian@ewok.cl'; // The email address that will receive contact messages
$file_count = 'data/requests-count.txt';
$file_ips = 'data/requests-ips.txt';

/* Copy lines below to your own config file in order to update */
$security_question = true; // Set to true to show security question, or set to false otherwise

$error_messages = array(
	's_q' => 'Please answer the security question',
	'first_name' => 'Please enter first name',
	'last_name' => 'Please enter last name',
	'email' => 'Please enter your email',
	'phone' => 'Please enter your phone number',
	'subject' => 'Please enter the subject of your message',
	'message' => 'Please enter your message',
	'many_requests' => 'Too many contact requests at the moment. Please wait approximately 30 seconds. Thank You.'
);

$before_error = ''; // output before each field error
$after_error = ''; // output after each field error

$required_fields = array('first_name', 'last_name', 'email', 'message');

$is_ajax = (isset($_GET['is_ajax']) || isset($_POST['is_ajax'])) ? true : false;