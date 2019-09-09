<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>


<div class="container">
<div class="row" id="content">
<?php
	$basecol = '12';
	if ($oscTemplate->hasBlocks('boxes_column_left')) {$basecol = $basecol-3; echo '<div class="col-md-3 content-boxes">'.$oscTemplate->getBlocks('boxes_column_left').'</div>';}
	if ($oscTemplate->hasBlocks('boxes_column_right')) {$basecol = $basecol-3;}
?>

<div class="col-md-<?php echo $basecol;?> content-center">

<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
