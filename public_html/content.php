<?php

//matt's cms - content page - ver. .1 - 3/9/9
// please email matt@matthewlittlehale.com

//error_reporting(E_ALL);
ini_set('display_errors', 'off');

require_once('lib/constants.php');
require_once(CONFIGURE);
require_once(CONNECTION);
require_once(CLASS_DIR . '/page.class.php');
require_once(CLASS_DIR . '/content.class.php');
require_once(CLASS_DIR . '/side.calendar.class.php');
require_once(CLASS_DIR . '/calendar.class.php');

require_once(CF);

$offset = 0;

$tp = new Page();
// let's get this going.

$now = date('Y-m-d H:i:s', strtotime("now"));
$page = 'error!';
$available_pages = $tp->available_pages();
if(!$available_pages)
	$available_pages = array();


$path = substr($_SERVER['REQUEST_URI'], 1);
$parts = explode("/",$path);



 if(isset($_REQUEST['sc']) && $_REQUEST['sc'] == 'cal' && isset($_REQUEST['d'])) {
		$ddd = date('Y-m-d', strtotime($_REQUEST['d']));
		require_once(CLASS_DIR . '/side.calendar.class.php');
		$home_calendar = new SideCalendar($ddd);
		echo $home_calendar->output_calendar_better();
		exit;	

} else if(isset($_REQUEST['ss']) && $_REQUEST['ss'] == 'day' && isset($_REQUEST['d'])) {
	
		$ddd = date('Y-m-d', strtotime($_REQUEST['d']));
		require_once(CLASS_DIR . '/calendar.class.php');
		$day = new Calendar($ddd);
		echo $day->print_pop_day();
		exit;	

} else if($parts[$offset] == 'semiperm_luncheon.html') {
	header('Location: ' . URL . '/index.html');
	exit;
		
} else if($parts[$offset] == 'contact.html' || $parts[$offset] == 'get_involved.html') {
	
	$ppt = "Contact Us";
	$ppt_page = "contact.html";
	if($parts[$offset] == 'get_involved.html') {
		$ppt = "Get Involved";
		$ppt_page = "get_involved.html";
	}
	
    require_once(THE_HEADER);
    require_once(LIB_DIR . '/contact.form.php');
    require_once(THE_FOOTER);
	exit;
		
} else if($parts[$offset] == 'index.html') {


	$is_home = true;

	 require_once(THE_HEADER);
	include(TEMPLATES . '/home.tem.php');
		require_once(THE_FOOTER);
		exit;
        
        
} else if($parts[$offset] == 'index2.html') {

    //test home
	$is_home = true;

	 require_once(THE_HEADER);
	include(TEMPLATES . '/home2.tem.php');
		require_once(THE_FOOTER);
		exit;



} else if(!in_array($parts[$offset], $available_pages)) {
    require_once(THE_HEADER);
    echo '
          <p>  <b>Oops!</b> Something unexpected happened and you have mistakingly reached this page!<br/><br/>
          <a href="' . URL . '">Home</a></p>
        ';
    require_once(THE_FOOTER);
	exit;
	
} else  {

    // here everything good happens!
	
	// prepare calendar
	//$this_calendar = new SideCalendar();
	
	// prepare output
    $all_pages = array_keys($available_pages,$parts[$offset]);
    $pager = new Page($all_pages[0]);
    
    require_once(THE_HEADER);
    
	$type_title = $pager->type_name;
	$side_nav = $pager->build_side_navigation();
	
	$side_images = $pager->get_side_images();
	
	$pager->printPage();
	
	require_once(THE_LEFT);
    
    include(THE_FOOTER);
}
    


?>

