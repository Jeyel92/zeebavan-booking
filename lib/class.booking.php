<?php
/*
2019 Dev Notes
There are 3 classes in here: TRNRequest, TRNResponse, TRNManager

Seems like TRNManager is used to build queries for TSD servers and is the most exposed layer
TRNRequest is the actual connection object. It builds the XML request when TRNManager calls it
TRNResponse is just the processor for responses from the TSD server

TRNRequest->__toString(); should create a string payload that we can send to TSD's servers
TRNRequest->send() is the function that calls cURL



Public Calls / Reference for TRNManager
TRNManager::get_locations();
TRNManager::get_rates( $pl, $dl, $pt, $dt, $discount = false, $classCode = false ); # Oh boy... These variable names could be better
TRNManager::get_extra( $pl, $pt, $rate, $class, $split = true );
TRNManager::reserve( $pl, $dl, $pt, $dt, $rate, $class, $form, $discount = false, array $extraCodes = [], $code = false, $prepaid = false, $deposit = 0 );
TRNManager::cancel( $code ); // $code = reservation code
TRNManager::get_reservation( $code ); // $code = reservation code
TRNManager::get_rate( $rate, $pl, $dl, $pt, $dt, $discount = false, $classCode = false );
TRNManager::get_bill( $pl, $dl, $pt, $dt, $rate, $class, array $extraCodes = [], $discount = false, $prepaid = false );
TRNManager::lastRequest();
TRNManager::lastResponse();


Public Calls / Reference for TRNRequest

TRNManager::reqloc( $locID = '', $businessHours = true ) # REQLOC = Request Location?
TRNManager::reqrez( $confirm ) # REQREZ = Request Reservation
TRNManager::reqcan( $confirm ) # REQCAN = Request Cancellation
TRNManager::reqrat( $pickLoc, $retLoc, $pickDate, $dropDate, $rate = false, $discount = false, $classCode = false, $passengers = 0, $luggage = 0, $prepaid = false, $totalPrice = true, array $extraCodes = [] )
TRNManager::reqpol( $loc ) # REQPOL = ???
TRNManager::reqbil( $pickLoc, $retLoc, $pickDate, $dropDate, $rateID, $classCode, $discount = false, $prepaid = false, array $extraCodes = [] )
TRNManager::reqext( $pickLoc, $pickDate, $rateID, $classCode )
TRNManager::addrez( $pickLoc, $retLoc, $pickDate, $dropDate, $rateId, $classCode, $form, $discount = false, array $extraCodes = [], $code = false, $prepaid = false, $deposit = 0 )


Application layer notes:
1. We send XML in a HTTP POST command. Using the <TRNXML> format
   1a. In the XML, <MessageID></MessageID> is the command. For example, 'REQREZ'.
       In this class, use $trn_request->reqrez($code); to call it
   1b. TRNXML contains all authentication information
   1c. TRNXML also contains our request
2. TSD Responds with XML
3. This library processes the XML and returns what it wants
*/

class TRNRequest {

	const R_NAME = 'TRNXML';
	const VERSION = '1.0.0';
	const RECIPIENT = 'TRN';
	const TRADING_PARTNER = 'Web01';

	/**
	 * @var DOMDocument
	 */
	private $dom;
	/**
	 * @var DOMNode
	 */
	private $root;
	private $raw;

	private $senderID;
	private $customerNumber;
	private $passcode;
	private $sid;
	private $ip;

	/**
	 * TRNXML constructor.
	 *
	 * @param string $senderID
	 * @param string $customerNumber
	 * @param string $passcode
	 * @param string $sid
	 * @param string $ip
	 */
	public function __construct( $senderID, $customerNumber, $passcode, $sid, $ip ) {
		$this->senderID       = $senderID;
		$this->customerNumber = $customerNumber;
		$this->passcode       = $passcode;
		$this->sid            = $sid;
		$this->ip             = $ip;
		$this->reload();
	}

