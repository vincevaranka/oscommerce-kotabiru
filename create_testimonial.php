<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_TESTIMONIAL);
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $error = false;
    $name = strip_tags($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $enquiry = strip_tags($HTTP_POST_VARS['enquiry']);
    if (!tep_validate_email($email_address)) {
      $error = true;
      $messageStack->add('testimonial', '<span class="error">'.ENTRY_EMAIL_ADDRESS_CHECK_ERROR.'</span>');
    }
	if (strlen($name) < 3) {
		$error = true;
		$messageStack->add('testimonial','<span class="error">'.ERROR_NAME.'</span>');
	}	
	if (strlen($enquiry) < 50) {
		$error = true;
		$messageStack->add('testimonial','<span class="error">'.ERROR_MESSAGE.'</span>');
	}
    $actionRecorder = new actionRecorder('ar_testimonial', (tep_session_is_registered('customer_id') ? $customer_id : null), $name);
    if (!$actionRecorder->canPerform()) {
      $error = true;
      $actionRecorder->record(false);
      $messageStack->add('testimonial', sprintf('<span class="error">'.ERROR_ACTION_RECORDER.'</span>', (defined('MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES') ? (int)MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES : 15)));
    }
    if ($error == false) {
	  $sql_data_array = array('testi_name' => tep_db_prepare_input($name),
								'testi_email' => $email_address,
								'testi_text' => tep_db_prepare_input($enquiry), 
								'testi_status' => '0'
								);
	  tep_db_perform(TABLE_TESTIMONIAL, $sql_data_array);

      $actionRecorder->record();
      tep_redirect(tep_href_link(FILENAME_CREATE_TESTIMONIAL, 'action=success'));
    }
  }

  
?>
<style>

.error{
color:red;
font-size:8pt;
font-family:verdana;
}
</style>
<script type="text/javascript" src="ext/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
(function($) {$.fn.extend( {limiter: function(limit, elem) {$(this).on("keyup focus", function() {setCount(this, elem);});function setCount(src, elem) {var chars = src.value.length;if (chars > limit) {src.value = src.value.substr(0, limit);chars = limit;}elem.html( limit - chars );}setCount($(this)[0], elem); }});})(jQuery);
</script>
<link rel="stylesheet" href="ext/bootstrap/css/bootstrap.min.css">


<?php
  if ($messageStack->size('testimonial') > 0) {
    echo $messageStack->output('testimonial');
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_SUCCESS; ?>
  </div>


</div>

<?php
  } else {
?>

<?php echo tep_draw_form('testimonial', tep_href_link(FILENAME_CREATE_TESTIMONIAL, 'action=send'), 'post', '', true); ?>

<div class="contentContainer" style="font-family:verdana; font-size:11pt">
  <div class="contentText">
  <div style="margin-top:10px;"><?php echo ENTRY_NAME; ?><br /><?php echo tep_draw_input_field('name','','class="form-control"'); ?></div>
  <div style="margin-top:10px;"><?php echo ENTRY_EMAIL;?><br /><?php echo tep_draw_input_field('email','','class="form-control"');?></div>
  <div style="margin-top:10px;"><?php echo ENTRY_ENQUIRY; ?><br /><?php echo tep_draw_textarea_field('enquiry', 'soft', 10,5,'','id=enquiry style="min-width:100%;" class="form-control"'); ?></div>
  
	<div id="chars">500</div>
  </div>
	<br />
  <?php echo tep_draw_button_booth(' '.IMAGE_BUTTON_CONTINUE, 'send', null, 'primary'); ?>
 
</div>

</form>
<script>
$(document).ready( function() {
	var elem = $("#chars");
	$("#enquiry").limiter(500, elem);
});
</script>
<?php
  }
  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
</body>