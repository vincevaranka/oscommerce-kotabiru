<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'heading' => BOX_HEADING_CUSTOM,
	'icon' => 'fa fa-star',
    'apps' => array(
		array(
        'code' => FILENAME_THEME,
        'title' => BOX_CUSTOM_THEME,
        'link' => tep_href_link(FILENAME_THEME)
      ),
	  array(
        'code' => FILENAME_INFORMATION,
        'title' => BOX_CUSTOM_INFORMATION,
        'link' => tep_href_link(FILENAME_INFORMATION)
      ),
      array(
        'code' => FILENAME_SLIDER,
        'title' => BOX_CUSTOM_SLIDER,
        'link' => tep_href_link(FILENAME_SLIDER)
      ),
	  array(
		'code' => FILENAME_FONTS,
		'title' => BOX_CUSTOM_FONTS,
		'link' => tep_href_link(FILENAME_FONTS)
		),
	  array(
        'code' => FILENAME_FOLLOW_US,
        'title' => BOX_CUSTOM_FOLLOW_US,
        'link' => tep_href_link(FILENAME_FOLLOW_US)
      ),
	  array(
        'code' => FILENAME_TESTIMONIAL,
        'title' => BOX_CUSTOM_TESTIMONIAL,
        'link' => tep_href_link(FILENAME_TESTIMONIAL)
      ),
	   array(
        'code' => FILENAME_BANNER,
        'title' => BOX_CUSTOM_BANNER,
        'link' => tep_href_link(FILENAME_BANNER)
      ),
    )
  );
?>