	private function reload() {
		$this->dom  = new DOMDocument();
		$this->root = $this->el( $this->dom, self::R_NAME );
		$this->attr( $this->root, 'version', self::VERSION );
		$this->el( $this->root, 'Dategmtime', date( 'm/d/Y H:i:s', time() ) );
		$this->el( $this->el( $this->root, 'Sender' ), 'SenderID', $this->senderID );
		$this->el( $this->el( $this->root, 'Recipient' ), 'RecipientID', self::RECIPIENT );
		$this->el( $this->el( $this->root, 'TradingPartner' ), 'TradingPartnerCode', self::TRADING_PARTNER );
		$customer = $this->el( $this->root, 'Customer' );
		$this->el( $customer, 'CustomerNumber', $this->customerNumber );
		$this->el( $customer, 'Passcode', $this->passcode );
		$this->el( $customer, 'SID', $this->sid );
		$this->el( $customer, 'RemoteAddress', $this->ip );
	}

	/**
	 * @param DOMNode $root
	 * @param string $element
	 * @param string|null $value
	 *
	 * @return DOMElement|DOMNode
	 */
	private function el( $root, $element, $value = null ) {
		$element = $this->dom->createElement( $element, $value );

		return is_null( $root ) ? $element : $root->appendChild( $element );
	}

	/**
	 * @param DOMNode $el
	 * @param string $key
	 * @param string $val
	 *
	 * @return DOMAttr
	 */
	private function attr( $el = null, $key, $val ) {
		$attr        = $this->dom->createAttribute( $key );
		$attr->value = $val;

		return is_null( $el ) ? $attr : $el->appendChild( $attr );
	}

	public function reqloc( $locID = '', $businessHours = true ) { # REQLOC = Request Location?
		$this->msg( 'REQLOC' );

		$payload = $this->el( $this->root, 'Payload' );
		if ( ! empty( $locID ) ) {
			$this->el( $payload, 'RentalLocationID', $locID );
		}
		$this->el( $payload, 'BusinessHours', $businessHours ? 1 : 0 );

		return $this;
	}

	/**
	 * @param string $message
	 * @param DOMNode|null $message_el
	 */
	private function msg( $message, &$message_el = null ) {
		$this->reload();
		$message_el = $this->root->appendChild( $this->dom->createElement( 'Message' ) );
		$message_el->appendChild( $this->dom->createElement( 'MessageID', $message ) );
	}

	public function reqrez( $confirm ) {
		$this->msg( 'REQREZ' );

		$payload = $this->el( $this->root, 'Payload' );
		$this->el( $payload, 'ConfirmNum', $confirm );

		return $this;
	}

	public function reqcan( $confirm ) {
		$this->msg( 'REQCAN' );

		$payload = $this->el( $this->root, 'PayLoad' );
		$this->el( $payload, 'ConfirmNum', $confirm );

		return $this;
	}

	public function reqrat( $pickLoc, $retLoc, $pickDate, $dropDate, $rate = false, $discount = false, $classCode = false, $passengers = 0, $luggage = 0, $prepaid = false, $totalPrice = true, array $extraCodes = [] ) {

		$this->msg( 'REQRAT' );

		$payload = $this->el( $this->root, 'Payload' );
		$this->el( $payload, 'RentalLocationID', $pickLoc );
		$this->el( $payload, 'ReturnLocationID', $retLoc );
		$this->el( $payload, 'PickupDateTime', date( 'mdY h:i A', $pickDate ) );
		$this->el( $payload, 'ReturnDateTime', date( 'mdY h:i A', $dropDate ) );
		$this->el( $payload, 'Prepaid', $prepaid ? 'Y' : 'N' );
		$this->el( $payload, 'TotalPricing', $totalPrice ? '1' : '0' );
		if ( $rate ) {
			$this->el( $payload, 'RateCode', $rate );
		}
		if ( $discount ) {
			$this->el( $payload, 'DiscountCode', $discount );
		}
		if ( $classCode ) {
			$this->el( $payload, 'ClassCode', $classCode );
		}
		if ( $passengers > 0 ) {
			$this->el( $payload, 'Passengers', $passengers );
		}
		if ( $luggage > 0 ) {
			$this->el( $payload, 'Luggage', $passengers );
		}
		foreach ( $extraCodes as $extraCode ) {
			$this->el( $payload, 'ExtraCode', $extraCode );
		}

		return $this;
	}

	public function reqpol( $loc ) {
		$this->msg( 'REQPOL' );

		$payload = $this->el( $this->root, 'Payload' );
		$this->el( $payload, 'RentalLocationID', $loc );

		return $this;
	}

