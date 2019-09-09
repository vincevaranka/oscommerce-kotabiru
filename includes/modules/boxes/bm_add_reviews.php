<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_reviews {
    var $code = 'bm_add_reviews';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_REVIEWS_TITLE;
      $this->description = MODULE_BOXES_REVIEWS_DESCRIPTION;

      if ( defined('MODULE_BOXES_REVIEWS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_REVIEWS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_REVIEWS_STATUS == 'True');
        $this->pages = MODULE_BOXES_REVIEWS_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate,  $cPath, $languages_id,$HTTP_GET_VARS;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '<div class="wrap-wide bg-review"><div class="container con-review">';
		$data .= '<div class="module-header"><h2 class="md-review-color">Reviews of our Product</h2></div>
				';
		$data .= '<div class="row">
					<div class="col-md-12">';
		$reviews_query = tep_db_query("select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, rd.reviews_text, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and r.reviews_status = 1");
		if(tep_db_num_rows($reviews_query) > 0) {
		while($reviews = tep_db_fetch_array($reviews_query)) {
				$data .= '<div class="col-md-6 col-sm-6 review-box">';
				$data .= '<div class="row"><div class="col-xs-3">'.tep_image(DIR_WS_IMAGES_PRODUCT . $reviews['products_image'], $reviews['products_name']).'</div>';
				$data .= '<div class="col-xs-9"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">'.$reviews['products_name'].'</a>'.tep_get_stars($reviews['reviews_rating']).''.tep_break_string(tep_output_string_protected($reviews['reviews_text']), 15, '-<br />').'</div>';
				$data .= '</div></div>';
			}
		} else {
		}
		$data .= '</div>
				  </div>';
		$data .= '</div></div>';
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_REVIEWS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_REVIEWS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_REVIEWS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_REVIEWS_DISPLAY_PAGES', '', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Reviews','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','review-outer','.bg-review','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','review-in','.con-review','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_attr_1,t_value,t_value_1,t_group) values ('Header','md-review-color','.md-review-color','background','color','','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	   $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_REVIEWS_STATUS', 'MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT', 'MODULE_BOXES_REVIEWS_SORT_ORDER','MODULE_BOXES_REVIEWS_DISPLAY_PAGES');
    }
  }
  
?>