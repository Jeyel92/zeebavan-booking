<?php
	// function step1(){
	// 	$data = $_POST['data'];
	// 	return $data;
	// }
	// $timezone = 'America/Los_Angeles';
	function required( $needle, $haystack ) {
		return array_key_exists( $needle, $haystack ) && ! empty( $haystack[ $needle ] );
	}

	add_action('wp_ajax_nopriv_step1', 'step1');
	add_action('wp_ajax_step1', 'step1');


	function step1(){
		$url = 'https://weblink.tsdasp.net/requests/service.svc/';
		$trn_mn  = new trnManager($url, 'ZEB01', get_option('booking_api_username'), get_option('booking_api_password'), get_client_ip(), get_client_ip());
		if(isset($_POST['data']))
			$data = $_POST['data'];
		$error['ok'] = 'false';
		$errors = array();
		$timezone = 'America/Los_Angeles';
		// echo json_encode($data);
		// wp_die();
		if(!array_key_exists('pickup_date', $data) || empty($data['pickup_date'])) {
			$error['pickup_date'] = 'Pickup Date should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		$pt = new DateTime($data['pickup_date'], new DateTimeZone($timezone));
		$pt = $pt->getTimestamp();
		if(time() > $pt) {
			$error['pt'] = 'Pickup time can`t be before now';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}


		if(!array_key_exists('dropoff_date', $data) || empty($data['dropoff_date'])) {
			$error['dropoff_date'] = 'Dropoff Date should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}


		$dt = new DateTime($data['dropoff_date'], new DateTimeZone($timezone));
		$dt = $dt->getTimestamp();
		if($pt >= $dt) {
			$error['dt'] = 'Dropoff time can`t be before Pickup time';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}

		//if(!array_key_exists('van_type', $data) || get_post_type($data['van_type']) !== 'vehicle') {
		//	$error['van_type'] = 'Selected van not found';
		//	$error['ok'] = 'false';
		//	echo json_encode($error);
		//	wp_die();
		//}
		if(!array_key_exists('pickup_location', $data)) {
			$error['pickup_location'] = 'Pickup location should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		} else {
			$locs = get_posts(array(
				'post_type' => 'location',
				'post_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => 'sys_trn_id',
						'value' => $data['pickup_location'],
						'compare' => '='
					)
				)
			));

			if(count($locs) == 0) {
				$error['pickup_location'] = 'Pickup location not found';
				echo json_encode($error);
				wp_die();
			}
			$data['pickup_address'] = gf('coordinates', $locs[0])['address'];
			$error['ok'] = 'ok';
			// json_encode($error);
		}
		if($data['different_location'] == 'yes') {
			if ( $dt - $pt <= 2 * 24 * 60 * 60 ) {

				$error['different_location'] = 'One-way rental available from 3 days';
				$error['ok'] = 'false';
				echo json_encode($error);
				wp_die();
			}
			if(!array_key_exists('dropoff_location', $data)) {
				$error['dropoff_location'] = 'Dropoff location should be set';
				$error['ok'] = 'false';
				echo json_encode($error);
				wp_die();
			} else if($data['dropoff_location'] == $data['pickup_location']) {

				$error['dropoff_location'] = 'Dropoff Location should be different from Pickup Location';
				$error['ok'] = 'false';
				echo json_encode($error);
				wp_die();
			} else {
				$locs = get_posts(array(
					'post_type' => 'location',
					'post_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => 'sys_trn_id',
							'value' => $data['dropoff_location'],
							'compare' => '='
						)
					)
				));
				if(count($locs) == 0) {
					$error['dropoff_location'] = 'Dropoff location not found';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				$data['dropoff_address'] = gf('coordinates', $locs[0])['address'];
				$error['ok'] = 'ok';
				// json_encode($error);
			}
		}
		else {
			$data['dropoff_location'] = $data['pickup_location'];
			$data['dropoff_location_text'] = $data['pickup_location_text'];
			$data['dropoff_address'] = $data['pickup_address'];
			// // wp_die();
			$error['ok'] = 'ok';
			// $error['data'] = $data;
			// json_encode($error);
			// wp_die();
		}
		zeeba_book()->set( 'step_1', $data );
		zeeba_book()->complete( 1, true );


		$selected_van = zeeba_get( 'step_1.van_type' );
		$pl           = zeeba_get( 'step_1.pickup_location' );
		$dl           = zeeba_get( 'step_1.dropoff_location' );
		$pt = new DateTime(zeeba_get( 'step_1.pickup_date' ), new DateTimeZone($timezone));
		$pt = $pt->getTimestamp();
		zeeba_book()->set( 'step_1.pt', $pt );

		$dt = new DateTime(zeeba_get( 'step_1.dropoff_date' ), new DateTimeZone($timezone));
		$dt = $dt->getTimestamp();
		zeeba_book()->set( 'step_1.dt', $dt );

		$diff = $dt - $pt;
		// 24 * 60 * 60 = 86400 – seconds in one day
		$period =  ceil( ( $diff * 1.0 ) / ( 24 * 60 * 60 ) );
		zeeba_book()->set( 'step_1.rental_period', $period );
		$dc           = zeeba_get( 'step_1.discount_code' );

		$rates = $trn_mn->get_rates( $pl, $dl, $pt, $dt, $dc );
		zeeba_book()->trn->lastRequest();
		zeeba_book()->trn->lastResponse();
		zeeba_book()->set( 'step_2.rates', $rates );

		$result = '';
		if ( is_array( $rates ) ) {
			$rates_render = [];
			$vehicles     = get_posts( [
				'post_type'     => 'vehicle',
				'posts_per_page' => -1
			] );
			$used  = [];
			foreach ( $rates as $key => $rate ) {
				$class_code = $rate['class_code'];
				$vehicle    = null;
				foreach ( $vehicles as &$v ) {
					$classes_raw = strtolower( gf( 'sys_class_code', $v ) );
					$rate['vehicle_title'] = $v->post_title;
					$rate['side_image'] = gf( 'side_image', $v )['sizes']['medium'];
					$rate['side_image_alt'] = gf( 'side_image', $v )['alt'];
					$rate['doors'] = gf('doors', $v );
					$rate['mpg_min'] = gf('mpg_min', $v );
					$rate['mpg_max'] = gf('mpg_max', $v );
					$rate['bags'] = gf('bags', $v );
					if(gf('air_conditioning', $v )){
						$rate['air_conditioning'] = "<span class='mt-2'><i data-feather='check'></i></span>";
					}
					else{
						$rate['air_conditioning'] = "";
					}
					$rate['car'] = gf('car', $v );

					$rate['rate_mileage'] = '';
					if($rate['total']['days'] == 1){
						$rate_mileage = gf( 'mileage_lt', $v );
					}else{
						$rate_mileage = gf( 'mileage_gt', $v );
					}
					if ( !$rate_mileage['unlimited'] ){
						$rate['rate_mileage'] .= $rate_mileage['limit'];
						$rate['rate_mileage'] .= " <div class='value'><span> ";
						$rate['rate_mileage'] .= $rate_mileage['unit'];
						$rate['rate_mileage'] .= "</span>";

                        if($rate_mileage['with_asterisk']){
                        	$rate['rate_mileage'] .= "<sup>*</sup>";
                        }
                        $rate['rate_mileage'] .= "</div>";

					}else{
						$rate['rate_mileage'] = "Unlimited Mileage";
					}
					if ( !$rate_mileage['unlimited'] && $rate_mileage['with_asterisk'] ){
						$rate['asterisk_text'] = $rate_mileage['asterisk_text'];
					}else{
						$rate['asterisk_text'] = "";
					}

					$classes = json_decode( $classes_raw, true );
					if ( in_array( strtolower( $class_code ), $classes ) ) {
						$vehicle = $v;
						$used[]  = $classes_raw;
						// $vehicle_title[]  = $v->post_title;
						// $used[]  = $classes_raw;
						break;
					}
				}
				if ( is_null( $vehicle ) ) {
					continue;
				}
				$rate["daily_rate"] = number_format(round_up($rate["total"]["rate_charge"] / $rate["total"]["days"],2),2);
				global $post;
				$post           = $vehicle;
				$order = gf( 'display_order' ) + ( $vehicle->ID == $selected_van ? 0 : 1000 );
				if($order < 1000){
					$rate['vehicle-active'] = "vehicle-active";
					$k = 0;
				}
				else{
					$rate['vehicle-active'] = "";
					$k = $key + 1;
				}
				$rates_render[$k] = [
					'order' => $order,
					'html'  => [
						'rate'     => $rate,
						'selected' => $vehicle->ID == $selected_van
					],
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
							$rate['vehicle_title'] = $v->post_title;
							$rate['side_image'] = gf( 'side_image', $v )['sizes']['medium'];
							$rate['side_image_alt'] = gf( 'side_image', $v )['alt'];
							$rate['doors'] = gf('doors', $v );
							$rate['mpg_min'] = gf('mpg_min', $v );
							$rate['mpg_max'] = gf('mpg_max', $v );
							$rate['bags'] = gf('bags', $v );
							if(gf('air_conditioning', $v )){
								$rate['air_conditioning'] = "<span class='mt-2'><i data-feather='check'></i></span>";
							}
							else{
								$rate['air_conditioning'] = "";
							}
							$rate['car'] = gf('car', $v );

							$rate['rate_mileage'] = '';
							if($rate['total']['days'] == 1){
								$rate_mileage = gf( 'mileage_lt', $v );
							}else{
								$rate_mileage = gf( 'mileage_gt', $v );
							}
							if ( !$rate_mileage['unlimited'] ){
								$rate['rate_mileage'] .= $rate_mileage['limit'];
								$rate['rate_mileage'] .= " <div class='value'><span> ";
								$rate['rate_mileage'] .= $rate_mileage['unit'];
								$rate['rate_mileage'] .= "</span>";

		                        if($rate_mileage['with_asterisk']){
		                        	$rate['rate_mileage'] .= "<sup>*</sup>";
		                        }
		                        $rate['rate_mileage'] .= "</div>";

							}else{
								$rate['rate_mileage'] = "Unlimited Mileage";
							}
							if ( !$rate_mileage['unlimited'] && $rate_mileage['with_asterisk'] ){
								$rate['asterisk_text'] = $rate_mileage['asterisk_text'];
							}else{
								$rate['asterisk_text'] = "";
							}

							$order = gf( 'display_order' ) + 2000;
							if($order < 1000){
								$rate['vehicle-active'] = "vehicle-active";
							}
							else{
								$rate['vehicle-active'] = "";
							}
							$rates_render[] = [
								'order' => $order,
								'html'  => [
									'rate'     => $rate,
									'selected' => false
								],
							];

							wp_reset_query();
						}
					}
			}
			zeeba_book()->set( 'step_2.vehicles', $rates_render );
			// uasort( $rates_render, function ( $a, $b ) {
			// 	return $a['order'] - $b['order'];
			// } );
			// $result = array_reduce( $rates_render, function ( $carry, $el ) {
			// 	return $carry . $el['html'];
			// } );
		}
		if ( ! is_array( $rate['total'] ) ) {
			$error['rate']['total'] = false;
			// $result = [
			// 	'message' => $rates
			// ];
		}





		// $_SESSION['booking']['step_1'] = array_merge($data, array('complete'=>true));
		// $_SESSION['booking']['step_1_load'] = array_merge($result, array('complete'=>true));
		// $_SESSION['booking']['step_1_result'] = $rates_render;
		// $_SESSION['booking']['step_1_result'] = [$pl, $dl, $pt, $dt];
		// $_SESSION['booking']['step_1_result'] = $rates;
		// $error['rates'] = $rates;
		$error['ok'] = 'ok';
		$error['resp'] = $rates;
		$error['rates_render'] = $rates_render;
		$error['result'] = $vehicle;
		$error['vehicles'] = zeeba_book()->get( 'step_2.vehicles' );
		$error['rental_period'] = zeeba_book()->get( 'step_1.rental_period' );
		$error['dir'] = ZEEBAVAN_ASSETS;
		$error['location'] = zeeba_get( 'step_1.pickup_location_text' );
		$error['return_location'] = zeeba_get( 'step_1.dropoff_location_text' );
		$error['pick_up_date'] = zeeba_get( 'step_1.pickup_date' );
		$error['drop_off_date'] = zeeba_get( 'step_1.dropoff_date' );
		$error['discount_code'] = zeeba_get( 'step_1.discount_code' );
		// $error['vehicle'] = $result;
		// $error['classes_raw'] = $classes_raw;
		// $error['classes'] = $classes[1];
		echo json_encode($error);
		wp_die();

	}


	/************** STEP 2 ****************/
	add_action('wp_ajax_nopriv_step_2_load', 'step_2_load');
	add_action('wp_ajax_step_2_load', 'step_2_load');
	function step_2_load() {
		if ( ! zeeba_book()->complete( 1 ) ) {
			$error['ok'] = false;
			echo json_encode($error);
			wp_die();
		}

		$selected_van = zeeba_get( 'step_1.van_type' );
		$pl           = zeeba_get( 'step_1.pickup_location' );
		$dl           = zeeba_get( 'step_1.dropoff_location' );
		$pt    = zeeba_get( 'step_1.pt' );
		$dt    = zeeba_get( 'step_1.dt' );
		$dc    = zeeba_get( 'step_1.discount_code' );

		$diff = $dt - $pt;
		// 24 * 60 * 60 = 86400 – seconds in one day
		$period =  ceil( ( $diff * 1.0 ) / ( 24 * 60 * 60 ) );
		zeeba_book()->set( 'step_1.rental_period', $period );

		$rates = zeeba_book()->trn->get_rates( $pl, $dl, $pt, $dt, $dc );
		zeeba_book()->trn->lastRequest();
		zeeba_book()->trn->lastResponse();
		zeeba_book()->set( 'step_2.rates', $rates );

		$result = '';
		if ( is_array( $rates ) ) {
			$rates_render = [];
			$vehicles     = get_posts( [
				'post_type'     => 'vehicle',
				'posts_per_page' => -1
			] );
			$used  = [];
			foreach ( $rates as $key => $rate ) {
				$class_code = $rate['class_code'];
				$vehicle    = null;
				foreach ( $vehicles as &$v ) {
					$classes_raw = strtolower( gf( 'sys_class_code', $v ) );
					$rate['vehicle_title'] = $v->post_title;
					$rate['side_image'] = gf( 'side_image', $v )['sizes']['medium'];
					$rate['side_image_alt'] = gf( 'side_image', $v )['alt'];
					$rate['doors'] = gf('doors', $v );
					$rate['mpg_min'] = gf('mpg_min', $v );
					$rate['mpg_max'] = gf('mpg_max', $v );
					$rate['bags'] = gf('bags', $v );
					if(gf('air_conditioning', $v )){
						$rate['air_conditioning'] = "<span class='mt-2'><i data-feather='check'></i></span>";
					}
					else{
						$rate['air_conditioning'] = "";
					}
					$rate['car'] = gf('car', $v );

					$rate['rate_mileage'] = '';
					if($rate['total']['days'] == 1){
						$rate_mileage = gf( 'mileage_lt', $v );
					}else{
						$rate_mileage = gf( 'mileage_gt', $v );
					}
					if ( !$rate_mileage['unlimited'] ){
						$rate['rate_mileage'] .= $rate_mileage['limit'];
						$rate['rate_mileage'] .= " <div class='value'><span> ";
						$rate['rate_mileage'] .= $rate_mileage['unit'];
						$rate['rate_mileage'] .= "</span>";

                        if($rate_mileage['with_asterisk']){
                        	$rate['rate_mileage'] .= "<sup>*</sup>";
                        }
                        $rate['rate_mileage'] .= "</div>";

					}else{
						$rate['rate_mileage'] = "Unlimited Mileage";
					}
					if ( !$rate_mileage['unlimited'] && $rate_mileage['with_asterisk'] ){
						$rate['asterisk_text'] = $rate_mileage['asterisk_text'];
					}else{
						$rate['asterisk_text'] = "";
					}

					$classes = json_decode( $classes_raw, true );
					if ( in_array( strtolower( $class_code ), $classes ) ) {
						$vehicle = $v;
						$used[]  = $classes_raw;
						// $vehicle_title[]  = $v->post_title;
						// $used[]  = $classes_raw;
						break;
					}
				}
				if ( is_null( $vehicle ) ) {
					continue;
				}

				$rate["daily_rate"] = number_format(round_up($rate["total"]["rate_charge"] / $rate["total"]["days"],2),2);
				global $post;
				$post           = $vehicle;
				$order = gf( 'display_order' ) + ( $vehicle->ID == $selected_van ? 0 : 1000 );
				if($order < 1000){
					$rate['vehicle-active'] = "vehicle-active";
					$k = 0;
				}
				else{
					$rate['vehicle-active'] = "";
					$k = $key + 1;
				}
				$rates_render[$k] = [
					'order' => $order,
					'html'  => [
						'rate'     => $rate,
						'selected' => $vehicle->ID == $selected_van
					],
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
							$rate['vehicle_title'] = $v->post_title;
							$rate['side_image'] = gf( 'side_image', $v )['sizes']['medium'];
							$rate['side_image_alt'] = gf( 'side_image', $v )['alt'];
							$rate['doors'] = gf('doors', $v );
							$rate['mpg_min'] = gf('mpg_min', $v );
							$rate['mpg_max'] = gf('mpg_max', $v );
							$rate['bags'] = gf('bags', $v );
							if(gf('air_conditioning', $v )){
								$rate['air_conditioning'] = "<span class='mt-2'><i data-feather='check'></i></span>";
							}
							else{
								$rate['air_conditioning'] = "";
							}
							$rate['car'] = gf('car', $v );

							$rate['rate_mileage'] = '';
							if($rate['total']['days'] == 1){
								$rate_mileage = gf( 'mileage_lt', $v );
							}else{
								$rate_mileage = gf( 'mileage_gt', $v );
							}
							if ( !$rate_mileage['unlimited'] ){
								$rate['rate_mileage'] .= $rate_mileage['limit'];
								$rate['rate_mileage'] .= " <div class='value'><span> ";
								$rate['rate_mileage'] .= $rate_mileage['unit'];
								$rate['rate_mileage'] .= "</span>";

		                        if($rate_mileage['with_asterisk']){
		                        	$rate['rate_mileage'] .= "<sup>*</sup>";
		                        }
		                        $rate['rate_mileage'] .= "</div>";

							}else{
								$rate['rate_mileage'] = "Unlimited Mileage";
							}
							if ( !$rate_mileage['unlimited'] && $rate_mileage['with_asterisk'] ){
								$rate['asterisk_text'] = $rate_mileage['asterisk_text'];
							}else{
								$rate['asterisk_text'] = "";
							}

							$order = gf( 'display_order' ) + 2000;
							if($order < 1000){
								$rate['vehicle-active'] = "vehicle-active";
							}
							else{
								$rate['vehicle-active'] = "";
							}
							$rates_render[] = [
								'order' => $order,
								'html'  => [
									'rate'     => $rate,
									'selected' => false
								],
							];

							wp_reset_query();
						}
					}
			}
			zeeba_book()->set( 'step_2.vehicles', $rates_render );
		}
		if ( ! is_array( $rate['total'] ) ) {
			$error['rate']['total'] = false;
		}
		$error['ok'] = 'ok';
		$error['rates_render'] = $rates_render;
		$error['result'] = $vehicle;
		$error['vehicles'] = zeeba_book()->get( 'step_2.vehicles' );
		$error['rental_period'] = zeeba_book()->get( 'step_1.rental_period' );
		$error['dir'] = ZEEBAVAN_ASSETS;
		$error['location'] = zeeba_get( 'step_1.pickup_location_text' );
		$error['return_location'] = zeeba_get( 'step_1.dropoff_location_text' );
		$error['pick_up_date'] = zeeba_get( 'step_1.pickup_date' );
		$error['drop_off_date'] = zeeba_get( 'step_1.dropoff_date' );
		echo json_encode($error);
		wp_die();
	}


	add_action('wp_ajax_nopriv_step2', 'step2');
	add_action('wp_ajax_step2', 'step2');
	function step2() {

		if(isset($_POST['data']))
			$data =  $_POST['data'];
		$result = '';
		$hidden_extras = [];
		$bundles = [];
		$rate = false;
		// foreach ( zeeba_get( 'step_2.rates' ) as $rt ) {
		// 	if ( $rt['id'] == $data['rate'] ) {
		// 		$rate = $rt;
		// 		break;
		// 	}
		// }
		foreach ( zeeba_get( 'step_2.rates' ) as $key=>$rt ) {
			if ( $rt['id'] == $data['rate'] ) {
				$rate = $rt;
				// zeeba_book()->set( 'step_2_select_vehicle_title', $rt['vehicle_title'] );
				// zeeba_book()->set( 'step_2.select_vehicle', $rate );
				// zeeba_book()->set( 'step_2.select_vehicle_title', zeeba_get( 'step_2.vehicles' )[$dkey]['vehicle_title'] );
				// zeeba_book()->set( 'step_2.select_vehicle_img', $rate['side_image'] );
				break;
			}
		}
		foreach ( zeeba_get( 'step_2.vehicles' ) as $rts ) {
			if ( $rts['html']['rate']['id'] == $data['rate'] ) {
				$rate_select = $rts['html']['rate'];
				zeeba_book()->set( 'step_2.select_vehicle', $rate_select );
				zeeba_book()->set( 'step_2.select_vehicle_title', $rate_select['vehicle_title'] );
				zeeba_book()->set( 'step_2.select_vehicle_img', $rate_select['side_image'] );
				break;
			}
		}
		if ( ! $rate ) {
			$response['error'] = 'Rate not found';

			return;
		}

		zeeba_book()->set( 'step_2.rate', $rate['id'] );
		zeeba_book()->set( 'step_2.rate_data', $rate );
		zeeba_book()->complete( 2, true );
		$timezone = 'America/Los_Angeles';
		$pl    = zeeba_get( 'step_1.pickup_location' );
		$pts = new DateTime(zeeba_get( 'step_1.pickup_date' ), new DateTimeZone($timezone));
		$pt = $pts->getTimestamp();

		$rate  = zeeba_get( 'step_2.rate' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );

		$extras = zeeba_book()->trn->get_extra( $pl, $pt, $rate, $class );
		zeeba_book()->set( 'step_3.available', $extras );
		$rental_period = zeeba_book()->period();
		if($rental_period == 1){
			$response['rental_period_text'] = get_option("options_empty");
		}
		if ( is_array( $extras ) ) {
			// $bundles = [];
			$i = 0;
			if(get_option('bundle_name')){
				foreach(get_option('bundle_name') as $key => $bundle){
					$i++;
					$bundles[] = [
						'id' => 'BUNDLE'.$i,
						'branch' => 'BUNDLE'.$i,
						'calc' => 'DAILY',
						'desc' => $bundle,
						'note' => get_option('bundle_tooltip')[$key],
						'rate' => get_option('bundle_price')[$key],
						'amount' => get_option('bundle_price')[$key] * $rental_period,
					];
				}
			}

			$non_free = [];
			foreach($extras['non-free'] as $nonfree){
				if($nonfree['calc'] == 'DAILY'){
					$nonfree['desc'] = $nonfree['desc'] . " / $" . floatval($nonfree['amount']) . " day";
					$nonfree['amount'] = floatval($nonfree['amount']) * $rental_period;
				}
				else{
					$nonfree['amount'] = floatval($nonfree['amount']);
				}

				$non_free[] = $nonfree;
			}

			$extras['non-free'] = $non_free;
			// echo json_encode($bundles);
			// wp_die();
			$extras['non-free'] = array_merge($bundles, $extras['non-free']);



			$hidden_extras = explode(' ', get_option('hidden_options'));
			$extras['non-free'] = array_filter($extras['non-free'], function($v) use ($hidden_extras) {
				return !in_array($v['id'], $hidden_extras);
			});
			$extras['free'] = array_filter($extras['free'], function($v) use ($hidden_extras) {
				return !in_array($v['id'], $hidden_extras);
			});

			$response['rental_period'] = zeeba_book()->period();
			if(zeeba_book()->period() == 1) {
				$extras['non-free'] = [];
			}

			$renames = [];
			if(get_option('renaming_options_code')){
				foreach(get_option('renaming_options_code') as $key => $value){
					$renames[strtolower($value)] = get_option('renaming_options_text')[$key];
				}
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
			// $non_free = !count($extras['non-free']) ? false : va_get_template_part( 'panel-booking-extras', [
			// 	'options' => $extras['non-free']
			// ], true );

			// $free = va_get_template_part( 'panel-booking-extras', [
			// 	'options' => $extras['free']
			// ], true );

			// $jx->variable( 'non_free', $non_free );
			// $jx->variable( 'free', $free );
		}
		// else {
		// 	$result = va_get_template_part( 'panel-booking-error', [
		// 		'message' => $extras
		// 	], true );
		// }

		// $jx->variable( 'hidden', $hidden_extras );
		// $jx->variable( 'extras', $extras );
		// $jx->variable( 'result', $result );


		$response['dir'] = ZEEBAVAN_ASSETS;
		$response['vehicle_title'] = zeeba_get('step_2.select_vehicle_title');
		$response['vehicle_img'] = zeeba_get('step_2.select_vehicle_img');
		$response['extras'] = $extras ;
		$response['result'] = $result ;
		$response['bundles'] = $bundles;
		$response['rate'] = zeeba_get( 'step_2.select_vehicle' );
		$response['path'] = ZEEBAVAN_DIR;
		$response['pick_up_date'] = zeeba_get( 'step_1.pickup_date' );
		$response['drop_off_date'] = zeeba_get( 'step_1.dropoff_date' );
		$response['discount_code'] = zeeba_get( 'step_1.discount_code' );
		echo json_encode($response);
		wp_die();

	}


	/************** STEP 3 ****************/
	add_action('wp_ajax_nopriv_step3_send_email', 'step3_send_email');
	add_action('wp_ajax_step3_send_email', 'step3_send_email');

	function step3_send_email() {
		/* @var wpjxmResponse $jx  */
		if(isset($_POST['data']))
			$data = $_POST['data'];

		if(!array_key_exists('email', $data) || empty($data['email'])) {
			$response['msg'] = 'Email is required';
			echo json_encode($response);
			wp_die();
		} else if(is_email($data['email'])) {

			$pl    = zeeba_get( 'step_1.pickup_location' );
			$dl    = zeeba_get( 'step_1.dropoff_location' );
			$pt    = zeeba_get( 'step_1.pt' );
			$dt    = zeeba_get( 'step_1.dt' );
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
			$options = bundle_options($data['options']);
			$bill     = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );
			$vehicle  = count( $vehicles ) ? $vehicles[0] : [];

			// $trn_manager = get_trn_manager();

			// $pl = zeeba_get( 'step_1.pickup_location' );
			// $dl = zeeba_get( 'step_1.dropoff_location' );
			// $pl = zeeba_get( 'step_1.pickup_location' );
			// $pt    = zeeba_get( 'step_1.pt' );
			// $dt    = zeeba_get( 'step_1.dt' );
			// $rate = zeeba_get( 'step_1.rate_data' );
			// $class = $rate['class_code'];
			// $dc = zeeba_get( 'step_1.discount_code' );

			// $bill = $trn_manager->get_bill($pl, $dl, $pt, $dt, $rate['id'], $class, $data['options'], $dc);
			// $step2_data = zeeba_get( 'step_2.rate_data' );
			// $vehicles = get_posts(array(
			// 	'post_type' => 'vehicle',
			// 	'post_per_page' => -1,
			// 	'meta_query' => array(
			// 		array(
			// 			'key' => 'sys_class_code',
			// 			'value' => zeeba_get( 'step_2.rate_data.class_code' ),
			// 			'compare' => '='
			// 		)
			// 	)
			// ));
			// $vehicle = count($vehicles) ? $vehicles[0] : array();

			ob_start();
			include(ZEEBAVAN_DIR . "templates/email-copy.php");
			$message = ob_get_contents();
			ob_end_clean();
			wp_mail( $data['email'], 'Your Zeebavans Price Quote', $message, 'Content-type: text/html' );
			if(!wp_mail) {
			 	$response['error'] = true;
			 	$response['msg'] = 'Message could not be sent.';
			//     // echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
			 }else{
				$response['error'] = false;
				$response['bill'] = $bill;
				$response['option'] = $options;
				$response['msg'] = 'Mail is sent';
			 }
			echo json_encode($response);
			wp_die();

		} else {
			$response['error'] = true;
			$response['msg'] = 'Email is invalid';
			echo json_encode($response);
			wp_die();
		}
	}


	add_action('wp_ajax_nopriv_step3_more', 'step3_more');
	add_action('wp_ajax_step3_more', 'step3_more');
	function step3_more() {
		$data = $_POST['data'];
		$form = $data['form'];

		if ( ! required( 'first_name', $form ) ) {
			$response['msg'] = 'First Name is required';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else if ( ! required( 'last_name', $form ) ) {
			$response['msg'] = 'Last name is required';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else if ( ! required( 'phone', $form ) ) {
			$response['msg'] = 'Phone is required';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else if ( ! required( 'email', $form ) ) {
			$response['msg'] = 'Email is required';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else if ( ! is_email( $form['email'] ) ) {
			$response['msg'] = 'Email is invalid';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else if ( ! required( 'message', $form ) ) {
			$response['msg'] = 'Message is required';
			$response['error'] = true;
			echo json_encode($response);
			wp_die();
		} else {
			$pl    = zeeba_get( 'step_1.pickup_location' );
			$dl    = zeeba_get( 'step_1.dropoff_location' );
			$pt    = zeeba_get( 'step_1.pt' );
			$dt    = zeeba_get( 'step_1.dt' );
			$dc    = zeeba_get( 'step_1.discount_code' );
			$rate  = zeeba_get( 'step_2.rate_data' );
			$class = zeeba_get( 'step_2.rate_data.class_code' );

			$options = bundle_options($data['options']);

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

			ob_start();
			include(ZEEBAVAN_DIR . "templates/email-more.php");
			$message = ob_get_contents();
			ob_end_clean();


			$manager_email = gf( 'reservation_email', 'options' );
			if ( empty( $manager_email ) ) {
				$manager_email = 'valery.alexeev@me.com';
			}

			wp_mail( $manager_email, 'Website information request', $message, 'Content-type: text/html' );

			if(!wp_mail) {
			 	$response['error'] = true;
			 	$response['msg'] = 'Message could not be sent.';
			 }else{
				$response['error'] = false;
				$response['msg'] = 'Mail is sent';
			 }
			echo json_encode($response);
			wp_die();
		}
	}

	add_action( 'phpmailer_init', 'configure_smtp' );
	function configure_smtp( \PHPMailer\PHPMailer\PHPMailer $phpmailer ){
	  $phpmailer->isSMTP(); //switch to smtp

	     $phpmailer->Host       = SMTP_HOST;
	     $phpmailer->SMTPAuth   = SMTP_AUTH;
	     $phpmailer->Port       = SMTP_PORT;
	     $phpmailer->SMTPSecure = SMTP_SECURE;
	     $phpmailer->Username   = SMTP_USERNAME;
	     $phpmailer->Password   = SMTP_PASSWORD;
	     $phpmailer->From       = SMTP_FROM;
	     $phpmailer->FromName   = SMTP_FROMNAME;
	}


	add_action('wp_ajax_nopriv_step3_submit', 'step3_submit');
	add_action('wp_ajax_step3_submit', 'step3_submit');

	function step3_submit() {
		if(isset($_POST['data']))
			$data = $_POST['data'];
		$timezone = 'America/Los_Angeles';
		$total = $data['total'];
		$charity = $data['charity'];

		zeeba_book()->set( 'step_3.total_bill', $total );
		zeeba_book()->set( 'step_3.charity_bill', $charity );

		$pl    = zeeba_get( 'step_1.pickup_location' );
		$dl    = zeeba_get( 'step_1.dropoff_location' );
		$pts = new DateTime(zeeba_get( 'step_1.pickup_date' ), new DateTimeZone($timezone));
		$pt = $pts->getTimestamp();
		$dts = new DateTime(zeeba_get( 'step_1.dropoff_date' ), new DateTimeZone($timezone));
		$dt = $dts->getTimestamp();
		$dc    = zeeba_get( 'step_1.discount_code' );
		$rate  = zeeba_get( 'step_2.rate_data' );
		$class = zeeba_get( 'step_2.rate_data.class_code' );
		if($charity != 0){
			$new_rate = $rate;
			$rate['total']['charge']=$total;
			zeeba_book()->set( 'step_2.rate_data', $rate );
		}
		// $bundles = get_option('bundle_code');
		// echo json_encode($bundles);
		// wp_die();
		// // $options = $this->bundle_options($data['options']);
		// $options = $this->bundle_options($data['data']);
		// echo json_encode($options);
		// wp_die();
		$options = [];
		$bundles = get_option('bundle_code');//gf('bundles', get_page_by_path( 'booking/step-3' )->ID);
		foreach($data['data'] as $option) {
			if(preg_match('/^BUNDLE\d+$/', $option)) {
				$bundle_id = intval(substr($option, 6));
				if(!empty($bundles[$bundle_id - 1])) {
					$options = array_merge($options, explode(' ',$bundles[$bundle_id - 1]));
				}
			} else {
				$options[] = $option;
			}
		}
		$bill = zeeba_book()->trn->get_bill( $pl, $dl, $pt, $dt, $rate['id'], $class, $options, $dc );
		$bill['total']=$total;

		zeeba_book()->set( 'step_3.bill', $bill );
		zeeba_book()->set( 'step_3.options', $options );
		zeeba_book()->complete( 3, true );

		$response['dir'] = ZEEBAVAN_ASSETS;
		$response['bill'] = $bill;
		$response['options'] = $options ;
		$response['rate'] = zeeba_get( 'step_2.rate_data' );
		$response['vehicle_title'] = zeeba_get('step_2.select_vehicle_title');
		$response['vehicle_img'] = zeeba_get('step_2.select_vehicle_img');
		$response['rental_period'] = zeeba_get( 'step_1.rental_period' );
		// if(!array_key_exists('rate', $bill)) {
			$response['total'] = $total;
		// }else{
		// 	$response['total'] = $bill['total'];
		// }
		$response['charity'] = zeeba_get('step_3.charity_bill');
		echo json_encode($response);
		wp_die();

	}

	function bundle_options($initial) {
		$options = [];
		$bundles = get_option('bundle_code');//gf('bundles', get_page_by_path( 'booking/step-3' )->ID);
		foreach($initial as $option) {
			if(preg_match('/^BUNDLE\d+$/', $option)) {
				$bundle_id = intval(substr($option, 6));
				if(!empty($bundles[$bundle_id - 1])) {
					$options = array_merge($options, explode(' ',$bundles[$bundle_id - 1]));
				}
			} else {
				$options[] = $option;
			}
		}
		return $options;

		// if(get_option('bundle_name')){
		// 	foreach(get_option('bundle_name') as $key => $bundle){
		// 		$i++;
		// 		$bundles[] = [
		// 			'id' => 'BUNDLE'.$i,
		// 			'branch' => 'BUNDLE'.$i,
		// 			'calc' => 'DAILY',
		// 			'desc' => $bundle,
		// 			'note' => get_option('bundle_tooltip')[$key],
		// 			'rate' => get_option('bundle_price')[$key],
		// 			'amount' => get_option('bundle_price')[$key] * $rental_period,
		// 		];
		// 	}
		// }
	}


	/**
	 * step4_submit
	 */
	add_action('wp_ajax_nopriv_step4_submit', 'step4_submit');
	add_action('wp_ajax_step4_submit', 'step4_submit');

	function step4_submit() {

		if(isset($_POST['data']))
			$data = $_POST['data'];

		$form = $_POST['data']['form'];

		$pl         = zeeba_get( 'step_1.pickup_location' );
		$dl         = zeeba_get( 'step_1.dropoff_location' );
		$pt         = zeeba_get( 'step_1.pt' );
		$dt         = zeeba_get( 'step_1.dt' );
		$dc         = zeeba_get( 'step_1.discount_code' );
		$rate       = zeeba_get( 'step_2.rate_data' );
		$class      = zeeba_get( 'step_2.rate_data.class_code' );
		$extraCodes = zeeba_get( 'step_3.options' );
		$code       = false;

		$prepaid = gf( 'reservation_prepaid', 'options' );
		$deposit = 0;
		if ( $prepaid ) {
			$prepaid = gf( 'reservation_prepaid_amount', 'options' );
			$deposit = gf( 'reservation_deposit_amount', 'options' );
		}
		//$error['ok'] = 'true';
		//echo json_encode($error);
		//wp_die();
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
		if(!array_key_exists('first_name', $form) || empty($form['first_name'])) {
			$error['error'] = 'First Name should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('last_name', $form) || empty($form['last_name'])) {
			$error['error'] = 'Last Name should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('phone_number', $form) || empty($form['phone_number'])) {
			$error['error'] = 'Phone Number should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('email', $form) || empty($form['email'])) {
			$error['error'] = 'Email should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('email_confirm', $form) || empty($form['email_confirm'])) {
			$error['error'] = 'Email Confirm should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if ( $form['email'] != $form['email_confirm'] ) {
			$error['error'] = 'Email Confirm should be equal to Email';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('country', $form) || empty($form['country'])) {
			$error['error'] = 'Country should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('address', $form) || empty($form['address'])) {
			$error['error'] = 'Address should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('zip', $form) || empty($form['zip'])) {
			$error['error'] = 'ZIP should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('city', $form) || empty($form['city'])) {
			$error['error'] = 'City should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
		if(!array_key_exists('state', $form) || empty($form['state'])) {
			$error['error'] = 'State should be set';
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
		}
        //$error['ok'] = 'true';
		//echo json_encode($error);
		//wp_die();

		# TEST
		if (isset($data['form']['stripe_token'])) {
			// Don't do anything...
		}
		else { // Original cases
			//if ( ! zeeba_book()->modify() ) {
				if(!array_key_exists('card_number', $form) || empty($form['card_number'])) {
					$error['error'] = 'Card number should be set';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				if ( ! va_validate_card( preg_replace( '/[^\d]/', '', $form['card_number'] ) ) ) {
					$error['error'] = 'Card number is required';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				if(!array_key_exists('card_name', $form) || empty($form['card_name'])) {
					$error['error'] = 'Name on card should be set';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				if(!array_key_exists('card_month', $form) || empty($form['card_month'])) {
					$error['error'] = 'Card Month should be set';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				if(!array_key_exists('card_year', $form) || empty($form['card_year'])) {
					$error['error'] = 'Card Year should be set';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				if(!array_key_exists('card_cvv', $form) || empty($form['card_cvv'])) {
					$error['error'] = 'Card CVV should be set';
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}

			//}
		}
		$docs    = [];
		$allowed = zeeba_get( 'step_4.allowed_attaches' );
		if (array_key_exists('attached', $form)) {
			foreach ( json_decode( $data['form']['attached'] ) as $attach ) {
				if ( in_array( $attach, $allowed ) ) {
					$docs[] = wp_get_attachment_url( $attach );
				}
			}
		}
		$data['form']['documents'] = implode( ' ', $docs );

		$pl         = zeeba_get( 'step_1.pickup_location' );
		$dl         = zeeba_get( 'step_1.dropoff_location' );
		$pt         = zeeba_get( 'step_1.pt' );
		$dt         = zeeba_get( 'step_1.dt' );
		$dc         = zeeba_get( 'step_1.discount_code' );
		$rate       = zeeba_get( 'step_2.rate_data' );
		$class      = zeeba_get( 'step_2.rate_data.class_code' );
		$extraCodes = zeeba_get( 'step_3.options' );
		$code       = false;

		$prepaid = gf( 'reservation_prepaid', 'options' );
		$deposit = 0;
		if ( $prepaid ) {
			$prepaid = gf( 'reservation_prepaid_amount', 'options' );
			$deposit = gf( 'reservation_deposit_amount', 'options' );
		}
		$bill = zeeba_get( 'step_3.bill' );
		$error['bill'] =$bill;
		 //echo json_encode($error);
		 //wp_die();

		// This is where the stripe charge happens
		if (STRIPE_ENABLED && $code === false && isset($form['stripe_token'])) { // $code == false for new transactions. Otherwise it's the reservation number.
			require_once(ZEEBAVAN_DIR . '_inc/stripe/init.php');


			if (STRIPE_LIVE) {
				\Stripe\Stripe::setApiKey(STRIPE_LIVE_SECRET_KEY);
			}
			else {
				\Stripe\Stripe::setApiKey(STRIPE_TEST_SECRET_KEY);
			}


		// 	# Let's try and make a charge
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
					'amount'   => $bill['total'] * 100,

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
					$error['error'] = $body['error']['message'];
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				else {
					$error['error'] = $body['error'];
					$error['ok'] = 'false';
					echo json_encode($error);
					wp_die();
				}
				return;
			}
		}
        //$error['ok'] = 'false';
        //echo json_encode($error);
		//wp_die();
		$res = zeeba_book()->trn->reserve( $pl, $dl, $pt, $dt, $rate['id'], $class, $data['form'], $dc, $extraCodes, $code, $prepaid, $deposit );
		if ( ! is_array( $res ) ) {
			$error['error'] = $res;
			$error['ok'] = 'false';
			echo json_encode($error);
			wp_die();
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
		charityDataInsert(['amount'=>zeeba_get('step_3.charity_bill'), 'reservation_id'=>$res['id']]);
		$error['ok'] = ['ok'];
		$error['step5_rd_content_name'] = get_option('step5_rd_content_name');
		$error['step5_rd_content_value'] = get_option('step5_rd_content_value');
		$error['step5_rd_content_highlight'] = get_option('step5_rd_content_highlight');
		$data['form']['id'] = $res['id'];
		$data['form']['pickup_location'] = zeeba_get( 'step_1.pickup_location_text' );
		$error['form-data'] = $data['form'];
		$error['form_id'] = $res['id'];
		// echo json_encode($error);
		// wp_die();
		$manager_email = gf( 'reservation_email', 'options' );
		if ( empty( $manager_email ) ) {
			$manager_email = 'valery.alexeev@me.com';
		}

		// if ( zeeba_book()->modify() ) {
		// 	zeeba_book()->set( 'status', 'modified' );

		// 	$result = va_get_template_part( 'email-modify', array(), true );
		// 	wp_mail( zeeba_get( 'step_4.form.email' ), 'Your Zeebavans Reservation Modification', $result, 'Content-type: text/html' );
		// 	wp_mail( $manager_email, 'Zeebavans Reservation Modification', $result, 'Content-type: text/html' );


		// 	$jx->redirect( get_permalink( get_page_by_path( 'booking/modify' ) ) /*. '?v='.time()*/ );
		// } else {
		// 	$result = va_get_template_part( 'email-info', array(), true );
		// 	wp_mail( zeeba_get( 'step_4.form.email' ), 'Your Zeebavans Reservation', $result, 'Content-type: text/html' );
			//$bill = zeeba_get( 'step_3.bill' );
			//$error['bill'] =$bill;
			ob_start();
			include(ZEEBAVAN_DIR . "templates/email-info.php");
			$message = ob_get_contents();
			ob_end_clean();

			wp_mail( $form['email'], 'Your Zeebavans Reservation', $message, 'Content-type: text/html' );

		// 	$result = va_get_template_part( 'email-docs', array('docs' => $docs), true );
		// 	wp_mail( $manager_email, 'Zeebavans Reservation', $result, 'Content-type: text/html' );
			ob_start();
			include(ZEEBAVAN_DIR . "templates/email-docs.php");
			$message = ob_get_contents();
			ob_end_clean();

			wp_mail( $manager_email, 'Zeebavans Reservation', $message, 'Content-type: text/html' );

			$last = $pt - time();
			if ( $last <= 0 ) {
				$status = 'closed';
			} elseif ( $last <= 259200 ) { // 72 * 60 * 60
				$status = '72hours';
			} else {
				$status = 'open';
			}
			zeeba_book()->set( 'status', $status );
			//zeeba_book()->modify( true );


		// }
		$v_id    = zeeba_get( 'step_2.rate_data.class_code' );
		// $vehicle = get_posts( [
		//   'post_type'      => 'vehicle',
		//   'posts_per_page' => 1,
		//   'meta_query'     => [
		//     'relation' => 'OR',
		//     [
		//       'key'     => 'sys_class_code',
		//       'value'   => $v_id,
		//       'compare' => 'LIKE',
		//     ]
		//   ],
		// ] )[0];
		$error['v_id'] = $v_id;
		$error['vehicle_title'] = zeeba_get('step_2.select_vehicle_title');
		$error['ok'] = 'true';
		echo json_encode($error);
		wp_die();
	}

	function charityDataInsert($data){
		global $wpdb;

	    $wpdb->insert("{$wpdb->base_prefix}rental_price_charity", [
	        // 'ID' => $data['reservation_id'],
	        'amount' => $data['amount'],
	        'reservation_id' => $data['reservation_id'],
	        'created_at' => gmdate('Y-m-d H:i:s')
	    ]);
	    return true;
	}

	add_action('wp_ajax_nopriv_file_upload', 'file_upload');
	add_action('wp_ajax_file_upload', 'file_upload');

	function file_upload() {
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
		$result['result'] = $ret;
		$result['attaches'] = zeeba_get( 'step_4.allowed_attaches');
		echo json_encode( $result );
		wp_die();
	}

	function round_up ( $value, $precision ) {
		$pow = pow ( 10, $precision );
		return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
	}