	public function reqbil( $pickLoc, $retLoc, $pickDate, $dropDate, $rateID, $classCode, $discount = false, $prepaid = false, array $extraCodes = [] ) {
		$this->msg( 'REQBIL' );

		$payload = $this->el( $this->root, 'PayLoad' );
		$this->el( $payload, 'RentalLocationID', $pickLoc );
		$this->el( $payload, 'ReturnLocationID', $retLoc );
		$this->el( $payload, 'PickupDateTime', date( 'mdY h:i A', $pickDate ) );
		$this->el( $payload, 'ReturnDateTime', date( 'mdY h:i A', $dropDate ) );
		$this->el( $payload, 'RateID', $rateID );
		$this->el( $payload, 'ClassCode', $classCode );
		if ( $discount ) {
			$this->el( $payload, 'DiscountCode', $discount );
		}
		$this->el( $payload, 'Prepaid', $prepaid ? 'Y' : 'N' );
		foreach ( $extraCodes as $extraCode ) {
			$this->el( $payload, 'ExtraCode', $extraCode );
		}

		return $this;
	}

	public function reqext( $pickLoc, $pickDate, $rateID, $classCode ) {
		$this->msg( 'REQEXT' );

		$payload = $this->el( $this->root, 'Payload' );
		$this->el( $payload, 'RentalLocationID', $pickLoc );
		$this->el( $payload, 'PickupDateTime', date( 'mdY h:i A', $pickDate ) );
		$this->el( $payload, 'RateCode', $rateID );
		$this->el( $payload, 'ClassCode', $classCode );

		return $this;
	}

	// 6/27/2019 - New function to update the prepaid amount
	public function update_prepaid($code, $prepaid = false, $deposit = 0) {
		$this->msg( 'ADDREZ' );

		$this->el( $payload, 'ConfirmNum', $code );

		if ( $prepaid !== false ) {
			$this->el( $payload, 'Prepaid', 'Y' );
			$this->el( $payload, 'PrepaidAmount', $prepaid );
			$this->el( $payload, 'CardDepositAmount', $deposit );
		} else {
			$this->el( $payload, 'Prepaid', 'N' );
		}

		return $this;
	}


	public function addrez( $pickLoc, $retLoc, $pickDate, $dropDate, $rateId, $classCode, $form, $discount = false, array $extraCodes = [], $code = false, $prepaid = false, $deposit = 0 ) {
		$this->msg( 'ADDREZ' );

		$commented_fields = [
			'a_driver-first_name-1' => 'Additional Driver 1 First Name',
			'a_driver-last_name-1'  => 'Additional Driver 1 Last Name',
			'a_driver-first_name-2' => 'Additional Driver 2 First Name',
			'a_driver-last_name-2'  => 'Additional Driver 2 Last Name',
			'card_name'             => 'Name on Card',
			'other_country_mexico'  => 'Mexico',
			'other_country_canada'  => 'Canada',
			'documents'             => 'Documents',
			'special'               => 'Special Remarks',
		];
		$comment          = '';
		foreach ( $commented_fields as $key => $val ) {
			if ( array_key_exists( $key, $form ) && ! empty( $form[ $key ] ) ) {
				$comment .= $val . ': ' . $form[ $key ] . "<br> \n";
			}
		}


		// Fill credit card with fake params if we have a stripe token
		if (isset($form['stripe_token'])) {
			$form['card_number'] = '4242424242424242';
			$form['card_name'] = 'Stripe';
			$form['card_month'] = '12';
			$form['card_year'] = ((int)date('Y')) + 10;
			$form['card_cvv'] = '123';
		}


		$payload = $this->el( $this->root, 'Payload' );
		$this->el( $payload, 'RentalLocationID', $pickLoc );
		$this->el( $payload, 'ReturnLocationID', $retLoc );
		$this->el( $payload, 'PickupDateTime', date( 'mdY h:i A', $pickDate ) );
		$this->el( $payload, 'ReturnDateTime', date( 'mdY h:i A', $dropDate ) );
		$this->el( $payload, 'RateID', $rateId );
		$this->el( $payload, 'TermsAndConditions', 'Y' );
		$this->el( $payload, 'ClassCode', $classCode );

		$this->el( $payload, 'RentalComments', $comment );
		$this->el( $payload, 'RenterFirst', $form['first_name'] );
		$this->el( $payload, 'RenterLast', $form['last_name'] );
		$this->el( $payload, 'EmailAddress', $form['email'] );
		$this->el( $payload, 'RenterHomePhone', $form['phone_number'] );
		$this->el( $payload, 'RenterCountry', $form['country'] );
		$this->el( $payload, 'RenterAddress1', $form['address'] );
		$this->el( $payload, 'RenterCity', $form['city'] );
		$this->el( $payload, 'RenterState', $form['state'] );
		$this->el( $payload, 'RenterZip', $form['zip'] );
		$this->el( $payload, 'RenterEmployer', $form['company_name'] );
		if ( $form['flight'] != '' ) {
			$this->el( $payload, 'Flight', $form['flight'] );
		}
		if ( $form['flight_airline'] != '' ) {
			$this->el( $payload, 'Airline', $form['flight_airline'] );
		}
		foreach ( $extraCodes as $extraCode ) {
			$this->el( $payload, 'ExtraCode', $extraCode );
		}
		if ( $discount ) {
			$this->el( $payload, 'DiscountCode', $discount );
		}
		if ( $code ) {
			$this->el( $payload, 'ConfirmNum', $code );
		}
		$this->el( $payload, 'CCType', self::cardType( $form['card_number'] ) );
		$this->el( $payload, 'CCNumber', preg_replace( '/[^\d]/', '', $form['card_number'] ) );
		$this->el( $payload, 'CCExp', $this->cardExp( $form['card_month'], $form['card_year'] ) );
		$this->el( $payload, 'CCSecCode', $form['card_cvv'] );


		if ( $prepaid !== false ) {
			$this->el( $payload, 'Prepaid', 'Y' );
			$this->el( $payload, 'PrepaidAmount', $prepaid );
			$this->el( $payload, 'CardDepositAmount', $deposit );
		} else {
			$this->el( $payload, 'Prepaid', 'N' );
		}

		return $this;
	}

