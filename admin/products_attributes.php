<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $option_page = (isset($HTTP_GET_VARS['option_page']) && is_numeric($HTTP_GET_VARS['option_page'])) ? $HTTP_GET_VARS['option_page'] : 1;
  $value_page = (isset($HTTP_GET_VARS['value_page']) && is_numeric($HTTP_GET_VARS['value_page'])) ? $HTTP_GET_VARS['value_page'] : 1;
  $attribute_page = (isset($HTTP_GET_VARS['attribute_page']) && is_numeric($HTTP_GET_VARS['attribute_page'])) ? $HTTP_GET_VARS['attribute_page'] : 1;

  $page_info = 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($HTTP_POST_VARS['products_options_id']);
        $option_name_array = $HTTP_POST_VARS['option_name'];

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $HTTP_POST_VARS['value_name'];
        $value_id = tep_db_prepare_input($HTTP_POST_VARS['value_id']);
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$value_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$option_id . "', '" . (int)$value_id . "')");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_attributes':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
        $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
        $value_price = tep_db_prepare_input($HTTP_POST_VARS['value_price']);
        $price_prefix = tep_db_prepare_input($HTTP_POST_VARS['price_prefix']);

        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values (null, '" . (int)$products_id . "', '" . (int)$options_id . "', '" . (int)$values_id . "', '" . (float)tep_db_input($value_price) . "', '" . tep_db_input($price_prefix) . "')");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_id = tep_db_insert_id();

          $products_attributes_filename = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        $option_name_array = $HTTP_POST_VARS['option_name'];
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($option_name) . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        $value_name_array = $HTTP_POST_VARS['value_name'];
        $value_id = tep_db_prepare_input($HTTP_POST_VARS['value_id']);
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name) . "' where products_options_values_id = '" . tep_db_input($value_id) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . (int)$option_id . "'  where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
        $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
        $value_price = tep_db_prepare_input($HTTP_POST_VARS['value_price']);
        $price_prefix = tep_db_prepare_input($HTTP_POST_VARS['price_prefix']);
        $attribute_id = tep_db_prepare_input($HTTP_POST_VARS['attribute_id']);

        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "', options_values_id = '" . (int)$values_id . "', options_values_price = '" . (float)tep_db_input($value_price) . "', price_prefix = '" . tep_db_input($price_prefix) . "' where products_attributes_id = '" . (int)$attribute_id . "'");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_filename = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " set products_attributes_id = '" . (int)$attribute_id . "', products_attributes_filename = '" . tep_db_input($products_attributes_filename) . "', products_attributes_maxdays = '" . tep_db_input($products_attributes_maxdays) . "', products_attributes_maxcount = '" . tep_db_input($products_attributes_maxcount) . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($HTTP_GET_VARS['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($HTTP_GET_VARS['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        $attribute_id = tep_db_prepare_input($HTTP_GET_VARS['attribute_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$attribute_id . "'");

// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="sub-content">
<div class="row">
	<div class="col-md-6">
		<h2></h2>

<!-- options //-->
<?php
  if ($action == 'delete_product_option') 
  { // delete product option
				$options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$HTTP_GET_VARS['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
				$options_values = tep_db_fetch_array($options);
			?>
				<h2><?php echo $options_values['products_options_name']; ?></h2>
							
							<table class="table">

			<?php
				$products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
				if (tep_db_num_rows($products)) {
			?>
							  <tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
								<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
								<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
							  </tr>

			<?php
				  $rows = 0;
				  while ($products_values = tep_db_fetch_array($products)) {
					$rows++;
			?>
							  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
								<td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
								<td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
								<td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
							  </tr>
			<?php
				  }
			?>
							  <tr>
								<td colspan="3"><?php echo tep_black_line(); ?></td>
							  </tr>
							  <tr>
								<td colspan="3" class="main"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
							  </tr>
							  <tr>
								<td align="right" colspan="3" class="smallText"><br /><?php echo tep_draw_button(IMAGE_BACK, 'triangle-1-w', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)); ?>&nbsp;</td>
							  </tr>
			<?php
				} else {
			?>
							  <tr>
								<td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
							  </tr>
							  <tr>
								<td class="smallText" align="right" colspan="3"><br /><?php echo tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $HTTP_GET_VARS['option_id'] . '&' . $page_info), 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)); ?>&nbsp;</td>
							  </tr>
			<?php
				}
			?>
							</table></td>
						  </tr>
			<?php
  } else {
				?>
				
				<h2><?php echo HEADING_TITLE_OPT; ?></h2>
				<div class="row">
					<div class="col-md-12 text-right">
						<?php
							$options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_id";
							$options_split = new splitPageResults($option_page, MAX_ROW_LISTS_OPTIONS, $options, $options_query_numrows);
							echo $options_split->display_links($options_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $option_page, 'value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'option_page');
						?>
					</div>
				</div>
				<table class="table">
					<thead>
							  <tr>
								<td>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
								<td>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
								<td class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
							  </tr>
					</thead>
				<?php
					$next_id = 1;
					$rows = 0;
					$options = tep_db_query($options);
					while ($options_values = tep_db_fetch_array($options)) {
					  $rows++;
				?>
				 <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
				<?php
					  if (($action == 'update_option') && ($HTTP_GET_VARS['option_id'] == $options_values['products_options_id'])) {
						echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name&' . $page_info) . '" method="post">';
						$inputs = '';
						for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
						  $option_name = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
						  $option_name = tep_db_fetch_array($option_name);
						  $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br />';
						}
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
								<td class="smallText"><?php echo $inputs; ?></td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)); ?>&nbsp;</td>
				<?php
						echo '</form>' . "\n";
					  } else {
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
								<td class="smallText">&nbsp;<?php echo $options_values["products_options_name"]; ?>&nbsp;</td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info),'default') . tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info),'default'); ?>&nbsp;</td>
				<?php
					  }
				?>
							  </tr>
				<?php
					  $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
					  $max_options_id_values = tep_db_fetch_array($max_options_id_query);
					  $next_id = $max_options_id_values['next_id'];
					}
				?>
		
				<?php
					if ($action != 'update_option') {
				?>
							  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?> bg-eee">
				<?php
					  echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&' . $page_info) . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
					  $inputs = '';
					  for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
						$inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" class="form-control">&nbsp;<br />';
					  }
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
								<td class="smallText"><?php echo $inputs; ?></td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_INSERT, 'plus-square','default'); ?>&nbsp;</td>
				<?php
					  echo '</form>';
				?>
							  </tr>
							  </table>
				<?php
					}
  }
