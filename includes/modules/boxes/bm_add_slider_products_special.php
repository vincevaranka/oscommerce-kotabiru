<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_slider_products_special {
    var $code = 'bm_add_slider_products_special';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_TITLE;
      $this->description = MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_DESCRIPTION;

      if ( defined('MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_STATUS == 'True');
        $this->pages = MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $cart,$cPath,$HTTP_GET_VARS, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '	
			<div class="wrap-wide bg-slide1">
				<script>$(document).ready(function(){var owl = $("#prod-slider2");owl.owlCarousel({
				  autoPlay : 3000,
				  stopOnHover : true,
				  items : 4,
				  itemsDesktop : [1000,3],
				  itemsDesktopSmall : [900,3], 
				  itemsTablet: [600,1], 
				  pagination:false,
				  itemsMobile : false 
			  });
			  $(".next2").click(function(){
				owl.trigger(\'owl.next\');
			  })
			  $(".prev2").click(function(){
				owl.trigger(\'owl.prev\');
			  })
			});</script>
			<div class="container ps-cont bg-slide1-in">
				<h2>SALE</h2>
				<div id="prod-slider2" class="ps-pad">';
					$specials_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_best, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
				while ($specials = tep_db_fetch_array($specials_query)) {
					$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$specials['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
					if ($new_price = tep_get_products_special_price($specials['products_id'])) {
						$products_price = '<del>' . $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</del><br /><span class="price">' . $currencies->display_price($new_price, tep_get_tax_rate($specials['products_tax_class_id'])) . '</span>';
						} else {
						$products_price = $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id']));
						}
					
		$data .= '		
					<div class="col-md-3 col-xs-6 btn-act nopadding product-grid text-left">
						<div class="col  text-center">
						<div class="image">
							<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']).'">'.tep_image(DIR_WS_IMAGES_PRODUCT . $specials['products_image'], $specials['products_name']).'</a>
							<div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $data .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $specials['products_name'],'','class="img-me"');
					}
								
				$data .='<div class="btn-view"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']).'"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
						</div>
						<div class="prod-name"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' . $specials['products_name'].'</a>
						</div>
							<div class="prod-price">'.$currencies->display_price($specials['specials_new_products_price'], tep_get_tax_rate($specials['products_tax_class_id'])).'
							</div>
							<div class="prod-btn text-center"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']).'"  class="btn"><i class="fa fa-search"></i></a><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']).'" class="btn"><i class="fa fa-shopping-cart"></i></a></div>
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
      return defined('MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	   tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Special Product Slider','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','slider-sp-out','.bg-slide1','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','slider-sp-in','.bg-slide1-in','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_STATUS', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_CONTENT_PLACEMENT', 'MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_SORT_ORDER','MODULE_BOXES_SLIDER_PRODUCTS_SPECIAL_DISPLAY_PAGES');
    }
  }
  
?>