	public static function cardType( $number ) {
		$number = preg_replace( '/[^\d]/', '', $number );
		if ( preg_match( '/^3[47][0-9]{13}$/', $number ) ) {
			return 'AX';
		} elseif ( preg_match( '/^5[1-5][0-9]{14}$/', $number ) ) {
			return 'MC';
		} elseif ( preg_match( '/^4[0-9]{12}(?:[0-9]{3})?$/', $number ) ) {
			return 'VI';
		} elseif ( preg_match( '/^6(011\d{12}|4[4-9]\d{13}|5\d{14}|22([2-8]\d{12}|1(2[6-9]\d{10}|[3-9]\d{11})|9(2[1-5]\d{10}|1\d{11})))$/', $number ) ) {
			return 'DI';
		} else {
			return 'UN';
		}
	}

	public function cardExp( $m, $y ) {
		return date( 'm/t/y', strtotime( $m . '/1/' . $y ) );
	}

	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	public function send( $url ) {
		$request   = $this->__toString();
		$this->raw = $request;

		$soap    = curl_init( $url );
		$options = [
			CURLOPT_POST           => true,
			CURLOPT_HEADER         => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER     => [
				'Content-Type: text/xml; charset=utf-8',
				'Content-Length: ' . strlen( $request ),
			],
			CURLOPT_POSTFIELDS     => $request,
		];
		curl_setopt_array( $soap, $options );
		$response = curl_exec( $soap );
		curl_close( $soap );

		return $response;
	}

	public function __toString() {
		return $this->dom->saveXML( $this->root );
	}

	public function raw() {
		return $this->raw;
	}

}

class TRNResponse {

	const R_NAME = 'TRNXML';
	const LOCATIONS = 'RSPLOC';
	const RATES = 'RSPRAT';
	const POLICY = 'RSPPOL';
	const BILL = 'RSPBIL';
	const EXTRA = 'RSPEXT';
	const RESERVATION = 'RSPREZ';
	const CANCELLATION = 'RSPCAN';
	const ERROR = 'RSPERR';

	private $xml;
	private $type;
	private $data = null;
	private $raw = '';

	public function __construct( $data = null, $pre = true ) {
		if ( $data ) {
			$this->setData( $data, $pre );
		}
	}

	/**
	 * @return string TRNXML type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return SimpleXMLElement TRNXML object
	 */
	public function getXml() {
		return $this->xml;
	}

	/**
	 * @return array|null parsed data
	 */
	public function getData() {
		return $this->data;
	}

