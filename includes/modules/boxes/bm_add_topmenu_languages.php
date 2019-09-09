<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_topmenu_languages {
    var $code = 'bm_add_topmenu_languages';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_topmenu_languages() {
      $this->title = MODULE_BOXES_TOPMENU_LANGUAGES_TITLE;
      $this->description = MODULE_BOXES_TOPMENU_LANGUAGES_DESCRIPTION;

      if ( defined('MODULE_BOXES_TOPMENU_LANGUAGES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TOPMENU_LANGUAGES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TOPMENU_LANGUAGES_STATUS == 'True');
        $this->pages = MODULE_BOXES_TOPMENU_LANGUAGES_DISPLAY_PAGES;
         $this->group = ((MODULE_BOXES_TOPMENU_LANGUAGES_CONTENT_PLACEMENT == 'Left TopMenu') ? 'boxes_topmenu_left' : 'boxes_topmenu_right');
      }
    }

    function execute() {
      global $PHP_SELF, $lng, $request_type, $oscTemplate, $language, $current_lang_key;

      if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
        if (!isset($lng) || (isset($lng) && !is_object($lng))) {
          include(DIR_WS_CLASSES . 'language.php');
          $lng = new language;
        }

        if (count($lng->catalog_languages) > 1) {
          $languages_string = '';
          reset($lng->catalog_languages);
          foreach( $lng->catalog_languages as $key => $value ) {
            if ( $value['directory'] == $language ) {
              $current_lang_key = $key;
							$current_lang_icon = $value['image'];
							$current_lang_name = $value['name'];
							$current_lang_directory = $value['directory'];
              break;
            }
          }
          reset($lng->catalog_languages);
          $home_page_redirect = defined( 'USU5_HOME_PAGE_REDIRECT' ) && ( USU5_HOME_PAGE_REDIRECT == 'true' ) ? true : false;
					while (list($key, $value) = each($lng->catalog_languages)) {
						if ($current_lang_key == $key )	{
							$current_lang = 'activ';
						}else{
							$current_lang = '';
						}
					  if ( defined( 'USU5_MULTI_LANGUAGE_SEO_SUPPORT' ) && ( USU5_MULTI_LANGUAGE_SEO_SUPPORT == 'true' )
                                                              && defined( 'USU5_ENABLED' )
                                                              && ( USU5_ENABLED == 'true' ) ) {
              if( false === ( $language == $value['directory'] ) ) { // we don't want to show a link to the currently loaded language
                if ( false !== $home_page_redirect ) { // If we are using the root site redirect
                  $link = str_replace( array( FILENAME_DEFAULT, '/' . $current_lang_key ), '', tep_href_link( FILENAME_DEFAULT ) );
                } else {
                  $link = str_replace('/' . $current_lang_key, '', tep_href_link( FILENAME_DEFAULT ) );
                }
                if ( $key !== DEFAULT_LANGUAGE ) { // if it is not the default language we are dealing with
                  if ( false === strpos( $link, '.php' ) && ( false !== $home_page_redirect ) ) {
                    $link_array = explode( '?', $link );
                    $qs = array_key_exists( 1, $link_array ) ? ( '?' . $link_array[1] ) : '';
                    $link = $link_array[0] . FILENAME_DEFAULT . '/' . $key . $qs;
                  } else {
                    $link_array = explode( '?', $link );
                    $qs = array_key_exists( 1, $link_array ) ? ( '?' . $link_array[1] ) : ''; 
                    $link = str_replace( '.php', '.php/' . $key . $qs, $link );
                  }
                }
                // USU5  shows the language link and image
                $languages_string .= ' <li class="'.$current_lang_key.'"><a href="' . $link . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name'],'','','') . $value['name'].'</a></li> ';
              }
            } else { // Just do the standard osCommerce links
            // Standard osCommerce shows the language link and image
              $languages_string .= '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name'],'','','') .' '. $value['name'].'</a> ';
            }
          }
          $data =  "\n" .
					'<div class="box-languages">'.tep_image(DIR_WS_LANGUAGES . $current_lang_directory . '/images/' . $current_lang_icon, $current_lang_name,'','','').' '.$current_lang_name.''. "\n" .
		  		'<i class="fa fa-angle-down"></i><div class="lang-content">'. "\n" .
				  $languages_string  ."\n" .
          '		</div>'. "\n" .
				  '</div>'. "\n";
				 
          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TOPMENU_LANGUAGES_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Language Module', 'MODULE_BOXES_TOPMENU_LANGUAGES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TOPMENU_LANGUAGES_CONTENT_PLACEMENT', 'Left TopMenu', 'Should the module be loaded in the Top Menu Block?', '6', '1', 'tep_cfg_select_option(array(\'Left TopMenu\',\'Right TopMenu\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TOPMENU_LANGUAGES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TOPMENU_LANGUAGES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Top Menu Languages','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Text Color','lang-col','.box-languages','color','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group,t_attr_1) values ('a link Color','lang-a','.box-languages a','color','".$id."','background')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group,t_attr_1) values ('a link Color Hover','lang-a-hover','.box-languages a:hover','color','".$id."','background')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_TOPMENU_LANGUAGES_STATUS', 'MODULE_BOXES_TOPMENU_LANGUAGES_CONTENT_PLACEMENT', 'MODULE_BOXES_TOPMENU_LANGUAGES_SORT_ORDER','MODULE_BOXES_TOPMENU_LANGUAGES_DISPLAY_PAGES');
    }
  }
  
?>