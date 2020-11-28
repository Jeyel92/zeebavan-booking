<?php

function get_trn_manager() {
	return new TRNManager('https://weblink.tsdasp.net/requests/service.svc/', 'ZEB01', get_option('booking_api_username'), get_option('booking_api_password'), get_client_ip(), get_client_ip());
}

function getTimeFormat() {
	return 'F d, Y H:i a';
}

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'M') {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}


/*
add_action('jx_find_location', function($jx) {
	///* @var wpjxmResponse $jx
	$search_keys = [
		'sys_trn_id',
		'sys_city',
		'sys_state',
		'sys_state_code',
		'sys_zip',
	];
	$term = $jx->getData()['term'];
	if(preg_match('/^\d{5}$/', $term) && $term >= 210 && $term <= 99950	) {
		$zip = (int) $term;
		$res = json_decode(file_get_contents('http://api.zippopotam.us/us/'.$zip));
		if(!empty($res) && isset($res->places) && count($res->places) > 0) {
			$lat1 = $res->places[0]->latitude;
			$lng1 = $res->places[0]->longitude;

			$locations = get_posts( [
				'post_type' => 'location',
				'posts_per_page' => -1,
			] );

			$min_dist = -1;
			$min_location = null;
			foreach($locations as $location) {
				$lat2 = gf('coordinates', $location)['lat'];
				$lng2 = gf('coordinates', $location)['lng'];
				$dist = distance($lat1, $lng1, $lat2, $lng2);
				if($min_dist == -1 || $dist < $min_dist) {
					$min_dist = $dist;
					$min_location = $location;
				}
			}
			if(!is_null($min_location)) {
				$state = (empty(gf('sys_trn_id', $min_location)) ? '' : ' ('.gf('sys_trn_id', $min_location).')').(empty(gf('sys_state', $min_location)) ? '' : ', '.gf('sys_state', $min_location));
				$result = [
					[
					'value' => get_the_title($min_location).$state,
					'data' => gf('sys_trn_id', $min_location),
					]
				];
				$jx->variable('locations', $result);
				return;
			}
		}
	}

	$args = array(
		'post_type' => 'location',
		'posts_per_page' => -1,
		'_meta_or_title' => $term,
	);
	if (!empty($term)) {
		$args['meta_query'] = array('relation'	=> 'OR');
		foreach($search_keys as $key) {
			$args['meta_query'][] = array(
				'key' => $key,
				'value' => $term,
				'compare' => 'LIKE'
			);
		}
		$locations = get_posts( $args );
	}
	foreach($locations as $location) {
		$state = (empty(gf('sys_trn_id', $location)) ? '' : ' ('.gf('sys_trn_id', $location).')').(empty(gf('sys_state', $location)) ? '' : ', '.gf('sys_state', $location));
		$result[] = array(
			'value' => get_the_title($location).$state,
			'data' => gf('sys_trn_id', $location),
		);
	}
	if($locations)
	$jx->variable('locations', $result);
});*/