	public function setData( $data, $pre = true ) {
		$data      = trim( $data );
		$this->raw = $data;
		if ( $pre ) {
			$data = substr( substr( $data, 5 ), 0, - 6 );
		}

		$this->xml = new SimpleXMLElement( $data );
		if ( $this->xml->getName() != self::R_NAME ) {
			throw new Exception( "Not a TRNXML" );
		}
		$this->type = $this->xml->Message->MessageID;
		$this->parse();

		return $this;
	}

	public function raw() {
		return $this->raw;
	}

	private function parse() {
		switch ( $this->type ) {
			case self::LOCATIONS:
				$this->parseLocations();
				break;
			case self::RATES:
				$this->parseRates();
				break;
			case self::BILL:
				$this->parseBill();
				break;
			case self::EXTRA:
				$this->parseExtra();
				break;
			case self::POLICY:
				$this->parsePolicies();
				break;
			case self::RESERVATION:
				$this->parseReservation();
				break;
			case self::CANCELLATION:
				$this->parseCancellation();
				break;
			case self::ERROR:
				$this->parseError();
				break;
		}
	}

	private function parseLocations() {
		$rental = [];
		if ( isset( $this->xml->Payload ) && isset( $this->xml->Payload->RentalLocation ) ) {
			foreach ( $this->xml->Payload->RentalLocation as $location ) {
				$loc           = [
					'id'               => $location->RentalLocationID,
					'type'             => $location->RentalLocationType,
					'name'             => $location->RentalLocationName,
					'active'           => $location->RentalLocationStatus,
					'provider'         => $location->RentalVehicleProvider,
					'latitude'         => $location->Latitude,
					'longitude'        => $location->Longitude,
					'address'          => [
						'line1'      => $location->AddressLine1,
						'line2'      => $location->AddressLine2,
						'city'       => $location->AddressCity,
						'state'      => $location->AddressState,
						'state_name' => $location->AddressStateName,
						'zip'        => $location->AddressZipCode,
						'country'    => $location->AddressCountry,
					],
					'foreign'          => $location->ForeignLocation,
					'phone'            => $location->PhoneNumber,
					'phone_alt'        => $location->AltPhoneNumber,
					'fax'              => $location->FAXNumber,
					'email'            => $location->RentalLocationEmail,
					'currency'         => $location->RentalLocationCurrency,
					'drop24'           => $location->Drop24Hour,
					'pickup24'         => $location->Pickup24Hour,
					'unit'             => $location->MilageUnit,
					'airport'          => $location->AirportIndicator,
					'airport_type'     => $location->LocationType,
					'shuttle_distance' => $location->ShuttleDistance,
					'shuttle_time'     => $location->ShuttleTime,
				];
				$businessHours = [];
				foreach ( $location->BusinessHours as $businessHour ) {
					$businessHours[] = [
						'from' => $businessHour->FromDate,
						'to'   => $businessHour->ToDate,
						'mon'  => $this->businessHour( $businessHour->OpenCloseMon ),
						'tue'  => $this->businessHour( $businessHour->OpenCloseTue ),
						'wed'  => $this->businessHour( $businessHour->OpenCloseWed ),
						'thu'  => $this->businessHour( $businessHour->OpenCloseThu ),
						'fri'  => $this->businessHour( $businessHour->OpenCloseFri ),
						'sat'  => $this->businessHour( $businessHour->OpenCloseSat ),
						'sun'  => $this->businessHour( $businessHour->OpenCloseSun ),
					];
				}
				$loc['business_hours'] = $businessHours;

				$rental[] = $this->stringify( $loc );
			}
		}
		$this->data = [
			'rental' => $rental,
		];
	}

	private function businessHour( $arr ) {
		$result = [];
		foreach ( $arr as $el ) {
			if ( ! empty( $el . '' ) ) {
				$tmp      = explode( '-', $el );
				$result[] = [
					'from' => $tmp[0],
					'to'   => $tmp[1],
				];
			}
		}

		return $result;
	}

	private function stringify( array $arr ) {
		return array_map( function ( $el ) {
			return is_array( $el ) ? $this->stringify( $el ) : $el . '';
		}, $arr );
	}

