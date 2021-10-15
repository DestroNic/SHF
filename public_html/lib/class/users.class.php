<?php error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
?>
<?
	class User {

        var $uID = 0;
        var $un;
		var $pw = '9999999999999999';
		// only when saving
        var $name;
        var $email;
        var $f_name;
		var $l_name;
		var $business_name;
		var $ph;
		var $fa;
		var $add1;
		var $add2;
		var $city;
		var $state;
		var $zip;
		var $is_admin = 'n';
		var $is_newsletter = 'n';
		var $is_lead = 'y';
		var $unique_id;
		var $date_added;
		var $date_modified;
		var $is_verified = 'n';
		
		var $is_valid = 'n';



		function User($var="", $type="") {
			if (is_numeric($var) && ($var) && $type=="") {
				require_once(CONNECTION);
				$sql = "SELECT * FROM users WHERE uID = $var";
                $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
                
			} else if ($type == 'contact_form') {
				
				$email = $var['email_address'];
				if($this->passes_in_tests($var['last_name']) && $this->passes_in_tests($var['first_name']) && $this->is_valid_email($email) && $this->passes_in_tests($email)) {
					$this->email = $email;
					$this->check_email();
					$this->f_name = $var['first_name'];
					$this->l_name = $var['last_name'];
					$this->business_name = $var['business_name'];
					$this->add1 = $var['street_address'];
					$this->add2 = $var['volunteer'];
					$this->city = $var['city'];
					$this->state = $var['state'];
					$this->zip = $var['zip'];
					
					$this->ph = $var['phone_number'];
					$this->is_newsletter = $var['subscribe_news'];;
					$this->is_lead = 'y';
					$this->is_valid = 'y';
				} 
				
			} else if ($type == 'registration_form') {
					$this->uID =  $var['which_user'];
					 $this->un =  $var['email_address'];
					 $this->name =  $var['name'];
					 $this->email =  $var['email_address'];
					 //$this->check_email();
					$this->generate_uid();
					$this->is_newsletter = 'y';
					$this->is_verified = 'y';
					$this->is_valid = 'y';
					if($var['password1'] != '')
						$this->pw = crypt($var['password1']);
			} else if($type == 'complete_registration') {
					$e = clean_input($var['email_address']);
					$vv = clean_input($var['confirm']);
					require_once(CONNECTION);
					$sql = "SELECT * FROM users WHERE  email='$e' and unique_id='$vv'";
					$result = @$mysqli->query($sql);
					$count=@$result->num_rows;
					if($count == 0) {
						$this->un = 'invalid';
					} else {
						$row = @$result->fetch_array();
						$this->makeFromRow($row);
						$this->is_verified = 'y';
						$this->Save();
					}
			}
            

		}

		function makeFromRow($row="") {
                $this->uID =                     isset($row["uID"])    ?    $row["uID"]	    :    ($this->uID  ?  $this->uID : 0);
                $this->un =                 isset($row["un"])    ?    $row["un"]	    :    ($this->un  ?  $this->un : '');
                $this->name =                   isset($row["name"])    ?    $row["name"]	    :    ($this->name  ?  $this->name : '');
                $this->email =                  isset($row["email"])    ?    $row["email"]	    :    ($this->email  ?  $this->email : '');
				$this->f_name =                  isset($row["FirstName"])    ?    $row["FirstName"]	    :    ($this->FirstName  ?  $this->FirstName : '');
				$this->l_name =                  isset($row["LastName"])    ?    $row["LastName"]	    :    ($this->LastName  ?  $this->LastName : '');
				
				$this->business_name =                  isset($row["business_name"])    ?    $row["business_name"]	    :    ($this->business_name  ?  $this->business_name : '');
				$this->ph =                  isset($row["phone"])    ?    $row["phone"]	    :    ($this->phone  ?  $this->phone : '');
				$this->fa =                  isset($row["fax"])    ?    $row["fax"]	    :    ($this->fax  ?  $this->fax : '');
				$this->add1 =                  isset($row["address1"])    ?    $row["address1"]	    :    ($this->address1  ?  $this->address1 : '');
				$this->add2 =                  isset($row["address2"])    ?    $row["address2"]	    :    ($this->address2  ?  $this->address2 : '');
				$this->city =                  isset($row["city"])    ?    $row["city"]	    :    ($this->city  ?  $this->city : '');
				$this->state =                  isset($row["state"])    ?    $row["state"]	    :    ($this->state  ?  $this->state : '');
				$this->zip =                  isset($row["zip"])    ?    $row["zip"]	    :    ($this->zip  ?  $this->zip : '');
				$this->is_admin =                  isset($row["email"])    ?    $row["email"]	    :    ($this->email  ?  $this->email : 'n');
				$this->is_newsletter =                  isset($row["is_newsletter"])    ?    $row["is_newsletter"]	    :    ($this->is_newsletter  ?  $this->is_newsletter : 'n');
				$this->is_lead =                  isset($row["is_lead"])    ?    $row["is_lead"]	    :    ($this->is_lead  ?  $this->is_lead : 'y');
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                     if(is_string($value)) {
                        $this->$key = $this->clean_input($value);
                    }
                }
			$d_a = date('Y-m-d');
			if ($this->uID) {

				$sql = "UPDATE users SET"
					. " un = '$this->un',"
                    . " name = '$this->name',"
                    . " email = '$this->email', FirstName = '$this->f_name', LastName = '$this->l_name', phone = '$this->ph', fax = '$this->fa', address1 = '$this->add1', address2 = '$this->add2',"
					. " city = '$this->city', state = '$this->state', zip = '$this->zip', is_newsletter = '$this->is_newsletter', is_lead = '$this->is_lead', unique_id = '$this->unique_id', date_modified = '$da', is_verified='$this->is_verified'"
                    . " WHERE uID         = $this->uID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {
				$sql = "INSERT INTO users"
					        . " (uID,"            
                            . " un,"         ;
				if($this->pw != '9999999999999999') 
					$sql .= " pw,";
					
				$sql .= " name,"          
                            . " email, FirstName, LastName, phone, fax, address1, address2, city, state, zip, is_newsletter, is_lead,unique_id,date_added)"  
					. " VALUES"
                        . " ($this->uID,"            
                        . " '$this->un',";
				if($this->pw != '9999999999999999') 
					$sql .= " '$this->pw',";
				
				$sql .= " '$this->name',"          
                        . " '$this->email', '$this->f_name', '$this->l_name', '$this->ph', '$this->fa', '$this->add1', '$this->add2', '$this->city', '$this->state', '$this->zip', '$this->is_newsletter', '$this->is_lead', '$this->unique_id', '$d_a')";


				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->uID = $mysqli->insert_id;

			}
		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM users WHERE uID = $this->uID";
			$result = @$mysqli->query($sql);
		}
        
        function getField($field) {
            return $this->$field;
        }
        
        function setField($field, $value) {
            $this->$field = $value;
        }
        
       
        function update_from_post($post) {
            foreach($post as $key => $value) {
                if(array_key_exists($key, $this)) {
                    $this->setField($key, $this->clean_input($value));
                }
            }
        }
		
		function generate_uid() {
			$pre = date('md');
			$pre = 'jbnj_' . $pre;
			$this->unique_id = uniqid($pre, true);
		}
		
		function check_email() {
			require_once(CONNECTION);
			$sql = "SELECT uID FROM users WHERE email = '$this->email'";
			$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count > 0) {
				$row = @$result->fetch_array();
				$this->uID = $row['uID'];
			}
		}
		
		function check_email_better() {
			require_once(CONNECTION);
			$sql = "SELECT uID FROM users WHERE email = '$this->email' && is_newsletter = 'y'";
			$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count > 0) {
				return false;
			}
			return true;
		}
				
        
        function clean_input($input) {
            $input=strip_tags($input);
            //$input=str_replace("#","%23",$input);
            //$input=str_replace("'","`",$input);
            $input=trim($input);
             if (ini_get('magic_quotes_gpc')) { 
                $input = stripslashes($input); 
            } 
            $input=$mysqli->real_escape_string($input);
            return $input; 
        }
		
		function is_valid() {
			return ($this->is_valid == 'n') ? false : true;
		}
		
		function get_name() {
			return $this->name();
		}
		
		function validate($cc) {
			if($cc == $this->unique_id && $this->un != 'invalid') {
				$this->is_verified = 'y';
				$this->Save();
				return false;
			}
			
			return true;
		}
		
		function is_valid_registration($confirm) {
			require_once(CONNECTION);
			$sql = "SELECT * FROM users WHERE unique_id = '$confirm'";
			$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count < 1)
				return false;
				
			$row = @$result->fetch_array();
			$this->makeFromRow($row);
			return true;
		}
            
        function get_list() {
                $rere = array();
            	$sql = "SELECT * FROM users WHERE is_admin='n'";
                $result = @$mysqli->query($sql);
                $count=@$result->num_rows;
                if($count < 1)
                    return false;
                    
                while($row = @$result->fetch_array()) {
                    $rere[$row['uID']] = $row['name'];
                }
                
                return $rere;
        }
		
		function print_list() {
			$l = $this->get_list();
			echo '<p>';
			if($l) {
				foreach($l as $i => $user) {
					echo '• <a href="' . ADMIN_URL . '/index.php?m=users&which_user=' . $i . '&action=edit">' . $user . '</a><br/>';
				}
			} else {
				echo "<i>no users yet!</i>";
			}
			echo '</p>
				<p><a href="' . ADMIN_URL . '/index.php?m=users&action=new"><b>Add New User!</b></a></p>';
		}
				
		
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
		
		function send_message($msg) {
			if($msg == '')
				$msg = "No Comments";
			if(!$this->passes_in_tests($msg))
				return false;
            else if ( stristr($this->f_name, '888') || stristr($this->email, 'hacker'))
                return false;
			else {
				$f = 'A contact from the internet!' . PHP_EOL . PHP_EOL;
				$f .= 'Name: ' . $this->f_name . ' ' . $this->l_name . PHP_EOL;
				$f .= 'Business Name: ' . $this->business_name  . PHP_EOL;
				$f .= 'Email: ' . $this->email  . PHP_EOL;
				$f .= 'Phone: ' . $this->ph . PHP_EOL;
				$f .= 'Address: '  . PHP_EOL
					. $this->add1  . PHP_EOL
					. $this->city . ', ' . $this->state . ' ' . $this->zip . PHP_EOL . PHP_EOL;
				$f .= 'Volunteer?: ' . $this->add2 . PHP_EOL;
				// $f .= 'Volunteer? ' . ($this->add2 == 'yes') ? 'Yes' : 'No' . PHP_EOL;
				$f .= 'Subscribe?: ' . $this->is_newsletter . PHP_EOL;
				$f .= 'Comments:' . PHP_EOL;
				$f .= $msg . PHP_EOL . PHP_EOL;
				$f .= '---------------------------------------------------------------' . PHP_EOL . PHP_EOL;
				$f .= 'end of message';
				
				$msg = $f;
				
				return $this->send_mail(EMAIL_ADDRESS, "Website Contact Form", "website@settlementhousingfund.org", "SHF - Contact Form", $msg);
			}
			
		}
		
		function send_confirmation() {
			// send registration email with some crazy gobbldy.
			$msg = $this->f_name . ', '. PHP_EOL;
			$msg .= 'You are receiving this message because you have signed up at JBNJ.net'. PHP_EOL;
			$msg .= '----------------------------------------------------------------------------------'. PHP_EOL;
			$msg .= 'Please use the following link in order to complete the registration process:'. PHP_EOL;
			$msg .= URL . '/register.html?complete=' . $this->unique_id . '&date=' . date('Y-m-d') . PHP_EOL. PHP_EOL;
			$msg .= 'If you believe you have received this email in error, please disregard or email info@jbnj.net for more information';
			
			return $this->send_mail($this->email,"JBNJ, Management","info@jbnj.net", "JBNJ.net Registration - One More Step",$msg);
		}
		
		
		function send_mail($recipients, $sender_name, $sender_email, $email_subject, $email_msg) {

			$send_to = trim($recipients);

			$mime_boundary = md5(time()); 

			$headers = '';
			$msg = '';


			$headers .= 'From: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
			$headers .= 'Reply-To: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
			$headers .= 'Return-Path: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
			$headers .= "Message-ID: <" . time() . "" . $_SERVER['SERVER_NAME'] . ">" . PHP_EOL;
			$headers .= 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"] . PHP_EOL;
			$headers .= "X-Mailer: PHP v" . phpversion() . PHP_EOL;

			$headers .= 'MIME-Version: 1.0' . PHP_EOL;
		//	$headers .= 'Content-Type: multipart/related; boundary="' . $mime_boundary . '"';
			$headers .= 'Content-Type: multipart/mixed; boundary="' . $mime_boundary . '"';

			$msg .= '--' . $mime_boundary . PHP_EOL;
			$msg .= 'Content-Type: text/plain; charset="iso-8859-1"' . PHP_EOL;
			$msg .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

			$msg .= $email_msg . PHP_EOL . PHP_EOL;


			$msg .= '--' . $mime_boundary . '--' . PHP_EOL . PHP_EOL;

			@ini_set('sendmail_from', $sender_email);
			$send_status = mail($send_to, $email_subject, $msg, $headers);
			@ini_restore('sendmail_from');

			return $send_status;
		}
		
		 function print_form() {
                // indeed.
                require_once(FORM_MAKER);
				
                echo '<div id="form_box">';
				if($this->uID != 0)
					echo '<h2>Edit User: <i>' . $this->name . '</i></h2>';
				else
					echo '<h2>Add New User</h2>';
					
                
                echo '<form name="page_form" action="' . ADMIN_URL . '/index.php" method="post">';
                
                input_hidden_print('m', 'users');
                input_hidden_print('user_save','yes');
                input_hidden_print('which_user', $this->uID);
                input_hidden_print('uID', $this->uID);
				echo '
					<div class="fform">';
				input_text_print('Name','name',$this->name,'required'); 
				echo '</div>
					<div class="fform">';
				input_text_print('Email Address', 'email_address',$this->un,'required');
				echo '</div>
					<div class="fform">';
				input_password_print('Change Password', 'password1','','required');
				echo '<br/><span class="sub_input">Only enter new password to change password,<br/> otherwise, leave blank</span>';
				echo '</div>
					<div class="fform">';            
                               
               
				
                submit_print('submit_page', 'Save User', 'nice_pos');                
                
                echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=users">back to list</a></span>';
				echo '</div>';
                echo '</form>';
                echo '</div>';
        }
		
		function print_details() {
			echo '<h2>' . $this->name . '</h2>
				<p><i>Username/Email Address: </i> <b>' . $this->un . '</b><br/>
				<br/>
				<a href="' . ADMIN_URL . '/index.php?m=users&which=' . $this->uID . '&action=edit">edit user</a> | <a href="' . ADMIN_URL . '/index.php?m=users&which_user=' . $this->uID . '&action=remove" class="remover">delete</a><br/>
				
			</p>';
		}
				
			
		
        
      
                
      

	}

?>
