<?php
/*

6/17/09 - Matt's nice CMS - ver. 0.4
the best version yet.

contact: matt@matthewlittlehale.com for help!


*/

// errors
error_reporting(E_ALL);
ini_set('display_errors', 'on');

//session
session_start();

//configuration
require_once('/home/g80kpw0cm9si/public_html/lib/constants.php');
require_once(CONFIGURE);
require_once(CONNECTION);
require_once(LOGIN_FUNCTIONS);

$logged_in = false;
$mess = $errs = array();

$m = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : 'home';
$m = (isset($_POST['m'])) ? $_POST['m'] : $m;
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : false;

//check to see if login is coming
if(isset($_POST['user']) && isset($_POST['pw'])) {
    $user = login($_POST['user'], $_POST['pw'], true, true);
    if($user) {
        $logged_in = true;
        $mess[] = "You have successfully logged in!";
    } else {
        $errs[] = "Your username or password are incorrect";
    }
} else if ($m == 'logout') {
    logout();
    $mess[] = "You have successfully been logged out!";
    $logged_in = false;
} else {
    $logged_in = check_login();
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
	require_once(CLASS_DIR . '/page.class.php');
    require_once(CLASS_DIR . '/content.class.php');
	require_once(CLASS_DIR . '/calendar.class.php');
	require_once(CLASS_DIR . '/users.class.php');
    
      $page_id = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 0;
      $page_id = (isset($_POST['page'])) ? $_POST['page'] : $page_id;
      $page = new Page($page_id);
    
    
        include(ADMIN_HEADER);
    if('preview' == $action) {
        $pager = $page;
        $pager->setField('title', $pager->getField('title') . " <b>PREVIEW!</b>");
        include(THE_HEADER);
       $pager->printPage(); 
        include(THE_FOOTER);
    
    } else {
    
        

      
        
        
         
        if('pages' == $m) {
            if(isset($_POST['page_save']) && $_POST['page_save'] == 'yes') {
                $page = new Page($_POST);
                $page->Save();
                $action = 'view';
            }
            
            $page_list = $page->get_list();
			$page_titles = "Pages";
            include(ADMIN_TEMPLATES . '/page.left.tem.php');
            echo '<div id="theRight">
						<div id="theRightContent">';
                
            if('view' == $action) {
                $page->print_details();
            } else if ('new' == $action || 'edit' == $action) {
                $page->print_form();
            } else if ('remove' == $action) {
                $page->Delete();
                echo " <p>The page <i>". $page->getField('name') . "</i> has been removed!</p>";
            } else {
                echo " <p>Please select a page from the left to view or edit.</p>";
            }
        } else if ('content' == $m) {
            $content_id = (isset($_REQUEST['content'])) ? $_REQUEST['content'] : 0;
            $content = new Content($content_id);
            if(isset($_POST['content_save']) && $_POST['content_save'] == 'yes') {
                $content = new Content($_POST);
                $content->setField('content', $_POST['FCKeditor1']);
                $content->Save();
                $action = 'view';
            }  
            
            $page_list = $page->get_list();
			$page_titles = "Pages";
            include(ADMIN_TEMPLATES . '/page.left.tem.php');
            //echo '<div id="page_right">';
            echo '<div id="theRight">
						<div id="theRightContent">';
						
            if('view' == $action) {
                $content->print_details();
            } else if ('new' == $action || 'edit' == $action) {
                $content->print_form($page_id);
            } else if ('remove' == $action) {
                $content->Delete();
                echo " <p>The content <i>" . $content->getField('ref_name') . "</i> has been removed!</p>";
            } else {
                echo " <p>Please select a piece of content.</p>";
            }    
        } else if ('calendar' == $m) {
            $calendar_id = (isset($_REQUEST['cal'])) ? $_REQUEST['cal'] : 0;
            $calendar = new Calendar($calendar_id);
            if(isset($_POST['cal_save']) && $_POST['cal_save'] == 'yes') {
                $calendar = new Calendar($_POST);
                $calendar->Save();
                $action = 'view';
            }  
            
            $page_list = $calendar->get_list(true);
			$page_titles = "Calendar Events";
            include(ADMIN_TEMPLATES . '/cal.left.tem.php');
            //echo '<div id="page_right">';
			echo '<div id="theRight">
						<div id="theRightContent">';
            if('view' == $action) {
                $calendar->print_details();
            } else if ('new' == $action || 'edit' == $action) {
                $calendar->print_form($calendar_id);
            } else if ('remove' == $action) {
                $calendar->Delete();
                echo " <p>The calendar event <i>" . $calendar->get_title() . "</i> has been removed!</p>";
            } else {
                echo " <p>Please select a calendar event.</p>";
				$calendar->print_list();
            }    
		 } else if ('users' == $m) {
            $user_id = (isset($_REQUEST['which_user'])) ? $_REQUEST['which_user'] : 0;
            $user = new User($user_id);
            if(isset($_POST['user_save']) && $_POST['user_save'] == 'yes') {
                $user = new User($_POST, 'registration_form');
                $user->Save();
                $action = 'view';
            }  
            
            $page_list = $user->get_list();
			$page_titles = "Users";
            include(ADMIN_TEMPLATES . '/user.left.tem.php');
            //echo '<div id="page_right">';
			echo '<div id="theRight">
						<div id="theRightContent">';
            if('view' == $action) {
                $user->print_details();
            } else if ('new' == $action || 'edit' == $action) {
                $user->print_form();
            } else if ('remove' == $action) {
                $user->Delete();
                echo " <p>The user: <i>" . $user->name . "</i> has been removed!</p>";
            } else {
                echo " <p>These are users who are able to login to see password protected pages. <br/>You can select a user below to edit or create new users.</p>";
				$user->print_list();
            }    
        } else {
            $page_list = $page->get_list();
            include(ADMIN_TEMPLATES . '/page.left.tem.php');
            echo '<div id="theRight">
						<div id="theRightContent"> ';
            echo "<p>Welcome to your content management system, please select a page to the left</p>";
        }
        
        echo '</div></div>';     
        
        include(ADMIN_FOOTER);
    }
}