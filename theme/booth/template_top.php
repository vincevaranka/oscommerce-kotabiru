<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php 

	echo '<link rel="stylesheet" href="ext/bootstrap/css/bootstrap.min.css">';
	$font_query = tep_db_query("select font_code,font_use from ".TABLE_FONTS." where status = '1'");
	$font = tep_db_fetch_array($font_query);
	echo '<style>'.$font['font_code'].' body{'.$font['font_use'].'}</style>';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/stylesheet.css">';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/megamenu.css">';
	echo '<link rel="stylesheet" href="ext/bootstrap/fonts/font-awesome.min.css">';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/owl.carousel.css">';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/owl.theme.css">';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/colorbox.css">';
	echo '<link rel="stylesheet" href="'.DIR_WS_THEME.THEME_DEFAULT.'/css/slideshow.css">';
	$theme_query = tep_db_query("select t_class, t_value, t_attr,t_attr_1,t_value_1,t_compile from ".TABLE_THEME);
	echo '<style>';
	while($theme = tep_db_fetch_array($theme_query)){
		if($theme['t_value'] != ''){
			echo $theme['t_class'].'{'.$theme['t_attr'].':'.$theme['t_value'].(($theme['t_impo']='1')?' !important':''). ';'.(($theme['t_value_1']!='') ? $theme['t_attr_1'].':'.$theme['t_value_1'].(($theme['t_impo_1']='1')?' !important':''):'').'}';
			if($theme['t_compile'] != '') {echo $theme['t_compile'].$theme['t_value'];}
		} else if ($theme['t_compile'] != '' && $theme['t_tb'] == '2'){
			echo $theme['t_compile'].'{'.$theme['t_attr'].':'.$theme['t_value'].';'.(($theme['t_value_1']!='') ? $theme['t_attr_1'].':'.$theme['t_value_1']:'').'}';
		}
	}
	$style_query = tep_db_query("select slider_id, slider_title_style, slider_text1_style, slider_text2_style from ".TABLE_SLIDER." where slider_status = '1'");
				while($style = tep_db_fetch_array($style_query)) {
					echo '.sh'.$style['slider_id'].' {'.$style['slider_title_style'].'}';
					echo '.sta'.$style['slider_id'].' {'.$style['slider_text1_style'].'}';
					echo '.stb'.$style['slider_id'].' {'.$style['slider_text2_style'].'}';
				}
	echo '</style>';
	
?>
<script type="text/javascript" src="ext/jquery/jquery-1.11.1.js"></script>
<script type="text/javascript" src="ext/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ext/jquery/bxGallery/jquery.bxGallery.1.1.min.js"></script>
<script type="text/javascript" src="ext/jquery/jquery.nivo.slider.pack.js"></script>
<?php
	echo '<script type="text/javascript" src="'.DIR_WS_THEME.THEME_DEFAULT.'/js/zoomsl-3.0.min.js"></script>';
	echo '<script type="text/javascript" src="'.DIR_WS_THEME.THEME_DEFAULT.'/js/menu.accordion.js"></script>';
	echo '<script type="text/javascript" src="'.DIR_WS_THEME.THEME_DEFAULT.'/js/owl.carousel.js"></script>';
	echo '<script type="text/javascript" src="'.DIR_WS_THEME.THEME_DEFAULT.'/js/colorbox-min.js"></script>';
	echo '<script type="text/javascript" src="'.DIR_WS_THEME.THEME_DEFAULT.'/js/custom.js"></script>';
	echo $oscTemplate->getBlocks('header_tags'); 
?>
<script>
$(window).scroll(function () {
	if ($(this).scrollTop() > 100) 
		{$('.backtop').fadeIn();} 
	else {	$('.backtop').stop().fadeOut();}
	
});$('.backtop').click(function () {$("html, body").animate({scrollTop: 0}, 1000);return false;});

</script>
 </head>
<body>

<?php
	$oscTemplate->buildBlocks();
	if($oscTemplate->hasBlocks('boxes_main')) {echo $oscTemplate->getBlocks('boxes_main');}
?>
<div class="wrap-wide head-one">
	<div class="top-head custom-head-bg">
		<div class="wrap-box">
		<div class="row">
			<div class="col-sm-6 col-xs-6 text-left lang-cur">
			<?php 
			if ($oscTemplate->hasBlocks('boxes_topmenu_left')) 
			{echo $oscTemplate->getBlocks('boxes_topmenu_left');}
			?>
			</div>
			<div class="col-sm-6 col-xs-6 cart lang-cur text-right">
			<?php if ($oscTemplate->hasBlocks('boxes_topmenu_right')) 
				{echo $oscTemplate->getBlocks('boxes_topmenu_right');};?>
			</div>
		</div>
		</div>
	</div>
<?php if($oscTemplate->hasBlocks('boxes_column_header'))
		{echo $oscTemplate->getBlocks('boxes_column_header');}?>
<div class="spacehide"></div>
<div class="backtop" id="backtop"><a title="Back to Top" href="javascript:void(0)" class="backtotop btn"><i class="fa fa-chevron-up"></i></a></div>
<?php 
	if ($oscTemplate->hasBlocks('boxes_column_top')) 
	{echo $oscTemplate->getBlocks('boxes_column_top');}
?>	
