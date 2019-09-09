<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_manufacture {
    var $code = 'bm_add_manufacture';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_manufacture() {
      $this->title = MODULE_BOXES_MANUFACTURE_TITLE;
      $this->description = MODULE_BOXES_MANUFACTURE_DESCRIPTION;

      if ( defined('MODULE_BOXES_MANUFACTURE_STATUS') ) {
        $this->sort_order = MODULE_BOXES_MANUFACTURE_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_MANUFACTURE_STATUS == 'True');
        $this->pages = MODULE_BOXES_MANUFACTURE_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_MANUFACTURE_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate,  $cPath, $languages_id,$HTTP_GET_VARS;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '
		<script>$(document).ready(function() {var owl = $("#manufacture");owl.owlCarousel({ items : 8,  itemsDesktop : [1000,5], itemsDesktopSmall : [900,4], itemsTablet: [600,4],  itemsMobile : false , pagination:false,autoPlay : 3000,});});</script>
			<div class="wrap-wide manufacture-bg">
			<div class="container manufacture-bg-in">
			<div id="manufacture" class="owl-carousel owl-theme">';
		
					$logo_query = tep_db_query("select manufacturers_id,manufacturers_name,manufacturers_image from ".TABLE_MANUFACTURERS);
					while($logo = tep_db_fetch_array($logo_query)){
						$data .= '<div class="item"><a href="'.tep_href_link(FILENAME_DEFAULT,'manufacturers_id='.$logo['manufacturers_id']).'"><img src="'.DIR_WS_IMAGES_MANUFACTURE.$logo['manufacturers_image'].'" alt="'.$logo['manufacturers_name'].'" title="'.$logo['manufacturers_name'].'" /></a></div>';
					}
		$data .='</div></div></div>';
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_MANUFACTURE_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_MANUFACTURE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_MANUFACTURE_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_MANUFACTURE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_MANUFACTURE_DISPLAY_PAGES', '', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	   tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Manufacture Slider','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Outer Background','manufacture-bg','.manufacture-bg','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Inner Background','manufacture-bg-in','.manufacture-bg-in','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_MANUFACTURE_STATUS', 'MODULE_BOXES_MANUFACTURE_CONTENT_PLACEMENT', 'MODULE_BOXES_MANUFACTURE_SORT_ORDER','MODULE_BOXES_MANUFACTURE_DISPLAY_PAGES');
    }
  }
  
?>