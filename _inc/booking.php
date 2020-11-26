<?php

//ini_set( 'display_errors', 1 );
//ini_set( 'display_startup_errors', 1 );
//error_reporting( E_ALL );

require_once 'class.booking.php';
require_once 'booking.ajax.php';

define( 'TRN_TIME_FORMAT', 'F d, Y H:i a' );


/**
 * @property TRNManager trn
 * @property array merged
 */
class ZeebaBooking {

	public $currentStep = 0;
	private $trnManager = null;
	private $data = [];
	private $mergedData = [];

	/**
	 * ZeebaBooking constructor.
	 *
	 * @param $url
	 * @param $senderID
	 * @param $customerNumber
	 * @param $passcode
	 * @param $sid
	 * @param $ip
	 */
	public function __construct( $url, $senderID, $customerNumber, $passcode, $sid, $ip ) {
		$this->trnManager = new TRNManager( $url, $senderID, $customerNumber, $passcode, $sid, $ip );

		$this->init();
		$this->helper();

	}

	private function init() {
		add_action( 'init', [ $this, 'session_open' ], 1 );
		add_action( 'wp_logout', [ $this, 'session_close' ] );
		add_action( 'wp_login', [ $this, 'session_close' ] );
		add_action( 'init', [ $this, 'load' ] );
	}

	private function helper() {
		function zeeba_book() {
			global $zeeba_book;

			return $zeeba_book;
		}

		function zeeba_get( $name ) {
			global $zeeba_book;

			return $zeeba_book->get( $name );
		}

		function zeeba_field( $key, $echo = true ) {
			$val = ( zeeba_book()->complete( zeeba_book()->currentStep ) && array_key_exists( $key, zeeba_book()->merged ) ) ? zeeba_book()->merged[ $key ] : '';
			if ( $echo ) {
				echo $val;
			}

			return $val;
		}

		function zeeba_form( $key, $shout = false ) {
			global $vehicle;
			$result = $key;
			if(!is_array(zeeba_field( 'form', false )) || count(zeeba_field( 'form', false )) == 0) {
				return '';
			}
			switch ( $key ) {
				case 'id':
					$result = zeeba_field( 'reservation', false );
					break;
				case 'pickup_location':
					$result = zeeba_field( 'pickup_location_text', false );
					break;
				case 'dropoff_location':
					$result = zeeba_field( 'dropoff_location_text', false );
					break;
				case 'pickup_address':
					$result = zeeba_field( 'pickup_address', false );
					break;
				case 'dropoff_address':
					$result = zeeba_field( 'dropoff_address', false );
					break;
				case 'rental_location':
					$result = zeeba_field( 'pickup_location_text', false );
					break;
				case 'return_location':
					$result = zeeba_field( 'dropoff_location_text', false );
					break;
				case 'pickup_datetime':
					$result = date( 'D M d, Y \@ H:i a', DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_field( 'pickup_date', false ) . ' ' . zeeba_field( 'pickup_time', false ) )->getTimestamp() );
					break;
				case 'dropoff_datetime':
					$result = date( 'D M d, Y \@ H:i a', DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_field( 'dropoff_date', false ) . ' ' . zeeba_field( 'dropoff_time', false ) )->getTimestamp() );
					break;
				case 'vehicle':
// 					$result = gf( 'car', $vehicle );
					$result = get_the_title($vehicle);
					break;
				case 'first_name':
				case 'last_name':
				case 'email':
				case 'phone_number':
				case 'flight':
				case 'flight_airline':
				case 'country':
				case 'address':
				case 'city':
				case 'zip':
				case 'card_name':
				case 'state':
					$result = zeeba_field( 'form', false )[ $key ];
					break;
				case 'card_type':
					$result = zeeba_field( 'form', false )[ $key ];
					if ( empty( $result ) || is_null( $result ) ) {
						$result = TRNRequest::cardType( zeeba_field( 'form', false )['card_number'] );
					}
					break;
				case 'card_number':
					$result = '**** **** **** ' . substr( zeeba_field( 'form', false )[ $key ], - 4 );
					break;
				case 'state':
					$result = ucwords( strtolower( us_states()[ zeeba_field( 'form', false )[ $key ] ] ) );
					break;
				case 'card_exp':
					$result = zeeba_field( 'form', false )[ $key ];
					if ( empty( $result ) || is_null( $result ) ) {
						$result = zeeba_field( 'form', false )['card_month'] . '/' . substr( zeeba_field( 'form', false )['card_year'], - 2 );
					} else {
						$result = substr( $result, 0, 2 ) . '/' . substr( $result, - 2 );
					}
					break;
			}
			if ( $shout ) {
				echo $result;
			}

