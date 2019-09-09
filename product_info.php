<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php'); 
  
?>

<?php
  if ($product_check['total'] < 1) {
?>

<div class="container">
<div class="breadcrumb"><?php echo  $breadcrumb->trail(' &raquo; '); ?> </div>
  <div class="contentText">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>

<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><small>[' . $product_info['products_model'] . ']</small>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>



<div class="content">
<div class="breadcrumb"><?php echo  $breadcrumb->trail(' &raquo; '); ?> </div>
<h1 class="prod-head"><?php echo $products_name; ?></h1>
<div class="row " id="prod-info">

<script>
jQuery(function(){
	$('.my-foto').imagezoomsl({ 
		 zoomrange: [2, 2],
		 zoomstart: 1,
		 innerzoom: true,
		 magnifierborder: "5px solid #01b3df"	
	});  
	$('.colorbox').colorbox({
			overlayClose: true,
			opacity: 0.8,
			maxHeight: 550,
			maxWidth: 550,
			width:'100%'
		});
/******** ================= ********/

});

</script>
	<div class="col-sm-6 prod-info" >
		<?php
    if (tep_not_null($product_info['products_image'])) {
      $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
      if (tep_db_num_rows($pi_query) > 0) {
	  $pos = true;
	  
?>
<div id="piGal" class="pigal1">
      <ul>

<?php
		$pi_counter = 0;
        while ($pi = tep_db_fetch_array($pi_query)) {
			 $pi_counter++;
		$pi_entry = '<li><a class="colorbox" href="';
		 if (tep_not_null($pi['htmlcontent'])) {
            $pi_entry .= '#piGalimg_' . $pi_counter.'">';
          } else {
            $pi_entry .= tep_href_link(DIR_WS_IMAGES_PRODUCT . $pi['image'], '', 'NONSSL', false).'">';
          }
		$pi_entry .= tep_image(DIR_WS_IMAGES_PRODUCT . $pi['image'], addslashes($pi['products_name']), '93%', '100%', 'data-large="'.DIR_WS_IMAGES_PRODUCT . $pi['image'].'" class="my-foto"');
          $pi_entry .= '</a>';
		  if (tep_not_null($pi['htmlcontent'])) {
            $pi_entry .= '<div style="display: none; margin"><div id="piGalimg_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div></div>';
          }

		$pi_entry .='</li>';
          echo $pi_entry;
        }
?>

      </ul>

    </div>


<script type="text/javascript">
$('#piGal ul').bxGallery({thumbwidth: <?php echo (($pi_counter > 1) ? '75' : '0'); ?>,
  thumbcontainer:300,load_image: 'ext/jquery/bxGallery/spinner.gif'});
</script>
<?php
      } else {
?>
	<div id="piGal" ><a href="<?php echo DIR_WS_IMAGES_PRODUCT . $product_info['products_image'];?>" class="colorbox">
		<?php echo tep_image(DIR_WS_IMAGES_PRODUCT . $product_info['products_image'], addslashes($product_info['products_name']), '100%', '100%', 'data-large="'.DIR_WS_IMAGES_PRODUCT . $product_info['products_image'].'" class="my-foto"');?></a>


	</div>

<?php
      }
    }
	if ($product_info['products_quantity'] == 0) { $products_stock = '<span class="label label-danger">empty</span>'; } else { $products_stock = '<span class="label label-success">Ready</span>'; }
	if($product_info['products_call']=='1') {$products_stock = '<span class="label label-default">Pre-Order</span>'; } 
	?>
		 
	</div>
	<div class="col-sm-6" >
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td>Brand</td>
					<td><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'manufacturers_id='.$product_info['manufacturers_id']);?>"><?php echo tep_get_manufacturers_name($product_info['manufacturers_id'],1);?></a></td>
				<tr>
				<tr>
					<td>ID</td>
					<td> <?php echo $product_info["products_id"];?></td>
				<tr>
				<tr>
					<td>Status</td>
					<td> <?php echo $products_stock; ?></td>
				<tr>
				<tr>
					<td>Promo</td>
					<td><span class="label label-warning">SALE</span> <span class="label label-info">BEST</span></td>
				<tr>
			</tbody>
		</table>
		<div class="price">Price : <?php echo $products_price; ?></div>
		<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>

    <p><?php echo TEXT_PRODUCT_OPTIONS; ?></p>

    <p>
<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }

        if (is_string($HTTP_GET_VARS['products_id']) && isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
?>
      <strong><?php echo $products_options_name['products_options_name'] . ':'; ?></strong><br /><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?><br />
<?php
      }
?>
    </p>

<?php
    }
?>
		<?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button_booth(IMAGE_BUTTON_IN_CART, 'shopping-cart', null, 'primary'); ?>
	</div>
</div>

<div class="description">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#pane1" data-toggle="tab">Description</a></li>
    <li><a href="#pane2" data-toggle="tab">Reviews (<?php echo tep_count_reviews_product($product_info["products_id"]);?>)</a></li>
  </ul>
  <div class="tab-content">
    <div id="pane1" class="tab-pane active">
      <div>
			 <?php echo stripslashes($product_info['products_description']);?>
	  </div>
    </div>
    <div id="pane2" class="tab-pane">
      <div>
			<?php
		$reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1 order by r.reviews_id desc";
		$reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
		if ($reviews_split->number_of_rows > 0) {
			if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
	?>
	<div class="contentText">
		<p style="float: right;"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></p>
		<p><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></p>
	</div>
  <br />
  <?php
    }
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
	?>
 <div class="product-review ui-corner-all">
  <div class="contentText">
   <div class="added"><?php echo tep_date_long($reviews['date_added']); ?></div>
   <?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br />') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br />'.tep_get_stars($reviews['reviews_rating']); ?>
   
   <span style="float:right">
   <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</a>'; ?>
  </span>
  </div>
</div>
<?php
    }
  } else {
?>

  <div class="contentText">
    <?php echo TEXT_NO_REVIEWS; ?>
	<br />
	<br />
  </div>

<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>



<?php
  }
  
?>
  <div class="contentText">
   <?php echo tep_draw_button_booth(IMAGE_BUTTON_WRITE_REVIEW, 'edit', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()), 'default');?>
   <?php echo tep_draw_button_booth(IMAGE_BUTTON_REVIEWS_SEE_ALL . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : ''), 'search', tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()),'default'); ?>
  </div>
	  
	  
	  
	  </div>
    </div>
   
  </div><!-- /.tab-content -->
</div><!-- /.tabbable -->





<hr />
<?php
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
?>



</form>

<?php
  }
?>
</div>

<?php
  require(DIR_WS_THEME.THEME_DEFAULT . '/template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
