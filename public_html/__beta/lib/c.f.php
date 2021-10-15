<?php
		
	
	// forms!
	if(isset($_POST['a_form']) && $_POST['a_form'] == 'true') {
		require_once(CLASS_DIR . '/users.class.php');
		if($_POST['form_type'] == 'contact') {
			$u = new User($_POST,'contact_form');
			//check for errors
			
			if(!$u->is_valid()) {
				$contact_errors = true;
			} else {
				//$u->Save();
				if($u->send_message($_POST['comments'])) {
					$contact_errors = false;
				} else {
					$contact_errors = true;
				}
			}
		}
			// this is a login
	}
	
			
		
			
		
		
		
		
		
		
		
		
		
		
?>