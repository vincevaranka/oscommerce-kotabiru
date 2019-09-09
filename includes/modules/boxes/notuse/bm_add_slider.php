<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_slider {
    var $code = 'bm_add_slider';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_slider() {
      $this->title = MODULE_BOXES_SLIDER_TITLE;
      $this->description = MODULE_BOXES_SLIDER_DESCRIPTION;

      if ( defined('MODULE_BOXES_SLIDER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SLIDER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SLIDER_STATUS == 'True');
        $this->pages = MODULE_BOXES_SLIDER_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_SLIDER_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $HTTP_GET_VARS, $cPath,$cart, $languages_id,$currencies;
		$data = '';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .='<style>';
		$style_query = tep_db_query("select slider_id, slider_title_style, slider_text1_style, slider_text2_style from ".TABLE_SLIDER." where slider_status = '1'");
		while($style = tep_db_fetch_array($style_query)) {
			$data .= '.sh'.$style['slider_id'].' {'.$style['slider_title_style'].'}';
			$data .= '.sta'.$style['slider_id'].' {'.$style['slider_text1_style'].'}';
			$data .= '.stb'.$style['slider_id'].' {'.$style['slider_text2_style'].'}';
		}
		$data .='</style>';
		//$data .= '<style>.sh1{position:absolute; background:red; padding:10px; color:#fff; top:200px; left:300px;}</style>';
		$data .= '<div class="wrap-wide"><div class="container slider"><div id="myCarousel" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">';	
		$startslider_query = tep_db_query("select slider_id from ".TABLE_SLIDER." where slider_status = '1' order by slider_sort ASC");
		$to = '0';
		while($startslider = tep_db_fetch_array($startslider_query))
		{  if($to == '0'){ $active = 'class="active"'; } else {$active = '';}
			$data .= '<li data-target="#myCarousel" data-slide-to="'.$to.'" '.$active.'></li>';
			$to++;
		}
		$data .= '</ol><div class="carousel-inner">';
		$slider_query = tep_db_query("select slider_id, slider_title, slider_text1, slider_text2, slider_image, slider_ani_title,slider_ani_text1,slider_ani_text2 from ".TABLE_SLIDER." where slider_status = '1' order by slider_sort ASC");
		$tos = '1';
		while($slider = tep_db_fetch_array($slider_query)) {
			$id = $slider['slider_id'];
			$data .= '<div class="item '.(($tos=='1') ? 'active':'').'">';
			$data .= '<img src="'.DIR_WS_IMAGES_SLIDER.$slider['slider_image'].'" alt="'.$slider['slider_title'].'">';
			$data .= '<div class="container"><div class="carousel-caption1">';
			((!empty($slider['slider_title'])) ? $data .= '<h1 class="sh'.$id.' '.$slider['slider_ani_title'].'">'.$slider['slider_title'].'</h1>' : '');
			((!empty($slider['slider_text1'])) ? $data .= '<div class="sta'.$id.' '.$slider['slider_ani_text1'].'">'.$slider['slider_text1'].'</div>' : '');
			((!empty($slider['slider_text2'])) ? $data .= '<div class="stb'.$id.' '.$slider['slider_ani_text2'].'">'.$slider['slider_text2'].'</div>' : '');
			$data .= '</div></div></div>';
			$tos++;
		}
		$data .= '	
				  </div>
				  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
				  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
			 </div></div></div>';
	
		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SLIDER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_SLIDER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SLIDER_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SLIDER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SLIDER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SLIDER_STATUS', 'MODULE_BOXES_SLIDER_CONTENT_PLACEMENT', 'MODULE_BOXES_SLIDER_SORT_ORDER','MODULE_BOXES_SLIDER_DISPLAY_PAGES');
    }
  }
  
?>