<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'heading' => BOX_HEADING_ORDERS,
	'icon' => 'fa fa-shopping-cart',
    'apps' => array(
      array(
        'code' => FILENAME_ORDERS,
        'title' => BOX_ORDERS_ORDERS,
        'link' => tep_href_link(FILENAME_ORDERS)
      )
    )
  );
?>
