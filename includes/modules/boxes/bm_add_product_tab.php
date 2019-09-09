<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_product_tab {
    var $code = 'bm_add_product_tab';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_PRODUCT_TAB_TITLE;
      $this->description = MODULE_BOXES_PRODUCT_TAB_DESCRIPTION;

      if ( defined('MODULE_BOXES_PRODUCT_TAB_STATUS') ) {
        $this->sort_order = MODULE_BOXES_PRODUCT_TAB_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_PRODUCT_TAB_STATUS == 'True');
        $this->pages = MODULE_BOXES_PRODUCT_TAB_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_PRODUCT_TAB_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $cart,$cPath,$HTTP_GET_VARS, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '	

<div class="container col-tab-products">
	<div class="product-tabs">
	  <ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#pane1" data-toggle="tab">SALE</a></li>
		<li><a href="#pane2" data-toggle="tab">Best Seller</a></li>
		<li><a href="#pane3" data-toggle="tab">Recommended</a></li>
	  </ul>
	  <hr class="line-tabs" />
	  <div class="tab-content">
			<div id="pane1" class="tab-pane active slide in col-prod" >';
				
			$specials_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_best, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC limit 8");
			while ($specials = tep_db_fetch_array($specials_query)) {
				 $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_new['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
					  $sale='<span class="icon-sale"></span>';
				$data .= '
					 <div class="col-md-3 col-xs-12 col-sm-4 btn-act nopadding product-grid text-left">
						<div class="col text-center">
						<div class="image">
							<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']).'">'.(($products_new['products_best']) ? '<span class="icon-star"></span>':'').$sale.tep_image(DIR_WS_IMAGES_PRODUCT . $specials['products_image'], $specials['products_name']).'</a>
							<div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $data .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $specials['products_name'],'','class="img-me"');
					}
								
				$data .='<div class="btn-view sml"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy sml"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']).'"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
						</div>
						<div class="name"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' . $specials['products_name'].'</a>
						</div>
							<div class="price"><del>'.$currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])).'</del> '.$currencies->display_price($specials['specials_new_products_price'], tep_get_tax_rate($specials['products_tax_class_id'])).'
							</div>
							<div class="product-btn">
								<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']).'"  class="btn"><i class="fa fa-search"></i></a>
								<a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']).'" class="btn"><i class="fa fa-shopping-cart"></i></a>
							</div>
						</div>
				
					</div>';
						
					}
			$data .= '
			</div>
			<div id="pane2" class="tab-pane slide">';
			
				$bestseller_query = tep_db_query("select p.products_id, p.products_quantity, p.products_ordered, pd.products_name, pd.products_description, p.products_image, p.products_tax_class_id, p.products_best, p.products_price, p.products_tax_class_id, p.products_date_added, p2c.products_id,p2c.categories_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on (p.products_id = p2c.products_id),  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered DESC limit 8");
				while($bestseller = tep_db_fetch_array($bestseller_query)) {
				if ($new_price = tep_get_products_special_price($bestseller['products_id'])) {
						$products_price = '<del>' . $currencies->display_price($bestseller['products_price'], tep_get_tax_rate($bestseller['products_tax_class_id'])) . '</del> ' . $currencies->display_price($new_price, tep_get_tax_rate($bestseller['products_tax_class_id'])) . '';
						$sale = '<span class="icon-star"></span>';
						} else {
						$sale = '';
						$products_price = $currencies->display_price($bestseller['products_price'], tep_get_tax_rate($bestseller['products_tax_class_id']));
						}
				$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$bestseller['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
				$data .= '
				<div class="col-md-3 col-xs-12 col-sm-4 btn-act nopadding product-grid text-left">
						<div class="col  text-center">
						<div class="image">
							<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bestseller['products_id']).'"><span class="icon-star"></span>'.tep_image(DIR_WS_IMAGES_PRODUCT . $bestseller['products_image'], $bestseller['products_name']).'</a>
							<div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $data .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $bestseller['products_name'],'','class="img-me"');
					}
								
				$data .='<div class="btn-view"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bestseller['products_id']) . '"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $bestseller['products_id']).'"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
						</div>
						<div class="name"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bestseller['products_id']) . '">' . $bestseller['products_name'].'</a>
						</div>
							<div class="price">'.$products_price.'
							</div>
							<div class="product-btn">
								<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bestseller['products_id']).'"  class="btn"><i class="fa fa-search"></i></a>
								<a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $bestseller['products_id']).'" class="btn"><i class="fa fa-shopping-cart"></i></a>
							</div>
						</div>
				
					</div>	
					';
				}
				
				
			$data .= '</div><div id="pane3" class="tab-pane slide">';
			
				$best_query = tep_db_query("select p.products_id, p.products_quantity, p.products_ordered, pd.products_name, pd.products_description, p.products_image, p.products_tax_class_id, p.products_best, p.products_price, p.products_tax_class_id, p.products_date_added, p2c.products_id,p2c.categories_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on (p.products_id = p2c.products_id),  " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_best = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_ordered DESC limit 8");
				while($best = tep_db_fetch_array($best_query)) {
				if ($new_price = tep_get_products_special_price($best['products_id'])) {
						$products_price = '<del>' . $currencies->display_price($best['products_price'], tep_get_tax_rate($best['products_tax_class_id'])) . '</del> ' . $currencies->display_price($new_price, tep_get_tax_rate($best['products_tax_class_id'])) . '';
						$sale = '<span class="icon-star"></span>';
						} else {
						$sale = '';
						$products_price = $currencies->display_price($best['products_price'], tep_get_tax_rate($best['products_tax_class_id']));
						}
				$pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$best['products_id'] . "' limit 1");
					  if (tep_db_num_rows($pi_query) > 0) { 
					  $pi_image = tep_db_fetch_array($pi_query);
					  $image_add = $pi_image['image']; $go = '';
					  } else {$image_add = ''; $go='go-trans' ;} 
				$data .= '
				<div class="col-md-3 col-xs-12 col-sm-4 btn-act nopadding product-grid text-left">
						<div class="col  text-center">
						<div class="image">
							<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best['products_id']).'"><span class="icon-star"></span>'.tep_image(DIR_WS_IMAGES_PRODUCT . $best['products_image'], $best['products_name']).'</a>
							<div class="animate-me '.$go.'">';
					if(!empty($image_add)) { $data .= tep_image(DIR_WS_IMAGES_PRODUCT . $image_add, $best['products_name'],'','class="img-me"');
					}
								
				$data .='<div class="btn-view"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best['products_id']) . '"><i class="fa fa-search"></i></a></div>
								<div class="btn-buy"><a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $best['products_id']).'"><i class="fa fa-shopping-cart"></i></a></div>
							</div>
						</div>
						<div class="name"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best['products_id']) . '">' . $best['products_name'].'</a>
						</div>
							<div class="price">'.$products_price.'
							</div>
							<div class="product-btn">
								<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best['products_id']).'"  class="btn"><i class="fa fa-search"></i></a>
								<a href="'.tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $best['products_id']).'" class="btn"><i class="fa fa-shopping-cart"></i></a>
							</div>
						</div>
				
					</div>	
					';
				}
			
	 $data .= '</div></div></div></div>';
		}
	
        $oscTemplate->addBlock('<div class="wrap-wide col-tab-products-outer">'.$data.'</div>', $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_PRODUCT_TAB_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_PRODUCT_TAB_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_PRODUCT_TAB_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_PRODUCT_TAB_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_PRODUCT_TAB_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Product Tabs','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','col-tab-products-outer','.col-tab-products-outer','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','col-tab-products','.col-tab-products','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	   $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_PRODUCT_TAB_STATUS', 'MODULE_BOXES_PRODUCT_TAB_CONTENT_PLACEMENT', 'MODULE_BOXES_PRODUCT_TAB_SORT_ORDER','MODULE_BOXES_PRODUCT_TAB_DISPLAY_PAGES');
    }
  }
  
?>