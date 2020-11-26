<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Titles</a>
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- title section -->
    <div class="bhoechie-tab-content active">
        <div class="form-group">
          <label for="step2_intro">Intro</label>
          <input type="text" class="form-control" id="step2_intro" name="step2_intro" value="<?php echo get_option('step2_intro'); ?>" />
        </div>
        <div class="form-group">
          <label for="step2_intro_text">Intro Text</label>
          <textarea type="text"  class="form-control" id="step2_intro_text" name="step2_intro_text" rows="5"><?php echo get_option('step2_intro_text'); ?></textarea>
        </div>
        <div class="form-group">
          <label for="step2_change_dates">Change booking dates</label>
          <input type="text"  class="form-control" id="step2_change_dates" name="step2_change_dates" value="<?php echo get_option('step2_change_dates'); ?>" />
        </div>
        <div class="form-group">
          <label for="step2_info_rental_rate">Info Rental Rate</label>
          <input type="text"  class="form-control" id="step2_info_rental_rate" name="step2_info_rental_rate" value="<?php echo get_option('step2_info_rental_rate'); ?>" />
        </div>
        <div class="form-group">
          <label for="step2_info_total">Info Total</label>
          <input type="text"  class="form-control" id="step2_info_total" name="step2_info_total" value="<?php echo get_option('step2_info_total'); ?>" />
        </div>
    </div>
</div>