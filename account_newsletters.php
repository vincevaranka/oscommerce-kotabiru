<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    if (isset($HTTP_POST_VARS['newsletter_general']) && is_numeric($HTTP_POST_VARS['newsletter_general'])) {
      $newsletter_general = tep_db_prepare_input($HTTP_POST_VARS['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }

    if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

  require(DIR_WS_THEME.THEME_DEFAULT . '/template_top.php');
  require(DIR_WS_THEME.THEME_DEFAULT . '/header.php');
?>
<div class="content">
<h1><?php echo HEADING_TITLE; ?></h1>

<?php echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

<div class="contentContainer">
  <h2><?php echo MY_NEWSLETTERS_TITLE; ?></h2>

    <?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false)); ?>
       <strong><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?><br /><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?>
  
  </div>
<hr />
   <?php echo tep_draw_button_booth(IMAGE_BUTTON_CONTINUE, 'save', null, 'primary'); ?>
    <?php echo tep_draw_button_booth(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link(FILENAME_ACCOUNT, '', 'SSL')); ?>



</form>
</div>
<?php
 require(DIR_WS_THEME.THEME_DEFAULT . '/template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
