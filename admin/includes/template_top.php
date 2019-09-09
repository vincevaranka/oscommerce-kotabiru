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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="robots" content="noindex,nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php echo TITLE; ?></title>
<base href="<?php echo ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN; ?>" />
<?php
	$font_query = tep_db_query("select font_code,font_use from ".TABLE_FONTS." where status = '1'");
	$font = tep_db_fetch_array($font_query);
	echo '<style>'.$font['font_code'].' body{'.$font['font_use'].'}</style>';
?>
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/bootstrap/css/bootstrap.min.css', '', 'SSL'); ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/bootstrap/fonts/font-awesome.min.css', '', 'SSL'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/bootstrap/colorpicker/css/bootstrap-colorpicker.min.css', '', 'SSL'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/jquery/ui/redmond/jquery-ui-1.10.4.min.css'); ?>">
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/jquery-1.11.1.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap/js/bootstrap.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap/colorpicker/js/bootstrap-colorpicker.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/jquery-ui-1.10.4.min.js'); ?>"></script>
<!--[if IE]><script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js', '', 'SSL'); ?>"></script><![endif]-->
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/custom.js', '', 'SSL'); ?>"></script>

<?php
  if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js', '', 'SSL'); ?>"></script>
<script type="text/javascript">
$.datepicker.setDefaults($.datepicker.regional['<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>']);
</script>
<?php
  }
?>

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.time.min.js', '', 'SSL'); ?>"></script>

<script type="text/javascript" src="includes/general.js"></script>
</head>
<body class="bg">
<div class="wrap">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="column-menu">
<?php
  if (tep_session_is_registered('admin')) {
    include(DIR_WS_INCLUDES . 'column_left.php');
  } else {
?>



<?php
  }
?>
</div>
<div id="wrapper">
<div id="content">
