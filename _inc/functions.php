<?php 
/**
 * @package zeebavans-booking
 * @since 1.0
 */

/*
 * Creating custom post type for vehicle
 */

// add_action( 'phpmailer_init', 'configure_smtp' );
// function configure_smtp( PHPMailer $phpmailer ){
//     $phpmailer->isSMTP(); //switch to smtp
//     $phpmailer->Host = 'smtp.gmail.com';
//     $phpmailer->SMTPAuth = true;
//     $phpmailer->Port = 587;
//     $phpmailer->Username = 'devsnetsmtp@gmail.com';
//     $phpmailer->Password = 'B6qdczKdVskV1K';
//     $phpmailer->SMTPSecure = false;
//     $phpmailer->From = 'devsnetsmtp@gmail.com';
//     $phpmailer->FromName='Devsnet';
// }

  
function vehicle_post_type()
{

    $labels = array(
        'name' => 'Vehicles',
        'singular_name' => 'Vehicle',
        'menu_name' => 'Vehicles',
        'all_items' => 'All Vehicles',
        'view_item' => 'View Vehicle',
        'add_new_item' => 'Add New Vehicle',
        'add_new' => 'Add New',
        'edit_item' => 'Edit Vehicle',
        'update_item' => 'Update Vehicle',
        'search_items' => 'Search Vehicle',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash',
    );

    $supports = array(
        'title', 
        'thumbnail', 
        'revisions'
    );
    
    $args = array(
        'label' => 'vehicles', 
        'description' => 'Vehicle Information',
        'labels' => $labels,
        'supports' => $supports,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'menu_icon' => 'dashicons-dashboard'
    );

    // Registering your Custom Post Type
    register_post_type('vehicle', $args);

}

add_action('init', 'vehicle_post_type', 0);

/*
 * Creating custom post type for location
 */

function location_post_type()
{

    $labels = array(
        'name' => 'Locations',
        'singular_name' => 'Location',
        'menu_name' => 'Locations',
        'all_items' => 'All Locations',
        'view_item' => 'View Vehicle',
        'add_new_item' => 'Add New Vehicle',
        'add_new' => 'Add New',
        'edit_item' => 'Edit Vehicle',
        'update_item' => 'Update Vehicle',
        'search_items' => 'Search Vehicle',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash',
    );

    $supports = array(
        'title', 
        'thumbnail', 
        'revisions'
    );
    
    $args = array(
        'label' => 'locations', 
        'description' => 'location Information',
        'labels' => $labels,
        'supports' => $supports,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'menu_icon' => 'dashicons-location-alt'
    );

    // Registering your Custom Post Type
    register_post_type('location', $args);

}

add_action('init', 'location_post_type', 0);


/*
 * Creating custom post type for booking
 */

function booking_post_type()
{

    $labels = array(
        'name' => 'booking',
        'singular_name' => 'booking',
        'menu_name' => 'booking',
        'all_items' => 'All booking',
        'view_item' => 'View booking',
        'add_new_item' => 'Add New booking',
        'add_new' => 'Add New',
        'edit_item' => 'Edit booking',
        'update_item' => 'Update booking',
        'search_items' => 'Search booking',
        'not_found' => 'Not Found',
        'not_found_in_trash' => 'Not found in Trash',
    );

    $supports = array(
        'title', 
        'thumbnail', 
        'revisions'
    );
    
    $args = array(
        'label' => 'booking', 
        'description' => 'booking Information',
        'labels' => $labels,
        'supports' => $supports,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'menu_icon' => 'dashicons-dashboard'
    );

    // Registering your Custom Post Type
    //register_post_type('booking', $args);

}

//add_action('init', 'booking_post_type', 0);

// register shortcode to render booking form

function zeeba_plugin_form($atts)
{
  $next_url = shortcode_atts(array( 'next_slug' => ''), $atts) ;
  $next_page_url = $next_url['next_slug'];
  include(ZEEBAVAN_DIR.'templates/booking_start.php');
}

add_shortcode('zeeba-form', 'zeeba_plugin_form');

function zeeba_plugin_form_next_page($atts)
{
    include(ZEEBAVAN_DIR.'templates/booking_next_page.php');
}

