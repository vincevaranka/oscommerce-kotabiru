<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_topmenu_call_us {
    var $code = 'bm_add_topmenu_call_us';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_topmenu_call_us() {
      $this->title = MODULE_BOXES_TOPMENU_CALL_US_TITLE;
      $this->description = MODULE_BOXES_TOPMENU_CALL_US_DESCRIPTION;

      if ( defined('MODULE_BOXES_TOPMENU_CALL_US_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TOPMENU_CALL_US_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TOPMENU_CALL_US_STATUS == 'True');
        $this->pages = MODULE_BOXES_TOPMENU_CALL_US_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_TOPMENU_CALL_US_CONTENT_PLACEMENT == 'Left TopMenu') ? 'boxes_topmenu_left' : 'boxes_topmenu_right');
		$this->callusnumber = MODULE_BOXES_TOPMENU_CALL_US_PHONE;
      }
    }

    function execute() {
      global $PHP_SELF, $currencies, $languages, $oscTemplate;
		$data = '<div class="call-us">'.TEXT_CALL_US. $this->callusnumber.'</div>';
	
        
          $oscTemplate->addBlock($data, $this->group);
        
      
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TOPMENU_CALL_US_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_TOPMENU_CALL_US_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TOPMENU_CALL_US_CONTENT_PLACEMENT', 'Right TopMenu', 'Should the module be loaded in the Above Header Block?', '6', '1', 'tep_cfg_select_option(array(\'Left TopMenu\',\'Right TopMenu\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Call Us Number', 'MODULE_BOXES_TOPMENU_CALL_US_PHONE', '+123456789', 'Input your Call Us Number', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TOPMENU_CALL_US_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TOPMENU_CALL_US_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Banner 1','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Text Color','call-us','.call-us','color','','".$id."')");
	
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	    $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_TOPMENU_CALL_US_STATUS', 'MODULE_BOXES_TOPMENU_CALL_US_CONTENT_PLACEMENT','MODULE_BOXES_TOPMENU_CALL_US_PHONE' ,'MODULE_BOXES_TOPMENU_CALL_US_SORT_ORDER','MODULE_BOXES_TOPMENU_CALL_US_DISPLAY_PAGES');
    }
  }
  
?>