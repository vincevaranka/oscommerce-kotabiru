<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_topmenu_user {
    var $code = 'bm_add_topmenu_user';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_topmenu_user() {
      $this->title = MODULE_BOXES_TOPMENU_USER_TITLE;
      $this->description = MODULE_BOXES_TOPMENU_USER_DESCRIPTION;

      if ( defined('MODULE_BOXES_TOPMENU_USER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TOPMENU_USER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TOPMENU_USER_STATUS == 'True');
        $this->pages = MODULE_BOXES_TOPMENU_USER_DISPLAY_PAGES;
         $this->group = ((MODULE_BOXES_TOPMENU_USER_CONTENT_PLACEMENT == 'Left TopMenu') ? 'boxes_topmenu_left' : 'boxes_topmenu_right');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate;
		$data = '';
		if(tep_session_is_registered('customer_id')){
			$data = '<a href="'.tep_href_link(FILENAME_DEFAULT).'" class="btn btn-large">Home</a>
				<div class="dropdown">
				<a class="btn dropdown-toggle" data-toggle="dropdown" id="dropdownMenu1">'.HEADER_TITLE_MY_ACCOUNT.'<i class="glyphicon glyphicon-chevron-down mini"></i></a>
				<ul class="dropdown-menu">
<li><a href="'.tep_href_link(FILENAME_ACCOUNT).'"  role="menuitem" tabindex="-1"><i class="glyphicon glyphicon-user"></i>'. HEADER_TITLE_MY_ACCOUNT.'</a></li>
<li><a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY).'"><i class="glyphicon glyphicon-calendar"></i>'. HEADER_TITLE_ACCOUNT_HISTORY.'</a></li>
<li><a href="'.tep_href_link(FILENAME_ADDRESS_BOOK).'"><i class="glyphicon glyphicon-book"></i>'.HEADER_TITLE_ADDRESS_BOOK.'</a></li>
<li><a href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING).'"><i class="glyphicon glyphicon-shopping-cart"></i>'. HEADER_TITLE_CHECKOUT.'</a></li>
<li><a href="'.tep_href_link(FILENAME_LOGOFF).'"><i class="glyphicon glyphicon-log-out"></i>'.HEADER_TITLE_LOGOFF.'</a></li>
					</ul>
				</div>';
			} else {
				$data .= '<a href="'.tep_href_link(FILENAME_DEFAULT).'" class="btn btn-large">Home</a><a href="'.tep_href_link(FILENAME_CREATE_ACCOUNT).'" class="btn btn-large">'.HEADER_TITLE_CREATE_ACCOUNT.'</a><a href="'.tep_href_link(FILENAME_LOGIN).'" class="btn btn-large">'.HEADER_TITLE_LOGIN.'</a>';
			}
	  $oscTemplate->addBlock($data, $this->group);
      
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TOPMENU_USER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_TOPMENU_USER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TOPMENU_USER_CONTENT_PLACEMENT', 'Right TopMenu', 'Should the module be loaded in thet Top Menu Block?', '6', '1', 'tep_cfg_select_option(array(\'Left TopMenu\',\'Right TopMenu\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TOPMENU_USER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TOPMENU_USER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_TOPMENU_USER_STATUS', 'MODULE_BOXES_TOPMENU_USER_CONTENT_PLACEMENT', 'MODULE_BOXES_TOPMENU_USER_SORT_ORDER','MODULE_BOXES_TOPMENU_USER_DISPLAY_PAGES');
    }
  }
  
?>