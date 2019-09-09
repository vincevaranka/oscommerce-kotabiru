<?php
  class bm_add_column_categories {
    var $code = 'bm_add_column_categories';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function __construct() {
      $this->title = MODULE_BOXES_COLUMN_CATEGORIES_TITLE;
      $this->description = MODULE_BOXES_COLUMN_CATEGORIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_COLUMN_CATEGORIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_COLUMN_CATEGORIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_COLUMN_CATEGORIES_STATUS == 'True');
		$this->pages = MODULE_BOXES_COLUMN_CATEGORIES_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_COLUMN_CATEGORIES_CONTENT_PLACEMENT == 'Column Left') ? 'boxes_column_left' : 'boxes_column_right');
		$this->placement = MODULE_BOXES_COLUMN_CATEGORIES_CONTENT_PLACEMENT;
      }
    }

    function cattreeLeft($parent_id = 0, $level = 0, $cPath_tree = ''){
		global $languages_id, $cPath_array, $HTTP_GET_VARS;
		if(isset($HTTP_GET_VARS['products_id'])){
			$prodpath = tep_get_product_path($HTTP_GET_VARS['products_id']);
			if(strpos($prodpath,'_') != false){
				$prod_category = strstr_after($prodpath,'_');
				if(strpos($prod_category,'_') == true) { $prod_category = strstr_after(prod_category,'_');}
			}
			
		}
		
		if (strpos($HTTP_GET_VARS['cPath'], '_') !== false) {  
						$category_path = strstr_after($HTTP_GET_VARS['cPath'], '_');
						if(strpos($category_path,'_') == true) { $category_path = strstr_after($category_path,'_');}
						$cat_name = tep_categories_name($category_path_first)."/".tep_categories_name($category_path)."/";	
					}  
					else {
						$category_path = $HTTP_GET_VARS['cPath'];
						$cat_name = tep_categories_name($category_path)."/";
					}
		$categories_query_top = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '".(int)$parent_id."' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
		$i = 0;
		$totCat = tep_db_num_rows($categories_query_top);
		while ($categories_top = tep_db_fetch_array($categories_query_top))  {
			
			if($categories_top['parent_id'] == 0){
				if($categories_top['categories_id'] == $category_path){ $active = 'active'; } else {$active = "";}
				if($this->cattreeLeft($categories_top['categories_id'], ($level+1), $categories_top['categories_id']) != ""){
					$output .= '<li><a class="'.$active.'" href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories_top['categories_id']).'">'.$categories_top['categories_name'].'</a><span class="down"></span>';
				}else{
					$output .= '<li><a class="'.$active.'" href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories_top['categories_id']).'">'.$categories_top['categories_name'].'</a></li>';
				}
				$output .= $this->cattreeLeft($categories_top['categories_id'], ($level+1), $categories_top['categories_id']);
			}else{
				if($categories_top['categories_id'] == $category_path or $categories_top['categories_id'] == $prod_category)
					{ $active = 'active'; } else {$active = "";}
				
				if($i == 0 && $level == 1){
					$output .= '<ul>';

				}elseif($i == 0 && $level > 1){
					$output .= '<span class="down"></span><ul>';
					
				}
				$output .= '<li>';
				$output .= '<a class="'.$active.'" href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cPath_tree.'_'.$categories_top['categories_id']).'">'.$categories_top['categories_name'].'</a>';
				$output .= $this->cattreeLeft($categories_top['categories_id'], ($level+1), $cPath_tree.'_'.$categories_top['categories_id']);
				$output .='</li>';
				
				if($i == ($totCat-1)){
					if($level == $i && $level >1) {
					$output .= '</ul>';} else { $output .='</ul></li>';}
					//$output .= '</ul></li>';
				}			
			}
			$i++;
		}
		return $output;
	  }	

    function execute() {
	  global $HTTP_GET_VARS,$oscTemplate,$PHP_SELF;
	  //if(isset($HTTP_GET_VARS['cPath']) || isset($HTTP_GET_VARS['products_id']) || basename($PHP_SELF) == FILENAME_SPECIALS || basename($PHP_SELF) == FILENAME_PRODUCTS_NEW || basename($PHP_SELF) == FILENAME_PRODUCTS_BEST || basename($PHP_SELF) == FILENAME_ADVANCED_SEARCH_RESULT) {
	  $data .= '<div class="box c-categories">' .
              '  <div class="box-heading" >'.MODULE_BOXES_COLUMN_CATEGORIES_BOX_TITLE.'</div>' .
              '  <div class="box-content box-category"><ul id="cat_accordion">' . $this->cattreeLeft() . '</ul></div>' .
              '</div>';
     if(basename($PHP_SELF) != 'index.php') {
		  $oscTemplate->addBlock($data, $this->group); 
	  } else {
		if(isset($HTTP_GET_VARS['cPath']) || isset($HTTP_GET_VARS['products_id']))
		{
			$oscTemplate->addBlock($data, $this->group); 
		}
	  }
	  
	 
    }
    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_COLUMN_CATEGORIES_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Categories Module', 'MODULE_BOXES_COLUMN_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_COLUMN_CATEGORIES_CONTENT_PLACEMENT', 'Column Left', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Column Left\', \'Column Right\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_COLUMN_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_COLUMN_CATEGORIES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_COLUMN_CATEGORIES_STATUS', 'MODULE_BOXES_COLUMN_CATEGORIES_CONTENT_PLACEMENT', 'MODULE_BOXES_COLUMN_CATEGORIES_SORT_ORDER','MODULE_BOXES_COLUMN_CATEGORIES_DISPLAY_PAGES');
    }
  }
?>