add_action('jx_booking_step_1_submit', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	$errors = array();
	if(!array_key_exists('pickup_date', $data) || empty($data['pickup_date'])) {
		$jx->variable('error', 'Pickup Date should be set');
		return;
	}
	if(!array_key_exists('pickup_time', $data) || empty($data['pickup_time'])) {
		$jx->variable('error', 'Pickup Time should be set');
		return;
	}
	if(!array_key_exists('pickup_date', $errors) && !array_key_exists('pickup_time', $errors)) {
		$pt = DateTime::createFromFormat(getTimeFormat(), $data['pickup_date'].' '.$data['pickup_time'])->getTimestamp();
		if(time() > $pt) {
			$jx->variable('error', 'Pickup time can`t be before now');
			return;
		}
	}

	if(!array_key_exists('dropoff_date', $data) || empty($data['dropoff_date'])) {
		$jx->variable('error', 'Dropoff Date should be set');
		return;
	}
	if(!array_key_exists('dropoff_time', $data) || empty($data['dropoff_time'])) {
		$jx->variable('error', 'Dropoff Time should be set');
		return;
	}
	$dt = DateTime::createFromFormat(getTimeFormat(), $data['dropoff_date'].' '.$data['dropoff_time'])->getTimestamp();
	if($pt >= $dt) {
		$jx->variable('error', 'Dropoff time can`t be before Pickup time');
		return;
	}

	if(!array_key_exists('van_type', $data) || get_post_type($data['van_type']) !== 'vehicle') {
		$jx->variable('error', 'Selected van not found');
		return;
	}
	if(!array_key_exists('pickup_location', $data)) {
		$jx->variable('error', 'Pickup location should be set');
		return;
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
			$jx->variable('error', 'Pickup location not found');
			return;
		}
		$data['pickup_address'] = gf('coordinates', $locs[0])['address'];
	}
	if($data['different_location'] == 'yes') {
		if ( $dt - $pt <= 2 * 24 * 60 * 60 ) {
			$jx->variable( 'error', 'One-way rental available from 3 days' );

			return;
		}
		if(!array_key_exists('dropoff_location', $data)) {
			$jx->variable('error', 'Dropoff location should be set');
			return;
		} else if($data['dropoff_location'] == $data['pickup_location']) {
			$jx->variable('error', 'Dropoff Location should be different from Pickup Location');
			return;
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
				$jx->variable('error', 'Dropoff location not found');
				return;
			}
			$data['dropoff_address'] = gf('coordinates', $locs[0])['address'];
		}
	} else {
		$data['dropoff_location'] = $data['pickup_location'];
		$data['dropoff_location_text'] = $data['pickup_location_text'];
		$data['dropoff_address'] = $data['pickup_address'];
	}
	$_SESSION['booking']['step_1'] = array_merge($data, array('complete'=>true));

	$jx->redirect(get_permalink(get_page_by_path('booking/step-2')));
});

add_action('jx_booking_step_modify_submit', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	$errors = array();
	if(!array_key_exists('reservation', $data) || empty($data['reservation'])) {
		$jx->variable('error', 'Reservation Code should be set');
		return;
	}
	$info = get_trn_manager()->get_reservation($data['reservation']);
	if(!is_array($info)) {
		$jx->variable('error', $info);
		return;
	}
	if(array_key_exists('step_1', $info)) {
		$vehicles = get_posts(array(
			'post_type' => 'vehicle',
			'post_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'sys_class_code',
					'value' => $info['step_1']['van_type'],
					'compare' => '='
				)
			)
		));
		if(count($vehicles) == 1) {
			$info['step_1']['van_type'] = $vehicles[0]->ID;
		}
		$locations = get_posts(array(
			'post_type' => 'location',
			'post_per_page' => -1,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'sys_trn_id',
					'value' => $info['step_1']['pickup_location'],
					'compare' => '='
				),
				array(
					'key' => 'sys_trn_id',
					'value' => $info['step_1']['dropoff_location'],
					'compare' => '='
				)
			)
		));
		foreach($locations as $loc) {
			if(gf('sys_trn_id', $loc) == $info['step_1']['pickup_location']) {
				$info['step_1']['pickup_location_text'] = get_the_title($loc).' ('.gf('sys_trn_id', $loc).')';
			}
			if(gf('sys_trn_id', $loc) == $info['step_1']['dropoff_location']) {
				$info['step_1']['dropoff_location_text'] = get_the_title($loc).' ('.gf('sys_trn_id', $loc).')';
			}
		}
		wp_reset_query();
		$info['step_1']['complete'] = true;
	}
	$info['step_2']['complete'] = true;
	$info['step_2']['skip'] = true;
	$info['step_3']['complete'] = true;
	$info['step_4']['complete'] = true;
	$info['step_4']['modify'] = true;
	$info['modify'] = true;

	$_SESSION['booking'] = $info;

	$jx->redirect(get_permalink(get_page_by_path('booking/modify')));
});

