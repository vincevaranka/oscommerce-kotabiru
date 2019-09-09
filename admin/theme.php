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
	  case 'save':
		$theme_query = tep_db_query("select * from ".TABLE_THEME." where t_group ='".(int)$HTTP_GET_VARS['tID']."'");
		while($theme = tep_db_fetch_array($theme_query)){
			if($theme['t_attr_1'] != ''){
				tep_db_query("update ".TABLE_THEME." set t_value='".tep_db_prepare_input($HTTP_POST_VARS[$theme['t_code']])."', t_value_1='".tep_db_prepare_input($HTTP_POST_VARS[$theme['t_code'].'-'.$theme['t_attr_1']])."' where t_id = '".$theme['t_id']."'");
			} else {
				tep_db_query("update ".TABLE_THEME." set t_value='".tep_db_prepare_input($HTTP_POST_VARS[$theme['t_code']])."' where t_id = '".$theme['t_id']."'");
			}
		}
		tep_redirect(tep_href_link(FILENAME_THEME));
        break;
	  case 'clear':
		$theme_query = tep_db_query("select * from ".TABLE_THEME." where t_group ='".(int)$HTTP_GET_VARS['tID']."'");
		while($theme = tep_db_fetch_array($theme_query)){
			tep_db_query("update ".TABLE_THEME." set t_value='', t_value_1='' where t_id='".(int)$theme['t_id']."'");
		}
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
                <td class="dataTableHeadingContent"><?php echo TEXT_THEME_NAME; ?></td>
				<td class="dataTableHeadingContent"><?php echo TEXT_THEME_INFO; ?></td>
				<td class="dataTableHeadingContent"><?php echo TEXT_THEME_MODULE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TEXT_THEME_ACTION; ?>&nbsp;</td>
              </tr>
				<?php
				  $tg_query_raw = "select t_group,tg_name, tg_info, tg_module from " . TABLE_THEME_GROUP. " order by t_group";
				  $tg_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $tg_query_raw, $tg_query_numrows);
				  $tg_query = tep_db_query($tg_query_raw);
				  while ($tg = tep_db_fetch_array($tg_query)) {
					if ((!isset($HTTP_GET_VARS['tID']) || (isset($HTTP_GET_VARS['tID']) && ($HTTP_GET_VARS['tID'] == $tg['t_group']))) && !isset($tInfo) && (substr($action, 0, 3) != 'new')) {
					  $tInfo = new objectInfo($tg);
					}
					if (isset($tInfo) && is_object($tInfo) && ($tg['t_group'] == $tInfo->t_group)) {
					  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tg['t_group']) . '\'">' . "\n";
					}
				?>
                <td class="dataTableContent"><?php echo $tg['tg_name']; ?></td>
				 <td class="dataTableContent"><?php echo $tg['tg_info']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $tg['tg_module'];?></td>
                <td class="dataTableContent" align="right"><?php if (isset($tInfo) && is_object($tInfo) && ($tg['t_group'] == $tInfo->t_group) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tg['t_group']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
			
              </tr>
			<?php
			  }
			?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $tg_split->display_count($tg_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $tg_split->display_links($tg_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
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
				case 'edit':
				  $heading[] = array('text' => '<strong>' . $tInfo->tg_name . '</strong>');
				  $contents = array('form' => tep_draw_form('tg', FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group . '&action=save'));
				  $theme_query = tep_db_query("select * from ".TABLE_THEME." where t_group = '".$tInfo->t_group."'");
				  $code = '';
				  while($theme = tep_db_fetch_array($theme_query)){
				  $contents[] = array('text'=>'
							<strong>'.$theme['t_name'].'</strong><br />'.$theme['t_attr'].'
								<div class="input-group '.$theme['t_code'].'">
									<span class="input-group-addon"><i></i></span>'. tep_draw_input_field($theme['t_code'],$theme['t_value'],'id="'.$theme['t_code'].'"').'
								</div>'.
							(($theme['t_attr_1']!='') ? '<br /><br />'.$theme['t_attr_1'].'
								<div class="input-group '.$theme['t_code'].'">
									<span class="input-group-addon"><i></i></span>'.tep_draw_input_field($theme['t_code'].'-'.$theme['t_attr_1'],$theme['t_value_1'],'id="'.$theme['t_code'].'-'.$theme['t_attr_1'].'"').'</div>':''));
				  $code .= '$(".'.$theme['t_code'].'").colorpicker({format:\'rgba\'});';
					if($theme['t_attr_1'] != '') {
						$code .= '$(".'.$theme['t_code'].'-'.$theme['t_attr_1'].'").colorpicker({format:\'rgba\'});';
					}
				  }
				  $contents[] = array('align' => 'center','text' => '<script>$(function(){'.$code.'});</script>');
				  $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group),'primary'). tep_draw_button_bs(TEXT_THEME_DEFAULT, 'refresh', tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group.'&action=clear'),'primary'));
				  break;
				
				default:
				  if (is_object($tInfo)) {
					$heading[] = array('text' => '<strong>' . $tInfo->tg_name . '</strong>');
					$contents[] = array('align' => 'center', 'text' => tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group . '&action=edit'),'primary').tep_draw_button_bs(TEXT_THEME_DEFAULT, 'refresh', tep_href_link(FILENAME_THEME, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tInfo->t_group.'&action=clear'),'primary'));
					$theme_query = tep_db_query("select * from ".TABLE_THEME." where t_group = '".$tInfo->t_group."'");
					while($theme = tep_db_fetch_array($theme_query)){
						$contents[] = array('text' => '<br /><strong>'.$theme['t_name'].'</strong><br />'. $theme['t_attr'] .': '.tep_get_color($theme['t_value']).''.(($theme['t_attr_1'] != '')? '<br /> '.$theme['t_attr_1'].': '.tep_get_color($theme['t_value_1']).'' : ''));
					}
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
