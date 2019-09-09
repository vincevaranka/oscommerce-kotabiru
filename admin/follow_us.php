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
	  case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['fID'])) {
            tep_set_fs_status($HTTP_GET_VARS['fID'], $HTTP_GET_VARS['flag']);
          }
        }
        tep_redirect(tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $HTTP_GET_VARS['fID']));
       break;
	   
      case 'insert':
	  case 'save':
		$fs_link = tep_db_prepare_input($HTTP_POST_VARS['fs_link']);
		$status = ((isset($HTTP_GET_VARS['fs_status'])) ? '1' : '0');
		$fs_sort = tep_db_prepare_input($HTTP_POST_VARS['fs_sort']);
		$sql_data_array = array('fs_link' => $fs_link,'fs_status' => $status,'fs_sort' => $fs_sort);
		if($action == 'insert') {
			tep_db_perform(TABLE_FOLLOW_US,$sql_data_array);
		} else if($action == 'save') {
			tep_db_perform(TABLE_FOLLOW_US, $sql_data_array, 'update', "fs_id = '".(int)$HTTP_GET_VARS['fID']."'");
		}
		tep_redirect(tep_href_link(FILENAME_FOLLOW_US));
        break;
      
      case 'deleteconfirm':
		$info_id = tep_db_prepare_input($HTTP_GET_VARS['fID']);
        tep_db_query("delete from " . TABLE_INFORMATION . " where info_id = '" . (int)$info_id . "'");
		tep_db_query("delete from " . TABLE_INFORMATION_DESCRIPTION." where info_id = '".(int)$info_id."'");
        tep_redirect(tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="sub-content">
<h1><?php echo  HEADING_TITLE;?></h1>
	<div class="col-md-9 main-content">
		<table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FOLLOW_US_NAME; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FOLLOW_US_LINK; ?></td>
				 <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FOLLOW_US_STATUS; ?></td>
				 <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FOLLOW_US_SORT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FOLLOW_US_ACTION; ?>&nbsp;</td>
              </tr>
				<?php
				  $fs_query_raw = "select fs_id, fs_name, fs_link, fs_icon, fs_status, fs_sort from " . TABLE_FOLLOW_US. " order by fs_status, fs_sort";
				  $fs_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $fs_query_raw, $fs_query_numrows);
				  $fs_query = tep_db_query($fs_query_raw);
				  while ($fs = tep_db_fetch_array($fs_query)) {
					if ((!isset($HTTP_GET_VARS['fID']) || (isset($HTTP_GET_VARS['fID']) && ($HTTP_GET_VARS['fID'] == $fs['fs_id']))) && !isset($fInfo) && (substr($action, 0, 3) != 'new')) {
					  $fInfo = new objectInfo($fs);
					}
					if (isset($fInfo) && is_object($fInfo) && ($fs['fs_id'] == $fInfo->fs_id)) {
					  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fs['fs_id']) . '\'">' . "\n";
					}
				?>
                <td class="dataTableContent"><?php echo $fs['fs_name']; ?></td>
				 <td class="dataTableContent"><?php echo $fs['fs_link']; ?></td>
                <td class="dataTableContent" align="center" width="40">
				<?php
									if ($fs['fs_status'] == '1') {
										echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FOLLOW_US, 'action=setflag&flag=0&fID=' . $fs['fs_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
									  } else {
										echo '<a href="' . tep_href_link(FILENAME_FOLLOW_US, 'action=setflag&flag=1&fID=' . $fs['fs_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
									  }
				?>
				</td>
				<td class="dataTableContent" align="center"><?php echo $fs['fs_sort'];?></td>
                <td class="dataTableContent" align="right"><?php if (isset($fInfo) && is_object($fInfo) && ($fs['fs_id'] == $fInfo->fs_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fs['fs_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
			
              </tr>
			<?php
			  }
			?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $fs_split->display_count($fs_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $fs_split->display_links($fs_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>

                </table></td>
              </tr>
            </table>
	</div>
	<div class="col-md-3">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"></td>
			<?php
			  $heading = array();
			  $contents = array();

			  switch ($action) {
				case 'new':
				  $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</strong>');

				  $contents = array('form' => tep_draw_form('fs', FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
				  $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
				  $contents[] = array('text' => '<br />' . TABLE_HEADING_FOLLOW_US_LINK. '<br />' . tep_draw_input_field('fs_link','','class="form-control"'));
				  $contents[] = array('text' => '<br />' . TABLE_HEADING_FOLLOW_US_SORT . '<br />' . tep_draw_input_field('fs_sort','','class="form-control"'));
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page']),'primary'));
				  break;
				case 'edit':
				  $heading[] = array('text' => '<strong>' . $fInfo->fs_name . '</strong>');
				  $contents = array('form' => tep_draw_form('fs', FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id . '&action=save'));
				  $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

				  $contents[] = array('text' => '<br />' . TABLE_HEADING_FOLLOW_US_LINK . '<br />' . tep_draw_input_field('fs_link', $fInfo->fs_link,'class="form-control"'));
				 
				   $contents[] = array('text' => '<br />' . TABLE_HEADING_FOLLOW_US_SORT . '<br />' . tep_draw_input_field('fs_sort', $fInfo->fs_sort,'class="form-control"'));
					$contents[] = array('text' => '<br />' . tep_draw_checkbox_field('fs_status',$fInfo->fs_status,(($fInfo->fs_status=='1') ? TRUE : FALSE)).' '. TEXT_FS_STATUS);
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id),'primary'));
				  break;
				case 'delete':
				  $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_INFORMATION . '</strong>');
				  $contents = array('form' => tep_draw_form('info', FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id . '&action=deleteconfirm'));
				  $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
				  $contents[] = array('text' => '<br /><strong>' . $fInfo->fs_name . '</strong>');
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id),'primary'));
				  break;
				default:
				  if (is_object($fInfo)) {
					$heading[] = array('text' => '<strong>' . $fInfo->fs_name . '</strong>');
					$contents[] = array('align' => 'center', 'text' => tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_FOLLOW_US, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->fs_id . '&action=edit'),'primary'));
					$contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_FOLLOW_US_NAME . '</strong><br />' . $fInfo->fs_name);
					$contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_FOLLOW_US_LINK . '</strong><br /> ' . $fInfo->fs_link);
					$contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_FOLLOW_US_ACTION . '</strong>' . $fInfo->fs_status);
					$contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_FOLLOW_US_SORT . '</strong> ' . $fInfo->fs_sort);
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



<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
