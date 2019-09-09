<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_NEW));
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php'); 
?>

<div class="content text-center">
<div class="breadcrumb"><?php echo  $breadcrumb->trail(' &raquo; '); ?> </div>
<h1><?php echo HEADING_TITLE; ?></h1>

<?php
  $products_new_array = array();

  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
  $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

    <div class="row paging">
		<div class="col-md-6 display-count">
			<?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $products_new_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
		</div>
    </div>


<?php
  }
?>

<?php
  if ($products_new_split->number_of_rows > 0) {
?>

    <div class="row">

<?php
    $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($products_new = tep_db_fetch_array($products_new_query)) {
	   $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_new['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 		
      if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
        $products_price = '<del>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
      }
?>
      <div class="col-md-4 col-sm-4 col-xs-12 product-grid btn-act text-center">
		<div class="col">
        <div class="image">
			<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '">' . tep_image(DIR_WS_IMAGES_PRODUCT . $products_new['products_image'], $products_new['products_name']) . '</a>'; ?>
			<div class="animate-me <?php echo $go;?>">
			<?php
					if(!empty($image_add)) { echo tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $bestseller['products_name'],'','class="img-me"');
					};
			?>
								
				<div class="btn-view"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']);?>"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id']);?>"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
		</div>
        <div class="nameAdded">
			<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']) . '"><strong>' . $products_new['products_name'] . '</strong></a><br />'.tep_date_long($products_new['products_date_added']) . '<br />' . TEXT_MANUFACTURER . ' ' . $products_new['manufacturers_name'] . '<br />'; ?>
		</div>
		<div class="row prod-act">
			<div class="col-xs-6 prod-price">
				<?php echo $products_price;?>
			</div>
			<div class="col-xs-5 prod-btn text-right">
				
				<?php echo tep_draw_button_booth('', 'shopping-cart', tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id'])); ?>
				
			</div>
			
		</div>
      
      </div>
	  </div>
<?php
    }
?>

    </div>

<?php
  } else {
?>

    <div>
      <?php echo TEXT_NO_NEW_PRODUCTS; ?>
    </div>

<?php
  }

  if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

    <br />

    <div class="row paging">
		<div class="col-md-6 display-count text-left">
			<?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $products_new_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
		</div>
		
    </div>

<?php
  }
?>

</div>

<?php
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