	private function parseRates() {
		$rates = [];
		if ( isset( $this->xml->Payload ) && isset( $this->xml->Payload->RateProduct ) ) {
			foreach ( $this->xml->Payload->RateProduct as $rate ) {
				$rt = [
					'id'         => $rate->RateID,
					'vendor'     => $rate->RateVendor,
					'code'       => $rate->RateCode,
					'pickup'     => $rate->RentalLocation,
					'dropoff'    => $rate->ReturnLocation,
					'class_code' => $rate->ClassCode,
					'amount'     => $rate->RateAmount,
					'plan'       => $rate->RatePlan,
					'free_miles' => $rate->FreeMiles,
					'per_mile'   => $rate->PerMileAmount,
					'per_hour'   => $rate->PerHourCharge,
					'discount'   => $rate->DiscountPercent,
				];
				if ( isset( $rate->TotalPricing ) ) {
					$total       = $rate->TotalPricing;
					$rt['total'] = [
						'days'        => $total->RentalDays,
						'rate_charge' => $total->RateCharge,
						'late_charge' => $total->RatePlusLate,
						'charge'      => $total->TotalCharges,
						'taxes'       => $total->TotalTaxes,
						'free_miles'  => $total->TotalFreeMiles,
						'per_mile'    => $total->PerMileAmount,
						'discount'	  => $total->RateDiscount
					];
				}

				$rates[] = $this->stringify( $rt );
			}
		}
		$this->data = [
			'rates' => $rates,
		];
	}

	private function parseBill() {
		$bill = [];
		if ( isset( $this->xml->Payload ) ) {
			$pl    = $this->xml->Payload;
			$bill  = [
				'days'     => $pl->RentalDays,
				'rate'     => $pl->RateCharge / $pl->RentalDays,
				'charge'   => $pl->RateCharge,
				'extras'   => $pl->TotalExtras,
				'subtotal' => $pl->RateCharge + $pl->TotalExtras,
				'total'    => $pl->TotalCharges,
			];
			$taxes = [];
			$tx    = $pl->Taxes;
			for ( $i = 1; $i <= 15; $i ++ ) {
				$tx_name = 'Tax' . $i;
				if ( isset( $tx->{$tx_name . 'Amount'} ) ) {
					$taxes[] = [
						'amount' => $tx->{$tx_name . 'Amount'},
						'rate'   => $tx->{$tx_name . 'Rate'},
						'desc'   => $this->beautify( $tx->{$tx_name . 'Desc'} ),
						'charge' => $tx->{$tx_name . 'Charge'},
						'type'   => $tx->{$tx_name . 'Type'},
					];
				}
			}
			$bill['taxes'] = $taxes;
			$extra         = [];
			foreach ( $pl->DailyExtra as $de ) {
				$extra[] = [
					'id'     => $de->ExtraCode,
					'desc'   => $this->beautify( $de->ExtraDesc ),
					'amount' => $de->ExtraAmount,
				];
			}
			$bill['extra'] = $extra;
		}
		$this->data = [
			'bill' => $this->stringify( $bill ),
		];
	}

	private function parseExtra() {
		$extras = [];
		if ( isset( $this->xml->Payload ) && isset( $this->xml->Payload->DailyExtra ) ) {
			foreach ( $this->xml->Payload->DailyExtra as $ext ) {
				$extra = [
					'id'     => $ext->ExtraCode,
					'desc'   => $this->beautify( $ext->ExtraDesc ),
					'amount' => $ext->ExtraAmount,
					'calc'   => $ext->ExtraCalcType,
					'notes'  => $ext->ExtraNotes,
					'branch' => $ext->Branch,
				];

				$extras[] = $this->stringify( $extra );
			}
		}
		$this->data = [
			'extras' => $extras,
		];
	}

	private function parsePolicies() {
		$pols = [];
		if ( isset( $this->xml->Payload ) && isset( $this->xml->Payload->Policy ) ) {
			foreach ( $this->xml->Payload->Policy as $policy ) {
				$pol = [
					'language'    => $policy->PolicyLanguage,
					'description' => $policy->Description,
					'start'       => $policy->StartDate,
					'end'         => $policy->EndDate,
					'text'        => $policy->PolicyText,
				];

				$pols[] = $this->stringify( $pol );
			}
		}
		$this->data = [
			'policy' => $pols,
		];
	}

