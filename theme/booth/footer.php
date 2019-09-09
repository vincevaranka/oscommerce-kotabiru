<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>

<?php
	if($oscTemplate->hasBlocks('boxes_column_bottom'))
	{echo $oscTemplate->getBlocks('boxes_column_bottom');}
	
?>

<footer id="footer">
<?php 
	if ($oscTemplate->hasBlocks('boxes_column_footer')) 
	{echo $oscTemplate->getBlocks('boxes_column_footer');}
?>

</div>
</footer> 



<script type="text/javascript">
$('.productListTable tr:nth-child(even)').addClass('alt');
</script>
