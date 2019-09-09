<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_banner {
    var $code = 'bm_add_banner';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_banner() {
      $this->title = MODULE_BOXES_BANNER_TITLE;
      $this->description = MODULE_BOXES_BANNER_DESCRIPTION;

      if ( defined('MODULE_BOXES_BANNER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_BANNER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_BANNER_STATUS == 'True');
        $this->pages = MODULE_BOXES_BANNER_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_BANNER_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate,  $cPath, $languages_id, $HTTP_GET_VARS;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '<div class="wrap-wide banner-bg"><div class="container banner banner-bg-in">
				<div class="row">';
		$banner_query = tep_db_query("select b_title, b_url, b_image from ".TABLE_BANNER." where b_status = '1' and b_pos='1' order by b_sort");
		while($banner = tep_db_fetch_array($banner_query)){
			$data .= '<div class="col-sm-6 banner-item"><div class="sub"><a href="'.tep_href_link($banner['b_url']).'" class="banner-a"><img src="'.DIR_WS_IMAGES.'banners/'.$banner['b_image'].'" alt="'.$banner['b_title'].'" /><span class="caption banner-animate">Check It Out </span></a></div></div>';			
		}
		$data .= '</div></div></div>';
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_BANNER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_BANNER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BANNER_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BANNER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_BANNER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("update ".TABLE_BANNER_POS." set pb_status ='1' where pb_pos = '1'");
	    tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Banner Two Column','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Banner Outer Background','banner-bg','.banner-bg','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Banner Inner Background','banner-bg-in','.banner-bg-in','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  tep_db_query("update ".TABLE_BANNER_POS." set pb_status ='0' where pb_pos = '1'");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_BANNER_STATUS', 'MODULE_BOXES_BANNER_CONTENT_PLACEMENT', 'MODULE_BOXES_BANNER_SORT_ORDER','MODULE_BOXES_BANNER_DISPLAY_PAGES');
    }
  }
  
?>