add_action('jx_booking_step_2_load', function($jx) {
	/* @var wpjxmResponse $jx  */
	if($_SESSION['booking']['step_1']['complete']) {
		$selected_van = $_SESSION['booking']['step_1']['van_type'];
		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$dc = $_SESSION['booking']['step_1']['discount_code'];

		$trn_manager = get_trn_manager();

		$i = 0;
		$rates = $trn_manager->get_rates($pl, $dl, $pt, $dt, $dc);
		$_SESSION['booking']['step_2'] = array('rates'=>$rates);
		if(is_array($rates)) {
			$rates_render = [];
			$vehicles = get_posts(array(
				'post_type' 	=> 'vehicle',
				'post_per_page' => -1
			));
			$used = array();
			foreach($rates as $rate) {
				$class_code = $rate['class_code'];
				$vehicle = null;
				foreach($vehicles as &$v) {
					if(strtolower(gf('sys_class_code', $v)) == strtolower($class_code)) {
						$vehicle = $v;
						$used[] = gf('sys_class_code', $v);
						break;
					}
				}
				if(is_null($vehicle)) {
					continue;
				}
				global $post;
				$post = $vehicle;
				ob_start();
				include(locate_template( 'panel-booking-rate.php', false, false ));
				$rates_render[] = [
					'order' => gf('display_order') + ($vehicle->ID == $selected_van ? 0 : 1000),
					'html' => ob_get_clean(),
				];
				wp_reset_query();
			}
			if(count($rates_render) == 0) {
				$rates = 'No rates found.';
			} else {
				$selected_van = -1;
				foreach($vehicles as &$v) {
					if(!in_array(gf('sys_class_code', $v), $used)) {
						$rate = false;
						global $post;
						$post = $v;
						ob_start();
						include(locate_template( 'panel-booking-rate.php', false, false ));
						$rates_render[] = [
							'order' => gf('display_order') + 2000,
							'html' => ob_get_clean(),
						];
						wp_reset_query();
					}
				}
			}
			uasort($rates_render, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			$result = array_reduce($rates_render, function($carry, $el) {
				return $carry.$el['html'];
			});
		}
		if(!is_array($rates)) {
			$booking_error = $rates;
			ob_start();
			include(locate_template( 'panel-booking-error.php', false, false ));
			$result = ob_get_clean();
		}
		$jx->variable('rates', $rates);
		$jx->variable('result', $result);
	} else {
		$jx->redirect(get_permalink(get_page_by_path('booking/step-1')));
	}
});

add_action('jx_booking_step_2_submit', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	if(true) { // @TODO validate
		$rate = [];
		foreach($_SESSION['booking']['step_2']['rates'] as $rt) {
			if($rt['id'] == $data['rate']) {
				$rate = $rt;
				break;
			}
		}
		$_SESSION['booking']['step_2'] = array_merge($data, array('rate_data'=>$rate, 'complete'=>true));

		$jx->redirect(get_permalink(get_page_by_path('booking/step-3')));
	} else {
		$jx->variable('error', 'Error');
	}
});

add_action('jx_booking_step_3_load', function($jx) {
	/* @var wpjxmResponse $jx  */
	if($_SESSION['booking']['step_2']['complete']) {
		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate'];
		$class = $_SESSION['booking']['step_2']['rate_data']['class_code'];
		$days = $_SESSION['booking']['step_2']['rate_data']['total']['days'];
		$trn_manager = get_trn_manager();

		$i = 0;
		$extras = $trn_manager->get_extra($pl, $pt, $rate, $class);
		$_SESSION['booking']['step_3'] = array('available'=>$extras);
		if(is_array($extras)) {
			$options = $extras['non-free'];
			ob_start();
			include(locate_template( 'panel-booking-extras.php', false, false ));
			$non_free = ob_get_clean();

			$options = $extras['free'];
			ob_start();
			include(locate_template( 'panel-booking-extras.php', false, false ));
			$free = ob_get_clean();

			$result = 'OK';

			$jx->variable('non_free', $non_free);
			$jx->variable('free', $free);
		} else {
			$booking_error = $extras;

			ob_start();
			include(locate_template( 'panel-booking-error.php', false, false ));
			$result = ob_get_clean();
		}
		$jx->variable('extras', $extras);
		$jx->variable('result', $result);
	} else {
		$jx->redirect(get_permalink(get_page_by_path('booking/step-2')));
	}
});

