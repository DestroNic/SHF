<?php
	// this is a nice contact form!
	require_once(FORM_MAKER);

	if(!isset($ppt)) {
		if(isset($_POST['ppt'])) {
			$ppt = $_POST['ppt'];
		} else {
			$ppt = "Contact Us Today!";
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

<div id="contact_form_left">





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
	<p>Please take a moment to fill our the following form to find out more information about Settlement Housing Fund. </p>
<?php } ?>


<?php if(!isset($contact_errors) || $contact_errors) { ?>
	<form action="<?=URL?>/<?=$ppt_page?>" method="post" name="contact_form" class="niceform">

	<?php
		input_hidden_print('a_form', 'true');
		input_hidden_print('form_type', 'contact');
		input_hidden_print('ppt', $ppt);
	?>

	<div class="fform"><?php input_text_print('First Name','first_name',(isset($u) ? ($u->passes_in_tests($_POST['first_name']) ? $_POST['first_name'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('Last Name', 'last_name',(isset($u) ? ($u->passes_in_tests($_POST['last_name']) ? $_POST['last_name'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('Phone Number', 'phone_number',(isset($u) ? ($u->passes_in_tests($_POST['phone_number']) ? $_POST['phone_number'] : '') : ''),'required'); ?></div>
	<div class="fform"><?php 
		input_text_print('Email Address', 'email_address',(isset($u) ? ($u->passes_in_tests($_POST['email_address']) ? $_POST['email_address'] : '') : ''),'required');?></div>
		
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
		one_check_box('Interested in volunteering?','volunteer','yes');?></div>	
	<div class="fform">
		<label for="comments">Comments</label>
		<div class="txtarea">
			<textarea rows="7" cols="20" name="comments"><?=(isset($u) ? ($u->passes_in_tests($_POST['comments']) ? $_POST['comments'] : '') : '')?></textarea>
		</div>
	</div>

	<div class="fform">
		<label>&nbsp;</label>
		<?php submit_print('submit_form','Submit'); ?>
	</div>
	<div class="fform"><br/><br/>
		<b><i>* All Fields Required</i></b>
		</div>
	</form>
<?php }	?>
<br/><br/><br/><br/>
</div>

<div id="contact_right">
	<h2>Contact Information</h2>
	<p><b>New York City Office:</b><br/>
247 West 37th Street<br/>
New York, NY 10018<br/>
(212) 265-6530<br/>
(212) 757-0571 (fax)<br/></p>



</div>