?>

	</div>
	<div class="col-md-6"><!---RIGHT COL --->
<!-- value //-->
		<?php
		  if ($action == 'delete_option_value') { // delete product option value
			$values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$HTTP_GET_VARS['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
			$values_values = tep_db_fetch_array($values);
		?>
				<h2><?php echo $values_values['products_options_values_name']; ?></h2>	
				<div class="table-responsive">
				<table class="table">
           
			<?php
				$products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$HTTP_GET_VARS['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
				if (tep_db_num_rows($products)) {
			?>
					<thead>
							  <tr>
								<td class="text-center"><?php echo TABLE_HEADING_ID; ?></td>
								<td><?php echo TABLE_HEADING_PRODUCT; ?></td>
								<td><?php echo TABLE_HEADING_OPT_NAME; ?></td>
							  </tr>
					</thead>

			<?php
				  while ($products_values = tep_db_fetch_array($products)) {
					$rows++;
			?>
							  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
								<td align="center" class="smallText"><?php echo $products_values['products_id']; ?></td>
								<td class="smallText"><?php echo $products_values['products_name']; ?></td>
								<td class="smallText"><?php echo $products_values['products_options_name']; ?></td>
							  </tr>
			<?php
				  }
			?>
							  <tr>
								<td colspan="3"><?php echo tep_black_line(); ?></td>
							  </tr>
							  <tr>
								<td class="main" colspan="3"><br /><?php echo TEXT_WARNING_OF_DELETE; ?></td>
							  </tr>
							  <tr>
								<td class="smallText" align="right" colspan="3"><br /><?php echo tep_draw_button_bs(IMAGE_BACK, 'triangle-1-w', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info),'default'); ?>&nbsp;</td>
							  </tr>
			<?php
				} else {
			?>
							  <tr>
								<td class="main" colspan="3"><br /><?php echo TEXT_OK_TO_DELETE; ?></td>
							  </tr>
							  <tr>
								<td class="smallText" align="right" colspan="3"><br /><?php echo tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $HTTP_GET_VARS['value_id'] . '&' . $page_info), 'default') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info),'default'); ?>&nbsp;</td>
							  </tr>
			<?php
				}
			?>
           </table>
		   </div>
          