add_action('jx_booking_step_3_calculate', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	if(true) {
		$trn_manager = get_trn_manager();

		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate_data'];
		$class = $rate['class_code'];
		$dc = $_SESSION['booking']['step_1']['discount_code'];

		$bill = $trn_manager->get_bill($pl, $dl, $pt, $dt, $rate['id'], $class, $data['options'], $dc);
		$_SESSION['booking']['step_3'] = array_merge($data, array('bill'=>$bill, 'complete'=>true));

		ob_start();
		include(locate_template( 'panel-booking-bill.php', false, false ));
		$result = ob_get_clean();

		$jx->variable('bill', $bill);
		$jx->variable('result', $result);
		$jx->variable('booking', $_SESSION['booking']);
	} else {
		$jx->variable('error', 'Error');
	}
});

add_action('jx_booking_step_3_copy', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	if(!array_key_exists('email', $data) || empty($data['email'])) {
		$jx->variable('error', 'Email is required');
	} else if(is_email($data['email'])) {
		$trn_manager = get_trn_manager();

		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate_data'];
		$class = $rate['class_code'];
		$dc = $_SESSION['booking']['step_1']['discount_code'];

		$bill = $trn_manager->get_bill($pl, $dl, $pt, $dt, $rate['id'], $class, $data['options'], $dc);
		$vehicles = get_posts(array(
			'post_type' => 'vehicle',
			'post_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'sys_class_code',
					'value' => $_SESSION['booking']['step_2']['rate_data']['class_code'],
					'compare' => '='
				)
			)
		));
		$vehicle = count($vehicles) ? $vehicles[0] : array();

		ob_start();
		include(locate_template( 'email-copy.php', false, false ));
		$result = ob_get_clean();

		wp_mail($data['email'], 'Your Zeebavans Price Quote', $result, 'Content-type: text/html');
	} else {
		$jx->variable('error', 'Email is invalid');
	}
});

add_action('jx_booking_step_3_more', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	if(!array_key_exists('first_name', $data) || empty($data['first_name'])) {
		$jx->variable('error', 'First Name is required');
	} else if(!array_key_exists('last_name', $data) || empty($data['last_name'])) {
		$jx->variable('error', 'Last Name is required');
	} else if(!array_key_exists('phone', $data) || empty($data['phone'])) {
		$jx->variable('error', 'Phone is required');
	} else if(!array_key_exists('email', $data) || empty($data['email'])) {
		$jx->variable('error', 'Email is required');
	} else if(!is_email($data['email'])) {
		$jx->variable('error', 'Email is invalid');
	} else if(!array_key_exists('message', $data) || empty($data['message'])) {
		$jx->variable('error', 'Message is required');
	} else {
		$trn_manager = get_trn_manager();

		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate_data'];
		$class = $rate['class_code'];
		$dc = $_SESSION['booking']['step_1']['discount_code'];

		$bill = $trn_manager->get_bill($pl, $dl, $pt, $dt, $rate['id'], $class, $data['options'], $dc);
		$vehicles = get_posts(array(
			'post_type' => 'vehicle',
			'post_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'sys_class_code',
					'value' => $_SESSION['booking']['step_2']['rate_data']['class_code'],
					'compare' => '='
				)
			)
		));
		$vehicle = count($vehicles) ? $vehicles[0] : array();

		ob_start();
		include(locate_template( 'email-more.php', false, false ));
		$result = ob_get_clean();


		$manager_email = gf('reservation_email', 'options');
		if (empty($manager_email)) {$manager_email = 'valery.alexeev@me.com';}

		wp_mail($manager_email, 'Website information request', $result, 'Content-type: text/html');
	}
});

