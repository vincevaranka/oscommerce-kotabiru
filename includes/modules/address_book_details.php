<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
<div class="row">
	<div class="col-md-12">
	<h2><?php echo NEW_ADDRESS_TITLE; ?></h2>
	<?php echo FORM_REQUIRED_INFORMATION; ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6 form-group x-row">
		<?php
			if (ACCOUNT_GENDER == 'true') {
			$male = $female = false;
			if (isset($gender)) {
				$male = ($gender == 'm') ? true : false;
				$female = !$male;
			} elseif (isset($entry['entry_gender'])) {
			$male = ($entry['entry_gender'] == 'm') ? true : false;
			$female = !$male;
			}
		?>
		<label class="field" for="gender"><?php echo ENTRY_GENDER; ?></label>
		<?php echo '<label class="inline">'.tep_draw_radio_field('gender', 'm', $male) . '' . MALE . '</label><label class="inline">' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '</label>' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>

		<?php
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_FIRST_NAME;?></label>
		<?php echo tep_draw_input_field('firstname',(isset($entry['entry_firstname']) ? $entry['entry_firstname'] : ''),'class="form-control" ') . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement  form-group-addon">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?>
	</div>
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_LAST_NAME;?></label>
		<?php echo tep_draw_input_field('lastname',(isset($entry['entry_lastname']) ? $entry['entry_lastname'] : ''),'placeholder="'.ENTRY_LAST_NAME.'" class="form-control"') . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement  form-group-addon">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?php
		if (ACCOUNT_COMPANY == 'true') {
		?>
		<label class="field"><?php echo ENTRY_COMPANY;?></label>
		<?php echo tep_draw_input_field('company', (isset($entry['entry_company']) ? $entry['entry_company'] : ''),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?>
		<?php
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<label class="field"><?php echo ENTRY_STREET_ADDRESS;?></label>
	<?php echo tep_draw_input_field('street_address', (isset($entry['entry_street_address']) ? $entry['entry_street_address'] : ''),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?>
	</div>
</div>
<div class="row">
	<?php
		if (ACCOUNT_SUBURB == 'true') {
	?>
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_SUBURB;?></label>
		<?php echo tep_draw_input_field('suburb', (isset($entry['entry_suburb']) ? $entry['entry_suburb'] : ''),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?>
	</div>
	<?php
	}
	?>
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_POST_CODE;?></label>
		<?php echo tep_draw_input_field('postcode', (isset($entry['entry_postcode']) ? $entry['entry_postcode'] : ''),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_CITY;?></label>
		<?php echo tep_draw_input_field('city', (isset($entry['entry_city']) ? $entry['entry_city'] : ''),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>
	</div>
	<?php
	if (ACCOUNT_STATE == 'true') {
	?>
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_STATE;?></label>
		<?php
		if ($process == true) {
			if ($entry_state_has_zones == true) {
			$zones_array = array();
			$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			echo tep_draw_pull_down_menu('state', $zones_array);
			} else {
				echo tep_draw_input_field('state');
			}
	   } else {
			echo tep_draw_input_field('state', (isset($entry['entry_country_id']) ? tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']) : ''),'class="form-control"');
		}
		if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT . '</span>';
?>
	</div>
	<?php } ?>
</div>

<div class="row">
	<div class="col-md-6">
		<label class="field"><?php echo ENTRY_COUNTRY; ?></label>
		<?php echo tep_get_country_list('country', (isset($entry['entry_country_id']) ? $entry['entry_country_id'] : STORE_COUNTRY),'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?php
			if ((isset($HTTP_GET_VARS['edit']) && ($customer_default_address_id != $HTTP_GET_VARS['edit'])) || (isset($HTTP_GET_VARS['edit']) == false) ) {
		?>
			<?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' ' . SET_AS_PRIMARY; ?>
		<?php
		}
		?>
	</div>
</div>
