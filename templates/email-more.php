<?php
/**
 * @var array $data
 * @var object $vehicle
 * @var array $bill
 */
?>
<html>
<body>
<b>Website information request</b><br>
<br>
Dear Zeeba, someone has requested more information about the following booking.<br>
<br>
<b>First Name:</b> <?= $form['first_name']; ?><br>
<b>Last Name:</b> <?= $form['last_name'] ; ?><br>
<b>Phone:</b> <?= $form['phone']; ?><br>
<b>Email:</b> <?= $form['email'] ?>;<br>
<b>Message:</b> <?= $form['message'] ?><br>
<b>Pickup:</b> <?php echo zeeba_get( 'step_1.pickup_location_text' ); ?> <b>@</b> <? echo zeeba_get( 'step_1.pickup_date' ); ?><br>
<b>Dropoff:</b> <?= zeeba_get( 'step_1.dropoff_location_text' ); ?> <b>@</b> <?= zeeba_get( 'step_1.dropoff_date' ); ?><br>
<b>Vehicle:</b> <?= get_the_title( $vehicle ) ?><br>
<b>Rate Code:</b> <?= zeeba_get( 'step_2.rate_data', false )['code'] ?><br>
<b>Rate ID:</b> <?= zeeba_get( 'step_2.rate', false ) ?><br>
<br>
<b>Booking Inquiry Details:</b><br>
<table><?php include(ZEEBAVAN_DIR . "templates/panel-booking-bill.php");?></table>
</body>
</html>