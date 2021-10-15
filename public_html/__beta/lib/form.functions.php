<?php
// form functions for adding users and sending emails

function is_valid_email($email) {
			/* Credits: http://www.ilovejackdaniels.com/php/email-address-validation/ */

	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {    
		return false;  
	}  
	$email_array = explode("@", $email);  
	$local_array = explode(".", $email_array[0]);  
	for ($i = 0; $i < sizeof($local_array); $i++) {     
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {      
			return false;    
		}  
	}    
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { 
		$domain_array = explode(".", $email_array[1]);    
		if (sizeof($domain_array) < 2) {        
			return false; // Not enough parts to domain    
		}    
		for ($i = 0; $i < sizeof($domain_array); $i++) {      
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {       
				return false;      
			}    
		}  
	} 
	return true;
} 

function has_injection_chars($s) {
	return (eregi("\r", $s) || eregi("\n", $s) || eregi("%0a", $s) || eregi("%0d", $s)) ? true : false;
}

function has_other_injection_test($str) { 
	$tests = array("/bcc\:/i", "/Content\-Type\:/i", "/Mime\-Version\:/i", "/cc\:/i", "/from\:/i", "/to\:/i", "/Content\-Transfer\-Encoding\:/i"); 
	if($str == preg_replace($tests, "", $str))
		return false;
	
	return true;
} 

function passes_in_tests($in) {
	$pass = true;

	if(eregi("\r", $in) || eregi("\n", $in) || eregi("%0a", $in) || eregi("%0d", $in)) {
		$pass = false;
	}
	/*
	$old_in = $in;
	$tests = array("/bcc\:/i", "/Content\-Type\:/i", "/Mime\-Version\:/i", "/cc\:/i", "/from\:/i", "/to\:/i", "/Content\-Transfer\-Encoding\:/i"); 
	if($old_in != preg_replace($tests, "", $in)) {
		$pass = false;
	}
	*/
	if($in == '')
		$pass = false;
		
	return $pass;
}

function validate_post($ps) {
	$errs = array();
	foreach($ps as $v => $p) {
		if($v != 'address2' && $v != 'fax_number') {
			if(!passes_in_tests($p)) {
				$errs[] = $v;
			} else if(stristr($p,'email') && !is_valid_email($p)) {
				$errs[] = $v;
			}
		}
	}
	
	return $errs;
}

        
function clean_input($input) {
	$input=strip_tags($input);
	//$input=str_replace("#","%23",$input);
	//$input=str_replace("'","`",$input);
	$input=trim($input);
	 if (ini_get('magic_quotes_gpc')) { 
		$input = stripslashes($input); 
	} 
	//$input=$mysqli->real_escape_string($input);
	return $input; 
}
		