add_action('jx_booking_step_3_submit', function($jx) {
	/* @var wpjxmResponse $jx  */
	$data = $jx->getData();
	unset($data['jx_action']);

	if(true) { // @TODO validate
		$trn_manager = get_trn_manager();

		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate_data'];
		$class = $rate['class_code'];
		$dc = $_SESSION['booking']['step_1']['discount_code'];


		$bill = $trn_manager->get_bill($pl, $dl, $pt, $dt, $rate['id'], $class, $data['options'], $dc);

		$_SESSION['booking']['step_3'] = array('bill'=>$bill, 'options'=>$data['options'], 'complete'=>true);

		$jx->redirect(get_permalink(get_page_by_path('booking/step-4')));
	} else {
		$jx->variable('error', 'Error');
	}
});

add_action('jx_booking_step_4_submit', function($jx) {
	/* @var wpjxmResponse $jx  */

	$data = $jx->getData();
	unset($data['jx_action']);

	$form = $data['form'];
	if(!array_key_exists('first_name', $form) || empty($form['first_name'])) {
		$jx->variable('error', 'First Name should be set');
		return;
	}
	if(!array_key_exists('last_name', $form) || empty($form['last_name'])) {
		$jx->variable('error', 'Last Name should be set');
		return;
	}
/*
	if(!array_key_exists('company_name', $form) || empty($form['company_name'])) {
		$jx->variable('error', 'Company should be set');
		return;
	}
*/
	if(!array_key_exists('phone_number', $form) || empty($form['phone_number'])) {
		$jx->variable('error', 'Phone Number should be set');
		return;
	}
	if(!array_key_exists('email', $form) || empty($form['email'])) {
		$jx->variable('error', 'Email should be set');
		return;
	}
	if(!array_key_exists('email_confirm', $form) || empty($form['email_confirm'])) {
		$jx->variable('error', 'Email Confirm should be set');
		return;
	}
	if($form['email'] != $form['email_confirm']) {
		$jx->variable('error', 'Email Confirm should be equal to Email');
		return;
	}
	if(!array_key_exists('address', $form) || empty($form['address'])) {
		$jx->variable('error', 'Address should be set');
		return;
	}
	if(!array_key_exists('zip', $form) || empty($form['zip'])) {
		$jx->variable('error', 'ZIP should be set');
		return;
	}
	if(!array_key_exists('city', $form) || empty($form['city'])) {
		$jx->variable('error', 'City should be set');
		return;
	}
	if(!array_key_exists('state', $form) || empty($form['state'])) {
		$jx->variable('error', 'State should be set');
		return;
	}
	if(!$_SESSION['booking']['step_2']['skip']) {
		if(!array_key_exists('card_number', $form) || empty($form['card_number'])) {
			$jx->variable('error', 'Card number is required');
			return;
		}
		if(!array_key_exists('card_name', $form) || empty($form['card_name'])) {
			$jx->variable('error', 'Name on card should be set');
			return;
		}
		if(!array_key_exists('card_month', $form) || empty($form['card_month'])) {
			$jx->variable('error', 'Card Month should be set');
			return;
		}
		if(!array_key_exists('card_year', $form) || empty($form['card_year'])) {
			$jx->variable('error', 'Card Year should be set');
			return;
		}
		if(!array_key_exists('card_year', $form) || empty($form['card_year'])) {
			$jx->variable('error', 'Card Year should be set');
			return;
		}
		if(!array_key_exists('card_year', $form) || empty($form['card_year'])) {
			$jx->variable('error', 'Card Year should be set');
			return;
		}
		if(!array_key_exists('card_cvv', $form) || empty($form['card_cvv'])) {
			$jx->variable('error', 'Card CVV should be set');
			return;
		}
	}
	if(true) { // @TODO validate
		$docs = [];
		$allowed = $_SESSION['booking']['step_4']['allowed_attaches'];
		foreach(json_decode($data['form']['attached']) as $attach) {
			if(in_array($attach, $allowed)) {
				$docs[] = wp_get_attachment_url($attach);
			}
		}
		$data['form']['documents'] = implode(' ', $docs);

		$trn_manager = get_trn_manager();

		$pl = $_SESSION['booking']['step_1']['pickup_location'];
		$dl = $_SESSION['booking']['step_1']['dropoff_location'];
		$pt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['pickup_date'].' '.$_SESSION['booking']['step_1']['pickup_time'])->getTimestamp();
		$dt = DateTime::createFromFormat(getTimeFormat(), $_SESSION['booking']['step_1']['dropoff_date'].' '.$_SESSION['booking']['step_1']['dropoff_time'])->getTimestamp();
		$rate = $_SESSION['booking']['step_2']['rate_data'];
		$class = $rate['class_code'];
		$dc = $_SESSION['booking']['step_1']['discount_code'];
		$extraCodes = $_SESSION['booking']['step_3']['options'];
		$code = array_key_exists('modify', $_SESSION['booking']['step_4']) && $_SESSION['booking']['step_4']['modify'] ? $_SESSION['booking']['step_4']['reservation'] : false;

		// Prepaid
		$prepaid = gf('reservation_prepaid', 'options');
		$deposit = 0;
		if($prepaid) {
			$prepaid = gf('reservation_prepaid_amount', 'options');
			$deposit = gf('reservation_deposit_amount', 'options');
		}

		$res = $trn_manager->reserve($pl, $dl, $pt, $dt, $rate['id'], $class, $data['form'], $dc, $extraCodes, $code, $prepaid, $deposit);
		if(!is_array($res)) {
			$jx->variable('error', $res);
			return;
		}

		//unset($data['form']['card_cvv']);
		$data['form']['card_number'] = '*** **** **** '.substr($data['form']['card_number'], -4);

		$_SESSION['booking']['step_4'] = array_merge($_SESSION['booking']['step_4'] ?: array(), array('reservation'=> $res['id'], 'form'=>$data['form'], 'complete'=>true));

		ob_start();
		include(locate_template( 'email-info.php', false, false ));
		$result = ob_get_clean();
		wp_mail($_SESSION['booking']['step_4']['form']['email'], 'Your Zeebavans Reservation', $result, 'Content-type: text/html');

		if(count($docs) > 0) {
			ob_start();
			include(locate_template( 'email-docs.php', false, false ));
			$result = ob_get_clean();

			$manager_email = gf('reservation_email', 'options');
			if (empty($manager_email)) {$manager_email = 'valery.alexeev@me.com';}
			wp_mail($manager_email, 'Zeebavans Reservation', $result, 'Content-type: text/html');
		}

		$jx->redirect(get_permalink(get_page_by_path('booking/step-5')));
	} else {
		$jx->variable('error', 'Error');
	}
});

function booking_file_upload() {
	$attach_id = media_handle_upload('docs', 0);
	$ret = array('attach_id' => $attach_id);
	if(is_wp_error($attach_id)) {
		$ret = array('error' => $attach_id->get_error_message());
	} else {
		$step = isset($_SESSION['booking']['step_4']) ? $_SESSION['booking']['step_4'] : array();
		if(!isset($step['allowed_attaches'])) {
			$step['allowed_attaches'] = array($attach_id);
		} else {
			$step['allowed_attaches'][] = $attach_id;
		}
		$_SESSION['booking']['step_4'] = $step;
	}
	echo json_encode($ret);
	wp_die();
}
add_action('wp_ajax_booking_file_upload', 'booking_file_upload');
add_action('wp_ajax_nopriv_booking_file_upload', 'booking_file_upload');