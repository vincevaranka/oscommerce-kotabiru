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
	  case 'save': 
		$sql_data_array = array('b_title' => tep_db_prepare_input($HTTP_POST_VARS['b_title']),
								'b_url' => tep_db_prepare_input($HTTP_POST_VARS['b_url']),
								'b_image' => tep_db_prepare_input($HTTP_POST_VARS['b_image']),
								'b_status' => ((isset($HTTP_POST_VARS['b_status'])) ? '1' : ''),
								'b_pos' => tep_db_prepare_input($HTTP_POST_VARS['b_pos']));
		$b_image = new upload('b_image');
		$b_image->set_destination(DIR_FS_CATALOG_IMAGES.'banners/');
		if($b_image->parse() && $b_image->save()) {
			$sql_data_array['b_image'] = tep_db_prepare_input($b_image->filename);
		}
		if($action == 'insert') {
			tep_db_perform(TABLE_BANNER,$sql_data_array);
		} else if($action == 'save') {
			$b_id = tep_db_prepare_input($HTTP_GET_VARS['bID']);
			tep_db_perform(TABLE_BANNER,$sql_data_array,'update',"b_id = '".(int)$b_id."'");
		}
		if (USE_CACHE == 'true') {
          tep_reset_cache_block('banner');
        }
        tep_redirect(tep_href_link(FILENAME_BANNER));
        break;
      case 'deleteconfirm':
        $b_id = tep_db_prepare_input($HTTP_GET_VARS['bID']);
        tep_db_query("delete from " . TABLE_BANNER . " where b_id = '" . (int)$b_id . "'");
        tep_redirect(tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }
  $pos = array('1' => '2 Column Position', '2' => '3 Column Position');
  require(DIR_WS_INCLUDES . 'template_top.php');
   $pos_query = tep_db_query("select pb_name, pb_pos, pb_status from ".TABLE_BANNER_POS );
			 while($pos = tep_db_fetch_array($pos_query)){
				$pos_array[] = array('id' => $pos['pb_pos'],
									'text' => $pos['pb_name'].(($pos['pb_status'] == '1') ? ' (active)' : ' (innactive)'));
 			}
