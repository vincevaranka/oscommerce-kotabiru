<?php
  class ar_testimonial {
    var $code = 'ar_testimonial';
    var $title;
    var $description;
    var $sort_order = 0;
    var $minutes = 15;
    var $identifier;

    function __construct() {
      $this->title = MODULE_ACTION_RECORDER_TESTIMONIAL_TITLE;
      $this->description = MODULE_ACTION_RECORDER_TESTIMONIAL_DESCRIPTION;

      if ($this->check()) {
        $this->minutes = (int)MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES;
      }
    }

    function setIdentifier() {
      $this->identifier = tep_get_ip_address();
    }

    function canPerform($user_id, $user_name) {
      $check_query = tep_db_query("select date_added from " . TABLE_ACTION_RECORDER . " where module = '" . tep_db_input($this->code) . "' and (" . (!empty($user_id) ? "user_id = '" . (int)$user_id . "' or " : "") . " identifier = '" . tep_db_input($this->identifier) . "') and date_added >= date_sub(now(), interval " . (int)$this->minutes  . " minute) and success = 1 order by date_added desc limit 1");
      if (tep_db_num_rows($check_query)) {
        return false;
      } else {
        return true;
      }
    }

    function expireEntries() {
      tep_db_query("delete from " . TABLE_ACTION_RECORDER . " where module = '" . $this->code . "' and date_added < date_sub(now(), interval " . (int)$this->minutes  . " minute)");

      return tep_db_affected_rows();
    }

    function check() {
      return defined('MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Minutes Per Testimonial', 'MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES', '15', 'Minimum number of minutes to allow 1 testimonial to be sent (eg, 15 for 1 e-mail every 15 minutes)', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ACTION_RECORDER_TESTIMONIAL_MINUTES');
    }
  }
?>
