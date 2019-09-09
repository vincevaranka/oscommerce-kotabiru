
<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $listing_split = new splitPageResults($listing_sql, $limit, 'p.products_id');
//KOTABIRU CUSTOM START
isset($HTTP_GET_VARS['page'])?$curPage = $HTTP_GET_VARS['page']:'';
if(isset($HTTP_GET_VARS['filter_id'])) { $mID = tep_db_prepare_input($HTTP_GET_VARS['filter_id']); $filterM = 'filter_id='.$mID.'&'; } else { $filterM = ''; }
if(isset($HTTP_GET_VARS['cPath'])) { $filterM = 'cPath='.$HTTP_GET_VARS['cPath'].'&'; }
isset($HTTP_GET_VARS['manufacturers_id'])?$mID = tep_db_prepare_input($HTTP_GET_VARS['manufactures_id']):'';
if(strstr(curPageURL(),"?")) { $link = tep_limiter_word(curPageURL(),"?");} else {$link = curPageURL();}
$linked = basename($PHP_SELF);
$current_sort = $HTTP_GET_VARS['sort'];
//KOTABIRU CUSTOM END
?>

<?php
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
    <div class="row paging">
		<div class="col-md-6 display-count">
			<?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $listing_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
		</div>
    </div>
<?php
  }

  

  if ($listing_split->number_of_rows > 0) {
  if(!isset($HTTP_GET_VARS['keywords'])){
  ?>
  <!--kotabiru.com custom -->
  
 <div class="row product-filter">
          <div class="col-sm-6 limit"><b>Show:</b>
            <select id="limitSelect">
              <option value="<?php echo $link.'?'.$filterM.'limit=6';?>" <?php echo $limit=='6'?'selected':'';?>>6</option>
              <option value="<?php echo $link.'?'.$filterM.'limit=12';?>" <?php echo $limit=='12'?'selected':'';?>>12</option>
              <option value="<?php echo $link.'?'.$filterM.'limit=24';?>" <?php echo $limit=='24'?'selected':'';?>>24</option>
            </select>
          </div>
          <div class="col-sm-6 text-right"><b>Sort By:</b>
            <select id="sortSelect">
			<option value="" <?php empty($current_sort) ? 'selected' : '';?> placeholder="-select-"> Choose</option>
			<option value="<?php echo $link.'?'.$filterM.'sort=2a&amp;page='.$curPage;?>" <?php echo  $current_sort=='2a' ? 'selected' :  '';?>><?php echo TEXT_SORT_A_TO_Z;?></option>
			<option value="<?php echo $link.'?'.$filterM.'sort=2d&amp;page='.$curPage;?>" <?php echo  $current_sort=='2d' ? 'selected' :  '';?>><?php echo TEXT_SORT_Z_TO_A;?></option>
			<option value="<?php echo $link.'?'.$filterM.'sort=3a&amp;page='.$curPage;?>" <?php echo  $current_sort=='3a' ? 'selected' :  '';?>><?php echo TEXT_SORT_LOWEST_PRICE;?></option>
			<option value="<?php echo $link.'?'.$filterM.'sort=3d&amp;page='.$curPage;?>" <?php echo  $current_sort=='3d' ? 'selected' :  '';?>><?php echo TEXT_SORT_HIGHEST_PRICE;?></option>
			</select>
          </div>
        </div>
<!--kotabiru.com custom -->
  <?php
  }
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);

    $prod_list_contents .= '  <div class="row">';

    while ($listing = tep_db_fetch_array($listing_query)) {
		$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$listing['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
      $rows++;
		
	 // if ($rows % 3 == 0){ $prod_list_contents .= '<div class="clearfix visible-xs-block"></div>';} 
      $prod_list_contents .= '<!-- Add the extra clearfix for only the required viewport -->
		<div class="col-md-4 col-sm-4 col-xs-12 product-grid btn-act text-center">   <div class="col">';

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $prod_list_contents .= '        <div>' . $listing['products_model'] . '</div>';
            break;
          case 'PRODUCT_LIST_NAME':
            if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
              $prod_list_contents .= '        <div class="name"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a></div>';
            } else {
              $prod_list_contents .= '        <div class="name"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a></div>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $prod_list_contents .= '        <div class="name"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a></td>';
            break;
         
          case 'PRODUCT_LIST_QUANTITY':
            $prod_list_contents .= '        <div class="qty">' . $listing['products_quantity'] . '</div>';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $prod_list_contents .= '        <div class="weight">' . $listing['products_weight'] . '</div>';
            break;
          case 'PRODUCT_LIST_IMAGE':
			 if (tep_not_null($listing['specials_new_products_price'])) {$sale = '<span class="icon-sale"></span>';} else { $sale = '';}
            if (isset($HTTP_GET_VARS['manufacturers_id'])  && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
              $prod_list_contents .= '<div class="image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES_PRODUCT . $listing['products_image'], $listing['products_name']) . '</a></div>';
            } else {
              $prod_list_contents .= '        <div class="image"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $sale . (($listing['products_best']) ? '<span class="icon-star"></span>':'').tep_image(DIR_WS_IMAGES_PRODUCT . $listing['products_image'], $listing['products_name']) . '</a>
			  <div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $prod_list_contents .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $listing['products_name'],'','class="img-me"');
					}
								
				$prod_list_contents .='<div class="btn-view"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '"><i class="glyphicon glyphicon-search"></i></a></div>
								<div class="btn-buy"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']).'"><i class="glyphicon glyphicon-shopping-cart"></i></a></div>
				</div>
				
			  </div>';
            }
            break;
	
		  case 'PRODUCT_LIST_PRICE':
            if (tep_not_null($listing['specials_new_products_price'])) {
              $prod_list_contents .= '<div class="price"><del>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</del>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span></div>';
            } else {
              $prod_list_contents .= ' <div class="price">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</div>';
            }
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $prod_list_contents .= '<div class="product-btn text-center"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']).'"  class="btn"><i class="fa fa-search"></i></a>' . tep_draw_button_booth('', 'shopping-cart', tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id'])) . '</div>';
            break;
	
        }
      }

      $prod_list_contents .= '</div></div>';
    }

    $prod_list_contents .= '</div>';

    echo $prod_list_contents;
  } else {
?>

    <p><?php echo TEXT_NO_PRODUCTS; ?></p>

<?php
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>


    <div class="row paging">
		<div class="col-sm-6 display-count">
			<?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
		</div>
		<div class="col-sm-6 text-right">
			<?php echo $listing_split->display_links_booth(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
		</div>
    </div>

<?php
  }
?>

<script>
		$(function(){
		// bind change event to select
			$('#sortSelect').bind('change', function () {
				var url = $(this).val(); // get selected value
				if (url) { // require a URL
				window.location = url; // redirect
			}
			return false;
			});
			});
		$(function(){
		// bind change event to select
			$('#limitSelect').bind('change', function () {
				var url = $(this).val(); // get selected value
				if (url) { // require a URL
				window.location = url; // redirect
			}
			return false;
			});
			});
		</script>