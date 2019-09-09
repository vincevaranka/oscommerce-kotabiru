<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
  $order_status_query = tep_db_query("select orders_status_name from ".TABLE_ORDERS_STATUS." where orders_status_id = '".DEFAULT_ORDERS_STATUS_ID."'");
  $order_status = tep_db_fetch_array($order_status_query);
  $order_query = tep_db_query("select orders_id from ".TABLE_ORDERS." where orders_status ='".DEFAULT_ORDERS_STATUS_ID."'");
  $order = tep_db_num_rows($order_query);
  $reviews_query = tep_db_query("select reviews_id from ".TABLE_REVIEWS." where reviews_status ='0'");
  $reviews = tep_db_num_rows($reviews_query);
  $testi_query = tep_db_query("select testi_id from ".TABLE_TESTIMONIAL." where testi_status ='0'");
  $testi = tep_db_num_rows($testi_query);
  $total = $order + $reviews + $testi;
?>
<header>	
	<div class="row">
		<div class="col-xs-6">
			<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'oscommerce.png', 'osCommerce Online Merchant v' . tep_get_version()) . '</a>'; ?>
		</div>
		
		<div class="col-xs-6 text-right top-menu">
		
		<div class="menu-shop btn">
			<i class="fa fa-bell bell"><span class="numb"><?php echo $total;?></span></i>
			<div>
			<table class="table">
				<tr>
					<td class="text-left"><a href="<?php echo tep_href_link(FILENAME_ORDERS);?>"><?php echo $order_status['orders_status_name'];?></a?></td>
					<td><a href="<?php echo tep_href_link(FILENAME_ORDERS);?>"><?php echo $order;?></a></td>
				</tr>
				
				<tr>

					<td class="text-left"><a href="<?php echo tep_href_link(FILENAME_REVIEWS);?>">Product Reviews</a></td>
					<td><a href="<?php echo tep_href_link(FILENAME_REVIEWS);?>"><?php echo $reviews;?></a></td>
				</tr>
		
				<tr>
					<td class="text-left"><a href="<?php echo tep_href_link(FILENAME_TESTIMONIAL);?>">Testimonial</a></td>
					<td><a href="<?php echo tep_href_link(FILENAME_TESTIMONIAL);?>"><?php echo $testi;?></a></td>
				</tr>
				<tr>
					<td class="text-right">Total : </td>
					<td><?php echo $total;?></td>
				</tr>
			</table>
			</div>
		</div>
		<div class="menu-shop btn">
			<i class="fa fa-plus"></i>
			<div>
				<a href="<?php echo tep_href_link(FILENAME_CATEGORIES);?>" class="btn"><?php echo BOX_CATALOG_CATEGORIES_PRODUCTS;?></a>
				<a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS);?>" class="btn"><?php echo BOX_CATALOG_MANUFACTURERS;?></a>
				<a href="<?php echo tep_href_link(FILENAME_SPECIALS);?>" class="btn"><?php echo BOX_CATALOG_SPECIALS;?></a>
			</div>
		</div>
		<div class="menu-shop btn">
			<i class="fa fa-shopping-cart"></i>
			<div>
				<a href="<?php echo tep_catalog_href_link();?>" class="btn"><?php echo  HEADER_TITLE_ONLINE_CATALOG;?></a>
				<a class="btn">Documentation</a>
				<a class="btn">Support</a>
	
			</div>
		</div>
		<div class="menu-shop btn">
			<i class="fa fa-user"></i>
			<div>
				<?php echo 'Logged in As : '.$admin['username'];?><br />
				<a href="<?php echo tep_href_link(FILENAME_ADMINISTRATORS);?>" class="btn">Administrators</a>
				<a href="<?php echo tep_href_link(FILENAME_LOGIN,'action=logoff');?>" class="btn">Logoff</a>

			</div>
		</div>
	</div>
	</div>
</header>

