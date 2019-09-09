<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_header_mainmenu {
    var $code = 'bm_add_header_mainmenu';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_header_mainmenu() {
      $this->title = MODULE_BOXES_HEADER_MAINMENU_TITLE;
      $this->description = MODULE_BOXES_HEADER_MAINMENU_DESCRIPTION;

      if ( defined('MODULE_BOXES_HEADER_MAINMENU_STATUS') ) {
        $this->sort_order = MODULE_BOXES_HEADER_MAINMENU_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_HEADER_MAINMENU_STATUS == 'True');
        $this->pages = MODULE_BOXES_HEADER_MAINMENU_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_HEADER_MAINMENU_CONTENT_PLACEMENT == 'Header') ? 'boxes_column_header' : '');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $cart, $languages_id,$currencies;
		$categories_string = '';
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
		while ($categories = tep_db_fetch_array($categories_query))  {
			$cID = $categories['categories_id'];
			$catcount_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id='".(int)$cID."'");
			$catcount = tep_db_fetch_array($catcount_query);	
			$cat_name = tep_categories_name($cID);
			$cpath = 'cPath='.$cID;
			$categories_string .= '<div class="megamenu-column"><h3><a href="'.tep_href_link(FILENAME_DEFAULT, $cpath).'">'.strtoupper($categories['categories_name']).'</a></h3><div><ul>';
			$parent_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '".(int)$categories['categories_id']."' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name") or die(mysql_error());	
			while ($parent = tep_db_fetch_array($parent_query)) {
				$cpID = $parent['categories_id'];
				$cat_name = tep_categories_name($cID).'/'.tep_categories_name($cpID);
				$parent2_query = tep_db_query("select c.categories_id,cd.categories_name,c.parent_id from ".TABLE_CATEGORIES." c,".TABLE_CATEGORIES_DESCRIPTION." cd where c.parent_id = '".(int)$parent['categories_id']."' and c.categories_id = cd.categories_id and cd.language_id ='".(int)$languages_id."' order by sort_order, cd.categories_name") or die(mysql_error());
				$m = mysqli_num_rows($parent2_query);
				$cpath = 'cPath='.$cID.'_'.$cpID;
				$categories_string .= '<li><a href="'.tep_href_link(FILENAME_DEFAULT, $cpath).'">'.$parent['categories_name'].'</a></li>';
			}
		$categories_string .='</ul></div></div>';
		}	
		$data .='
					<div class="menu m-menu menu-sty"> <span>'.HEADER_TITLE_CATEGORY.'</span>
						<ul>
							<li class="categories">
								<div>'.$categories_string.'</div>					
								<div class="menu-field"><a href="'.tep_href_link(FILENAME_SPECIALS).'" class="manu bigman">'.HEADER_TITLE_SPECIAL.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_NEW).'" class="manu bigman">'.HEADER_TITLE_NEW.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_BEST).'" class="manu bigman">'.HEADER_TITLE_BEST.'</a></div>
							</li>			
						</ul>
					</div>';
		$datas = '<div class="menu-bg"><div class="wrap-box">'.$data.'</div></div>';
		$oscTemplate->addBlock($datas, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_HEADER_MAINMENU_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_HEADER_MAINMENU_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_HEADER_MAINMENU_CONTENT_PLACEMENT', 'Header', 'Should the module be loaded in the Header Block?', '6', '1', 'tep_cfg_select_option(array(\'Header\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_HEADER_MAINMENU_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_HEADER_MAINMENU_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_HEADER_MAINMENU_STATUS', 'MODULE_BOXES_HEADER_MAINMENU_CONTENT_PLACEMENT', 'MODULE_BOXES_HEADER_MAINMENU_SORT_ORDER','MODULE_BOXES_HEADER_MAINMENU_DISPLAY_PAGES');
    }
  }
  
?>