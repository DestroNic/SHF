<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Settlement Housing Fund Website Administration</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="keywords" content="" /> 
	<meta name="description" content="" /> 
    
    <link href="<?=ADMIN_URL?>/styles/style.css" rel="stylesheet" type="text/css" />
	<link href="<?=ADMIN_JS_URL?>/datepicker.css" rel="stylesheet" type="text/css" />
	<link href="<?=ADMIN_JS_URL?>/datepicker_vista/datepicker_vista.css" rel="stylesheet" type="text/css" />
	<?php /* if needed
	<!--[if IE 6]>
		<link href="<?=ADMIN_URL?>/styles/ie6.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	*/ ?>
	<script type="text/javascript" src="<?=ADMIN_JS_URL?>/moo.js"></script>
	<script type="text/javascript" src="<?=ADMIN_JS_URL?>/datepicker.js"></script>
	<!-- <script type="text/javascript" src="<?=ADMIN_JS_URL?>/more.js"></script>
	<script type="text/javascript" src="<?=ADMIN_JS_URL?>/cal.js"></script> -->
	<script type="text/javascript" src="<?=ADMIN_JS_URL?>/h.js"></script>
	
	<script type="text/javascript" src="<?=ADMIN_URL?>/lib/ckeditor/ckeditor.js"></script>

	

</head>
<body>

	<div id="theTop">
		<a href="<?=ADMIN_URL?>"><img src="<?=ADMIN_URL?>/images/shf_logo2.jpg" /></a>
	</div>
	
	<div id="theNavigation">
		<?php if($logged_in) { ?>
			<a href="<?=ADMIN_URL?>/index.php?m=logout"><b>logout</b></a><a href="<?=ADMIN_URL?>/index.php">home</a><a href="<?=ADMIN_URL?>/index.php?m=pages">pages</a><a href="<?=ADMIN_URL?>/index.php?m=calendar">calendar</a><!-- <a href="<?=ADMIN_URL?>/index.php?m=users">users</a> -->
		<?php } else { ?>
			<a href="<?=ADMIN_URL?>">login</a>
		<?php } ?>
	</div>
	
	<div id="theBody">
		<div id="theContent">
				<div id="messgs">
					<?php if(!empty($mess)) { ?>
						<p class="messg">
							<?php foreach($mess as $me) { echo $me . "<br/>"; } ?>
						</p>
					<?php } ?>
					<?php if(!empty($errs)) { ?>
						<p class="errs">
							<?php foreach($errs as $err) { echo $err . "<br/>"; } ?>
						</p>
					<?php } ?>
				</div>
	