?>
<div class="sub-content">
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="row">
	<div class="col-md-9 main-content">
		<table class="table">
		<thead>
              <tr>
                <td><?php echo TEXT_BANNER_TITLE; ?></td>
                <td><?php echo TEXT_BANNER_URL; ?></td>
				<td><?php echo TEXT_BANNER_PLACEMENT;?></td>
                <td class="text-right"><?php echo TEXT_BANNER_STATUS; ?></td>
				 <td class="text-right"><?php echo TEXT_BANNER_SORT; ?></td>
				  <td class="text-right"><?php echo TEXT_BANNER_ACTION; ?></td>
              </tr>
		</thead>
				<?php
				  $banner_query_row = "select b_id, b_title, b_url, b_image, b_status, b_sort, b_pos from " . TABLE_BANNER . " order by b_pos ASC, b_sort ASC";
				  $banner_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $banner_query_row,$banner_query_numrows);
				  $banner_query = tep_db_query($banner_query_row);
				  while ($banner = tep_db_fetch_array($banner_query)) {
					if ((!isset($HTTP_GET_VARS['bID']) || (isset($HTTP_GET_VARS['bID']) && ($HTTP_GET_VARS['bID'] == $banner['b_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
					  $bInfo = new objectInfo($banner);
					}

					if (isset($bInfo) && is_object($bInfo) && ($banner['b_id'] == $bInfo->b_id)) {
					  echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->b_id . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banner['b_id']) . '\'">' . "\n";
					}
					$place = $banner['b_pos'];

				?>
                <td><?php echo $banner['b_title']; ?></td>
				<td><?php echo $banner['b_url'];?></td>
				<td><?php echo $pos_array[$place-1]['text'];?>
                <td class="text-right">
					<?php
									if ($banner['b_status'] == '1') {
										echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 20, 20) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNER, 'action=setflag&flag=0&bID=' . $banner['b_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 20, 20) . '</a>';
									  } else {
										echo '<a href="' . tep_href_link(FILENAME_BANNER, 'action=setflag&flag=1&bID=' . $banner['b_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 20, 20) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 20, 20);
									  }
					?>
				</td>
				<td class="text-right"><?php echo $banner['b_sort'];?></td>
                <td class="text-right"><?php if (isset($bInfo) && is_object($bInfo) && ($banner['font_id'] == $bInfo->font_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banner['font_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
				<?php
				  }
				?>
			 </table>
			 <div class="row">
				<div class="col-xs-6">
					<?php echo $banner_split->display_count($banner_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?>
				</div>
				<div class="col-xs-6 text-right">
					<?php echo $banner_split->display_links($banner_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>
				</div>
			 </div>
			 <div class="row text-right">
				<div class="col-md-12">
				<?php
				  if (empty($action)) {
				?>
								<?php echo tep_draw_button_bs(TEXT_NEW_BANNER, 'plus', tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&action=new')); ?>
                 
					<?php
					  }
					?>
				</div>
			 </div>
	</div>
	<div class="col-md-3">
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
     
      <tr>

			<?php
			
				
			  $heading = array();
			  $contents = array();
			  $poss = $bInfo->b_pos;
			  switch ($action) {
				case 'new':
				  $heading[] = array('text' => '<strong>' . TEXT_NEW_BANNER . '</strong>');
				  $contents = array('form' => tep_draw_form('banner', FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert','post', 'enctype="multipart/form-data"'));
				  $contents[] = array('text' => '<br />' . TEXT_BANNER_TITLE . '<br />' . tep_draw_input_field('b_title','','class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_BANNER_URL . '<br />' . tep_draw_input_field('b_url','','class="form-control"'));
				  $contents[] = array('text' => TEXT_INFO_IMAGE.' : <br />'.tep_draw_file_field('b_image'));
				  $contents[] = array('text' => '<br />' .tep_draw_checkbox_field('b_status').  TEXT_BANNER_STATUS );
				  $contents[] = array('text' => TEXT_BANNER_PLACEMENT.': <br />'.tep_draw_pull_down_menu('b_pos', $pos_array, '', 'onchange="updateGross()" class="form-control "').'<br />'.TEXT_BANNER_INFO_PLACEMENT);
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page']),'primary'));
				  break;
				case 'edit':
				  $heading[] = array('text' => '<strong>' . TEXT_EDIT_BANNER . '</strong>');
				  $contents = array('form' => tep_draw_form('banner', FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&action=save','post', 'enctype="multipart/form-data"'));
				  $contents[] = array('text' => '<br />' . TEXT_BANNER_TITLE . '<br />' . tep_draw_input_field('b_title',$bInfo->b_title,'class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_BANNER_URL . '<br />' . tep_draw_input_field('b_url',$bInfo->b_url,'class="form-control"'));
				  $contents[] = array('text' => '<br />' . TEXT_BANNER_IMAGE.'<br />' .tep_image(DIR_WS_CATALOG_IMAGES.'/banners/'.$bInfo->b_image,'', '','','style="width:100%"'));
				  $contents[] = array('text' => TEXT_BANNER_CHANGE_IMAGE.' : <br />'.tep_draw_file_field('b_image'));
				  $contents[] = array('text' => '<br />' .tep_draw_checkbox_field('b_status',$bInfo->b_status,(($bInfo->b_status=='1') ? TRUE : FALSE)).  TEXT_BANNER_STATUS );
				  $contents[] = array('text' => TEXT_BANNER_PLACEMENT.': <br />'.tep_draw_pull_down_menu('b_pos', $pos_array, $pos_array[$poss-1]['id'], 'onchange="updateGross()" class="form-control "').'<br />'.TEXT_BANNER_INFO_PLACEMENT);
				 $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BANNER, 'bID=' . $HTTP_GET_VARS['bID']),'primary'));
				 break;
				case 'delete':
				  $heading[] = array('text' => '<strong>' . TEXT_DELETE_BANNER . '</strong>');
				  $contents = array('form' => tep_draw_form('banner', FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->b_id . '&action=deleteconfirm'));
				  $contents[] = array('text' => TEXT_INFO_DELETE_BANNER);
				  $contents[] = array('text' => '<br /><strong>' . $bInfo->b_id . '</strong>');
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->b_id),'primary'));
				  break;
				default:
				  if (is_object($bInfo)) {
					$heading[] = array('text' => '<strong>' . $bInfo->b_title . '</strong>');

					$contents[] = array('align' => 'center', 'text' => tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->b_id . '&action=edit')) . tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_BANNER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->b_id . '&action=delete'),'primary'));
					$contents[] = array('text' => '<br />' . TEXT_BANNER_TITLE . '<br />' . $bInfo->b_title);
					$contents[] = array('text' => '<br />' . TEXT_BANNER_URL . ' ' . $bInfo->b_url);
					$contents[] = array('text' => '<br />' . TEXT_BANNER_IMAGE.'<br />' .tep_image(DIR_WS_CATALOG_IMAGES.'/banners/'.$bInfo->b_image,'', '','','style="width:100%"'));
					$contents[] = array('text' => '<br />' . TEXT_BANNER_STATUS . ': ' . (($bInfo->b_status=='1')? 'Active':'Innactive'));
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