			return $result;
		}
	}

	public function get( $name ) {
		if ( empty( $name ) ) {
			return $this->data;
		}
		$keys    = explode( '.', $name );
		$key_len = count( $keys );
		$val     = $this->data;
		for ( $i = 0; $i < $key_len && ! is_null( $val ); $i ++ ) {
			$val = array_key_exists( $keys[ $i ], $val ) ? $val[ $keys[ $i ] ] : null;
		}
		if ( $val === null && $name === 'status' ) {
			$val = 'new';
		}

		return $val;
	}

	public function complete( $step = 0, $value = null ) {
		if ( $step == 0 ) {
			if ( ! is_null( $value ) ) {
				$this->set( 'complete', (bool) $value );
			}

			return (bool) $this->get( 'complete' );
		}
		if ( ! in_array( $step, [ 1, 2, 3, 4, 5 ] ) ) {
			return false;
		}
		if ( ! is_null( $value ) ) {
			$this->set( 'step_' . $step . '.complete', (bool) $value );
		}

		return (bool) $this->get( 'step_' . $step . '.complete' );
	}

	public function set( $name, $value ) {
		if ( empty( $name ) ) {
			$this->data = $value;
		} else {
			$keys    = explode( '.', $name );
			$key_len = count( $keys );
			$val     = &$this->data;
			for ( $i = 0; $i < ( $key_len - 1 ) && ! is_null( $val ); $i ++ ) {
				if ( ! array_key_exists( $keys[ $i ], $val ) ) {
					$val[ $keys[ $i ] ] = [];
				}
				$val = &$val[ $keys[ $i ] ];
			}
			$val[ $keys[ $key_len - 1 ] ] = $value;
		}

		$this->save();
	}

	private function save() {
		$_SESSION['booking'] = $this->data;
		$this->merge();
	}

	private function merge() {
		$result = [];
		foreach ( $this->data as $k => $v ) {
			if ( is_array( $v ) ) {
				$result = array_merge( $result, $v );
			} else {
				$result[ $k ] = $v;
			}
		}
		$this->mergedData = $result;
	}

	public function period() {
		$period = $this->get( 'step_2.rate_data.total.days' );
		if ( ! is_null( $period ) ) {
			return (int) $period;
		}

		$pt   = DateTime::createFromFormat( TRN_TIME_FORMAT, $this->get( 'step_1.pickup_date' ) . ' ' . $this->get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt   = DateTime::createFromFormat( TRN_TIME_FORMAT, $this->get( 'step_1.dropoff_date' ) . ' ' . $this->get( 'step_1.dropoff_time' ) )->getTimestamp();
		$diff = $dt - $pt;

		// 24 * 60 * 60 = 86400 – seconds in one day
		return ceil( ( $diff * 1.0 ) / ( 24 * 60 * 60 ) );
	}

	public function load() {
		if ( array_key_exists( 'new', $_GET ) ) {
			$_SESSION['booking'] = [];
			$this->follow( 'booking/step-1' );

			return;
		} elseif ( array_key_exists( 'home', $_GET ) ) {
			$_SESSION['booking'] = [];
			$this->follow( 'home' );

			return;
		}

		$this->data = array_key_exists( 'booking', $_SESSION ) ? $_SESSION['booking'] : [];
		if ( ! is_array( $this->data ) ) {
			$this->data = [];
		}
		$this->merge();
		$this->currentStep = $this->step() - 1;
	}

	private function follow( $slug ) {
		wp_redirect( get_permalink( get_page_by_path( $slug ) ) /*. '?v='.time()*/ );
		exit();
	}

	public function step() {
		foreach ( [ 4, 3, 2, 1 ] as $step ) {
			if ( $this->complete( $step ) ) {
				return $step + 1;
			}
		}

		return 0;
	}

	public function session_open() {
		if ( ! session_id() ) {
			session_start();
		}
	}

	public function session_close() {
		session_destroy();
	}

	function __get( $name ) {
		switch ( $name ) {
			case 'trn' :
				return $this->trnManager;
			case 'merged':
				return $this->mergedData;
		}

		return false;
	}

	public function exists( $name ) {
		$keys    = explode( '.', $name );
		$key_len = count( $keys );
		$val     = $this->data;
		for ( $i = 0; $i < $key_len && ! is_null( $val ); $i ++ ) {
			if ( array_key_exists( $keys[ $i ], $val ) ) {
				$val = $val[ $keys[ $i ] ];
			} else {
				return false;
			}
		}

		return true;
	}

	public function redirect( $step ) {
		if ( $step == 'modify' ) {
			if ( ! $this->modify() ) {
				$this->follow( 'booking/step-' . $this->step() );
			}
		} else {
			if ( $this->modify() && in_array($this->get( 'status' ), ['72hours', 'modified', 'cancelled']) ) {
				$this->follow( 'booking/modify' );
			}
		}
		for ( $i = 1; $i < $step; $i ++ ) {
			if ( ! $this->complete( $i ) ) {
				$this->follow( 'booking/step-' . $i );
			}
		}
		if ( $this->skip( $step ) ) {
			$this->follow( 'booking/step-' . ( $step + 1 ) );
		}
	}

	public function modify( $value = null ) {
		if ( ! is_null( $value ) ) {
			$this->set( 'modify', (bool) $value );
		}

		return (bool) $this->get( 'modify' );
	}

	public function skip( $step, $value = null ) {
		if ( ! in_array( $step, [ 1, 2, 3, 4 ] ) ) {
			return false;
		}
		if ( ! is_null( $value ) ) {
			$this->set( 'step_' . $step . '.skip', (bool) $value );
		}

		return (bool) $this->get( 'step_' . $step . '.skip' );
	}

}

global $zeeba_book, $zeeba_ajax;
$zeeba_book = new ZeebaBooking( 'https://weblink.tsdasp.net/requests/service.svc/', 'ZEB01', get_option('booking_api_username'), get_option('booking_api_password'), get_client_ip(), get_client_ip() );
// $zeeba_book = new ZeebaBooking( 'https://weblink.tsdasp.net/requests/service.svc/', 'ZEB01', '42357', '42357', get_client_ip(), get_client_ip() );
$zeeba_ajax = new ZeebaBookingAjax();