<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
echo '</div>';
if ($oscTemplate->hasBlocks('boxes_column_right')) { echo '<div class="col-boxes-right col-pos animate"><i class="fa fa-chevron-left"></i><div class="col-md-3 content-boxes" >
'.$oscTemplate->getBlocks('boxes_column_right').'</div></div>'; }
?>
</div>
</div>


<?php require(DIR_WS_THEME.THEME_DEFAULT . '/footer.php'); ?>

<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>


</body>
</html>