add_shortcode('zeeba-form-next-page', 'zeeba_plugin_form_next_page');

/***************zeeba_api start*********************/
function zeeba_api_register_settings() {
  //titles...
   add_option( 'booking_api_username', '42357');
   register_setting( 'zeeba_api_options_group', 'booking_api_username', 'zeeba_api_callback' );

   add_option( 'booking_api_password', '42357');
   register_setting( 'zeeba_api_options_group', 'booking_api_password', 'zeeba_api_callback' );
}
add_action( 'admin_init', 'zeeba_api_register_settings' );
/***************zeeba_api end*********************/

/*************** PLUGIN OPTIONS ******************/

/***************step 1 start*********************/
function zeeba_step1_options_register_settings() {
  //titles...
   add_option( 'step1_intro', 'Start a Booking');
   register_setting( 'zeeba_step_options_group', 'step1_intro', 'zeeba_step1_options_callback' );

   add_option( 'step1_intro_text', '<h1>Rent a Van</h1>');
   register_setting( 'zeeba_step_options_group', 'step1_intro_text', 'zeeba_step1_options_callback' );

   add_option( 'step1_modified_into', 'Start a Booking');
   register_setting( 'zeeba_step_options_group', 'step1_modified_intro', 'zeeba_step1_options_callback' );

   add_option( 'step1_modified_intro_text', '');
   register_setting( 'zeeba_step_options_group', 'step1_modified_intro_text', 'zeeba_step1_options_callback' );

   add_option( 'step1_book_tab', 'Book a Van');
   register_setting( 'zeeba_step_options_group', 'step1_book_tab', 'zeeba_step1_options_callback' );

   add_option( 'step1_modify_tab', 'Modify Reservation');
   register_setting( 'zeeba_step_options_group', 'step1_modify_tab', 'zeeba_step1_options_callback' );

  //form...
   add_option( 'step1_pick-up_date_placeholder', 'Select Pick-up Date');
   register_setting( 'zeeba_step_options_group', 'step1_pick-up_date_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_pick-up_time_placeholder', 'Select Pick-up Time');
   register_setting( 'zeeba_step_options_group', 'step1_pick-up_time_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_drop-off_date_placeholder', 'Select Drop-off Date');
   register_setting( 'zeeba_step_options_group', 'step1_drop-off_date_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_drop-off_time_placeholder', 'Select Drop-off Time');
   register_setting( 'zeeba_step_options_group', 'step1_drop-off_time_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_pick-up_location_placeholder', 'Type Location (city, state or US zip code)');
   register_setting( 'zeeba_step_options_group', 'step1_pick-up_location_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_drop-off_location_placeholder', 'Type Location (city, state or US zip code)');
   register_setting( 'zeeba_step_options_group', 'step1_drop-off_location_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_select_van_placeholder', 'Select Van Type');
   register_setting( 'zeeba_step_options_group', 'step1_select_van_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'step1_different_location', 'Return van to a different Zeeba location');
   register_setting( 'zeeba_step_options_group', 'step1_different_location', 'zeeba_step1_options_callback' );

   add_option( 'step1_discount_label', 'Enter a discount or promo code');
   register_setting( 'zeeba_step_options_group', 'step1_discount_label', 'zeeba_step1_options_callback' );

   add_option( 'step1_discount_placeholder', 'Type discount or promo code');
   register_setting( 'zeeba_step_options_group', 'step1_discount_placeholder', 'zeeba_step1_options_callback' );

   add_option( 'reservation_placeholder', 'Reservation Number');
   register_setting( 'zeeba_step_options_group', 'reservation_placeholder', 'zeeba_step1_options_callback' );
}
add_action( 'admin_init', 'zeeba_step1_options_register_settings' );
/***************step 1 end*********************/

/***************step 2 start*********************/
function zeeba_step2_options_register_settings() {
    //titles...
   add_option( 'step2_intro', 'Vehicle Confirmation');
   register_setting( 'zeeba_step_options_group', 'step2_intro', 'zeeba_step3_options_callback' );

   add_option( 'step2_intro_text', '<h1>Vehicle Confirmation</h1>');
   register_setting( 'zeeba_step_options_group', 'step2_intro_text', 'zeeba_step3_options_callback' );

   add_option( 'step2_change_dates', 'Change booking dates');
   register_setting( 'zeeba_step_options_group', 'step2_change_dates', 'zeeba_step3_options_callback' );

   add_option( 'step2_info_rental_rate', 'Your daily rate, excluding taxes and fees');
   register_setting( 'zeeba_step_options_group', 'step2_info_rental_rate', 'zeeba_step3_options_callback' );

   add_option( 'step2_info_total', 'Your total for the entire booking, including taxes and fees');
   register_setting( 'zeeba_step_options_group', 'step2_info_total', 'zeeba_step3_options_callback' );
}
add_action( 'admin_init', 'zeeba_step2_options_register_settings' );
/***************step 2 end*********************/
/***************step 3 start*********************/
function zeeba_step3_options_register_settings() {
    //titles...
   add_option( 'step3_into', 'Rate and Extras');
   register_setting( 'zeeba_step_options_group', 'step3_intro', 'zeeba_step3_options_callback' );

   add_option( 'step3_intro_text', '<h1>Rate and Extras</h1>');
   register_setting( 'zeeba_step_options_group', 'step3_intro_text', 'zeeba_step3_options_callback' );

   add_option( 'step3_modified_into', 'Rate and Extras');
   register_setting( 'zeeba_step_options_group', 'step3_modified_intro', 'zeeba_step3_options_callback' );

   add_option( 'step3_modified_intro_text', '');
   register_setting( 'zeeba_step_options_group', 'step3_modified_intro_text', 'zeeba_step3_options_callback' );

   add_option( 'step3_change_dates', 'Change reservation dates');
   register_setting( 'zeeba_step_options_group', 'step3_change_dates', 'zeeba_step3_options_callback' );

   //available options bundle......
   $args = array(
          'name' => ['Protect The Van / $39 day'],
          'code' => ['CDW'],
          'price' => ['39'],
          'tooltip' => ['"You will not be held financially responsible for loss or damage to the Zeeba van, with some exceptions as detailed in the Rental Agreement." "You don’t have to file a claim with personal auto insurance." "You only pay $500 deductible. "Please read complete details on our Insurance Information page."'],
          'renaming_options_code' => '',
          'renaming_options_text' => ''
      );

   add_option( 'bundle_name', $args['name']);
   register_setting( 'zeeba_step_options_group', 'bundle_name', 'zeeba_step3_options_callback' );

   add_option( 'bundle_code', $args['code']);
   register_setting( 'zeeba_step_options_group', 'bundle_code', 'zeeba_step3_options_callback' );

   add_option( 'bundle_price', $args['price']);
   register_setting( 'zeeba_step_options_group', 'bundle_price', 'zeeba_step3_options_callback' );

   add_option( 'bundle_tooltip', $args['tooltip']);
   register_setting( 'zeeba_step_options_group', 'bundle_tooltip', 'zeeba_step3_options_callback' );

   add_option( 'renaming_options_code', $args['renaming_options_code']);
   register_setting( 'zeeba_step_options_group', 'renaming_options_code', 'zeeba_step3_options_callback' );

   add_option( 'renaming_options_text', $args['renaming_options_text']);
   register_setting( 'zeeba_step_options_group', 'renaming_options_text', 'zeeba_step3_options_callback' );

   add_option( 'hidden_options', 'CDW RLP SLI RLP2 SLI2 ROADSIDE MEXINS');
   register_setting( 'zeeba_step_options_group', 'hidden_options', 'zeeba_step3_options_callback' );

   //coverage....
   add_option( 'options_col_1', 'Coverage options');
   register_setting( 'zeeba_step_options_group', 'options_col_1', 'zeeba_step3_options_callback' );

   add_option( 'options_col_2', 'Price');
   register_setting( 'zeeba_step_options_group', 'options_col_2', 'zeeba_step3_options_callback' );

   add_option( 'options_col_3', 'Add/Remove');
   register_setting( 'zeeba_step_options_group', 'options_col_3', 'zeeba_step3_options_callback' );

   add_option( 'options_empty', 'Coverage options not available for one day rentals');
   register_setting( 'zeeba_step_options_group', 'options_empty', 'zeeba_step3_options_callback' );

   //rates......

   add_option( 'rates_heading', 'Rate Quote');
   register_setting( 'zeeba_step_options_group', 'rates_heading', 'zeeba_step3_options_callback' );

   add_option( 'rates_warning', 'All rates are in USD.');
   register_setting( 'zeeba_step_options_group', 'rates_warning', 'zeeba_step3_options_callback' );

   add_option( 'rates_col_1', 'Details');
   register_setting( 'zeeba_step_options_group', 'rates_col_1', 'zeeba_step3_options_callback' );

   add_option( 'rates_col_2', 'Cost');
   register_setting( 'zeeba_step_options_group', 'rates_col_2', 'zeeba_step3_options_callback' );

   add_option( 'rates_total', 'Total Charges');
   register_setting( 'zeeba_step_options_group', 'rates_total', 'zeeba_step3_options_callback' );

   add_option( 'calculate_text', 'Calculate');
   register_setting( 'zeeba_step_options_group', 'calculate_text', 'zeeba_step3_options_callback' );

   add_option( 'recalculate_text', 'Confirm');
   register_setting( 'zeeba_step_options_group', 'recalculate_text', 'zeeba_step3_options_callback' );

   //cancelation......
   add_option( 'cancelation_text', 'All cancellations are subject to a $35 fee. Cancellations made within 172 hours of pick up date are charged for one full day of rental.');
   register_setting( 'zeeba_step_options_group', 'cancelation_text', 'zeeba_step3_options_callback' );

   //buttons......
   add_option( 'btn_request_more', 'Request More Information');
   register_setting( 'zeeba_step_options_group', 'btn_request_more', 'zeeba_step3_options_callback' );
   
   add_option( 'btn_email_copy', 'Email Quote');
   register_setting( 'zeeba_step_options_group', 'btn_email_copy', 'zeeba_step3_options_callback' );
   
   add_option( 'btn_submit', 'Last Step: 4');
   register_setting( 'zeeba_step_options_group', 'btn_submit', 'zeeba_step3_options_callback' );

   //buttons......
   add_option( 'btn_request_more', 'Request More Information');
   register_setting( 'zeeba_step_options_group', 'btn_request_more', 'zeeba_step3_options_callback' );
   
   add_option( 'btn_email_copy', 'Email Quote');
   register_setting( 'zeeba_step_options_group', 'btn_email_copy', 'zeeba_step3_options_callback' );
   
   add_option( 'btn_submit', 'Last Step: 4');
   register_setting( 'zeeba_step_options_group', 'btn_submit', 'zeeba_step3_options_callback' );

   //Request More Information......
   add_option( 'rm_heading', 'How can we help you to confirm this reservation?');
   register_setting( 'zeeba_step_options_group', 'rm_heading', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_first_name', 'First Name');
   register_setting( 'zeeba_step_options_group', 'rm_first_name', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_last_name', 'Last Name');
   register_setting( 'zeeba_step_options_group', 'rm_last_name', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_phone_number', 'Phone Number');
   register_setting( 'zeeba_step_options_group', 'rm_phone_number', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_email', 'Email');
   register_setting( 'zeeba_step_options_group', 'rm_email', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_message', 'Message');
   register_setting( 'zeeba_step_options_group', 'rm_message', 'zeeba_step3_options_callback' );
   
   add_option( 'rm_submit', 'Send');
   register_setting( 'zeeba_step_options_group', 'rm_submit', 'zeeba_step3_options_callback' );

   //email cop......
   add_option( 'ec_heading', 'Where do you want us to send you the quote?');
   register_setting( 'zeeba_step_options_group', 'ec_heading', 'zeeba_step3_options_callback' );
   
   add_option( 'ec_email', 'Email');
   register_setting( 'zeeba_step_options_group', 'ec_email', 'zeeba_step3_options_callback' );
   
   add_option( 'ec_submit', 'Send');
   register_setting( 'zeeba_step_options_group', 'ec_submit', 'zeeba_step3_options_callback' );

   //policy......
    $args = array('Please have your Driver\'s License and proof of insurance ID card for all drivers present at time of pick up'
    );

    add_option( 'step3_policy_heading', 'Key Rental Policies');
    register_setting( 'zeeba_step_options_group', 'step3_policy_heading', 'zeeba_step3_options_callback' );
    
    add_option( 'step3_policy_list', $args);
    register_setting( 'zeeba_step_options_group', 'step3_policy_list', 'zeeba_step3_options_callback' );

    add_option( 'step3_policy_download_text', 'View Policies');
    register_setting( 'zeeba_step_options_group', 'step3_policy_download_text', 'zeeba_step3_options_callback' );

    add_option( 'step3_policy_download_url', 'https://www.zeebavans.com/rental-policies-1/');
    register_setting( 'zeeba_step_options_group', 'step3_policy_download_url', 'zeeba_step3_options_callback' );

}
add_action( 'admin_init', 'zeeba_step3_options_register_settings' );
/***************step 3 start*********************/
/***************step 4 start*********************/
function zeeba_step4_options_register_settings() {
  //titles...
   add_option( 'step4_intro', 'Renter Details');
   register_setting( 'zeeba_step_options_group', 'step4_intro', 'zeeba_step1_options_callback' );

   add_option( 'step4_intro_text', '<h1>Renter Details</h1>');
   register_setting( 'zeeba_step_options_group', 'step4_intro_text', 'zeeba_step1_options_callback' );

   add_option( 'step4_modified_into', 'Renter Details');
   register_setting( 'zeeba_step_options_group', 'step4_modified_intro', 'zeeba_step1_options_callback' );

   add_option( 'step4_modified_intro_text', '');
   register_setting( 'zeeba_step_options_group', 'step4_modified_intro_text', 'zeeba_step1_options_callback' );

   add_option( 'step4_change_dates', 'Change booking dates');
   register_setting( 'zeeba_step_options_group', 'step1_change_dates', 'zeeba_step1_options_callback' );

  //Renter Information...
   add_option( 'step4_ri_heading', 'Renter Information');
   register_setting( 'zeeba_step_options_group', 'step4_ri_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_first_name', 'First Name');
   register_setting( 'zeeba_step_options_group', 'step4_ri_first_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_last_name', 'Last Name');
   register_setting( 'zeeba_step_options_group', 'step4_ri_last_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_company_name', 'Company Name');
   register_setting( 'zeeba_step_options_group', 'step4_ri_company_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_phone_number', 'Phone Number');
   register_setting( 'zeeba_step_options_group', 'step4_ri_phone_number', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_email', 'Email Address');
   register_setting( 'zeeba_step_options_group', 'step4_ri_email', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_email_confirm', 'Confirm Email Address');
   register_setting( 'zeeba_step_options_group', 'step4_ri_email_confirm', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_country', 'Country');
   register_setting( 'zeeba_step_options_group', 'step4_ri_country', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_address', 'Address');
   register_setting( 'zeeba_step_options_group', 'step4_ri_address', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_city', 'City');
   register_setting( 'zeeba_step_options_group', 'step4_ri_city', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_state', 'State / Province');
   register_setting( 'zeeba_step_options_group', 'step4_ri_state', 'zeeba_step1_options_callback' );

   add_option( 'step4_ri_zip', 'ZIP / Postal code');
   register_setting( 'zeeba_step_options_group', 'step4_ri_zip', 'zeeba_step1_options_callback' );

  //Driver Details...
   add_option( 'step4_dd_heading', 'Driver Details');
   register_setting( 'zeeba_step_options_group', 'step4_dd_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_dd_tip', 'Unless additional drivers are added, the person making the reservation is the only authorized driver. If the person making the reservation will not pick up the van, then the "I also authorize additional drivers to pick up the van on my behalf" check box must be checked off.');
   register_setting( 'zeeba_step_options_group', 'step4_dd_tip', 'zeeba_step1_options_callback' );

   add_option( 'step4_a_driver_first_name', 'Additional Driver First Name');
   register_setting( 'zeeba_step_options_group', 'step4_a_driver_first_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_a_driver_last_name', 'Additional Driver Last Name');
   register_setting( 'zeeba_step_options_group', 'step4_a_driver_last_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_a_driver_add', 'Add additional driver');
   register_setting( 'zeeba_step_options_group', 'step4_a_driver_add', 'zeeba_step1_options_callback' );

   add_option( 'step4_a_driver_allow', 'I also authorize the additional drivers to pick up the van on my behalf.');
   register_setting( 'zeeba_step_options_group', 'step4_a_driver_allow', 'zeeba_step1_options_callback' );

  //payment Details...
   add_option( 'step4_pd_heading', 'Renter Payment Details');
   register_setting( 'zeeba_step_options_group', 'step4_pd_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_text', 'Payment information mast match renter details');
   register_setting( 'zeeba_step_options_group', 'step4_pd_text', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_number', 'Credit Card Number');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_number', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_name', 'Name on Card');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_name', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_valid_until', 'Valid untill');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_valid_until', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_month', 'Month');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_month', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_year', 'Year');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_year', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_cvv', 'CVV');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_cvv', 'zeeba_step1_options_callback' );

   add_option( 'step4_pd_card_cvv_help', 'Last 3/4 digits on the back of your card');
   register_setting( 'zeeba_step_options_group', 'step4_pd_card_cvv_help', 'zeeba_step1_options_callback' );

  //further Information...
   add_option( 'step4_fi_heading', 'Further Information');
   register_setting( 'zeeba_step_options_group', 'step4_fi_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_flight_enabled_title', 'Are you flying into an airport?');
   register_setting( 'zeeba_step_options_group', 'step4_fi_flight_enabled_title', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_flight_enabled', 'Yes');
   register_setting( 'zeeba_step_options_group', 'step4_fi_flight_enabled', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_flight_number', 'Flight Number (optional)');
   register_setting( 'zeeba_step_options_group', 'step4_fi_flight_number', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_flight_airline', 'Airline Name');
   register_setting( 'zeeba_step_options_group', 'step4_fi_flight_airline', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_another_country', 'Will you drive into another country?');
   register_setting( 'zeeba_step_options_group', 'step4_fi_another_country', 'zeeba_step1_options_callback' );

   add_option( 'step4_fi_special', 'Special Remarks');
   register_setting( 'zeeba_step_options_group', 'step4_fi_special', 'zeeba_step1_options_callback' );

  //Document Upload...
   add_option( 'step4_du_heading', 'Document Upload (optional)');
   register_setting( 'zeeba_step_options_group', 'step4_du_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_du_text', 'The Renter and all additional drivers must show proof of insurance and a copy of their drivers licence at the time of pickup. To speed up the pickup process, we allow you to upload the images conveniently through the secure document uploader.');
   register_setting( 'zeeba_step_options_group', 'step4_du_text', 'zeeba_step1_options_callback' );

   add_option( 'du_warning', 'If you purchase "Protect Everything" insurance package from us then you are not required to provide proof of insurance for renter or any additional driver(s).');
   register_setting( 'zeeba_step_options_group', 'du_warning', 'zeeba_step1_options_callback' );

   add_option( 'step4_du_button', 'Choose files');
   register_setting( 'zeeba_step_options_group', 'step4_du_button', 'zeeba_step1_options_callback' );

  //Agreement...
   add_option( 'step4_ag_heading', 'Agreement');
   register_setting( 'zeeba_step_options_group', 'step4_ag_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_ag_modal_heading', 'Read this agreement');
   register_setting( 'zeeba_step_options_group', 'step4_ag_modal_heading', 'zeeba_step1_options_callback' );

   add_option( 'step4_ag_primary_label', 'I have read, understand and agree to Zeeba\'s Rental Policies and Terms of Use.');
   register_setting( 'zeeba_step_options_group', 'step4_ag_primary_label', 'zeeba_step1_options_callback' );

   add_option( 'step4_ag_secondary_label', 'I have read, understand and agree to Zeeba\'s Rental Policies and Terms of Use.');
   register_setting( 'zeeba_step_options_group', 'step4_ag_secondary_label', 'zeeba_step1_options_callback' );

   add_option( 'step4_ag_text', '');
   register_setting( 'zeeba_step_options_group', 'step4_ag_text', 'zeeba_step1_options_callback' );

   //other......
    add_option( 'step4_total_price', 'Your Total Price:');
    register_setting( 'zeeba_step_options_group', 'step4_total_price', 'zeeba_step3_options_callback' );
    
    add_option( 'step4_rental_period', 'Rental Period:');
    register_setting( 'zeeba_step_options_group', 'step4_rental_period', 'zeeba_step3_options_callback' );

    add_option( 'step4_submit_text', 'Confirm Reservation');
    register_setting( 'zeeba_step_options_group', 'step4_submit_text', 'zeeba_step3_options_callback' );

    add_option( 'step4_submit_note', 'By clicking this button you are making a reservation. Please click the button only once. Your reservation details will be emailed to you.');
    register_setting( 'zeeba_step_options_group', 'step4_submit_note', 'zeeba_step3_options_callback' );

  //policy......
    $step4_args = array('Please have your Drivers License and proof of Insurance for all drivers present at time of pick up.');

    add_option( 'step4_policy_heading', 'Key Rental Policies');
    register_setting( 'zeeba_step_options_group', 'step4_policy_heading', 'zeeba_step3_options_callback' );
    
    add_option( 'step4_policy_list', $step4_args);
    register_setting( 'zeeba_step_options_group', 'step4_policy_list', 'zeeba_step3_options_callback' );

    add_option( 'step4_policy_download_text', 'View full policies');
    register_setting( 'zeeba_step_options_group', 'step4_policy_download_text', 'zeeba_step3_options_callback' );

    add_option( 'step4_policy_download_url', 'https://www.zeebavans.com/rental-policies/');
    register_setting( 'zeeba_step_options_group', 'step4_policy_download_url', 'zeeba_step3_options_callback' );
}
add_action( 'admin_init', 'zeeba_step4_options_register_settings' );
/***************step 4 end*********************/

/***************step 5 start*********************/
function zeeba_step5_options_register_settings() {
  //titles...
   add_option( 'step5_intro', 'Complete');
   register_setting( 'zeeba_step_options_group', 'step5_intro', 'zeeba_step5_options_callback' );

   add_option( 'step5_intro_text', '<h1>Complete</h1>');
   register_setting( 'zeeba_step_options_group', 'step5_intro_text', 'zeeba_step5_options_callback' );

   add_option( 'step5_modified_intro', 'Complete');
   register_setting( 'zeeba_step_options_group', 'step5_modified_intro', 'zeeba_step5_options_callback' );

   add_option( 'step5_modified_intro_text', '');
   register_setting( 'zeeba_step_options_group', 'step5_modified_intro_text', 'zeeba_step5_options_callback' );

   add_option( 'step5_confirm_text', 'Thank You for Your Reservation<br/>You will receive email confirmation with all the details.');
   register_setting( 'zeeba_step_options_group', 'step5_confirm_text', 'zeeba_step5_options_callback' );

  //step info...
   add_option( 'step5_si_success', 'Your reservation is confirmed');
   register_setting( 'zeeba_step_options_group', 'step5_si_success', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_start', 'Start');
   register_setting( 'zeeba_step_options_group', 'step5_si_start', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_start_sub', 'A New Reservation');
   register_setting( 'zeeba_step_options_group', 'step5_si_start_sub', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_view_modify', 'Modify');
   register_setting( 'zeeba_step_options_group', 'step5_si_view_modify', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_view_modify_sub', 'Your Reservation Details');
   register_setting( 'zeeba_step_options_group', 'step5_si_view_modify_sub', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_print', 'Print');
   register_setting( 'zeeba_step_options_group', 'step5_si_print', 'zeeba_step5_options_callback' );

   add_option( 'step5_si_print_sub', 'Reservation');
   register_setting( 'zeeba_step_options_group', 'step5_si_print_sub', 'zeeba_step5_options_callback' );

  //renter details......
    $step5_args = array('Please have your Drivers License and proof of Insurance for all drivers present at time of pick up.');
    $step5_content_args = array(
      'name' => ['Confirmation Number'],
      'value' => ['id'],
      'highlight' => [1]
   );

    add_option( 'step5_rd_heading', 'Rental Details');
    register_setting( 'zeeba_step_options_group', 'step5_rd_heading', 'zeeba_step5_options_callback' );
    
    add_option( 'step5_rd_content_name', $step5_content_args['name']);
    register_setting( 'zeeba_step_options_group', 'step5_rd_content_name', 'zeeba_step5_options_callback' );
    
    add_option( 'step5_rd_content_value', $step5_content_args['value']);
    register_setting( 'zeeba_step_options_group', 'step5_rd_content_value', 'zeeba_step5_options_callback' );
    
    add_option( 'step5_rd_content_highlight', $step5_content_args['highlight']);
    register_setting( 'zeeba_step_options_group', 'step5_rd_content_highlight', 'zeeba_step5_options_callback' );

  //rate quote...
   add_option( 'step5_rq_heading', 'Rate Quote');
   register_setting( 'zeeba_step_options_group', 'step5_rq_heading', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_warning', 'All rates are in USD');
   register_setting( 'zeeba_step_options_group', 'step5_rq_warning', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_col1_details', 'Details');
   register_setting( 'zeeba_step_options_group', 'step5_rq_col1_details', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_col2_cost', 'Cost');
   register_setting( 'zeeba_step_options_group', 'step5_rq_col2_cost', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_rental_rate', 'Rental Rate');
   register_setting( 'zeeba_step_options_group', 'step5_rq_rental_rate', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_taxes', 'Taxes and Fees');
   register_setting( 'zeeba_step_options_group', 'step5_rq_taxes', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_mileage', 'Mileage');
   register_setting( 'zeeba_step_options_group', 'step5_rq_mileage', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_unlimited', 'Unlimited');
   register_setting( 'zeeba_step_options_group', 'step5_rq_unlimited', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_subtotal', 'Subtotal');
   register_setting( 'zeeba_step_options_group', 'step5_rq_subtotal', 'zeeba_step5_options_callback' );

   add_option( 'step5_rq_total', 'Total');
   register_setting( 'zeeba_step_options_group', 'step5_rq_total', 'zeeba_step5_options_callback' );

  //contacts...
   add_option( 'step5_contacts_title', 'Zeeba Rent-a-Van');
   register_setting( 'zeeba_step_options_group', 'step5_contacts_title', 'zeeba_step5_options_callback' );

   add_option( 'step5_contacts_content', '<ul><li><a href="https://www.zeebavans.com">Zeebavans.com</a></li><li><a href="tel:(800) 940-9332">(800) 940-9332</a></li><li><a href="mailto:hello@zeebavans.com">hello@zeebavans.com</a></li></ul>');
   register_setting( 'zeeba_step_options_group', 'step5_contacts_content', 'zeeba_step5_options_callback' );

  //policy......
    $step5_args = array('Please have your Drivers License and proof of Insurance for all drivers present at time of pick up.');

    add_option( 'step5_policy_heading', 'Key Rental Policies');
    register_setting( 'zeeba_step_options_group', 'step5_policy_heading', 'zeeba_step5_options_callback' );
    
    add_option( 'step5_policy_list', $step5_args);
    register_setting( 'zeeba_step_options_group', 'step5_policy_list', 'zeeba_step5_options_callback' );

    add_option( 'step5_policy_download_text', 'Download full Policies');
    register_setting( 'zeeba_step_options_group', 'step5_policy_download_text', 'zeeba_step5_options_callback' );

    add_option( 'step5_policy_download_url', 'https://www.zeebavans.com/wp-content/uploads/2019/01/Rental-Policies.pdf');
    register_setting( 'zeeba_step_options_group', 'step5_policy_download_url', 'zeeba_step5_options_callback' );
}
add_action( 'admin_init', 'zeeba_step5_options_register_settings' );
/***************step 5 end*********************/

// function zeeba_register_options_page() {
//   add_options_page('Zeeba Options Page', 'Booking', 'manage_options', 'zeeba-options-page', 'zeeba_options_page');
// }
// add_action('admin_menu', 'zeeba_register_options_page');

// function zeeba_options_page()
// {
//   include(ZEEBAVAN_DIR.'templates/option-page.php');
// }



