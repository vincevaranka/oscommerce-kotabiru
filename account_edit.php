<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {
        $error = true;

      $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (!tep_validate_email($email_address)) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname);

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");

// reset the session variables
      $customer_first_name = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }

  $account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $account = tep_db_fetch_array($account_query);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));


  require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php');
  require('includes/form_check.js.php');
?>
<div class="content">
<h1><?php echo HEADING_TITLE; ?></h1>

<?php
  if ($messageStack->size('account_edit') > 0) {
    echo $messageStack->output('account_edit');
  }
?>

<?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onsubmit="return check_form(account_edit);"', true) . tep_draw_hidden_field('action', 'process'); ?>

<div class="row">
	<div class="col-md-12">
		<?php echo FORM_REQUIRED_INFORMATION; ?>
		 <h2><?php echo MY_ACCOUNT_TITLE; ?></h2>
	</div>
	<div class="col-md-12">
		<?php
			if (ACCOUNT_GENDER == 'true') {if (isset($gender)) {$male = ($gender == 'm') ? true : false;} else{$male = ($account['customers_gender'] == 'm') ? true : false;}$female = !$male;
		?>
		<div class="form-group x-row">
			<label class="field"><?php echo ENTRY_GENDER; ?></label>
			<?php 
				echo '<label class="inline">'.tep_draw_radio_field('gender', 'm', $male) . MALE . '</label><label>' . tep_draw_radio_field('gender', 'f', $female) . FEMALE . '</label>' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
		</div>
		<?php } ?>
		<div class="row">
		<div class="col-md-6">
		<label class="field"><?php echo ENTRY_FIRST_NAME; ?></label>
				<?php echo tep_draw_input_field('firstname',$account['customers_firstname'],'placeholder="'.ENTRY_FIRST_NAME.'" class="form-control"') . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement  form-group-addon">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?>
		</div>
		<div class="col-md-6">
			<label class="field"><?php echo ENTRY_LAST_NAME; ?></label>
				<?php echo tep_draw_input_field('lastname',$account['customers_lastname'],'placeholder="'.ENTRY_LAST_NAME.'" class="form-control"') . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement  form-group-addon">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>		
		</div>
		</div>
		<div class="row">
		<div class="col-md-6">
			<?php if (ACCOUNT_DOB == 'true') {?>
				<label class="field" for="datepicker"><?php echo ENTRY_DATE_OF_BIRTH; ?></label>
				<?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob']), 'id="datepicker" type="text" readonly class="datepicker"'); ?>
			<?php } ?>
		</div>
		</div><br />
		<div class="row">
		<div class="col-md-6">
				<label class="field"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
				<?php echo tep_draw_input_field('email_address', $account['customers_email_address'],'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?>
		</div>
		</div>
		<div class="row">
		<div class="col-md-6">
				<label class="field"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
				<?php echo tep_draw_input_field('telephone', $account['customers_telephone'],'class="form-control"') . '' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?>
		</div>
		<div class="col-md-6">
				<label class="field"><?php echo ENTRY_FAX_NUMBER; ?></label>
				<?php echo tep_draw_input_field('fax', $account['customers_fax'],'class="form-control"') . '' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?>
		</div>
		</div>
	</div>
</div>




<hr />


<?php echo tep_draw_button_booth(IMAGE_BUTTON_CONTINUE, 'save', null, 'primary'); ?>
<?php echo tep_draw_button_booth(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'),'default'); ?>
</form>
</div>
	<script type="text/javascript" src="<?php echo DIR_WS_THEME.THEME_DEFAULT;?>/js/moment.js"></script>
	<script type="text/javascript" src="<?php echo DIR_WS_THEME.THEME_DEFAULT;?>/js/pikaday.js"></script>
   <script>  
 // You can get and set dates with moment objects
     var picker = new Pikaday(
    {
        field: document.getElementById('datepicker'),
        firstDay: 1,
        minDate: new Date('1981-01-01'),
        maxDate: new Date('2020-12-31'),
        yearRange: [1900,2018],
        onSelect: function() {
            var date = document.createTextNode(this.getMoment().format('YYYY-MM-DD') + ' ');
            document.getElementById('selected').appendChild(date);
        }
    });
</script>
<?php
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
