<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_medsos {
    var $code = 'bm_add_medsos';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function __construct() {
      $this->title = MODULE_BOXES_MEDSOS_TITLE;
      $this->description = MODULE_BOXES_MEDSOS_DESCRIPTION;

      if ( defined('MODULE_BOXES_MEDSOS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_MEDSOS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_MEDSOS_STATUS == 'True');
        $this->pages = MODULE_BOXES_MEDSOS_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_MEDSOS_CONTENT_PLACEMENT == 'Column Top') ? 'boxes_column_top' : 'boxes_column_bottom');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate,  $cPath, $languages_id;
		$data = '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=540894935925521&version=v2.0";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>
					<script>$(document).ready(function(){var owl = $("#testi");owl.owlCarousel({
								autoPlay : 3000,
							stopOnHover : true,
							  items : 1,
							  itemsDesktop : [1000,1],
							  itemsDesktopSmall : [900,1], 
							  itemsTablet: [600,1], 
							  pagination:false,
							  itemsMobile : false 
						  });
						  $(".next").click(function(){
							owl.trigger(\'owl.next\');
						  })
						  $(".prev").click(function(){
							owl.trigger(\'owl.prev\');
						  })
						});</script>
					';
		if(empty($cPath) && empty($HTTP_GET_VARS['manufacturers_id'])) 
		{
		$data .= '<div class="wrap-wide bg-medsos"><div class="container con-medsos">';
		$data .= '<div class="row">
					<div class="col-md-4"><div class="fb-like-box" data-href="https://www.facebook.com/kotabiruwebdesign" data-colorscheme="light"  data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div></div>
					<div class="col-md-4"><a class="twitter-timeline" href="https://twitter.com/kotabirudesign" data-chrome="noborders transparent" data-tweet-limit="2" width:"100%" data-aria-polite="assertive"
  height="300"  data-widget-id="523703537037877248">Tweets by @kotabirudesign</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
					<div class="col-md-4 testimonial"><h2>They Talk about Us</h2><div id="testi" class="text-center">';
		$testi_query = tep_db_query("select testi_name, testi_text from ".TABLE_TESTIMONIAL." where testi_status ='1' order by testi_date");
		while($testi = tep_db_fetch_array($testi_query)) {
		$data .= '<div class="item"><p class="bubble speech">'.substr($testi['testi_text'], 0, 250).'</p><span class="name"><i class="fa fa-user"></i>'.$testi['testi_name'].'</span></div>';
		}
		$data .='</div></div>
					
				';
		$data .= '</div></div></div>';
	

		}
        $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_MEDSOS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_MEDSOS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_MEDSOS_CONTENT_PLACEMENT', 'Column Top', 'Should the module be loaded in the Column Top or Bottom Block?', '6', '1', 'tep_cfg_select_option(array(\'Column Top\',\'Column Bottom\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_MEDSOS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_MEDSOS_DISPLAY_PAGES', '', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_MEDSOS_STATUS', 'MODULE_BOXES_MEDSOS_CONTENT_PLACEMENT', 'MODULE_BOXES_MEDSOS_SORT_ORDER','MODULE_BOXES_MEDSOS_DISPLAY_PAGES');
    }
  }
  
?>