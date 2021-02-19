<?php
global $options;
if (get_option('name') !== FALSE) {
  $value = get_option('name');
} else {
  $value = '';
}
print_r($value);
?>

<?php
include ZEEBAVAN_DIR . '_inc/steps/start.php';
?>

<div class="stepper-container" style="background-color: #ff9dd4;">
  <!-- Horizontal Steppers -->
  <div class="row">
    <div class="col-md-12" style="max-width: 800px; margin: auto;">
      <!-- Stepers Wrapper -->
      <ul class="stepper stepper-horizontal" style="margin-bottom: 0px;">
        <!-- First Step -->
        <li id="stepper1" class="active">
          <a href="#!">
            <span class="circle">1</span>
            <span class="label"><?php echo get_option('step1_intro'); ?></span>
          </a>
        </li>
        <!-- Second Step -->
        <li id="stepper2" class="default">
          <a href="#!">
            <span class="circle">2</span>
            <span class="label"><?php echo get_option('step2_intro'); ?></span>
          </a>
        </li>
        <!-- Third Step -->
        <li id="stepper3" class="default">
          <a href="#!">
            <span class="circle">3</span>
            <span class="label"><?php echo get_option('step3_intro'); ?></span>
          </a>
        </li>
        <!-- Fourth Step -->
        <li id="stepper4" class="default">
          <a href="#!">
            <span class="circle">4</span>
            <span class="label"><?php echo get_option('step4_intro'); ?></span>
          </a>
        </li>
      </ul>
      <!-- /.Stepers Wrapper -->

    </div>
  </div>
  <!-- /.Horizontal Steppers -->
</div>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$url = 'https://weblink.tsdasp.net/requests/service.svc/';
$trn_mn  = new trnManager($url, 'ZEB01', get_option('booking_api_username'), get_option('booking_api_password'), get_client_ip(), get_client_ip());


?>