	private function parseReservation() {
		$data = [];
		if ( isset( $this->xml->ConfirmNum ) ) {
			$data['id'] = $this->xml->ConfirmNum;
		}
		if ( isset( $this->xml->Payload ) ) {
			$pl              = $this->xml->Payload;
			$pickupDateTime  = DateTime::createFromFormat( 'mdY H:i a', $pl->PickupDateTime );
			$dropoffDateTime = DateTime::createFromFormat( 'mdY H:i a', $pl->ReturnDateTime );
			$data['step_1']  = [
				'pickup_date'      => $pickupDateTime->format( "F d, Y" ),
				'pickup_time'      => $pickupDateTime->format( "H:i A" ),
				'dropoff_date'     => $dropoffDateTime->format( "F d, Y" ),
				'dropoff_time'     => $dropoffDateTime->format( "H:i A" ),
				'van_type'         => $pl->ClassCode,
				'pickup_location'  => $pl->RentalLocationID,
				'dropoff_location' => $pl->ReturnLocationID,
			];

			$data['step_2'] = [
				'rate' => $pl->RateID,
				'rate_code' => $pl->RateCode,
			];

			$options = [];
			foreach ( $pl->DailyExtra as $extra ) {
				$options[] = $extra->ExtraCode;
			}
			$data['step_3'] = [
				'options' => $options,
			];

			$data['step_4'] = [
				'form' => [
					'first_name'    => $this->beautify( $pl->RenterFirst ),
					'last_name'     => $this->beautify( $pl->RenterLast ),
					'company_name'  => $this->beautify( $pl->RenterEmployer ),
					'phone_number'  => $pl->RenterHomePhone,
					'email'         => strtolower( $pl->EmailAddress ),
					'email_confirm' => strtolower( $pl->EmailAddress ),
					'country'       => $this->beautify( $pl->RenterCountry ),
					'address'       => $this->beautify( $pl->RenterAddress1 ),
					'zip'           => $pl->RenterZIP,
					'city'          => $this->beautify( $pl->RenterCity ),
					'state'         => $pl->RenterState,
					'flight'        => $pl->Flight,
					'flight_airline'=> $pl->Airline,
					'card_type'     => $pl->CardType,
					'card_number'   => $pl->CardNumber,
					'card_exp'      => $pl->CardExp,
				],
			];
			$data['status'] = $pl->ReservationStatus;
		}
		$this->data = $this->stringify( $data );
	}

	private function parseError() {
		$error = '';
		if ( isset( $this->xml->Message ) && isset( $this->xml->Message->MessageDescription ) ) {
			$error = $this->xml->Message->MessageDescription;
		}
		$this->data = $this->stringify( [
			'error' => $error,
		] );
	}

	private function beautify( $name ) {
		$string = ucwords( strtolower( $name ) );

		$words = preg_split( '/[\s()]+/', $string );
		foreach ( $words as $word ) {
			if ( null !== ( $correctForm = $this->beautifyWord( $word ) ) ) {
				$string = str_replace( $word, $correctForm, $string );
			}
		}

		return $string;
	}

	private function beautifyWord( $word ) {
		if ( strlen( $word ) == 0 ) {
			return null;
		}
		if ( strlen( $word ) <= 3 ) {
			if ( strtoupper( $word[0] ) != $word[0] ) {
				return strtoupper( $word );
			}
		}

		return null;
	}

	private function parseCancellation() {
		$data = [];
		if ( isset( $this->xml->Message->MessageDescription ) ) {
			$data['message'] = $this->xml->Message->MessageDescription;
		}
		$this->data = $this->stringify( $data );
	}

}
class TRNManager {

	private $_url = '';
	private $_request = null;
	private $_response = null;

	public function __construct( $url, $senderID, $customerNumber, $passcode, $sid, $ip ) {
		$this->_url      = $url;
		$this->_request  = new TRNRequest( $senderID, $customerNumber, $passcode, $sid, $ip );
		$this->_response = new TRNResponse();
	}

	public function get_locations() {
		return $this->response( $this->_request->reqloc(), 'rental' );
	}

	private function response( $data, $key = null ) {
		if ( $data instanceof TRNRequest ) {
			$data = $data->send( $this->_url );
		}
		$this->_response->setData( $data );

		$rsp = $this->_response->getData();

		# Return error if one exists
		if ( isset( $rsp['error'] ) ) {
			$error = explode( '-', $rsp['error'], 2 );

			return $error[ count( $error ) - 1 ];
		}

		# Return $rsp if $key is null or invalid. Otherwise return $rsp[$key]
		return ! is_null( $key ) && array_key_exists( $key, $rsp ) ? $rsp[ $key ] : $rsp;
	}

