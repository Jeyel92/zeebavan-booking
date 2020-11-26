<?php
class ZeebaBookingAjax {

	public function __construct() {
		$this->init();
	}

	private function init() {
		add_action( 'jx_zeeba_locations', [ $this, 'locations' ] );
		add_action( 'jx_zeeba_modify', [ $this, 'modify' ] );
		add_action( 'jx_zeeba_cancel', [ $this, 'cancel' ] );
		add_action( 'jx_zeeba_step_1', [ $this, 'step_1' ] );
		add_action( 'jx_zeeba_step_2_load', [ $this, 'step_2_load' ] );
		add_action( 'jx_zeeba_step_2', [ $this, 'step_2' ] );
		add_action( 'jx_zeeba_step_3_load', [ $this, 'step_3_load' ] );
		add_action( 'jx_zeeba_step_3_calculate', [ $this, 'step_3_calculate' ] );
		add_action( 'jx_zeeba_step_3_copy', [ $this, 'step_3_copy' ] );
		add_action( 'jx_zeeba_step_3_more', [ $this, 'step_3_more' ] );
		add_action( 'jx_zeeba_step_3', [ $this, 'step_3' ] );
		add_action( 'jx_zeeba_step_4', [ $this, 'step_4' ] );

		add_action( 'wp_ajax_zeeba_file_upload', [ $this, 'file_upload' ] );
		add_action( 'wp_ajax_nopriv_zeeba_file_upload', [ $this, 'file_upload' ] );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function modify( $jx ) {
		$data = $jx->getData();
		unset( $data['jx_action'] );

		if ( ! $this->required( 'reservation', $data ) ) {
			$jx->variable( 'error', 'Reservation Code should be set' );

			return;
		}

		$info = zeeba_book()->trn->get_reservation( $data['reservation'] );
		if ( ! is_array( $info ) ) {
			$jx->variable( 'error', $info );

			return;
		}
		if ( $info['status'] == 'closed' ) {
			$jx->variable( 'error', 'Your Reservation already closed' );

			return;
		}
		$jx->variable('request', zeeba_book()->trn->lastRequest());
		$jx->variable('response', zeeba_book()->trn->lastResponse());
		zeeba_book()->set( '', $info );

		if ( zeeba_book()->exists( 'step_1' ) ) {
			$vehicles = get_posts( [
				'post_type'     => 'vehicle',
				'post_per_page' => - 1,
				'meta_query'    => [
					[
						'key'     => 'sys_class_code',
						'value'   => zeeba_get( 'step_1.van_type' ),
						'compare' => 'LIKE'
					]
				]
			] );
			if ( count( $vehicles ) == 1 ) {
				zeeba_book()->set( 'step_1.van_code', zeeba_get( 'step_1.van_type' ) );
				zeeba_book()->set( 'step_1.van_type', $vehicles[0]->ID );
			}
			$locations = get_posts( [
				'post_type'     => 'location',
				'post_per_page' => - 1,
				'meta_query'    => [
					'relation' => 'OR',
					[
						'key'     => 'sys_trn_id',
						'value'   => zeeba_get( 'step_1.pickup_location' ),
						'compare' => '='
					],
					[
						'key'     => 'sys_trn_id',
						'value'   => zeeba_get( 'step_1.dropoff_location' ),
						'compare' => '='
					]
				]
			] );
			foreach ( $locations as $loc ) {
				if ( gf( 'sys_trn_id', $loc ) == zeeba_get( 'step_1.pickup_location' ) ) {
					zeeba_book()->set( 'step_1.pickup_location_text', get_the_title( $loc ) . ' (' . gf( 'sys_trn_id', $loc ) . ')' );
					zeeba_book()->set( 'step_1.pickup_address', gf( 'coordinates', $loc )['address'] );
				}
				if ( gf( 'sys_trn_id', $loc ) == zeeba_get( 'step_1.dropoff_location' ) ) {
					zeeba_book()->set( 'step_1.dropoff_location_text', get_the_title( $loc ) . ' (' . gf( 'sys_trn_id', $loc ) . ')' );
					zeeba_book()->set( 'step_1.dropoff_address', gf( 'coordinates', $loc )['address'] );
				}
			}
		}
		$jx->console( zeeba_book()->get( '' ) );
		zeeba_book()->complete( 0, true );
		zeeba_book()->complete( 1, true );
		zeeba_book()->complete( 2, true );
		zeeba_book()->complete( 3, true );
		zeeba_book()->complete( 4, false );
		zeeba_book()->skip( 2, true );
		zeeba_book()->modify( true );

		$jx->redirect( get_permalink( get_page_by_path( 'booking/modify' ) ) /*. '?v='.time()*/ );
	}

	private function required( $needle, $haystack ) {
		return array_key_exists( $needle, $haystack ) && ! empty( $haystack[ $needle ] );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function cancel( $jx ) {
		if ( ! zeeba_book()->modify() ) {
			$jx->variable( 'error', 'To cancel reservation You should start modifying one' );

			return;
		}
		if ( zeeba_book()->get( 'status' ) == '72hours' ) {
			$jx->variable( 'error', 'You can`t cancel Reservation within 72 hours from pickup' );

			return;
		}
		$info = zeeba_book()->trn->cancel( zeeba_get( 'step_4.reservation' ) );
		if ( ! is_array( $info ) ) {
			$jx->variable( 'error', $info );

			return;
		}

		$manager_email = gf( 'reservation_email', 'options' );
		if ( empty( $manager_email ) ) {
			$manager_email = 'valery.alexeev@me.com';
		}

		$result = va_get_template_part( 'email-cancel', [], true );
		wp_mail( zeeba_get( 'step_4.form.email' ), 'Your Zeebavans Reservation Cancellation', $result, 'Content-type: text/html' );
		wp_mail( $manager_email, 'Zeebavans Reservation Cancellation', $result, 'Content-type: text/html' );

		$jx->variable( 'success', gf( 'cancel_success_text', 'options' ) != '' ? gf( 'cancel_success_text', 'options' ) : $info['message'] );
		$jx->redirect( get_permalink( get_page_by_path( 'home' ) ) /*. '?v='.time()*/ );

		zeeba_book()->set( '', '' );

		return;
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function locations( $jx ) {
		/* @var wpjxmResponse $jx */
		$search_keys = [
			'sys_trn_id',
			'sys_city',
			'sys_state',
			'sys_state_code',
			'sys_zip',
		];
		$term        = $jx->getData()['term'];
		if ( preg_match( '/^\d{5}$/', $term ) && $term >= 210 && $term <= 99950 ) {
			$zip = (int) $term;
			$res = json_decode( file_get_contents( 'http://api.zippopotam.us/us/' . $zip ) );
			if ( ! empty( $res ) && isset( $res->places ) && count( $res->places ) > 0 ) {
				$lat1 = $res->places[0]->latitude;
				$lng1 = $res->places[0]->longitude;

				$locations = get_posts( [
					'post_type'      => 'location',
					'posts_per_page' => - 1,
				] );

				$min_dist     = - 1;
				$min_location = null;
				foreach ( $locations as $location ) {
					$lat2 = gf( 'coordinates', $location )['lat'];
					$lng2 = gf( 'coordinates', $location )['lng'];
					$dist = $this->distance( $lat1, $lng1, $lat2, $lng2 );
					if ( $min_dist == - 1 || $dist < $min_dist ) {
						$min_dist     = $dist;
						$min_location = $location;
					}
				}
				if ( ! is_null( $min_location ) ) {
					$state  = ( gf( 'sys_trn_id', $min_location ) == '' ? '' : ' (' . gf( 'sys_trn_id', $min_location ) . ')' ) . ( gf( 'sys_state', $min_location ) == '' ? '' : ', ' . gf( 'sys_state', $min_location ) );
					$result = [
						[
							'value' => get_the_title( $min_location ) . $state,
							'data'  => gf( 'sys_trn_id', $min_location ),
						]
					];
					$jx->variable( 'locations', $result );

					return;
				}
			}
		}

		$args = [
			'post_type'      => 'location',
			'posts_per_page' => - 1,
			'_meta_or_title' => $term,
		];
		if ( ! empty( $term ) ) {
			$args['meta_query'] = [ 'relation' => 'OR' ];
			foreach ( $search_keys as $key ) {
				$args['meta_query'][] = [
					'key'     => $key,
					'value'   => $term,
					'compare' => 'LIKE'
				];
			}
			$locations = get_posts( $args );
			$result    = [];
			foreach ( $locations as $location ) {
				$state    = ( gf( 'sys_trn_id', $location ) == '' ? '' : ' (' . gf( 'sys_trn_id', $location ) . ')' ) . ( gf( 'sys_state', $location ) == '' ? '' : ', ' . gf( 'sys_state', $location ) );
				$result[] = [
					'value' => get_the_title( $location ) . $state,
					'data'  => gf( 'sys_trn_id', $location ),
				];
			}
			if ( $locations ) {
				$jx->variable( 'locations', $result );
			}
		}
	}

	private function distance( $lat1, $lon1, $lat2, $lon2, $unit = 'M' ) {
		$theta = $lon1 - $lon2;
		$dist  = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
		$dist  = acos( $dist );
		$dist  = rad2deg( $dist );
		$miles = $dist * 60 * 1.1515;
		$unit  = strtoupper( $unit );

		if ( $unit == "K" ) {
			return ( $miles * 1.609344 );
		} else if ( $unit == "N" ) {
			return ( $miles * 0.8684 );
		} else {
			return $miles;
		}
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_1( $jx ) {
		$data = $jx->getData();
		unset( $data['jx_action'] );

		if ( ! $this->required( 'pickup_date', $data ) ) {
			$jx->variable( 'error', 'Pickup Date should be set' );

			return;
		}
		if ( ! $this->required( 'pickup_time', $data ) ) {
			$jx->variable( 'error', 'Pickup Time should be set' );

			return;
		}
		$pt = DateTime::createFromFormat( TRN_TIME_FORMAT, $data['pickup_date'] . ' ' . $data['pickup_time'] )->getTimestamp();
		if ( time() > $pt ) {
			$jx->variable( 'error', 'Pickup time can`t be before now' );

			return;
		}
		if ( ! $this->required( 'dropoff_date', $data ) ) {
			$jx->variable( 'error', 'Dropoff Date should be set' );

			return;
		}
		if ( ! $this->required( 'dropoff_time', $data ) ) {
			$jx->variable( 'error', 'Dropoff Time should be set' );

			return;
		}
		$dt = DateTime::createFromFormat( TRN_TIME_FORMAT, $data['dropoff_date'] . ' ' . $data['dropoff_time'] )->getTimestamp();
		if ( $pt >= $dt ) {
			$jx->variable( 'error', 'Dropoff time can`t be before Pickup time' );

			return;
		}
		if ( ! $this->required( 'van_type', $data ) ) {
			$jx->variable( 'error', 'Selected van not found' );

			return;
		}
		if ( ! $this->required( 'pickup_location', $data ) ) {
			$jx->variable( 'error', 'Pickup location should be set' );

			return;
		}
		if ( $data['different_location'] == 'yes' ) {
			if ( ! array_key_exists( 'dropoff_location', $data ) ) {
				$jx->variable( 'error', 'Dropoff location should be set' );

				return;
			} elseif ( $data['dropoff_location'] == $data['pickup_location'] ) {
				$jx->variable( 'error', 'Dropoff Location should be different from Pickup Location' );

				return;
			} elseif ($dt - $pt < 3*24*60*60) {
				$jx->variable( 'error', 'One way rentals require at least 3 days of rent' );

				return;
			}
		} else {
			$data['dropoff_location']      = $data['pickup_location'];
			$data['dropoff_location_text'] = $data['pickup_location_text'];
		}
		$locations = get_posts( [
			'post_type'     => 'location',
			'post_per_page' => - 1,
			'meta_query'    => [
				'relation' => 'OR',
				[
					'key'     => 'sys_trn_id',
					'value'   => $data['pickup_location'],
					'compare' => '='
				],
				[
					'key'     => 'sys_trn_id',
					'value'   => $data['dropoff_location'],
					'compare' => '='
				]
			]
		] );
		if ( count( $locations ) < ( $data['different_location'] == 'yes' ? 2 : 1 ) ) {
			$jx->variable( 'error', 'Location not found' );

			return;
		}
		foreach ( $locations as $location ) {
			$loc = gf( 'sys_trn_id', $location );
			if ( $loc == $data['pickup_location'] ) {
				$data['pickup_address'] = gf( 'coordinates', $location )['address'];
			}
			if ( $loc == $data['dropoff_location'] ) {
				$data['dropoff_address'] = gf( 'coordinates', $location )['address'];
			}
		}

		zeeba_book()->set( 'step_1', $data );
		zeeba_book()->complete( 1, true );

		$jx->redirect('/zeebavans/booking/step-2');
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_2_load( $jx ) {
		if ( ! zeeba_book()->complete( 1 ) ) {
			$jx->redirect('/zeebavans/booking/');
		}

		$selected_van = zeeba_get( 'step_1.van_type' );
		$pl           = zeeba_get( 'step_1.pickup_location' );
		$dl           = zeeba_get( 'step_1.dropoff_location' );
		$pt           = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt           = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
		$dc           = zeeba_get( 'step_1.discount_code' );

		$rates = zeeba_book()->trn->get_rates( $pl, $dl, $pt, $dt, $dc );
		$jx->variable('request', zeeba_book()->trn->lastRequest());
		$jx->variable('response', zeeba_book()->trn->lastResponse());
		zeeba_book()->set( 'step_2.rates', $rates );

		$result = '';
		if ( is_array( $rates ) ) {
			$rates_render = [];
			$vehicles     = get_posts( [
				'post_type'     => 'vehicle',
				'posts_per_page' => -1
			] );
			$used         = [];
			foreach ( $rates as $rate ) {
				$class_code = $rate['class_code'];
				$vehicle    = null;
				foreach ( $vehicles as &$v ) {
					$classes_raw = strtolower( gf( 'sys_class_code', $v ) );
					$classes = json_decode( $classes_raw, true );
					if ( in_array( strtolower( $class_code ), $classes ) ) {
						$vehicle = $v;
						$used[]  = $classes_raw;
						break;
					}
				}
				if ( is_null( $vehicle ) ) {
					continue;
				}

				global $post;
				$post           = $vehicle;
				$rates_render[] = [
					'order' => gf( 'display_order' ) + ( $vehicle->ID == $selected_van ? 0 : 1000 ),
					'html'  => va_get_template_part( 'panel-booking-rate', [
						'rate'     => $rate,
						'selected' => $vehicle->ID == $selected_van
					], true ),
				];
				wp_reset_query();
			}
			if ( count( $rates_render ) == 0 ) {
				$rates = 'No rates found.';
			} else {
				foreach ( $vehicles as &$v ) {
					if ( ! in_array( strtolower( gf( 'sys_class_code', $v ) ), $used ) ) {
						$rate = false;
						global $post;
						$post           = $v;
						$rates_render[] = [
							'order' => gf( 'display_order' ) + 2000,
							'html'  => va_get_template_part( 'panel-booking-rate', [
								'rate'     => $rate,
								'selected' => false
							], true ),
						];
						wp_reset_query();
					}
				}
			}
			uasort( $rates_render, function ( $a, $b ) {
				return $a['order'] - $b['order'];
			} );
			$result = array_reduce( $rates_render, function ( $carry, $el ) {
				return $carry . $el['html'];
			} );
		}
		if ( ! is_array( $rates ) ) {
			$result = va_get_template_part( 'panel-booking-error', [
				'message' => $rates
			], true );
		}
		$jx->variable( 'result', $result );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_2( $jx ) {
		if ( ! zeeba_book()->complete( 1 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-1' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		$rate = false;
		foreach ( zeeba_get( 'step_2.rates' ) as $rt ) {
			if ( $rt['id'] == $data['rate'] ) {
				$rate = $rt;
				break;
			}
		}
		if ( ! $rate ) {
			$jx->variable( 'error', 'Rate not found' );

			return;
		}

		zeeba_book()->set( 'step_2.rate', $rate['id'] );
		zeeba_book()->set( 'step_2.rate_data', $rate );
		zeeba_book()->complete( 2, true );

		$jx->redirect( get_permalink( get_page_by_path( 'booking/step-3' ) ) /*. '?v='.time()*/ );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_3_load( $jx ) {
		if ( ! zeeba_book()->complete( 2 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-2' ) ) /*. '?v='.time()*/ );
		}

		$pl    = zeeba_get( 'step_1.pickup_location' );
		$pt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$rate  = zeeba_get( 'step_2.rate' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );

		$extras = zeeba_book()->trn->get_extra( $pl, $pt, $rate, $class );
		zeeba_book()->set( 'step_3.available', $extras );
		if ( is_array( $extras ) ) {
			$bundles = [];
			$i = 0;
			foreach(gf('bundles', get_page_by_path( 'booking/step-3' )->ID) as $bundle) {
				$i++;
				$bundles[] = [
					'id' => 'BUNDLE'.$i,
					'branch' => 'BUNDLE'.$i,
					'calc' => 'DAILY',
					'desc' => $bundle['name'],
					'note' => $bundle['tooltip'],
					'amount' => $bundle['price'],
				];
			}
			$extras['non-free'] = array_merge($bundles, $extras['non-free']);
			
			$hidden_extras = explode(' ', gf('hidden_options', get_page_by_path( 'booking/step-3' )->ID));
			$extras['non-free'] = array_filter($extras['non-free'], function($v) use ($hidden_extras) {
				return !in_array($v['id'], $hidden_extras);
			});
			$extras['free'] = array_filter($extras['free'], function($v) use ($hidden_extras) {
				return !in_array($v['id'], $hidden_extras);
			});
			
			if(zeeba_book()->period() == 1) {
				$extras['non-free'] = [];
			}
			
			$renames_raw = gf('renaming_options', get_page_by_path( 'booking/step-3' )->ID);
			$renames = [];
			foreach($renames_raw as $rename) {
				$renames[strtolower($rename['code'])] = $rename['text'];
			}
			foreach($extras['non-free'] as &$extra) {
				if(array_key_exists(strtolower($extra['id']), $renames)) {
					$extra['desc'] = $renames[strtolower($extra['id'])];
				}
			}
			foreach($extras['free'] as &$extra) {
				if(array_key_exists(strtolower($extra['id']), $renames)) {
					$extra['desc'] = $renames[strtolower($extra['id'])];
				}
			}
			
			$result = 'OK';
			$non_free = !count($extras['non-free']) ? false : va_get_template_part( 'panel-booking-extras', [
				'options' => $extras['non-free']
			], true );

			$free = va_get_template_part( 'panel-booking-extras', [
				'options' => $extras['free']
			], true );

			$jx->variable( 'non_free', $non_free );
			$jx->variable( 'free', $free );
		} else {
			$result = va_get_template_part( 'panel-booking-error', [
				'message' => $extras
			], true );
		}

		$jx->variable( 'hidden', $hidden_extras );
		$jx->variable( 'extras', $extras );
		$jx->variable( 'result', $result );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_3_calculate( $jx ) {

		// Go back to step 2 if we aren't ready for step 2
		if ( ! zeeba_book()->complete( 2 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-2' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		$pl    = zeeba_get( 'step_1.pickup_location' );
		$dl    = zeeba_get( 'step_1.dropoff_location' );
		$pt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
		$dc    = zeeba_get( 'step_1.discount_code' );
		$rate  = zeeba_get( 'step_2.rate_data' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );

		$options = $this->bundle_options($data['options']);

		$bill = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );
		zeeba_book()->set( 'step_3', $data );
		zeeba_book()->set( 'step_3.bill', $bill );
		zeeba_book()->complete( 3, true );
		
		$jx->variable( 'result', va_get_template_part( 'panel-booking-bill', [
			'bill' => $bill
		], true ) );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_3_copy( $jx ) {

		// Go back to step 2 if we aren't ready for step 2
		if ( ! zeeba_book()->complete( 2 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-2' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		if ( ! $this->required( 'email', $data ) ) {
			$jx->variable( 'error', 'Email is required' );

			return;
		} else if ( ! is_email( $data['email'] ) ) {
			$jx->variable( 'error', 'Email is invalid' );

			return;
		}

		$pl    = zeeba_get( 'step_1.pickup_location' );
		$dl    = zeeba_get( 'step_1.dropoff_location' );
		$pt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
		$dc    = zeeba_get( 'step_1.discount_code' );
		$rate  = zeeba_get( 'step_2.rate_data' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );

		$vehicles = get_posts( [
			'post_type'     => 'vehicle',
			'post_per_page' => - 1,
			'meta_query'    => [
				[
					'key'     => 'sys_class_code',
					'value'   => $class,
					'compare' => 'LIKE'
				]
			]
		] );
		$options = $this->bundle_options($data['options']);
		$bill     = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );
		$vehicle  = count( $vehicles ) ? $vehicles[0] : [];

		$result = va_get_template_part( 'email-copy', [
			'bill'    => $bill,
			'vehicle' => $vehicle
		], true );

		wp_mail( $data['email'], 'Your Zeebavans Price Quote', $result, 'Content-type: text/html' );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_3_more( $jx ) {

		// Go back to step 2 if we aren't ready for step 2
		if ( ! zeeba_book()->complete( 2 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-2' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		if ( ! $this->required( 'first_name', $data ) ) {
			$jx->variable( 'error', 'First Name is required' );
		} else if ( ! $this->required( 'last_name', $data ) ) {
			$jx->variable( 'error', 'Last Name is required' );
		} else if ( ! $this->required( 'phone', $data ) ) {
			$jx->variable( 'error', 'Phone is required' );
		} else if ( ! $this->required( 'email', $data ) ) {
			$jx->variable( 'error', 'Email is required' );
		} else if ( ! is_email( $data['email'] ) ) {
			$jx->variable( 'error', 'Email is invalid' );
		} else if ( ! $this->required( 'message', $data ) ) {
			$jx->variable( 'error', 'Message is required' );
		} else {

			$pl    = zeeba_get( 'step_1.pickup_location' );
			$dl    = zeeba_get( 'step_1.dropoff_location' );
			$pt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
			$dt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
			$dc    = zeeba_get( 'step_1.discount_code' );
			$rate  = zeeba_get( 'step_2.rate_data' );
			$class = zeeba_get( 'step_2.rate_data.class_code' );

			$options = $this->bundle_options($data['options']);

			$vehicles = get_posts( [
				'post_type'     => 'vehicle',
				'post_per_page' => - 1,
				'meta_query'    => [
					[
						'key'     => 'sys_class_code',
						'value'   => $class,
						'compare' => 'LIKE'
					]
				]
			] );
			$bill     = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );
			$vehicle  = count( $vehicles ) ? $vehicles[0] : [];

			$result = va_get_template_part( 'email-more', [
				'data'    => $data,
				'bill'    => $bill,
				'vehicle' => $vehicle
			], true );


			$manager_email = gf( 'reservation_email', 'options' );
			if ( empty( $manager_email ) ) {
				$manager_email = 'valery.alexeev@me.com';
			}

			wp_mail( $manager_email, 'Website information request', $result, 'Content-type: text/html' );
		}
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_3( $jx ) {

		// Go back to step 2 if we aren't ready for step 2
		if ( ! zeeba_book()->complete( 2 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-2' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		$pl    = zeeba_get( 'step_1.pickup_location' );
		$dl    = zeeba_get( 'step_1.dropoff_location' );
		$pt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt    = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
		$dc    = zeeba_get( 'step_1.discount_code' );
		$rate  = zeeba_get( 'step_2.rate_data' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );

		$options = $this->bundle_options($data['options']);
		
		$bill = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );

		zeeba_book()->set( 'step_3.bill', $bill );
		zeeba_book()->set( 'step_3.options', $options );
		zeeba_book()->complete( 3, true );

		$jx->redirect( get_permalink( get_page_by_path( 'booking/step-4' ) ) /*. '?v='.time()*/ );
	}

	/**
	 * @param wpjxmResponse $jx
	 */
	public function step_4( $jx ) {

		// Go back to step 3 if we aren't ready for step 3
		if ( ! zeeba_book()->complete( 3 ) ) {
			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-3' ) ) /*. '?v='.time()*/ );
		}

		$data = $jx->getData();
		unset( $data['jx_action'] );

		$form = $data['form'];

		//
		// 6/6/2019 Temporary modification to include cvv in the note
		// Also keep the original 'special' field somewhere else so we can email without the cvv
		// ------------------------------------
		$original_special = '';
		$original_special = $data['form']['special'];
		if (isset($data['form']['card_cvv'])) {
			$data['form']['special'] = ' ###' . $data['form']['card_cvv'] . '### ' . $data['form']['special'];
		}
		// ------------------------------------

		if ( ! $this->required( 'first_name', $form ) ) {
			$jx->variable( 'error', 'First Name should be set' );

			return;
		}
		if ( ! $this->required( 'last_name', $form ) ) {
			$jx->variable( 'error', 'Last Name should be set' );

			return;
		}
		if ( ! $this->required( 'phone_number', $form ) ) {
			$jx->variable( 'error', 'Phone Number should be set' );

			return;
		}
		if ( ! $this->required( 'email', $form ) ) {
			$jx->variable( 'error', 'Email should be set' );

			return;
		}
		if ( ! $this->required( 'email_confirm', $form ) ) {
			$jx->variable( 'error', 'Email Confirm should be set' );

			return;
		}
		if ( $form['email'] != $form['email_confirm'] ) {
			$jx->variable( 'error', 'Email Confirm should be equal to Email' );

			return;
		}
		if ( ! $this->required( 'country', $form ) ) {
			$jx->variable( 'error', 'Country should be set' );

			return;
		}
		if ( ! $this->required( 'address', $form ) ) {
			$jx->variable( 'error', 'Address should be set' );

			return;
		}
		if ( ! $this->required( 'zip', $form ) ) {
			$jx->variable( 'error', 'ZIP should be set' );

			return;
		}
		if ( ! $this->required( 'city', $form ) ) {
			$jx->variable( 'error', 'City should be set' );

			return;
		}
		if ( ! $this->required( 'state', $form ) ) {
			$jx->variable( 'error', 'State should be set' );

			return;
		}

		# TEST
		if (isset($data['form']['stripe_token'])) {
			// Don't do anything...
		}
		else { // Original cases
			if ( ! zeeba_book()->modify() ) {
				if ( ! $this->required( 'card_number', $form ) ) {
					$jx->variable( 'error', 'Card number is required' );

					return;
				}
				if ( ! va_validate_card( preg_replace( '/[^\d]/', '', $form['card_number'] ) ) ) {
					$jx->variable( 'error', 'Card number is required' );

					return;
				}
				if ( ! $this->required( 'card_name', $form ) ) {
					$jx->variable( 'error', 'Name on card should be set' );

					return;
				}
				if ( ! $this->required( 'card_month', $form ) ) {
					$jx->variable( 'error', 'Card Month should be set' );

					return;
				}
				if ( ! $this->required( 'card_year', $form ) ) {
					$jx->variable( 'error', 'Card Year should be set' );

					return;
				}
				if ( ! $this->required( 'card_cvv', $form ) ) {
					$jx->variable( 'error', 'Card CVV should be set' );

					return;
				}
			}
		}
		$docs    = [];
		$allowed = zeeba_get( 'step_4.allowed_attaches' );
		if (isset($data['form']['attached']) && is_array($data['form']['attached'])) {
			foreach ( json_decode( $data['form']['attached'] ) as $attach ) {
				if ( in_array( $attach, $allowed ) ) {
					$docs[] = wp_get_attachment_url( $attach );
				}
			}
		}
		$data['form']['documents'] = implode( ' ', $docs );

		$pl         = zeeba_get( 'step_1.pickup_location' );
		$dl         = zeeba_get( 'step_1.dropoff_location' );
		$pt         = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.pickup_date' ) . ' ' . zeeba_get( 'step_1.pickup_time' ) )->getTimestamp();
		$dt         = DateTime::createFromFormat( TRN_TIME_FORMAT, zeeba_get( 'step_1.dropoff_date' ) . ' ' . zeeba_get( 'step_1.dropoff_time' ) )->getTimestamp();
		$dc         = zeeba_get( 'step_1.discount_code' );
		$rate       = zeeba_get( 'step_2.rate_data' );
		$class      = zeeba_get( 'step_2.rate_data.class_code' );
		$extraCodes = zeeba_get( 'step_3.options' );
		$code       = zeeba_book()->modify() ? zeeba_get( 'step_4.reservation' ) : false;

		$prepaid = gf( 'reservation_prepaid', 'options' );
		$deposit = 0;
		if ( $prepaid ) {
			$prepaid = gf( 'reservation_prepaid_amount', 'options' );
			$deposit = gf( 'reservation_deposit_amount', 'options' );
		}

		// This is where the stripe charge happens
		if (STRIPE_ENABLED && $code === false && isset($form['stripe_token'])) { // $code == false for new transactions. Otherwise it's the reservation number.
			require_once(get_stylesheet_directory() . '/inc/stripe/init.php');


			if (STRIPE_LIVE) {
				\Stripe\Stripe::setApiKey(STRIPE_LIVE_SECRET_KEY);
			}
			else {
				\Stripe\Stripe::setApiKey(STRIPE_TEST_SECRET_KEY);
			}


			# Let's try and make a charge
			try {

				$stripe_customer = \Stripe\Customer::create(array(
					'name' => $form['first_name'] . ' ' . $form['last_name'],
					'phone' => $form['phone_number'],
					'email' => $form['email'],
					'source' => $form['stripe_token'],
					'metadata' => ['TSD_Reservation'=>'...'] # This is acquired after it is entered in TSD
				));

				$stripe_charge = \Stripe\Charge::create(array(
					'customer' => $stripe_customer->id,
					'amount'   => zeeba_field('bill', false)['total'] * 100,

					// So that it doesn't charge the card now.
					// Charge must be captured within 7 days
					'capture'  => false,

					'currency' => 'usd'
				));

				$data['form']['special'] .= ' | Stripe ID: ' . $stripe_customer->id;
			}
			catch (Exception $e) {
				$body = $e->getJsonBody();

				if (isset($body['error']['message'])) {
					$jx->variable('error', 'Stripe Error: ' . $body['error']['message']);
				}
				else {
					$jx->variable('error', 'Stripe Error: ' . print_r($body['error'], true));
				}
				return;
			}
		}


		$res = zeeba_book()->trn->reserve( $pl, $dl, $pt, $dt, $rate['id'], $class, $data['form'], $dc, $extraCodes, $code, $prepaid, $deposit );
		if ( ! is_array( $res ) ) {
			$jx->variable( 'error', $res );

			return;
		}

		// After a charge is made, we'll update TSD with the Res #
		if (STRIPE_ENABLED && $code === false && isset($form['stripe_token'])) { // $code == false for new transactions. Otherwise it's the reservation number.
			if (isset($res['id'])) {

				// Update the TSD Reservation ID
				\Stripe\Customer::update(
					$stripe_customer->id,
					[
						'metadata' => ['TSD_Reservation' => $res['id']]
					]
				);
			}
		}


		// 6/6/2019 mod
		// See above for details
		$data['form']['special'] = $original_special;
		// ---------------------------------------

		unset( $data['form']['card_cvv'] );
		$data['form']['card_number'] = '*** **** **** ' . substr( $data['form']['card_number'], - 4 );

		zeeba_book()->set( 'step_4.reservation', $res['id'] );
		zeeba_book()->set( 'step_4.form', $data['form'] );
		zeeba_book()->complete( 4, true );
		zeeba_book()->skip( 1, true );
		zeeba_book()->skip( 2, true );
		zeeba_book()->skip( 3, true );
		zeeba_book()->skip( 4, true );

		$manager_email = gf( 'reservation_email', 'options' );
		if ( empty( $manager_email ) ) {
			$manager_email = 'valery.alexeev@me.com';
		}

		if ( zeeba_book()->modify() ) {
			zeeba_book()->set( 'status', 'modified' );

			$result = va_get_template_part( 'email-modify', array(), true );
			wp_mail( zeeba_get( 'step_4.form.email' ), 'Your Zeebavans Reservation Modification', $result, 'Content-type: text/html' );
			wp_mail( $manager_email, 'Zeebavans Reservation Modification', $result, 'Content-type: text/html' );

			$jx->redirect( get_permalink( get_page_by_path( 'booking/modify' ) ) /*. '?v='.time()*/ );
		} else {
			$result = va_get_template_part( 'email-info', array(), true );
			wp_mail( zeeba_get( 'step_4.form.email' ), 'Your Zeebavans Reservation', $result, 'Content-type: text/html' );

			$result = va_get_template_part( 'email-docs', array('docs' => $docs), true );
			wp_mail( $manager_email, 'Zeebavans Reservation', $result, 'Content-type: text/html' );
			
			$last = $pt - time();
			if ( $last <= 0 ) {
				$status = 'closed';
			} elseif ( $last <= 259200 ) { // 72 * 60 * 60
				$status = '72hours';
			} else {
				$status = 'open';
			}
			zeeba_book()->set( 'status', $status );
			zeeba_book()->modify( true );

			$jx->redirect( get_permalink( get_page_by_path( 'booking/step-5' ) ) /*. '?v='.time()*/ );
		}
	}

	public function file_upload() {
		$attach_id = media_handle_upload( 'docs', 0 );
		$ret       = [ 'attach_id' => $attach_id ];
		if ( is_wp_error( $attach_id ) ) {
			$ret = [ 'error' => $attach_id->get_error_message() ];
		} else {
			$attaches = zeeba_get( 'step_4.allowed_attaches' );
			if ( is_null( $attaches ) ) {
				$attaches = [];
			}
			$attaches[] = $attach_id;
			zeeba_book()->set( 'step_4.allowed_attaches', $attaches );
		}
		echo json_encode( $ret );
		wp_die();
	}
	
	private function bundle_options($initial) {
		$options = [];
		$bundles = gf('bundles', get_page_by_path( 'booking/step-3' )->ID);
		foreach($initial as $option) {
			if(preg_match('/^BUNDLE\d+$/', $option)) {
				$bundle_id = intval(substr($option, 6));
				if(!empty($bundles[$bundle_id - 1])) {
					$options = array_merge($options, explode(' ',$bundles[$bundle_id - 1]['codes']));
				}
			} else {
				$options[] = $option;
			}
		}
		return $options;
	}

}