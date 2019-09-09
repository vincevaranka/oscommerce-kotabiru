<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFORMATION);

 require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php');
  if(!empty($HTTP_GET_VARS['iID'])) {
	$infor_query = tep_db_query("select * from ".TABLE_INFORMATION." where info_id = '".(int)$HTTP_GET_VARS['iID']."'");
	$infor = tep_db_fetch_array($infor_query);
	 $breadcrumb->add($infor['info_title'], tep_href_link(FILENAME_INFORMATION));
?>
<div class="content">
<div class="breadcrumb"><?php echo  $breadcrumb->trail(' &raquo; '); ?> </div>
<h1><?php echo $infor['info_title']; ?></h1>
<div class="contentText">
<?php echo $infor['info_content'];?>
</div>
<?php echo tep_draw_button_booth(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT),'default');?>
</div>
<?php
 } else {
?>
	<div class="content">Information not Available</div>
<?php 
 
 }
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
