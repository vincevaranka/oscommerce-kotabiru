<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_slider1 {
    var $code = 'bm_add_slider1';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_SLIDER1_TITLE;
      $this->description = MODULE_BOXES_SLIDER1_DESCRIPTION;

      if ( defined('MODULE_BOXES_SLIDER1_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SLIDER1_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SLIDER1_STATUS == 'True');
        $this->pages = MODULE_BOXES_SLIDER1_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_SLIDER1_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $HTTP_GET_VARS, $cPath,$cart, $languages_id,$currencies;
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) {
			$data ='';
			$slider_query = tep_db_query("select slider_id, slider_title, slider_text1, slider_text2, slider_image, slider_ani_title,slider_ani_text1,slider_ani_text2 from ".TABLE_SLIDER." where slider_status = '1' order by slider_sort ASC");

			while($slider = tep_db_fetch_array($slider_query))
			{
				$id = $slider['slider_id'];
				$imageslider .='<a class="nivo-imageLink" href="'.$slider['slider_link'].'"><img src="'.DIR_WS_IMAGES.'/slider/'.$slider['slider_image'].'" alt="'.$slider['slider_name'].'" />';
				((!empty($slider['slider_title'])) ? $imageslider .= '<h1 class="caption sh'.$id.' '.$slider['slider_ani_title'].'">'.$slider['slider_title'].'</h1>' : '');
				((!empty($slider['slider_text1'])) ? $imageslider .= '<div class="caption sta'.$id.' '.$slider['slider_ani_text1'].'">'.$slider['slider_text1'].'</div>' : '');
				((!empty($slider['slider_text2'])) ? $imageslider .= '<div class="caption stb'.$id.' '.$slider['slider_ani_text2'].'">'.$slider['slider_text2'].'</div>' : '');
				$imageslider .= '</a>';
			}

			$data .= '<div class="wrap-wide slider-bg"><div class="container slider"><section class="slider-wrapper"> <div id="slideshow" class="nivoSlider">'.$imageslider.'</div></section></div></div>';
			$data .= '<script type="text/javascript">$(document).ready(function() {$(\'#slideshow\').nivoSlider();});</script>';
			$oscTemplate->addBlock($data, $this->group);
			}
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SLIDER1_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_SLIDER1_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SLIDER1_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SLIDER1_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SLIDER1_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	   tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Slider','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Slider Outer Background','slider-bg-out','.slider-bg','background','','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_value,t_group) values ('Slider Inner Background','slider-bg-in','.slider','background','','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_SLIDER1_STATUS', 'MODULE_BOXES_SLIDER1_CONTENT_PLACEMENT', 'MODULE_BOXES_SLIDER1_SORT_ORDER','MODULE_BOXES_SLIDER1_DISPLAY_PAGES');
    }
  }
  
?>