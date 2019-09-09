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
          if (isset($HTTP_GET_VARS['iID'])) {
            tep_set_information_status($HTTP_GET_VARS['iID'], $HTTP_GET_VARS['flag']);
          }
        }
        tep_redirect(tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $HTTP_GET_VARS['iID']));
       break;
	   
      case 'insert':
	  case 'save':
		$info_sort = tep_db_prepare_input($HTTP_POST_VARS['info_sort']);
		if(isset($HTTP_POST_VARS['status'])) { $status = '1';} else {$status = '0';}
		$languages = tep_get_languages();
		$info_id = tep_db_prepare_input($HTTP_GET_VARS['iID']);
		
		
		if($action == 'insert'){
		$sql_info_array = array('status' => $status, 'sort' => $info_sort);
			tep_db_perform(TABLE_INFORMATION, $sql_info_array);
			$info_id = tep_db_insert_id();
		} 
		
		
		
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('info_name' => tep_db_prepare_input($HTTP_POST_VARS['info_name'][$language_id]),
                                  'info_title' => tep_db_prepare_input($HTTP_POST_VARS['info_title'][$language_id]),
                                  'info_content' => tep_db_prepare_input($HTTP_POST_VARS['info_content'][$language_id])
								  );
		
		if($action == 'insert') {
		$insert_sql_data = array('info_id' => $info_id,
                                     'language_id' => $language_id);
									 
									 
			$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_INFORMATION_DESCRIPTION, $sql_data_array);
		}elseif($action == 'save'){
			tep_db_perform(TABLE_INFORMATION_DESCRIPTION, $sql_data_array, 'update', "info_id = '" . (int)$HTTP_GET_VARS['iID']. "' and language_id = '" . (int)$language_id . "'");
		}		
            
        }
			

		
		tep_redirect(tep_href_link(FILENAME_INFORMATION));
        break;
      
      case 'deleteconfirm':
		$info_id = tep_db_prepare_input($HTTP_GET_VARS['iID']);
        tep_db_query("delete from " . TABLE_INFORMATION . " where info_id = '" . (int)$info_id . "'");
		tep_db_query("delete from " . TABLE_INFORMATION_DESCRIPTION." where info_id = '".(int)$info_id."'");
        tep_redirect(tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<div class="sub-content">
<h1><?php echo  HEADING_TITLE;?></h1>
<div class="row">	
	<?php
		if($action == 'new' || $action == 'edit')
		{
			$languages = tep_get_languages();
			if($action == 'new') { $act = 'insert'; } else { 
			$act = 'save&iID='.$HTTP_GET_VARS['iID']; 
				$infor_query = tep_db_query("select i.info_id, i.status, i.sort, ii.info_name, ii.info_title, ii.info_content, ii.language_id from ".TABLE_INFORMATION." i, ".TABLE_INFORMATION_DESCRIPTION." ii where i.info_id = '".(int)$HTTP_GET_VARS['iID']."' and i.info_id = ii.info_id");
				$infor = tep_db_fetch_array($infor_query);
				if($infor['status'] == '1') {$status = TRUE;} else {$status = FALSE;}
			}
			echo tep_draw_form('info', FILENAME_INFORMATION, 'action='.$act);
	?>
		<table class="table borderless">
<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TABLE_HEADING_INFORMATION_NAME; ?></td>
            <td class="main"><?php echo tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('info_name[' . $languages[$i]['id'] . ']', (empty($infor['info_id']) ? '' : tep_get_information_name($infor['info_id'], $languages[$i]['id'])),'class="form-control"'); ?></td>
          </tr>
<?php
    }
?>
<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TABLE_HEADING_INFORMATION_TITLE; ?></td>
            <td class="main"><?php echo tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('info_title[' . $languages[$i]['id'] . ']', (empty($infor['info_id']) ? '' : tep_get_information_title($infor['info_id'], $languages[$i]['id'])),'class="form-control"'); ?></td>
          </tr>
<?php
    }
