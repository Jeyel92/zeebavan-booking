<?php
/**
 * @package zeebavans-booking
 * @since 1.0
 */

if (is_admin()) {

    add_action('admin_menu', 'zeebavans_plugin_setup_menu');
}

function zeebavans_plugin_setup_menu()
{
    add_menu_page('Zeebavans Booking', 'Booking Form', 'manage_options', 'zeebavans-booking', 'zeebavans_booking', 'dashicons-tickets-alt');
    add_submenu_page('zeebavans-booking', 'Zeeba Options Page Configuration', 'Configuration', 'manage_options', 'zeeba-options-page-configuration', 'config_zeebavan');
    add_submenu_page('zeebavans-booking', 'Zeeba API Configuration', 'API Configuration', 'manage_options', 'zeeba-api-configuration', 'API_config_zeebavan');
}

function zeebavans_booking()
{
    require_once ZEEBAVAN_DIR . 'pages/booking.php';
}

function config_zeebavan(){
    include(ZEEBAVAN_DIR.'templates/option-page.php');
}

function API_config_zeebavan(){
    include(ZEEBAVAN_DIR.'templates/API-cred-page.php');
}


function zeebavans_scripts()
{
    wp_register_style('zeebavans-css-dash', ZEEBAVAN_ASSETS . 'css/dashboard-styles.css', array(), true, false);
    wp_enqueue_style('zeebavans-css-dash');

    wp_register_style('tailwind-css-dash', ZEEBAVAN_ASSETS . 'css/tailwind.min.css', array(), true, false);
    // wp_enqueue_style('tailwind-css-dash');


}
add_action('admin_enqueue_scripts', 'zeebavans_scripts');


function zeebavans_scripts_frontend()
{
    /* CSS */

    wp_register_style('boostrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css', array(), true, false);
    wp_enqueue_style('boostrap-css');

    wp_register_style('select2-css', ZEEBAVAN_ASSETS . 'css/select2.css', array(), true, false);
    wp_enqueue_style('select2-css');

    wp_register_style('zeebavans-css', ZEEBAVAN_ASSETS . 'css/styles.css', array(), '1.25', false);
    wp_enqueue_style('zeebavans-css');

    wp_register_style('datetimepicker-css', ZEEBAVAN_ASSETS . 'css/jquery.datetimepicker.css', array(), true, false);
    wp_enqueue_style('datetimepicker-css');

    // wp_register_style('zeebavans-step-css', ZEEBAVAN_ASSETS . 'css/step/step.css', array(), true, false);
    // wp_enqueue_style('zeebavans-step-css');

    wp_register_style('zeebavans-step2-vehicle-css', ZEEBAVAN_ASSETS . 'css/step/step-2-vehicle.css', array(), '1.3', false);
    wp_enqueue_style('zeebavans-step2-vehicle-css');

    wp_register_style('formbooking-css', ZEEBAVAN_ASSETS . 'css/form-booking.css', array(), false, false);
    wp_enqueue_style('formbooking-css');

    /* JS */
    wp_register_script('boostrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js', array(), true, true);
    wp_enqueue_script('boostrap-js');
    wp_register_script('feather-js', ZEEBAVAN_ASSETS. 'js/feather.min.js', array(), true, true);
    wp_enqueue_script('feather-js');

    wp_register_script('select2-js', ZEEBAVAN_ASSETS. 'js/select2.min.js', array(), true, true);
    wp_enqueue_script('select2-js');


    wp_register_script('moment-js', ZEEBAVAN_ASSETS. 'js/moment.js', array(), true, true);
    wp_enqueue_script('moment-js');

    // wp_register_script('datetimepicker-js', ZEEBAVAN_ASSETS. 'js/jquery.datetimepicker.full.min.js', array(), true, true);
    wp_register_script('datetimepicker-js', ZEEBAVAN_ASSETS. 'datetimepicker-master/jquery.datetimepicker.js', array(), true, true);
    wp_enqueue_script('datetimepicker-js');

    wp_register_script('jquery.ui.widget-js', ZEEBAVAN_ASSETS. 'js/jquery.ui.widget.js', array(), true, true);
    wp_enqueue_script('jquery.ui.widget-js');

    wp_register_script('jquery.fileupload-js', ZEEBAVAN_ASSETS. 'js/jquery.fileupload.js', array(), true, true);
    wp_enqueue_script('jquery.fileupload-js');


    //step3 js
        wp_register_script('zeebavans-step3-scripts-js', ZEEBAVAN_ASSETS. 'js/step/step3.js', array(), '1.3', true);
        wp_enqueue_script('zeebavans-step3-scripts-js');
    //step4 js
        wp_register_script('zeebavans-step4-scripts-js', ZEEBAVAN_ASSETS. 'js/step/step4.js', array(), '1.6', true);
        wp_enqueue_script('zeebavans-step4-scripts-js');

    wp_register_script('zeeba-scripts-js', ZEEBAVAN_ASSETS. 'js/scripts.js', array(), '3.10', true);
    wp_enqueue_script('zeeba-scripts-js');

    // wp_register_script('litepicker-js', 'https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js', array(), true, true);

    wp_register_script('litepicker-js', 'https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js', array(), true, true);

    wp_enqueue_script('litepicker-js');


    wp_register_script('jquery.autocomplete-js', ZEEBAVAN_ASSETS. 'js/jquery.autocomplete.js', array(), true, true);
    wp_enqueue_script('jquery.autocomplete-js');


}
add_action('wp_enqueue_scripts', 'zeebavans_scripts_frontend');



function zeebavans_scripts_admin()
{
    /* CSS */
    wp_register_style('zeeba-admin-css', ZEEBAVAN_ASSETS. 'css/zeeba-admin.css', array(), true, false);
    wp_enqueue_style('zeeba-admin-css');


    /* JS */
    wp_register_script('zeeba-admin-js', ZEEBAVAN_ASSETS. 'js/zeeba-admin.js', array(), true, true);
    wp_enqueue_script('zeeba-admin-js');

}
add_action('admin_enqueue_scripts', 'zeebavans_scripts_admin');
