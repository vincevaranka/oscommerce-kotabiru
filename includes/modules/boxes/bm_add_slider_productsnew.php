<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_slider_productsnew {
    var $code = 'bm_add_slider_productsnew';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_slider_productsnew() {
      $this->title = MODULE_BOXES_SLIDER_PRODUCTSNEW_TITLE;
      $this->description = MODULE_BOXES_SLIDER_PRODUCTSNEW_DESCRIPTION;

      if ( defined('MODULE_BOXES_SLIDER_PRODUCTSNEW_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SLIDER_PRODUCTSNEW_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SLIDER_PRODUCTSNEW_STATUS == 'True');
        $this->pages = MODULE_BOXES_SLIDER_PRODUCTSNEW_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_SLIDER_PRODUCTSNEW_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $cart,$cPath,$HTTP_GET_VARS, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '	
			<div class="wrap-wide bg-prod-new">
				<script>$(document).ready(function(){var owl = $("#prod-slider1");owl.owlCarousel({
					autoPlay : 3000,
					
				stopOnHover : true,
				  items : 4,
				  itemsDesktop : [1000,3],
				  itemsDesktopSmall : [900,3], 
				  itemsTablet: [600,1], 
				  pagination:false,
				  itemsMobile : false 
			  });
			  $(".next").click(function(){
				owl.trigger(\'owl.next\');
			  })
			  $(".prev").click(function(){
				owl.trigger(\'owl.prev\');
			  })
			});</script>
			<div class="container ps-cont bg-in-prod-new text-center">
				<div class="ps-cont-header"><h2>New Product</h2></div>
				<hr class="hr-ps-cont"/>
				<div id="prod-slider1" class="ps-pad">';
					$products_new_query = tep_db_query("select p.products_id, p.products_quantity,pd.products_name,pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_best, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name limit 8");
					while($products_new = tep_db_fetch_array($products_new_query)) {
					if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
						$products_price = '<del>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</del> <span class="price">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
						$sale = '<span class="icon-star"></span>';
						} else {
						$sale = '';
						$products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
						}
					 $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_new['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
		$data .= '		
					<div class="item">
						<div class="prod-grid btn-act">
							<div class="prod-image"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']).'" >'.(($products_new['products_best']) ? '<span class="icon-star"></span>':''). $sale .tep_image(DIR_WS_IMAGES_PRODUCT . $products_new['products_image'], $products_new['products_name']).'</a>
							<div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $data .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $products_new['products_name'],'','class="img-me"');
					}
								
		$data .= '<div class="btn-view"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']).'"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id']).'"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
							</div>
							<div class="prod-name"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']).'">'.$products_new['products_name'].'</a></div>
						
								<div class="prod-price">'.$products_price.'</div>
								<div class="prod-btn text-center"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new['products_id']).'"  class="btn"><i class="fa fa-search"></i></a><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id']).'" class="btn"><i class="fa fa-shopping-cart"></i></a></div>
			
						</div>
					</div>';
				
		}
		$data .= '
				</div>
				<div class="customNavigation ps-nav-pos-next"><a class="btn next btn-ps"><i class="fa fa-chevron-right"></i></a></div>
				<div class="customNavigation ps-nav-pos-prev"><a class="btn prev btn-ps"><i class="fa fa-chevron-left"></i></a></div>
				</div>
				</div>';	
		}
	
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SLIDER_PRODUCTSNEW_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	   tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('New Products Slider','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','slider-pn-out','.bg-prod-new','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','slider-pn-in','.bg-in-prod-new','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_SLIDER_PRODUCTSNEW_STATUS', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_CONTENT_PLACEMENT', 'MODULE_BOXES_SLIDER_PRODUCTSNEW_SORT_ORDER','MODULE_BOXES_SLIDER_PRODUCTSNEW_DISPLAY_PAGES');
    }
  }
  
?>