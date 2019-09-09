<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

?>

<div class="container" style="background:#eaeaea">

	<!-- HEADER TOP-MENU AND CART -->
	<div class="row menu-top">
		<div class="col-md-4">
			<a href ="" class="btn btn-large">Home</a>
			<a href ="" class="btn btn-large">Register</a>
			<a href ="" class="btn btn-large">Login</a>
		</div>
		<div class="col-md-3 col-md-offset-5 cart active">
			<div class="btn cart-btn"><i class="glyphicon glyphicon-shopping-cart"></i> Cart  <span class="badge">42</span></div>
			<div class="cart-content">
				 
				 <?php
					$carts = '<table class="table">';
					$products = $cart->get_products();
					for($i=0, $n=sizeof($products); $i<$n; $i++)
					{
					   $carts .= '<tr>
									<td>'.tep_image(DIR_WS_IMAGES.$products[$i]['image'],'',50,50).'</td>
									<td>'.$products[$i]['name'].'</td><td class="quantity">'.$products[$i]['quantity'].'</td>
									<td>'.$currencies->format(($products[$i]['quantity'] * $products[$i]['price'])).'</td>
									<td><a href="'.tep_href_link(FILENAME_SHOPPING_CART, 'products_id=' . $products[$i]['id'] . '&action=remove_product').'"><i class="glyphicon glyphicon-remove"></i></a></td>
								</tr>'; 
					}
					$carts .='</table>';
					$carts .='<a href="'.tep_href_link(FILENAME_SHOPPING_CART).'" class="btn">'.TEXT_MY_CART.'</a>';
					if(tep_session_is_registered('customer_id')) {
						$carts .= '<a href="'. tep_href_link(basename(FILENAME_CHECKOUT)).'" class="btn btn-primary">Checkout</a>'; 
					} else {
						$carts .='<a href="'. tep_href_link(basename(FILENAME_LOGIN).'?status=1').'" class="btn btn-primary"> Checkout</a>'; } 
					if ($cart->count_contents() > 0) { echo $carts; } else { echo TEXT_CART_EMPTY;}
				?>
			</div>
					
		</div>
	</div>
	<!-- END HEADER TOP-MENU AND CART -->
	
	

	<header>
	<!-- HEADER LOGO AND SEARCH -->
	<div class="main-header row">
		<div class="col-md-4 logo">
			<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME) . '</a>'; ?>
		</div>
		<div class="col-md-4 col-md-offset-4 search">
	   <?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'). tep_draw_hidden_field('search_in_description', '1') 
		 .tep_hide_session_id(); ?> 
			<div class="input-group">
			<?php echo tep_draw_input_field('keywords', '', 'class="form-control"');?>
			<span class="input-group-btn">
				<button class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
			</span>
			</div>
		</form>
		</div>
		
	</div>
	<!-- END HEADER LOGO AND SEARCH -->
	</header>
	

	<!-- MENU UTAMA -->
	<div class="main-menu row">
	
		<?php
			for ($i=0; $i<$tree[$counter]['level']; $i++) {
				echo 'asdasd';
			}
			$categories_string = '';
				$tree = array();
				$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
				
				
				while ($categories = tep_db_fetch_array($categories_query))  
				{
					$cID = $categories['categories_id'];
					$catcount_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id='".(int)$cID."'");
					$catcount = tep_db_fetch_array($catcount_query);	
					$cat_name = tep_categories_name($cID);
					$cpath = 'cPath='.$cID;
					$categories_string .= '<div class="column"><a href="'.tep_href_link(FILENAME_DEFAULT, $cpath).'">'.strtoupper($categories['categories_name']).'</a><div><ul>';
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
		$data_menu = '<nav class="menu menu-sty menu-bg">
				
					<ul>

						<li class="categories_hor"><a>'.HEADER_TITLE_CATEGORY.'</a>
						<div>'.$categories_string.'</div>
						</li>
						  <li> <a href="'.tep_href_link(FILENAME_PRODUCTS_NEW).'" >'.HEADER_TITLE_NEW.'</a>
						</li>
						<li> <a href="'.tep_href_link(FILENAME_SPECIALS).'" >'.HEADER_TITLE_SPECIAL.'</a>
						</li>
						<li><a href="'.tep_href_link(FILENAME_PRODUCTS_BEST).'" >'.HEADER_TITLE_BEST.'</a>
						</li>
         
						
					</ul>
					</nav>
					<nav class="menu m-menu menu-sty"> <span>Kategori Produk</span>
						<ul>
					<li class="categories"><a>Categories</a>
						<div>'.$categories_string.'</div>
						<div class="menu-field"><a href="'.tep_href_link(FILENAME_SPECIALS).'" class="manu bigman">'.HEADER_TITLE_SPECIAL.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_NEW).'" class="manu bigman">'.HEADER_TITLE_NEW.'</a><a href="'.tep_href_link(FILENAME_PRODUCTS_BEST).'" class="manu bigman">'.HEADER_TITLE_BEST.'</a></div>
					</li>
					
					</ul>
					</nav>';
			echo $data_menu;
		?>
	</div>
	<!-- END MENU UTAMA -->

</div>



<div class="grid_24 ui-widget infoBoxContainer">
  <div class="ui-widget-header infoBoxHeading"><?php echo '&nbsp;&nbsp;' . $breadcrumb->trail(' &raquo; '); ?></div>
</div>

<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
