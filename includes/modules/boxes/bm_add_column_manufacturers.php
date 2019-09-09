<?php
  class bm_add_column_manufacturers {
    var $code = 'bm_add_column_manufacturers';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;
    function __construct() {
      $this->title = MODULE_BOXES_COLUMN_MANUFACTURERS_TITLE;
      $this->description = MODULE_BOXES_COLUMN_MANUFACTURERS_DESCRIPTION;

      if ( defined('MODULE_BOXES_COLUMN_MANUFACTURERS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_COLUMN_MANUFACTURERS_SORT_ORDER;
		$this->enabled = (MODULE_BOXES_COLUMN_MANUFACTURERS_STATUS == 'True');
		$this->pages = MODULE_BOXES_COLUMN_MANUFACTURERS_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_COLUMN_MANUFACTURERS_CONTENT_PLACEMENT == 'Column Left') ? 'boxes_column_left' : 'boxes_column_right');
		$this->placement = MODULE_BOXES_COLUMN_MANUFACTURERS_CONTENT_PLACEMENT;
      }
    }

    

    function execute() {
     global $HTTP_GET_VARS, $oscTemplate,$PHP_SELF;
		if (isset($HTTP_GET_VARS['products_id'])) 
		{
			if(isset($HTTP_GET_VARS['products_id'])){
			$category_query = tep_db_query("select cd.categories_name, cd.categories_id from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_TO_CATEGORIES." pc, ".TABLE_CATEGORIES_DESCRIPTION." cd where p.products_id = pc.products_id and pc.categories_id = cd.categories_id and p.products_id = '".(int)$HTTP_GET_VARS['products_id']."'");
			$category = tep_db_fetch_array($category_query);
			$manufacturer_query = tep_db_query("select m.manufacturers_name, m.manufacturers_id from ".TABLE_PRODUCTS." p, ".TABLE_MANUFACTURERS." m where m.manufacturers_id = p.manufacturers_id and p.products_id = '".(int)$HTTP_GET_VARS['products_id']."'");
			$manufacturer = tep_db_fetch_array($manufacturer_query);
			if(!empty($manufacturer['manufacturers_id'])) {
			//$manufacturers_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '" class="btn_man2"><b>' . $manufacturer['manufacturers_name'] . ' ('.tep_count_products_in_manufacturers($manufacturer['manufacturers_id']).' produk)</b></a>';
			$cat_search_query = tep_db_query("select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and p.manufacturers_id = '" . (int)$manufacturer['manufacturers_id'] . "' order by cd.categories_name");
						$manufacturers_list .='<ul>';
						if (tep_db_num_rows($cat_search_query) > 1) {
							 while ($cat_search = tep_db_fetch_array($cat_search_query)) {
						
									if ($category['categories_id']==$cat_search['id']) 
									{
										$cat_name = '<strong>' . $cat_search['name'] . '</strong>';
									}
									 else {
										$cat_name = $cat_search['name'];
									 }
									 $cat_name_edit = str_replace(' ', '-', ((strlen( $cat_search['name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($cat_search['name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' :  $cat_search['name'])); 
									 $manu_name_edit = tep_get_manufacturers_name($manufacturer['manufacturers_id'],1);
									$category_list .= '<li><a href="'.tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id'].'?filter_id='.$cat_search['id']).'">' . $cat_name .' ('. tep_count_products_in_category_manufacturers($cat_search['id'],$manufacturer['manufacturers_id']).') </a></li>';	
								}
								  $manufacturers_list .= $category_list;
						}
						$manufacturers_list .= "</ul>";
					$data = '<div class="box"><div class="box-heading"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">'.$manufacturer['manufacturers_name'].'</a></div><div class="box-content box-category">'.$manufacturers_list.
							'</div></div>';
			
				}
			}
			$oscTemplate->addBlock($data, $this->group);
			
		}
		else if (isset($HTTP_GET_VARS['cPath']) && !isset($HTTP_GET_VARS['filter_id'])) {
					if (strpos($HTTP_GET_VARS['cPath'], '_') !== false) {  
						$category_path = strstr_after($HTTP_GET_VARS['cPath'], '_');
						$category_path_first = strstr($HTTP_GET_VARS['cPath'],'_',true);
						$cat_name = tep_categories_name($category_path_first)."/".tep_categories_name($category_path)."/";		
					}  
					else {
						$category_path = $HTTP_GET_VARS['cPath'];
						$cat_name = tep_categories_name($category_path)."/";
					}
					$category_list = '<ul>';
					$manu_search_query= tep_db_query("select distinct m.manufacturers_id as id, m.manufacturers_name as name, m.manufacturers_image as image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_path. "' order by m.manufacturers_name");
					if (tep_db_num_rows($manu_search_query) > 0) {

						while ($manu_search = tep_db_fetch_array($manu_search_query)) {
	
							if (isset($HTTP_GET_VARS['filter_id']) && $HTTP_GET_VARS['filter_id']== $manu_search['id'])
							{
								$manu_name = '<strong>' . $manu_search['name'] . '</strong>';

							}							
							else {
								$manu_name = $manu_search['name'];
							}
							$manu_path = tep_manufacturers_name($manu_search['id']).'/';
							$category_list .= '<li><a href="'.tep_href_link(FILENAME_DEFAULT,'cPath='.$HTTP_GET_VARS['cPath'].'?filter_id='.$manu_search['id']).'">' . $manu_name . '</a></li>';
						}	
						$category_list .= '</ul>';
					
						  $content2 = $category_list;
						
						$data = '<div class="box"><div class="box-heading">' . MODULE_BOXES_COLUMN_MANUFACTURERS_BOX_TITLE . '</div><div class="box-content box-category">'.$content2.'</div></div>';
								
					}
					$oscTemplate->addBlock($data, $this->group);
			}
			
		else if (isset($HTTP_GET_VARS['manufacturers_id']) or isset($HTTP_GET_VARS['filter_id'])) {
			if(isset($HTTP_GET_VARS['filter_id'])) { $mID = tep_db_prepare_input($HTTP_GET_VARS['filter_id']); } else { $mID = tep_db_prepare_input($HTTP_GET_VARS['manufacturers_id']); }
			$categories_string = '';
			$category_query = tep_db_query("select distinct c.categories_id as id, cd.categories_name as name, p.manufacturers_id as mID from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and p.manufacturers_id = '" . (int)$mID . "'");
			while($category = tep_db_fetch_array($category_query))
			{
				$category['id'] == strstr_after($HTTP_GET_VARS['cPath'], '_') ? $catName='<strong>'.$category['name'].'</strong>' : $catName=$category['name'];
				$categories_string .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT,tep_get_category_full_path($category['id']).'&filter_id='.$mID).'">'.$catName.'('. tep_count_products_in_category_manufacturers($category['id'],$mID).')</a></li>';
			}
			$data = '<div class="box"><div class="box-heading">'.MODULE_BOXES_COLUMN_MANUFACTURERS_CATEGORY_BOX_TITLE.'</div><div class="box-content box-category"><strong>'.tep_manufacturers_name($mID).'</strong> : <ul>'.$categories_string.'</ul></div></div>';
			$oscTemplate->addBlock($data, $this->group);
		 }
		 
		// else
		// {
		//	$manufacturers_string = '';
			//$manu_query = tep_db_query("select manufacturers_id, manufacturers_name from ".TABLE_MANUFACTURERS." order by manufacturers_name");
			//while($manu = tep_db_fetch_array($manu_query)){
		//		$manufacturers_string .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT,'manufacturers_id='.$manu['manufacturers_id']).'">'.$manu['manufacturers_name'].'</a></li>';
			//}
			//$data = '<div class="box"><div class="box-heading">'.MODULE_BOXES_COLUMN_MANUFACTURERS_BOX_TITLE.'</div><div class="box-content box-category"><ul>'.$manufacturers_string.'</ul></div></div>';
			//$oscTemplate->addBlock($data, $this->group);
		//}
			
	
				//pilihan karakter END
		
    }
    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_COLUMN_MANUFACTURERS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturers Module', 'MODULE_BOXES_COLUMN_MANUFACTURERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_COLUMN_MANUFACTURERS_CONTENT_PLACEMENT', 'Column Left', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Column Left\', \'Column Right\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_COLUMN_MANUFACTURERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_COLUMN_MANUFACTURERS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_COLUMN_MANUFACTURERS_STATUS', 'MODULE_BOXES_COLUMN_MANUFACTURERS_CONTENT_PLACEMENT', 'MODULE_BOXES_COLUMN_MANUFACTURERS_SORT_ORDER','MODULE_BOXES_COLUMN_MANUFACTURERS_DISPLAY_PAGES');
    }
  }
?>
