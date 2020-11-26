<?php 
require_once(ZEEBAVAN_DIR . '_inc/class.booking.php');
$url = 'https://weblink.tsdasp.net/requests/service.svc/';
$trn_mn  = new trnManager($url, 'ZEB01', '42357', '42357', get_client_ip(), get_client_ip());

 ?>