	public function get_rates( $pl, $dl, $pt, $dt, $discount = false, $classCode = false ) {
		return $this->response( $this->_request->reqrat( $pl, $dl, $pt, $dt, false, $discount, $classCode ), 'rates' );
	}

	public function get_extra( $pl, $pt, $rate, $class, $split = true ) {
		$data = $this->response( $this->_request->reqext( $pl, $pt, $rate, $class ), 'extras' );
		if ( $split && is_array( $data ) ) {
			$free     = [];
			$non_free = [];
			foreach ( $data as $extra ) {
				if ( $extra['amount'] != '0.00' ) {
					$non_free[] = $extra;
				} else {
					$free[] = $extra;
				}
			}
			$data = [
				'free'     => $free,
				'non-free' => $non_free,
			];
		}

		return $data;
	}

	/*
	reserve() parameters

	$pl         = Pickup Location
	$dl         = Dropoff Location
	$pt         = Pickup Date/Time
	$dt         = Dropoff Date/Time
	$dc         = Discount Code
	$rate       = Rate
	$class      = Class Code
	$extraCodes = Options
	$code       = Resrvation code (starting with WZV...)
	*/
	public function reserve( $pl, $dl, $pt, $dt, $rate, $class, $form, $discount = false, array $extraCodes = [], $code = false, $prepaid = false, $deposit = 0 ) {
		return $this->response( $this->_request->addrez( $pl, $dl, $pt, $dt, $rate, $class, $form, $discount, $extraCodes, $code, $prepaid, $deposit ) );
	}

	public function cancel( $code ) { // $code = reservation code
		return $this->response( $this->_request->reqcan( $code ) ); # Dev note 2019: reqcan = Request Cancellation?
	}


	public function get_reservation( $code ) { // $code = reservation code
		$data = $this->response( $this->_request->reqrez( $code ) ); # Dev note 2019: reqrez = Request Reservation?
		if ( ! is_array( $data ) ) {
			return $data;
		}
		$pt = DateTime::createFromFormat( TRN_TIME_FORMAT, $data['step_1']['pickup_date'] . ' ' . $data['step_1']['pickup_time'] )->getTimestamp();
		$dt = DateTime::createFromFormat( TRN_TIME_FORMAT, $data['step_1']['dropoff_date'] . ' ' . $data['step_1']['dropoff_time'] )->getTimestamp();

		$data['step_2']['rate_data']   = $this->get_rate( $data['step_2']['rate_code'], $data['step_1']['pickup_location'], $data['step_1']['dropoff_location'], $pt, $dt, false, $data['step_1']['van_type'] );
		$data['step_3']['bill']        = $this->get_bill( $data['step_1']['pickup_location'], $data['step_1']['dropoff_location'], $pt, $dt, $data['step_2']['rate_data']['id'], $data['step_2']['rate_data']['class_code'], $data['step_3']['options'], $data['step_1']['discount_code'] );
		$data['step_4']['reservation'] = $code;

		if ( $data['status'] == 'ACTIVE' ) {
			$last = $pt - time();
			if ( $last <= 0 ) {
				$status = 'closed';
			} elseif ( $last <= 259200 ) { // 72 * 60 * 60
				$status = '72hours';
			} else {
				$status = 'open';
			}
		} else {
			$status = 'cancelled';
		}
		$data['status'] = $status;

		return $data;
	}

	public function get_rate( $rate, $pl, $dl, $pt, $dt, $discount = false, $classCode = false ) {
		return $this->response( $this->_request->reqrat( $pl, $dl, $pt, $dt, $rate, $discount, $classCode ), 'rates' )[0] ?: [];
	}

	public function get_bill( $pl, $dl, $pt, $dt, $rate, $class, array $extraCodes = [], $discount = false, $prepaid = false ) {
		return $this->response( $this->_request->reqbil( $pl, $dl, $pt, $dt, $rate, $class, $discount, $prepaid, $extraCodes ), 'bill' );
	}

	public function lastRequest() {
		return $this->_request->raw();
	}

	public function lastResponse() {
		return $this->_response->raw();
	}

}