<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_BEST);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_BEST));
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php'); 
?>



<div class="content">
<div class="breadcrumb"><?php echo  $breadcrumb->trail(' &raquo; '); ?> </div>
<h1 class="text-center"><?php echo HEADING_TITLE; ?></h1>
 
<?php
   $specials_query_raw = "select p.products_id, p.products_quantity, p.products_ordered, pd.products_name, pd.products_description, p.products_image, p.products_tax_class_id, p.products_best, p.products_price, p.products_tax_class_id, p.products_date_added, p2c.products_id,p2c.categories_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on (p.products_id = p2c.products_id),  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_best = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered DESC";
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);

  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
	<div class="row paging">
		<div class="col-md-6 display-count">
			<?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $specials_new_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
		</div>
		
    </div>


<?php
  }
?>

<div class="row">
<?php
    $row = 0;
    $specials_query = tep_db_query($specials_split->sql_query);
    while ($specials = tep_db_fetch_array($specials_query)) {
	if ($new_price = tep_get_products_special_price($specials['products_id'])) {
						$products_price = '<del>' . $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</del> ' . $currencies->display_price($new_price, tep_get_tax_rate($specials['products_tax_class_id'])) . '';
						$sale = '<span class="icon-sale"></span>';
						} else {
						$sale = '';
						$products_price = $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id']));
						}
	$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$specials['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
?>	
	 <div class="col-md-4 col-sm-4 col-xs-12 product-grid btn-act text-center">
		<div class="col">
        <div class="image">
			<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) ;?>"><span class="icon-star"></span><?php echo $sale;?><?php echo tep_image(DIR_WS_IMAGES_PRODUCT . $specials['products_image'], $specials['products_name']);?></a>
			<div class="animate-me <?php echo $go;?>">
			<?php
					if(!empty($image_add)) { echo tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $bestseller['products_name'],'','class="img-me"');
					};
			?>			
			<div class="btn-view"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']);?>"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="<?php echo tep_href_link(FILENAME_PRODUCTS_BEST, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']);?>"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
		</div>
		<div class="name">
			<?php echo '<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' . $specials['products_name'].'</a>';?>
		</div>

			<div class="price">
			<?php echo $products_price;?>
			</div>
			<div class="product-btn text-center">
				<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']);?>"  class="btn"><i class="fa fa-search"></i></a>
				<?php echo tep_draw_button_booth('', 'shopping-cart', tep_href_link(FILENAME_PRODUCTS_BEST, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id'])); ?>
			</div>

		</div>
	</div>

<?php
    
    }
?>
</div>

<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

    <br />

   <div class="row paging">
		<div class="col-md-6 display-count">
			<?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $specials_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
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
