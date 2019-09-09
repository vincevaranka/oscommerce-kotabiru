<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_add_footer {
    var $code = 'bm_add_footer';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;	

    function bm_add_footer() {
      $this->title = MODULE_BOXES_FOOTER_TITLE;
      $this->description = MODULE_BOXES_FOOTER_DESCRIPTION;

      if ( defined('MODULE_BOXES_FOOTER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_FOOTER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_FOOTER_STATUS == 'True');
        $this->pages = MODULE_BOXES_FOOTER_DISPLAY_PAGES;
        $this->group = ((MODULE_BOXES_FOOTER_CONTENT_PLACEMENT == 'Footer') ? 'boxes_column_footer' : '');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $cart, $languages_id,$currencies;
	  $data = '<div class="container bg-footer">
				<div class="row foot-content">
					<div class="col-md-6 about">';
						$about_query = tep_db_query("select id.info_name, id.info_content from ".TABLE_INFORMATION." i, ".TABLE_INFORMATION_DESCRIPTION." id where i.info_id = id.info_id and i.info_pos='0' and id.language_id = '".(int)$languages_id."'");
								$about = tep_db_fetch_array($about_query);
								$data .= '<h3>'.$about['info_name'].'</h3>';
								$data .= '<div class="text-justify">'.$about['info_content'].'</div>';
					
	  $data .=	'<div class="information">';
							$info_query = tep_db_query("select i.info_id, id.info_name from ".TABLE_INFORMATION." i, ".TABLE_INFORMATION_DESCRIPTION." id where status = '1' and i.info_id = id.info_id and id.language_id = '".(int)$languages_id."' and i.info_pos = '10' order by sort");
							while($info = tep_db_fetch_array($info_query))
							{
								$data .= '<a href="'.tep_href_link(FILENAME_INFORMATION,'iID='.$info['info_id']).'" class="btn">'.$info['info_name'].'</a>';
							}
	  $data .= '</div>
					</div>		
					
							<div class="col-md-2 col-sm-4">
								<div class="social">
									<h3>FOLLOW US</h3>
									<div class="follow-us">';
									$fol_query = tep_db_query("select fs_name,fs_link,fs_icon from ".TABLE_FOLLOW_US." where fs_status = '1' order by fs_sort");
								 while($fol = tep_db_fetch_array($fol_query)){
									$data .= '<a href="'.$fol['fs_link'].'"><i class="'.$fol['fs_icon'].'"></i></a>';
								 }
							
	$data .= '</div></div>
							</div>
							<div class="col-md-2 col-sm-4">
								<div>
									<h3>MY ACCOUNT</h3>
									<ul class="list-unstyled">';
									
										if(tep_session_is_registered('customer_id')){
								$data .=
										 '
										 
										 <li><a href="'.tep_href_link(FILENAME_ACCOUNT).'">'.HEADER_TITLE_MY_ACCOUNT.'</a></li>
										<li><a href="'.tep_href_link(FILENAME_ACCOUNT_HISTORY).'">'.HEADER_TITLE_ACCOUNT_HISTORY.'</a></li>
										<li><a href="'.tep_href_link(FILENAME_ADDRESS_BOOK).'">'.HEADER_TITLE_ADDRESS_BOOK.'</a></li>
										<li><a  data-toggle="modal" href="#testi-modal">'.TEXT_ADD_TESTIMONIAL.'</a></li>
										<li><a href="'.tep_href_link(FILENAME_LOGOFF).'">'.HEADER_TITLE_LOGOFF.'</a></li>';
							
										} else {	
								$data .=
										'<li><a href="'.tep_href_link(FILENAME_CREATE_ACCOUNT).'">'.HEADER_TITLE_CREATE_ACCOUNT.'</a></li>
										<li><a href="'.tep_href_link(FILENAME_LOGIN).'">'.HEADER_TITLE_LOGIN.'</a>
										<li><a  data-toggle="modal" href="#testi-modal">'.TEXT_ADD_TESTIMONIAL.'</a></li>';
								}
								$data .= '<li><a href="'.tep_href_link(FILENAME_ADVANCED_SEARCH).'">'.HEADER_TITLE_ADVANCED_SEARCH.'</a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-2 col-sm-4">';
								$contact_query = tep_db_query("select id.info_name, id.info_content from ".TABLE_INFORMATION." i, ".TABLE_INFORMATION_DESCRIPTION." id where i.info_id = id.info_id and i.info_pos='1' and id.language_id = '".(int)$languages_id."'");
								$contact = tep_db_fetch_array($contact_query);
								$data .= '<h3>'.$contact['info_name'].'</h3>';
								$data .= $contact['info_content'];
						$data .='
							</div></div>';
						$data .= '<div class="row powered">
								<div class="col-md-6 col-xs-12 power-by">&copy; 2014 Powered by <a href="http://www.kotabiru.com">Kotabiru</a></div>
								<div class="col-md-6 col-xs-12 text-right">';
								 
						if ($oscTemplate->hasBlocks('boxes_column_powered_right')) 
						{$data .= $oscTemplate->getBlocks('boxes_column_powered_right');}
					  $data .='</div>
						</div>
				
				<div class="modal fade" id="testi-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Modal title</h4>
						  </div>
						  <div class="modal-body">
							  <iframe src="'.tep_href_link(FILENAME_CREATE_TESTIMONIAL).'"></iframe>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						  </div>
						</div>
					  </div>
					</div>
				
				';
	  $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_FOOTER_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_FOOTER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_FOOTER_CONTENT_PLACEMENT', 'Footer', 'Should the module be loaded in the Footer Block?', '6', '1', 'tep_cfg_select_option(array(\'Footer\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_FOOTER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_FOOTER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  tep_db_query("insert into ". TABLE_THEME_GROUP." (tg_name,tg_module,tg_status) values ('Footer','".$this->code."', '0')");
	  $id = tep_db_insert_id();
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_attr_1,t_group) values ('Base Footer','footer','#footer','color','background','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Icon Header','header-icon','#footer i','color','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Footer Text Header','footer-text-header','#footer h3','color','".$id."')");
	  tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Footer a link','footer-a','#footer a','color','".$id."')");
	   tep_db_query("insert into ".TABLE_THEME." (t_name,t_code,t_class,t_attr,t_group) values ('Footer a link Hover','footer-a-hover','#footer a:hover','color','".$id."')");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  $g_query = tep_db_query("select t_group from ".TABLE_THEME_GROUP." where tg_module='".$this->code."'");
	  $g = tep_db_fetch_array($g_query);
	  tep_db_query("delete from " . TABLE_THEME_GROUP." where tg_module = '".$this->code."'");
	  tep_db_query("delete from " .TABLE_THEME." WHERE t_group = '".(int)$g['t_group']."'");
    }

    function keys() {
      return array('MODULE_BOXES_FOOTER_STATUS', 'MODULE_BOXES_FOOTER_CONTENT_PLACEMENT', 'MODULE_BOXES_FOOTER_SORT_ORDER','MODULE_BOXES_FOOTER_DISPLAY_PAGES');
    }
  }
  
?>