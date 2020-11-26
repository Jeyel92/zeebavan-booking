<?php
/*
* Plugin Name: Zeebavan Booking
* Description: This plugin manages and processes van booking for Zeebavans through TSDASP API.
* Author: Light House Graphics
* Version: 1.0
*/

// Set timezone
date_default_timezone_set("America/Los_Angeles");

defined('ABSPATH') or die('You are no authorized to this section.');

define('ZEEBAVAN_DIR', plugin_dir_path(__FILE__)); // http://<domain>/wp-content/plugins/zeebavan-booking/

define('ZEEBAVAN_ASSETS', plugin_dir_url(__FILE__) . 'assets/');

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

require_once(ZEEBAVAN_DIR . '_inc/schemas.php');
require_once(ZEEBAVAN_DIR . '_inc/init.php');

require_once(ZEEBAVAN_DIR . '_inc/stripe_config.php');
require_once(ZEEBAVAN_DIR . '_inc/va.php');

// require_once '_inc/va.php';
require_once(ZEEBAVAN_DIR . '_inc/breadcrumbs.php');
require_once(ZEEBAVAN_DIR . '_inc/booking.php');
require_once(ZEEBAVAN_DIR . '_inc/countries.php');
require_once(ZEEBAVAN_DIR . '_inc/us-states.php');
require_once(ZEEBAVAN_DIR . '_inc/ajax.php');
require_once(ZEEBAVAN_DIR . '_inc/steps/step1.php');
// require_once '_inc/booking.ajax.php';
require_once(ZEEBAVAN_DIR . '_inc/functions.php');
require_once(ZEEBAVAN_DIR . 'templates/charity.php');

 
// function to create the DB / Options / Defaults                   
function zeeba_rental_price_charity_options_install() {
    global $wpdb;
 
    // create the ECPT metabox database table
    // if($wpdb-&gt;get_var("show tables like '$your_db_name'") != $your_db_name) 
    // {
        $charset_collate = $wpdb->get_charset_collate();

        //$sql = "DROP TABLE IF EXISTS `{$wpdb->base_prefix}rental_price_charity`";
        //$wpdb->query($sql);

        $sql = "CREATE TABLE IF NOT EXIST `{$wpdb->base_prefix}rental_price_charity` (
          ID bigint(25) NOT NULL AUTO_INCREMENT,
          amount float(4) UNSIGNED NOT NULL,
          reservation_id varchar(100) NULL,
          created_at datetime NOT NULL,
          PRIMARY KEY  (ID)
        ) $charset_collate;";
 
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    // }
 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'zeeba_rental_price_charity_options_install');



