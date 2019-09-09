<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_topmenu_currencies {
    var $code = 'bm_add_topmenu_currencies';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_topmenu_currencies() {
      $this->title = MODULE_BOXES_TOPMENU_CURRENCIES_TITLE;
      $this->description = MODULE_BOXES_TOPMENU_CURRENCIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_TOPMENU_CURRENCIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TOPMENU_CURRENCIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TOPMENU_CURRENCIES_STATUS == 'True');
        $this->pages = MODULE_BOXES_TOPMENU_CURRENCIES_DISPLAY_PAGES;
         $this->group = ((MODULE_BOXES_TOPMENU_CURRENCIES_CONTENT_PLACEMENT == 'Left TopMenu') ? 'boxes_topmenu_left' : 'boxes_topmenu_right');
      }
    }

    function execute() {
      global $PHP_SELF, $currencies, $HTTP_GET_VARS, $request_type, $currency, $oscTemplate, $current_curr_key;

      if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
        if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
         
          reset($currencies->currencies);
          foreach($currencies->currencies as $key => $value ) {
            if ( $key == $currency ) {
              $current_curr_key = $key;
							$current_curr_symbol_left = $value['symbol_left'];
							$current_curr_symbol_right = $value['symbol_right'];
							$current_curr_value = $value['value'];
							$current_curr_title = $value['title'];
              break;
            }
          }
		  		
          reset($currencies->currencies);
          $currencies_array = array();
          $url = '';
				  while (list($key, $value) = each($currencies->currencies)) {
						if ($current_curr_key == $key )	{
							$current_curr = 'activ';
						}else{
							$current_curr = '';
						}
						$url = 'currency='.$key;
						$currencies_string .= '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('currency')) .'currency='.$key).'">'.$value['symbol_left']. ' ' .$value['title'].''.$value['symbol_right'].'</a>';
          }

          $hidden_get_variables = '';
          reset($HTTP_GET_VARS);
          while (list($key, $value) = each($HTTP_GET_VARS)) {
            if ( is_string($value) && ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
              $hidden_get_variables .= tep_draw_hidden_field($key, $value);
            }
          }
						
          $data = '
				
					<div class="box-currencies"><span>'.$current_curr_symbol_left.''.$current_curr_title.''.$current_curr_symbol_right.' <i class="fa fa-angle-down"></i></span>'. "\n".
		  		  '		<div class="cur-content">'. "\n".
						'	     ' . tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get', '') . "\n" .
			 			'				'. "\n" .
						'	  '. $hidden_get_variables . tep_hide_session_id() . ''. "\n".
						'			'.$currencies_string. ''."\n".
          	'	  </form></div>'. "\n".
				  '</div>'. "\n";
        
          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TOPMENU_CURRENCIES_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_TOPMENU_CURRENCIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TOPMENU_CURRENCIES_CONTENT_PLACEMENT', 'Left TopMenu', 'Should the module be loaded in the Above Header Block?', '6', '1', 'tep_cfg_select_option(array(\'Left TopMenu\',\'Right TopMenu\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TOPMENU_CURRENCIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TOPMENU_CURRENCIES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	 
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Top Menu Currencies','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Text Color','cur-col','.box-currencies','color','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group,t_attr_1) values ('a link Color','cur-a','.box-currencies a','color','".$id."','background')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group,t_attr_1) values ('a link Color Hover','cur-a-hover','.box-currencies a:hover','color','".$id."','background')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_TOPMENU_CURRENCIES_STATUS', 'MODULE_BOXES_TOPMENU_CURRENCIES_CONTENT_PLACEMENT', 'MODULE_BOXES_TOPMENU_CURRENCIES_SORT_ORDER','MODULE_BOXES_TOPMENU_CURRENCIES_DISPLAY_PAGES');
    }
  }
  
?>