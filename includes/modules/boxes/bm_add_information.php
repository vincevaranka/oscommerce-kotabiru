<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_information {
    var $code = 'bm_add_information';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_INFORMATION_TITLE;
      $this->description = MODULE_BOXES_INFORMATION_DESCRIPTION;

      if ( defined('MODULE_BOXES_INFORMATION_STATUS') ) {
        $this->sort_order = MODULE_BOXES_INFORMATION_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_INFORMATION_STATUS == 'True');
        $this->pages = MODULE_BOXES_INFORMATION_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate,  $cPath, $languages_id;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '<div class="wrap-wide col-info1">
	<div class="container">
		<div class="row info1-icon">
			<div class="col-md-4">
				<div class="col-md-4"><i class="glyphicon glyphicon-star"></i></div>
				<div class="col-md-8">
					<h4>Responsive Design</h4>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry.
				</div>
			</div>
			<div class="col-md-4">
				<div class="col-md-4"><i class="glyphicon glyphicon-heart"></i></div>
				<div class="col-md-8"><h4>Clean Theme</h4>
				Lorem Ipsum is simply dummy text of the printing and typesetting industry.
				</div>
			</div>
			<div class="col-md-4 ">
				<div class="col-md-4"><i class="glyphicon glyphicon-signal"></i>
				</div>
				<div class="col-md-8"><h4>Premium Support</h4>
				Lorem Ipsum is simply dummy text of the printing and typesetting industry.
				</div>
			</div>
		</div>
	</div>
</div>';
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_INFORMATION_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_INFORMATION_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_INFORMATION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_INFORMATION_DISPLAY_PAGES', '', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_INFORMATION_STATUS', 'MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT', 'MODULE_BOXES_INFORMATION_SORT_ORDER','MODULE_BOXES_INFORMATION_DISPLAY_PAGES');
    }
  }
  
?>