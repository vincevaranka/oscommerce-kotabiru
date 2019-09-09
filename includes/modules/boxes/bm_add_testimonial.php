<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_testimonial {
    var $code = 'bm_add_testimonial';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_testimonial() {
      $this->title = MODULE_BOXES_TESTIMONIAL_TITLE;
      $this->description = MODULE_BOXES_TESTIMONIAL_DESCRIPTION;

      if ( defined('MODULE_BOXES_TESTIMONIAL_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TESTIMONIAL_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TESTIMONIAL_STATUS == 'True');
        $this->pages = MODULE_BOXES_TESTIMONIAL_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_TESTIMONIAL_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $HTTP_GET_VARS, $cPath,$cart, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
			$data .= '<script>$(document).ready(function() {var owl = $("#testimonial");owl.owlCarousel({ items : 1,  itemsDesktop : [1000,1], itemsDesktopSmall : [900,1], itemsTablet: [600,1],  itemsMobile : false , pagination:false,autoPlay : 5000,});});</script>
			<div class="wrap-wide testi-bg"><div class="container testi-bg-in text-center"><i class="fa fa-comments-o"></i><h2>'.MODULE_BOXES_TESTIMONIAL_H2.'</h2>
			<div id="testimonial" class="owl-carousel owl-theme">';
			$testi_query = tep_db_query("select testi_name, testi_text from ".TABLE_TESTIMONIAL." where testi_status ='1' order by testi_date");
			while($testi = tep_db_fetch_array($testi_query)) {
				$data .= '<div class="item text-center"><p>'.substr($testi['testi_text'], 0, 250).'</p><span class="name">'.$testi['testi_name'].'</span></div>';
			}
			
			$data .= '</div></div></div>';
	
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TESTIMONIAL_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_TESTIMONIAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TESTIMONIAL_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TESTIMONIAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TESTIMONIAL_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Testimonial','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','testi-bg','.testi-bg','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','testi-bg-in','.testi-bg-in','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_TESTIMONIAL_STATUS', 'MODULE_BOXES_TESTIMONIAL_CONTENT_PLACEMENT', 'MODULE_BOXES_TESTIMONIAL_SORT_ORDER','MODULE_BOXES_TESTIMONIAL_DISPLAY_PAGES');
    }
  }
  
?>