<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->
<h2>Zeeba Plugin Option Page</h2>
<form method="post" action="options.php">
  <?php settings_fields( 'zeeba_step_options_group' ); ?>
    <?php  submit_button(); ?>
    <div class="wrap">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link " id="step-1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step-1" aria-selected="true">Booking Step 1</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="step-2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step-2" aria-selected="false">Booking Step 2</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="step-3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step-3" aria-selected="false">Booking Step 3</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="step-4-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step-4" aria-selected="false">Booking Step 4</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="step-5-tab" data-toggle="tab" href="#step5" role="tab" aria-controls="step-5" aria-selected="false">Booking Step 5</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" id="general-settings-tab" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-settings" aria-selected="false">General Settings</a>
          </li> -->
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="step1" role="tabpanel" aria-labelledby="step-1-tab">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-11 col-xs-11 bhoechie-tab-container">
                        <?php include(ZEEBAVAN_DIR.'templates/step/step-1-form.php'); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step-2-tab">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-11 col-xs-11 bhoechie-tab-container">
                        <?php include(ZEEBAVAN_DIR.'templates/step/step-2-form.php'); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step-3-tab">
              <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-11 col-xs-11 bhoechie-tab-container">
                        <?php include(ZEEBAVAN_DIR.'templates/step/step-3-form.php'); ?>
                    </div>
              </div>
            </div>
            <div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step-4-tab">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-11 col-xs-11 bhoechie-tab-container">
                        <?php include(ZEEBAVAN_DIR.'templates/step/step-4-form.php'); ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="step5" role="tabpanel" aria-labelledby="step-5-tab">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-11 col-xs-11 bhoechie-tab-container">
                        <?php include(ZEEBAVAN_DIR.'templates/step/step-5-form.php'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

