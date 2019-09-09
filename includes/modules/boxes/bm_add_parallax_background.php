<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_parallax_background {
    var $code = 'bm_add_parallax_background';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_parallax_background() {
      $this->title = MODULE_BOXES_PARALLAX_BACKGROUND_TITLE;
      $this->description = MODULE_BOXES_PARALLAX_BACKGROUND_DESCRIPTION;

      if ( defined('MODULE_BOXES_PARALLAX_BACKGROUND_STATUS') ) {
        $this->sort_order = MODULE_BOXES_PARALLAX_BACKGROUND_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_PARALLAX_BACKGROUND_STATUS == 'True');
        $this->pages = MODULE_BOXES_PARALLAX_BACKGROUND_DISPLAY_PAGES;
		$this->image = ((MODULE_BOXES_PARALLAX_IMAGE_USE == '1 Image') ? '1 Image' : '2 Image');
        $this->group = ((MODULE_BOXES_PARALLAX_BACKGROUND_CONTENT_PLACEMENT == 'Column Main') ? 'boxes_main' : '');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $HTTP_GET_VARS, $cPath,$cart, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
			if($this->image == '2 Image') {
			$data .= '<script>$(window).scroll(function () {
							if($(this).scrollTop() > 1000)
								{ $(\'.bg-main\').removeClass(\'bg1\'); $(\'.bg-main\').addClass(\'bg2\'); } else { $(\'.bg-main\').removeClass(\'bg2\'); $(\'.bg-main\').addClass(\'bg1\');}
						});</script>'; }
			$data .= '<div class="bg-main bg1"></div>';
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_PARALLAX_BACKGROUND_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_PARALLAX_BACKGROUND_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_PARALLAX_BACKGROUND_CONTENT_PLACEMENT', 'Column Main', 'Should the module be loaded in the Column Main?', '6', '1', 'tep_cfg_select_option(array(\'Column Main\'), ', now())");
	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image Use', 'MODULE_BOXES_PARALLAX_IMAGE_USE', '2 Image', 'How Many Image use?', '6', '1', 'tep_cfg_select_option(array(\'1 Image\',\'2 Image\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_PARALLAX_BACKGROUND_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_PARALLAX_BACKGROUND_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");

    }

    function keys() {
      return array('MODULE_BOXES_PARALLAX_BACKGROUND_STATUS', 'MODULE_BOXES_PARALLAX_BACKGROUND_CONTENT_PLACEMENT', 'MODULE_BOXES_PARALLAX_IMAGE_USE', 'MODULE_BOXES_PARALLAX_BACKGROUND_SORT_ORDER','MODULE_BOXES_PARALLAX_BACKGROUND_DISPLAY_PAGES');
    }
  }
  
?>