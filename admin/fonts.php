<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $font_name = tep_db_prepare_input($HTTP_POST_VARS['font_name']);
        $font_code = tep_db_prepare_input($HTTP_POST_VARS['font_code']);
        $font_use = tep_db_prepare_input($HTTP_POST_VARS['font_use']);
		if(isset($HTTP_POST_VARS['status'])) { $status = '1';} else {$status = '0';}
		if($status =='1'){
			tep_db_query("update ".TABLE_FONTS." set status='0' WHERE status='1'");
		}
        tep_db_query("insert into " . TABLE_FONTS . " (font_name, font_code, font_use, status) values ('" . tep_db_input($font_name) . "', '" . tep_db_input($font_code) . "', '" . tep_db_input($font_use) . "', '" . $status . "')");
        tep_redirect(tep_href_link(FILENAME_FONTS));
        break;
      case 'save':
        $font_id = tep_db_prepare_input($HTTP_GET_VARS['fID']);
        $font_name = tep_db_prepare_input($HTTP_POST_VARS['font_name']);
        $font_code = tep_db_prepare_input($HTTP_POST_VARS['font_code']);
        $status = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);
		if(isset($HTTP_POST_VARS['status'])) { $status = '1';} else {$status = '0';}
		if($status =='1'){
			tep_db_query("update ".TABLE_FONTS." set status='0' WHERE status='1'");
		}
        tep_db_query("update " . TABLE_FONTS . " set font_name = '" . tep_db_input($font_name) . "', font_code = '" . tep_db_input($font_code) . "', font_use = '" . tep_db_input($font_use) . "', status = '" . $status . "' where font_id = '" . (int)$font_id . "'");

        tep_redirect(tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $font_id));
        break;
      case 'deleteconfirm':
        $font_id = tep_db_prepare_input($HTTP_GET_VARS['fID']);
        tep_db_query("delete from " . TABLE_FONTS . " where font_id = '" . (int)$font_id . "'");
        tep_redirect(tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="sub-content">
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="row">
	<div class="col-md-9 main-content">
		<table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FONT_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FONT_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
				<?php
				  $fonts_query_raw = "select font_id, font_name, font_code, font_use, status from " . TABLE_FONTS . " order by status DESC, font_name ASC";
				  $fonts_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $fonts_query_raw, $fonts_query_numrows);
				  $fonts_query = tep_db_query($fonts_query_raw);
				  while ($fonts = tep_db_fetch_array($fonts_query)) {
					if ((!isset($HTTP_GET_VARS['fID']) || (isset($HTTP_GET_VARS['fID']) && ($HTTP_GET_VARS['fID'] == $fonts['font_id']))) && !isset($fInfo) && (substr($action, 0, 3) != 'new')) {
					  $fInfo = new objectInfo($fonts);
					}

					if (isset($fInfo) && is_object($fInfo) && ($fonts['font_id'] == $fInfo->font_id)) {
					  echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fonts['font_id']) . '\'">' . "\n";
					}
				?>
                <td class="dataTableContent"><?php echo $fonts['font_name']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $fonts['status'];?></td>
                <td class="dataTableContent" align="right"><?php if (isset($fInfo) && is_object($fInfo) && ($fonts['font_id'] == $fInfo->font_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fonts['font_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
				<?php
				  }
				?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $fonts_split->display_count($fonts_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $fonts_split->display_links($fonts_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
				<?php
				  if (empty($action)) {
				?>
								  <tr>
                    <td class="smallText" colspan="2" align="right"><?php echo tep_draw_button_bs(IMAGE_NEW_FONTS, 'plus', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new')); ?></td>
                  </tr>
					<?php
					  }
					?>
                </table></td>
              </tr>
            </table>
	</div>
	<div class="col-md-3">
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
     
      <tr>

			<?php
			  $heading = array();
			  $contents = array();

			  switch ($action) {
				case 'new':
				  $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</strong>');

				  $contents = array('form' => tep_draw_form('fonts', FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
				  $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_NAME . '<br />' . tep_draw_input_field('font_name','','class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_CODE . '<br />' . tep_draw_input_field('font_code','','class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_USE . '<br />' . tep_draw_input_field('font_use','','class="form-control"'));
				  $contents[] = array('text' => '<br />' .tep_draw_checkbox_field('status').  TEXT_INFO_FONT_STATUS );
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page']),'primary'));
				  break;
				case 'edit':
				  $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_EDIT_FONTS . '</strong>');
				  $contents = array('form' => tep_draw_form('fonts', FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id . '&action=save'));
				  $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_NAME . '<br />' . tep_draw_input_field('font_name', $fInfo->font_name,'class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_CODE . '<br />' . tep_draw_input_field('font_code', $fInfo->font_code,'class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_INFO_FONT_USE . '<br />' . tep_draw_input_field('font_use', $fInfo->font_use,'class="form-control"'));
				  if($fInfo->status == '0')  $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('status') . ' ' .TEXT_INFO_FONT_STATUS);
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id),'primary'));
				  break;
				case 'delete':
				  $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_FONTS . '</strong>');

				  $contents = array('form' => tep_draw_form('fonts', FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id . '&action=deleteconfirm'));
				  $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
				  $contents[] = array('text' => '<br /><strong>' . $fInfo->font_name . '</strong>');
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id),'primary'));
				  break;
				default:
				  if (is_object($fInfo)) {
					$heading[] = array('text' => '<strong>' . $fInfo->font_name . '</strong>');

					$contents[] = array('align' => 'center', 'text' => tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id . '&action=edit')) . tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_FONTS, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->font_id . '&action=delete'),'primary'));
					$contents[] = array('text' => '<br />' . TEXT_INFO_FONT_NAME . '<br />' . $fInfo->font_name);
					$contents[] = array('text' => '<br />' . TEXT_INFO_EDIT_FONT_CODE . ' ' . $fInfo->font_code);
					$contents[] = array('text' => '<br />' . TEXT_INFO_EDIT_FONT_USE . ' ' . $fInfo->font_use);
					if($fInfo->status == '1') {$status = 'Default Fonts';} else {$status = 'Not Using';}
					$contents[] = array('text' => $status);
				  }
				  break;
			  }

			  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
				echo '            <td width="25%" valign="top">' . "\n";

				$box = new box;
				echo $box->infoBox($heading, $contents);

				echo '            </td>' . "\n";
			  }
			?>
          </tr>
        </table>
	</div>
</div>
</div>
  

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
