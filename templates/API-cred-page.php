<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<h2>Zeeba API Configuration</h2>
<form method="post" action="options.php">
  <?php settings_fields( 'zeeba_api_options_group' ); ?>
  
    <div class="wrap">
      <div class="row">
          <div class="col-md-5 col-sm-12 bhoechie-tab-container">
            <div class="bhoechie-tab-content active">
              <div class="form-group">
                <label for="booking_api_username">API Customer Number</label>
                <input type="text"  class="form-control" id="booking_api_username" name="booking_api_username" value="<?php echo get_option('booking_api_username'); ?>" />
              </div>
              <div class="form-group">
                <label for="booking_api_password">API Pass Code</label>
                <input type="text"  class="form-control" id="booking_api_password" name="booking_api_password" value="<?php echo get_option('booking_api_password'); ?>" />
              </div>
              <div class="form-group">
                <?php  submit_button(); ?>
              </div>
            </div>
          </div>
      </div>
    </div>
</form>

