<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_header {
    var $code = 'bm_add_header';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_HEADER_TITLE;
      $this->description = MODULE_BOXES_HEADER_DESCRIPTION;

      if ( defined('MODULE_BOXES_HEADER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_HEADER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_HEADER_STATUS == 'True');
        $this->pages = MODULE_BOXES_HEADER_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_HEADER_CONTENT_PLACEMENT == 'Header') ? 'boxes_column_header' : '');
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
		$data = '<div class="cont-head animate cont-head-pos custom-head-bg">
		<div class="container cont-bg header" >
			<div class="row" >
				<div class="col-md-3 top-logo" >
					<a href="'.tep_href_link(FILENAME_DEFAULT).'">'.tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME).'</a>
				</div>
				<div class="col-md-9 smain-menu text-right">
				<div class="menu"> 
					<ul class="megamenu">
						<li class="cat">
							<a class="mainmenu-bg">'.HEADER_TITLE_CATEGORY.'</a>
							<div>'.$categories_string.'
							</div>
						</li>
						<li> <a class="mainmenu-bg" href="'.tep_href_link(FILENAME_PRODUCTS_NEW).'" >'.HEADER_TITLE_NEW.'</a>
						</li>
						<li> <a class="mainmenu-bg" href="'.tep_href_link(FILENAME_SPECIALS).'" >'.HEADER_TITLE_SPECIAL.'</a></li>
						<li> <a class="mainmenu-bg" href="'.tep_href_link(FILENAME_PRODUCTS_BEST).'" >'.HEADER_TITLE_BEST.'</a></li>
					</ul>
				</div>
				<div class="btn-menu-user">
					<div class="btn shop-search mainmenu-bg"><i class="fa fa-search"></i>
						<div class="shop-search-content">
							'.tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'). tep_draw_hidden_field('search_in_description', '1') 
									 .tep_hide_session_id().'
									<div class="input-group">
									'.tep_draw_input_field('keywords', '','class="form-control input-lg" placeholder="What are you looking for?"').'
									<span class="input-group-btn">
									<button class="btn btn-default"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</form>
						</div>
					</div>
					
					<div class="btn shop-user mainmenu-bg '.(tep_session_is_registered('customer_id')? 'activ':'').'" ><i class="fa fa-user"></i>
						<div class="shop-user-content">';
								if(tep_session_is_registered('customer_id')){
									$data .= '<a href="'.tep_href_link(FILENAME_ACCOUNT).'" class="btn"><i class="fa fa-user"></i> '. HEADER_TITLE_MY_ACCOUNT.'</a><a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY).'" class="btn"><i class="fa fa-calendar"></i> '. HEADER_TITLE_ACCOUNT_HISTORY.'</a><a href="'.tep_href_link(FILENAME_ADDRESS_BOOK).'" class="btn"><i class="fa fa-book"></i> '.HEADER_TITLE_ADDRESS_BOOK.'</a><a href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING).'" class="btn"><i class="fa fa-shopping-cart"></i> '. HEADER_TITLE_CHECKOUT.'</a><a href="'.tep_href_link(FILENAME_LOGOFF).'" class="btn"><i class="fa fa-sign-out"></i> '.HEADER_TITLE_LOGOFF.'</a>';
								} else {
									$data .= '<a href="'.tep_href_link(FILENAME_CREATE_ACCOUNT).'" class="btn">'.HEADER_TITLE_CREATE_ACCOUNT.'</a><a href="'.tep_href_link(FILENAME_LOGIN).'" class="btn">'.HEADER_TITLE_LOGIN.'</a>';
								}
			$data .= '
						</div>
					</div>
					
					<div class="btn shop-cart mainmenu-bg'.(($cart->count_contents() > 0) ? 'activ' : '').'"><i class="fa fa-shopping-cart"></i>
						<div class="shop-cart-content">';
									$carts =  '<table class="table">';
									$products = $cart->get_products();
									for($i=0,$n=sizeof($products); $i<$n; $i++)
									{
										$carts .= '<tr><td>'.tep_image(DIR_WS_IMAGES_PRODUCT.$products[$i]['image'],'',50,50).'</td>
												<td>'.$products[$i]['name'].'</td><td class="quantity">'.$products[$i]['quantity'].'</td>
												<td>'.$currencies->format(($products[$i]['quantity'] * $products[$i]['price'])).'</td>
												<td><a href="'.tep_href_link(FILENAME_SHOPPING_CART, 'products_id=' . $products[$i]['id'] . '&action=remove_product').'"><i class="fa fa-remove"></i></a></td>
												</tr>'; 
									}
									$carts .='</table>';
									$carts .= '<div><a href="'.tep_href_link(FILENAME_SHOPPING_CART).'" class="btn btn-default">'.TEXT_MY_CART.'</a>';
									$carts .= '<a href="'. tep_href_link(basename(FILENAME_CHECKOUT_SHIPPING)).'" class="btn btn-primary"><i class="fa fa-check"></i> Checkout</a></div>'; 
									$data .= (($cart->count_contents() > 0) ? $carts : '<div class="text-center cart-empty">'.TEXT_CART_EMPTY.'</div>');
			$data .= '	
						</div>
					</div>
					</div>
				</div>
				</div>
		</div>
	</div></div>
<div class="wrap-wide">
	<div class="menu m-menu menu-sty"> <span>'.HEADER_TITLE_CATEGORY.'</span>
			<ul>
				<li class="categories">
								<div>'.$categories_string.'</div>					
								<div class="menu-field"><a href="'.tep_href_link(FILENAME_SPECIALS).'" class="manu bigman">'.HEADER_TITLE_SPECIAL.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_NEW).'" class="manu bigman">'. HEADER_TITLE_NEW.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_BEST).'" class="manu bigman">'.HEADER_TITLE_BEST.'</a></div>
				</li>			
			</ul>
	</div>
	</div>';

          $oscTemplate->addBlock($data, $this->group);
   
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_HEADER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_HEADER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_HEADER_CONTENT_PLACEMENT', 'Header', 'Should the module be loaded in the Header Block?', '6', '1', 'tep_cfg_select_option(array(\'Header\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_HEADER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_HEADER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	   tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Header','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_attr_1,t_compile,t_group) values ('Main Menu Drop Down','mainmenu','.mainmenu-bg','color','background','.shop-cart-content,.shop-user-content,.shop-search-content,.megamenu > li > div { border-top:3px solid ','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_attr_1,t_group) values ('Main Menu Drop Down Hover','mainmenu-hover','.mainmenu-bg:hover','color','background','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name, t_code, t_class, t_attr,t_group) values ('Header Background','head-bg','.custom-head-bg','background','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Category Menu Min Screen','menu-sty','.menu-sty','background','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_HEADER_STATUS', 'MODULE_BOXES_HEADER_CONTENT_PLACEMENT', 'MODULE_BOXES_HEADER_SORT_ORDER','MODULE_BOXES_HEADER_DISPLAY_PAGES');
    }
  }
  
?>