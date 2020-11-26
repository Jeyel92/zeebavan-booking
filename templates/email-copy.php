<?
/**
 * @var object $vehicle
 * @var array $bill
 */
?>
<html>
<body>
<b>Your Van Rental Price Quote</b><br>
<br>
Hey fellow van fan! Here's the price quote you requested from zeebavans.com.<br>
<br>
<b>Pickup:</b> <?php echo zeeba_get( 'step_1.pickup_location_text' ); ?> <b>@</b> <? echo zeeba_get( 'step_1.pickup_date' ); ?><br>
<b>Dropoff:</b> <?= zeeba_get( 'step_1.dropoff_location_text' ); ?> <b>@</b> <?= zeeba_get( 'step_1.dropoff_date' ); ?><br>
<b>Vehicle:</b> <?= get_the_title( $vehicle ) ?><br>
<br>
<b>Price Quote</b>
<table><?php include(ZEEBAVAN_DIR . "templates/panel-booking-bill.php");?></table>
<br>
If you have any questions, give us a call at 1 (800) 940-9332, or complete your reservation online at zeebavans.com<br>
<br>
-Zeeba Vans Team
</body>
</html>