?>
<?php

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TABLE_HEADING_INFORMATION_CONTENT; ?></td>
            <td class="main"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main" width="100%"><?php echo tep_draw_textarea_field('info_content[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (empty($infor['info_id']) ? '' : tep_get_information_content($infor['info_id'], $languages[$i]['id'])),'class="ckeditor form-control"'); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>

		
			<tr>
				<td><?php echo TABLE_HEADING_INFORMATION_SORT;?></td>
				<td><?php echo tep_draw_input_field('info_sort',$infor['sort'],'style="width:100px;"');?></td>
			</tr>
			<tr><td></td><td><?php echo tep_draw_checkbox_field('status','',$status).' '.TEXT_INFORMATION_STATUS; ?> </td></tr>
			<tr><td colspan="2" > <br /><?php echo tep_draw_button_bs(IMAGE_SAVE,'save',null,'primary'). tep_draw_button_bs(IMAGE_CANCEL,'cancel',tep_href_link(FILENAME_INFORMATION));?></td></tr>
		</table>
		</form>
	<?php
		}
	?>
</div> <!--ROW TOP-->	
<div class="row">
<div class="main-content col-md-9">
	<table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFORMATION_NAME; ?></td>
				 <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_INFORMATION_STATUS; ?></td>
				 <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_INFORMATION_SORT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_INFORMATION_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $info_query_raw = "select i.info_id, id.info_name, id.info_title, i.status,i.info_pos, i.sort from " . TABLE_INFORMATION . " i, ".TABLE_INFORMATION_DESCRIPTION." id where i.info_id = id.info_id and id.language_id = '".(int)$languages_id."' order by i.status DESC, i.sort ASC";
  $info_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $info_query_raw, $info_query_numrows);
  $info_query = tep_db_query($info_query_raw);
  while ($info = tep_db_fetch_array($info_query)) {
    if ((!isset($HTTP_GET_VARS['iID']) || (isset($HTTP_GET_VARS['iID']) && ($HTTP_GET_VARS['iID'] == $info['info_id']))) && !isset($iInfo) && (substr($action, 0, 3) != 'new')) {
      $iInfo = new objectInfo($info);
    }
    if (isset($iInfo) && is_object($iInfo) && ($info['info_id'] == $iInfo->info_id)) {
      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->info_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $info['info_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $info['info_name']; ?></td>
                <td class="dataTableContent" align="center" width="40">
<?php
					if ($info['status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFORMATION, 'action=setflag&flag=0&iID=' . $info['info_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link(FILENAME_INFORMATION, 'action=setflag&flag=1&iID=' . $info['info_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
?>
				</td>
				<td class="dataTableContent" align="center"><?php echo $info['sort'];?></td>
                <td class="dataTableContent" align="right"><?php if (isset($iInfo) && is_object($iInfo) && ($info['info_id'] == $iInfo->info_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $info['info_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
			
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $info_split->display_count($info_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $info_split->display_links($info_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td class="smallText" colspan="2" align="right"><?php echo tep_draw_button_bs(IMAGE_NEW_INFO, 'plus', tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&action=new')); ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table>
</div>			
			
<div class="col-md-3">

<?php
  $heading = array();
  $contents = array();

  switch ($action) {
   
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_INFORMATION . '</strong>');

      $contents = array('form' => tep_draw_form('info', FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->info_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $iInfo->info_name . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->info_id)));
      break;
    default:
      if (is_object($iInfo)) {
        $heading[] = array('text' => '<strong>' . $iInfo->info_name . '</strong>');
        $contents[] = array('align' => 'center', 'text' => tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->info_id . '&action=edit')) . (($iInfo->info_pos >= '10') ? tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_INFORMATION, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->info_id . '&action=delete')):''));
        $contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_INFORMATION_NAME . '</strong><br />' . $iInfo->info_name);
        $contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_INFORMATION_TITLE . '</strong><br /> ' . $iInfo->info_title);
		if($iInfo->status == '1') {$status = 'Enable';} else {$status = 'Disable';}
		 $contents[] = array('text' => '<br /><strong>' . TABLE_HEADING_INFORMATION_STATUS . '</strong> : ' . $status);
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
          
	</div>
</div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
