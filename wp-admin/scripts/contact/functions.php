<?php
/**
 * Contact Form
 * Version 3.2
 */

class Dm3ContactScriptException extends Exception {}

/**
 * Validate number of requests per second and
 * number of requests from the same IP per given period of time
 * 
 * @param string $file_path_count Path to the file where to store requests count
 * @param string $file_path_ips Path to the file where to store IP stats
 * @param int $max_requests_second
 * @param int $max_requests_ip
 * @param int $wait_time In seconds
 * 
 * @throws Dm3ContactScriptException
 * 
 * @return boolean
 */
function validate_num_requests($file_path_count, $file_path_ips, $max_requests_second = 10, $max_requests_ip = 12, $wait_time = 30) {
  global $error_messages;

  if (!is_array($error_messages) || !isset($error_messages['many_requests'])) {
    $error_messages['many_requests'] = 'Too many contact requests at the moment. Please wait approximately 30 seconds. Thank You.';
  }
    
	$requests = file_get_contents($file_path_count);
	$requests = explode(',', $requests);
	$time = time();
	$seconds = $time - $requests[1];
	
	if (isset($requests[0])) {
		if ($seconds > 30) {
			//echo 'Refresh requests count';
			if (!@file_put_contents($file_path_count, '0,'.$time)) {
				throw new Dm3ContactScriptException('file_count not writable');
				return false;
			}
		} else if ($seconds == 0 && $requests[0] > $max_requests_second) {
			throw new Dm3ContactScriptException($error_messages['many_requests']);
			return false;
		} else if ($seconds > 0 && $requests[0] / $seconds > $max_requests_second) {
			throw new Dm3ContactScriptException($error_messages['many_requests']);
			return false;
		} else {
			if (!@file_put_contents($file_path_count, ($requests[0] + 1).','.$requests[1])) {
				throw new Dm3ContactScriptException('file_count not writable');
				return false;
			}
		}
	} else {
		if (!@file_put_contents($file_path_count, '0,'.$time)) {
			throw new Dm3ContactScriptException('file_count not writable');
			return false;
		}
	}
	
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if (empty($ip)) {
		return false;
	}
	
	$ips = array();
	$fh = fopen($file_path_ips, 'r');
	
	if ($fh) {
		$ip_found = false;
		
		while (($line = fgets($fh, 4096)) !== false) {
			$parts = explode(',', $line);
			
			if (count($parts) != 3) {
				continue;
			}
			
			// 0 => IP, 1 => count, 2 => timestamp
			if ($parts[0] == $ip) {
				$ip_found = true;
				
				if ($parts[1] >= $max_requests_ip) {
					if ($time - $parts[2] < $wait_time) {
					  throw new Dm3ContactScriptException($error_messages['many_requests']);
						return false;
					} else {
						$parts[1] = 0;
					}
				}
				
				$count = $parts[1] + 1;
				$parts[2] = $time;
				
				// Update record
				$ips[] = "$ip,$count,{$parts[2]}\n";
			} else {
				if ($time - $parts[2] < $wait_time) {
					$ips[] = "$line";
				}
			}
		}
		
		fclose($fh);
		
		if (!$ip_found) {
			// Register new IP in the file
			$ips[] = "$ip,0,$time\n";
		}
		
		$fh = @fopen($file_path_ips, 'w');
		
		if ($fh) {
      // Don't keep more than 3000 entries
      $i = count($ips) - 1; // Last ip index

      if ($i > 2999) {
        $i = 2999;
      }

      for (; $i >= 0; $i--) {
        fwrite($fh, $ips[$i]);
      }

			fclose($fh);
		} else {
			throw new Dm3ContactScriptException('file_ips not writable');
			return false;
		}
	}
	
	return true;
}

/**
 * Create captcha
 * 
 * Generate question and answer to validate the form submit
 * 
 * @param boolean $overwrite Overwrite current captcha in session
 * 
 * @return array(question => string, answer => int);
 */
function dm3_create_captcha($overwrite = false) {
	// If current captcha is not validated yet, just keep it
	if (isset($_SESSION['dm3_contact_form_captcha']) && $overwrite === false) {
		return $_SESSION['dm3_contact_form_captcha'];
  }
	
	$captcha = array();
	
	// Seed random number generator
	list($usec, $sec) = explode(' ', microtime());
	srand((float)$sec + ((float)$usec * 100000));
	
	$x = rand(5,10);
	$y = rand(0,5);
	$sign = rand(0, 1) ? '+' : '-';
	
	switch ($sign) {
		case '+':
			$captcha['question'] = "<span>$x</span><span style='display:none;'>" . rand(0,5) . "</span> + <span style='display:none;'>" . rand(5,10) . "</span>$y =";
			$captcha['answer'] = $x + $y;
			break;
			
		case '-':
			$captcha['question'] = "$x<span style='display:none;'>" . rand(0,5) . "</span> - <span>$y</span><span style='display:none;'>" . rand(5,10) . "</span> =";
			$captcha['answer'] = $x - $y;
			break;
	}
	
	$_SESSION['dm3_contact_form_captcha'] = $captcha;
	
	return $captcha;
}

/**
 * Validate the captcha
 * 
 * @param string $answer
 * 
 * @return boolean true/false
 */
function dm3_is_valid_capcha($answer) {
	if (isset($_SESSION['dm3_contact_form_captcha'])) {
		if ($_SESSION['dm3_contact_form_captcha']['answer'] == $answer) {
			unset($_SESSION['dm3_contact_form_captcha']);
			return true;
		}
	}
	
	return false;
}

/**
 * Strip injection chars from email headers
 *
 * @param string $key
 * @return string
 */
function dm3_escape_for_email($key) {
  return	preg_replace('#(?:\n|\r|\t|%0A|%0D|%08|%09)+#i', '', $key);
}

/**
 * Is string a valid email?
 *
 * @param string $input
 * @return boolean
 */
function dm3_is_valid_email($input) {
  $regex = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i';
  return preg_match($regex, $input);
}