<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if (isset($HTTP_GET_VARS['products_id'])) {
    $orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
      $counter = 0;
      $col = 0;

      $also_pur_prods_content = '<div class="row">';
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
          $also_pur_prods_content .= '<div class="col-md-3 col-xs-6 product-grid">';
    
        $also_pur_prods_content .= '
			<div class="col">
			<div class="image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES_PRODUCT . $orders['products_image'], $orders['products_name']) . '</a></div>
			
			<div class="name"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div>';

        

       
          $also_pur_prods_content .= '</div></div>';

       
      }

      $also_pur_prods_content .= '</div>';
?>

  <br />

  <div class="ui-widget infoBoxContainer">
    <div class="ui-widget-header ui-corner-top infoBoxHeading">
      <span><?php echo TEXT_ALSO_PURCHASED_PRODUCTS; ?></span>
    </div>

    <?php echo $also_pur_prods_content; ?>
  </div>

<?php
    }
  }
?>
