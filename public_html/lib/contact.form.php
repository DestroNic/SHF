<?php
	// this is a nice contact form!
	require_once(FORM_MAKER);

	if(!isset($ppt)) {
		if(isset($_POST['ppt'])) {
			$ppt = $_POST['ppt'];
		} else {
			$ppt = "Contact Us";
		}
	}
	
	if(!isset($ppt_page)) {
		if(isset($_POST['ppt_page'])) {
			$ppt_page = $_POST['ppt_page'];
		} else {
			$ppt_page = "contact.html";
		}
	}
?>

<h1><?=$ppt?></h1>


<?php error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
?>




<?php 
	if(isset($contact_errors)) {
		if($contact_errors) {
?>
			<p class="errors">There was a problem with your submission! Please check all fields for proper entries!</p>
<?php  } else { ?>
			<p class="alert">Thank you for your interest in Settlement Housing Fund. A representative will be in touch with you shortly.
			
			<br/><br/><br/><br/><br/><br/><br/><br/>
			</p>
<?php } } else { ?>
	<!-- <p>Please fill out the following form to receive our newsletter and other updates on our work.</p> -->
	<p>Please fill out the following form to make an inquiry and/or to receive our newsletter and other updates on our work, or to volunteer.</p>
	<p>Settlement Housing Fund does not share personal information with any third parties.</p>
<?php } ?>


<?php if(!isset($contact_errors) || $contact_errors) { ?>
	<form action="<?=URL?>/<?=$ppt_page?>" method="post" name="contact_form" class="niceform">

	<?php
		input_hidden_print('a_form', 'true');
		input_hidden_print('form_type', 'contact');
		input_hidden_print('ppt', $ppt);
	?>
<div id="contact_form_left">
	<div class="fform"><?php input_text_print('<b>*First Name</b>','first_name',(isset($u) ? ($u->passes_in_tests($_POST['first_name']) ? $_POST['first_name'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('<b>*Last Name</b>', 'last_name',(isset($u) ? ($u->passes_in_tests($_POST['last_name']) ? $_POST['last_name'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('Business Name', 'business_name',(isset($u) ? ($u->passes_in_tests($_POST['business_name']) ? $_POST['business_name'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('Phone Number', 'phone_number',(isset($u) ? ($u->passes_in_tests($_POST['phone_number']) ? $_POST['phone_number'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('<b>*Email Address</b>', 'email_address',(isset($u) ? ($u->passes_in_tests($_POST['email_address']) ? $_POST['email_address'] : '') : ''),'required');?></div>
		
		<div class="fform">
		<label for="comments">Comments</label>
		<div class="txtarea">
			<textarea rows="7" cols="25" name="comments"><?=(isset($u) ? ($u->passes_in_tests($_POST['comments']) ? $_POST['comments'] : '') : '')?></textarea>
			<br/><br/><br/><br/><br/>
		</div>
	</div>
</div>
<div id="contact_right">
	<div class="fform"><?php 
		input_text_print('Street Address', 'street_address',(isset($u) ? ($u->passes_in_tests($_POST['street_address']) ? $_POST['street_address'] : '') : ''),'required');?></div>
		
	<div class="fform"><?php 
		input_text_print('City', 'city',(isset($u) ? ($u->passes_in_tests($_POST['city']) ? $_POST['city'] : '') : ''),'required');?></div>	
	
	<div class="fform">
		<?php  input_text_print('State', 'state',(isset($u) ? ($u->passes_in_tests($_POST['state']) ? $_POST['state'] : '') : ''),'required');?>
	</div>
	
	
	<div class="fform">
		<?php  input_text_print('Zip Code', 'zip',(isset($u) ? ($u->passes_in_tests($_POST['zip']) ? $_POST['zip'] : '') : ''),'required');?>
	</div>
		
	<div class="fform"><?php 
		one_check_box('Subscribe to our Newsletter?','subscribe_news','yes');?>
		<br/><br/></div>

	<div class="fform"><?php 
		one_check_box('Interested in volunteering?','volunteer','yes');?>
		<br/><br/><br/><br/></div>	
	

	<div class="fform">
	<label>&nbsp;</label>
		
		<?php submit_print('submit_form','Submit'); ?>
	</div>
	<div class="fform"><label>&nbsp;</label>
		<b><i>* required</i></b>
		</div>
	</div>
	</form>
<?php }	?>
<br/><br/><br/><br/>