<?php
  } else {
		
?>		<h2><?php echo HEADING_TITLE_VAL; ?></h2>
		<div class="row">
			<div class="col-md-12">
				<?php
					$values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . (int)$languages_id . "' order by pov.products_options_values_id";
					$values_split = new splitPageResults($value_page, MAX_ROW_LISTS_OPTIONS, $values, $values_query_numrows);

					echo $values_split->display_links($values_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $value_page, 'option_page=' . $option_page . '&attribute_page=' . $attribute_page, 'value_page');
				?>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table">
			<thead>
              <tr>
                <td ><?php echo TABLE_HEADING_ID; ?></td>
                <td><?php echo TABLE_HEADING_OPT_NAME; ?></td>
                <td><?php echo TABLE_HEADING_OPT_VALUE; ?></td>
                <td class="text-center"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
			 </thead>

				<?php
					$next_id = 1;
					$rows = 0;
					$values = tep_db_query($values);
					while ($values_values = tep_db_fetch_array($values)) {
					  $options_name = tep_options_name($values_values['products_options_id']);
					  $values_name = $values_values['products_options_values_name'];
					  $rows++;
				?>
				<tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
				<?php
					  if (($action == 'update_option_value') && ($HTTP_GET_VARS['value_id'] == $values_values['products_options_values_id'])) {
						echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value&' . $page_info) . '" method="post">';
						$inputs = '';
						for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
						  $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
						  $value_name = tep_db_fetch_array($value_name);
						  $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br />';
						}
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
								<td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select name="option_id">
				<?php
						$options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
						while ($options_values = tep_db_fetch_array($options)) {
						  echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
						  if ($values_values['products_options_id'] == $options_values['products_options_id']) { 
							echo ' selected';
						  }
						  echo '>' . $options_values['products_options_name'] . '</option>';
						} 
				?>
								</select>&nbsp;</td>
								<td class="smallText"><?php echo $inputs; ?></td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'default') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info),'default'); ?>&nbsp;</td>
				<?php
						echo '</form>';
					  } else {
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
								<td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
								<td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info),'default') . tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info),'default'); ?>&nbsp;</td>
				<?php
					  }
					  $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
					  $max_values_id_values = tep_db_fetch_array($max_values_id_query);
					  $next_id = $max_values_id_values['next_id'];
					}
				?>
							  </tr>
							 
				<?php
					if ($action != 'update_option_value') {
				?>
							  <tr class="bg-eee <?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
				<?php
					  echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&' . $page_info) . '" method="post">';
				?>
								<td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
								<td align="center" class="smallText"><select name="option_id" class="form-control">
				<?php
					  $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
					  while ($options_values = tep_db_fetch_array($options)) {
						echo '<option  name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
					  }

					  $inputs = '';
					  for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
						$inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" class="form-control" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br />';
					  }
				?>
								</select>&nbsp;</td>
								<td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
								<td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_INSERT, 'plus','primary'); ?>&nbsp;</td>
				<?php
					  echo '</form>';
				?>
							  </tr>

					 </table></div>
				<?php
					}
				  }
				?>
	</div>
	
</div><!---ROW FIRST END -->


