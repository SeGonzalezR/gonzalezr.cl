<?php
/**
 * Contact Form
 * Version 3
 */

error_reporting(0);
require_once 'config.php';
require_once 'functions.php';

if ($security_question) {
	session_start();
	$captcha = dm3_create_captcha(true);
	echo $captcha['question'];
}