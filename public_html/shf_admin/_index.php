<?php
// ----------------------------------------------------------------------------------
// The Henry Schein Email Blast program - ver .1, 6/1/09
// loosely based on :
/* ************
	//matt's cms - content page - ver. .1 - 3/9/9
	// please email matt@matthewlittlehale.com
************ */
//
// ----------------------------------------------------------------------------------
//
// errors
error_reporting(E_ALL);
ini_set('display_errors', 'on');

//session
session_start();

// configuration
require_once('/home/fairhav1/public_html/beta/lib/constants.php');
require_once(CONFIGURE);
require_once(CONNECTION);
require_once(LOGIN_FUNCTIONS);

$logged_in = false;
$is_admin = false;
$mess = $errs = array();

$m = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : 'home';
$m = (isset($_POST['m'])) ? $_POST['m'] : $m;
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : false;
$action = (isset($_POST['action'])) ? $_POST['action'] : $action;

//check to see if login is coming
if(isset($_POST['user']) && isset($_POST['pw'])) {
    $user = login($_POST['user'], $_POST['pw']);
    if($user) {
        $logged_in = true;
		if($user['a'] == 'y') 
			$is_admin = true;
        $mess[] = "You have successfully logged in!";
    } else {
        $errs[] = "Your username or password are incorrect";
    }
} else if ($m == 'logout') {
    logout();
    $mess[] = "You have successfully been logged out!";
    $logged_in = false;
} else {
    $logged_in = check_login(true);
	if(isset($_SESSION['jbnj_administrator']) && $_SESSION['jbnj_administrator']=='yes')
		$is_admin = true;
    if(!$logged_in)
        logout();
}

if(!$logged_in) {
    include(ADMIN_HEADER);
    include(ADMIN_TEMPLATES . '/login.tem.php');
    include(ADMIN_FOOTER);
    exit;
}
else {
    //here we are totally logged in and everything is gravy
	require_once(CLASS_DIR . '/template.class.php');
	require_once(CLASS_DIR . '/blast.class.php');
	require_once(CLASS_DIR . '/value.class.php');
	
	//	
	
	// make sure to test for IS_ADMIN also.
	if('template' == $m && $is_admin) {
		$template_id = (isset($_REQUEST['template'])) ? $_REQUEST['template'] : 0;
		$template_id = (isset($_POST['template'])) ? $_POST['template'] : $template_id;
		$template = new HsTemplate($template_id);
	
		if(isset($_POST['template_save']) && $_POST['template_save'] == 'yes') {
			$template = new HsTemplate($_POST);
			$template->Save();
			$action = 'view';
		}
		include(ADMIN_HEADER);
		//print side navigation
		echo '<div id="theSideNavigation">';
			$template->print_side_nav();
		echo '</div>';
		echo '<div id="theInfo">';
			if('view' == $action) {
				$template->print_details();
			} else if ('new' == $action || 'edit' == $action) {
				$template->print_form();
			} else if ('remove' == $action) {
				$template->Delete();
				echo " <p>The template <i>". $template->getField('title') . "</i> has been removed!</p>";
			} else {
				echo " <p>Please select a template from the left to view or edit.</p>";
			}
		echo '</div>';
	} else if('blast' == $m) {
		$blast_id = (isset($_REQUEST['blast'])) ? $_REQUEST['blast'] : 0;
		$blast_id = (isset($_POST['blast'])) ? $_POST['blast'] : $blast_id;
		$blast = new HsBlast($blast_id);
	
		if(isset($_POST['blast_save']) && $_POST['blast_save'] == 'yes') {
			$blast = new HsBlast($_POST);
			$blast->Save();
			if('new' != $action)
				$action = 'view';
		}
		include(ADMIN_HEADER);
	
		if('view' == $action) {
			echo '<div id="theSideNavigation">';
				echo '<h1>' . $blast->title . '</h1>';
			echo '</div>';
			echo '<div id="theInfo">';
				$blast->print_details();
				
		} else if ('new' == $action) {
			$step = (isset($_REQUEST['step'])) ? $_REQUEST['step'] : 1;
			$step = (isset($_POST['step'])) ? $_POST['step'] : $step;
			$template_id = (isset($_REQUEST['template'])) ? $_REQUEST['template'] : 0;
			$template_id = (isset($_POST['tID'])) ? $_POST['tID'] : $template_id;
			
			if($step > 4)
				$step = 1;					
				
			echo '<div id="theSideNavigation">';
				echo '<h1>create new blast</h1>';
				$blast->print_step($step);
			echo '</div>';
			echo '<div id="theInfo">';
				// there are 4 steps here, 
				if(1 == $step) {
					$blast->print_template_selection();
				} else if(2 == $step) {
					$blast->set_template($template_id);
					$blast->print_form();
				} else if(3 == $step) {
					$blast->print_confirmation();
				} else if(4 == $step) {
					$blast->print_details();
				}
		} else if ('edit' == $action) {
			echo '<div id="theSideNavigation">';
				echo '<h1>Edit' . $blast->title . '</h1>';
				echo '<p><a href=' . URL . '/index.php?m=blast&action=copy&id=' . $blast_id . '">copy this blast</a></p>';
			echo '</div>';
			echo '<div id="theInfo">';
				$blast->print_form();
				
		} else if ('remove' == $action) {
			$blast->Delete();
			echo " <p>The blast <i>". $template->getField('title') . "</i> has been removed!</p>";
			
		} else {
			// this will be the list, different then usual
			$blast->print_list();
		}
		// end of theinfo div
		echo '</div>';
	} else {
		include(ADMIN_HEADER);
		echo '<p>you are logged in, killer.</p>';
	}
	
    include(ADMIN_FOOTER);
    exit;
}
    


?>

