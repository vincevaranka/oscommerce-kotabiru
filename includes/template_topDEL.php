<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $oscTemplate->buildBlocks();

?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link rel="stylesheet" href="ext/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="ext/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="ext/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="ext/jquery/custom.js"></script>
<link rel="stylesheet" href="stylesheet.css">

<?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<body>


<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