<div class="row">
	<h2><?php echo HEADING_TITLE_ATRIB; ?></h2>
	<div class="col-md-12">
	<?php
			if ($action == 'update_attribute') {
			$form_action = 'update_product_attribute';
			} else {
				$form_action = 'add_product_attributes';
			}
	?>
	<div>
		<?php
			  $attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name";
			  $attributes_split = new splitPageResults($attribute_page, MAX_ROW_LISTS_OPTIONS, $attributes, $attributes_query_numrows);

			  echo $attributes_split->display_links($attributes_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $attribute_page, 'option_page=' . $option_page . '&value_page=' . $value_page, 'attribute_page');
			?>
	</div>

	<form name="attributes" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=' . $form_action . '&' . $page_info); ?>" method="post">
		<div class="table-responsive">
		<table class="table">
		<thead>	
          <tr>
            <td><?php echo TABLE_HEADING_ID; ?></td>
            <td><?php echo TABLE_HEADING_PRODUCT; ?></td>
            <td><?php echo TABLE_HEADING_OPT_NAME; ?></td>
            <td><?php echo TABLE_HEADING_OPT_VALUE; ?></td>
            <td class="text-right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?></td>
            <td class="text-center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?></td>
            <td class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?></td>
          </tr>
		 </thead>
          
<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if (($action == 'update_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="smallText">&nbsp;<select name="products_id" class="form-control">
<?php
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['products_id'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      } 
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id">
<?php
      $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if ($attributes_values['options_id'] == $options_values['products_options_id']) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
        }
      } 
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
      $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . $languages_id . "' order by products_options_values_name");
      while($values_values = tep_db_fetch_array($values)) {
        if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" SELECTED>' . $values_values['products_options_values_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
        }
      } 
?>        
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $attributes_values['options_values_price']; ?>" size="6">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'default') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info),'default'); ?>&nbsp;</td>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount 
                              from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " 
                              where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $products_attributes_filename = $download['products_attributes_filename'];
          $products_attributes_maxdays  = $download['products_attributes_maxdays'];
          $products_attributes_maxcount = $download['products_attributes_maxcount'];
        }
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="5">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      }
?>
<?php
    } elseif (($action == 'delete_product_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="smallText">&nbsp;<strong><?php echo $attributes_values["products_attributes_id"]; ?></strong>&nbsp;</td>
            <td class="smallText">&nbsp;<strong><?php echo $products_name_only; ?></strong>&nbsp;</td>
            <td class="smallText">&nbsp;<strong><?php echo $options_name; ?></strong>&nbsp;</td>
            <td class="smallText">&nbsp;<strong><?php echo $values_name; ?></strong>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<strong><?php echo $attributes_values["options_values_price"]; ?></strong>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<strong><?php echo $attributes_values["price_prefix"]; ?></strong>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $HTTP_GET_VARS['attribute_id'] . '&' . $page_info), 'default') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info),'default'); ?>&nbsp;</td>
<?php
    } else {
?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<?php echo $attributes_values["options_values_price"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&' . $page_info),'default') . tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&' . $page_info),'default'); ?>&nbsp;</td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if ($action != 'update_attribute') {
?>

          <tr class="bg-eee <?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
      	    <td class="smallText">&nbsp;<select name="products_id" class="form-control">
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
    } 
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id" class="form-control">
<?php
    $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
    while ($options_values = tep_db_fetch_array($options)) {
      echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
    } 
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id" class="form-control">
<?php
    $values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "' order by products_options_values_name");
    while ($values_values = tep_db_fetch_array($values)) {
      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
    } 
?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" class="form-control" placeholder="Value Price">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="price_prefix" class="form-control" placeholder="prefix">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_draw_button_bs(IMAGE_INSERT, 'plus','primary'); ?>&nbsp;</td>
          </tr>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="5">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'class="form-control"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'class="form-control"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'class="form-control"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      } // end of DOWNLOAD_ENABLED section
?>
<?php
  }
?>

        </table></div></form>
	</div>
</div>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"></td>
<!-- option value eof //-->
      </tr> 
<!-- products_attributes //-->  
      <tr>
        <td class="smallText">&nbsp;</td>
      </tr>
    
      <tr>

        <td>
        </td>
      </tr>
    </table>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
