<?php 

if ( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Charity_Details_List extends WP_List_Table {

  /** Class constructor */
  public function __construct() {

    parent::__construct( [
      'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
      'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
      'ajax'     => false //does this table support ajax?
    ] );

  }


  /**
   * Retrieve customers data from the database
   *
   * @param int $per_page
   * @param int $page_number
   *
   * @return mixed
   */
  public static function get_customers( $per_page = 5, $page_number = 1 ) {

    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->base_prefix}rental_price_charity";

    if ( ! empty( $_REQUEST['orderby'] ) ) {
      $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
      $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    return $result;
  }

  /**
   * Returns the count of records in the database.
   *
   * @return null|string
   */
  public static function record_count() {
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM {$wpdb->base_prefix}rental_price_charity";

    return $wpdb->get_var( $sql );
  }


  /** Text displayed when no customer data is available */
  public function no_items() {
    _e( 'No data avaliable.', 'sp' );
  }


  /**
   * Render a column when no column specific method exist.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default( $item, $column_name ) {
    switch ( $column_name ) {
      case 'reservation_id':
      case 'amount':
      case 'created_at':
        return $item[ $column_name ];
      default:
        return print_r( $item, true ); //Show the whole array for troubleshooting purposes
    }
  }



  /**
   *  Associative array of columns
   *
   * @return array
   */
  function get_columns() {
    $columns = [
      'reservation_id'    => __( 'Reservation ID', 'sp' ),
      'amount' => __( 'Amount', 'sp' ),
      'created_at'    => __( 'Date', 'sp' )
    ];

    return $columns;
  }


  /**
   * Columns to make sortable.
   *
   * @return array
   */
  public function get_sortable_columns() {
    $sortable_columns = array(
      'reservation_id' => array( 'reservation_id', true ),
      'amount' => array( 'amount', false )
    );

    return $sortable_columns;
  }


  /**
   * Handles data query and filter, sorting, and pagination.
   */
  public function prepare_items() {

    $this->_column_headers = $this->get_column_info();

    /** Process bulk action */
    $this->process_bulk_action();

    $per_page     = $this->get_items_per_page( 'data_per_page', 5 );
    $current_page = $this->get_pagenum();
    $total_items  = self::record_count();

    $this->set_pagination_args( [
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page'    => $per_page //WE have to determine how many items to show on a page
    ] );

    $this->items = self::get_customers( $per_page, $current_page );
  }

}


class SP_Plugin {

  // class instance
  static $instance;

  // customer WP_List_Table object
  public $customers_obj;

  // class constructor
  public function __construct() {
    add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
    add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
  }


  public static function set_screen( $status, $option, $value ) {
    return $value;
  }

  public function plugin_menu() {
    $hook = add_submenu_page(
      'zeebavans-booking',
      'Zeeba Charity Page',
      'Charity',
      'manage_options',
      'zeeba-charity-pages',
      [ $this, 'plugin_settings_page' ],'dashicons-cart'
    );

    add_action( "load-$hook", [ $this, 'screen_option' ] );

  }


  /**
   * Plugin settings page
   */
  public function plugin_settings_page() {
      
    ?>
    <div class="charity-page wrap ml-5">
      <div class="row">
        <div col-md-12>
          <h2>Charity Details</h2>
          <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
              <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                  <form method="post">
                    <?php
                    $this->customers_obj->prepare_items();
                    $this->customers_obj->display(); ?>
                  </form>
                </div>
              </div>
            </div>
            <br class="clear">
            
          </div>
        </div>
      </div>

    </div>
  <?php
  }

  /**
   * Screen options
   */
  public function screen_option() {

    $option = 'per_page';
    $args   = [
      'label'   => 'Charity Lists',
      'default' => 5,
      'option'  => 'data_per_page'
    ];

    add_screen_option( $option, $args );

    $this->customers_obj = new Charity_Details_List();
  }


  /** Singleton instance */
  public static function get_instance() {
    if ( ! isset( self::$instance ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }

}


add_action( 'plugins_loaded', function () {
  SP_Plugin::get_instance();
} );
