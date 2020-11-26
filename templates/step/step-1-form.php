<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Titles</a>
    <a href="#" class="list-group-item text-center">Form</a>
    <!-- <a href="#" class="list-group-item text-center">Slider</a> -->
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- title section -->
    <div class="bhoechie-tab-content active">
        <div class="form-group">
          <label for="step1_intro">Intro</label>
          <input type="text" class="form-control" id="step1_intro" name="step1_intro" value="<?php echo get_option('step1_intro'); ?>" />
        </div>
        <div class="form-group">
          <label for="step1_intro_text">Intro Text</label>
          <textarea type="text"  class="form-control" id="step1_intro_text" name="step1_intro_text" rows="5"><?php echo get_option('step1_intro_text'); ?></textarea>
        </div>
        <hr>
        <h3>Modified Intro</h3>
        <div class="box">
          <div class="form-group">
            <label for="step1_modified_into">Intro</label>
            <input type="text" class="form-control" id="step1_modified_into" name="step1_modified_into" value="<?php echo get_option('step1_modified_into'); ?>" />
          </div>
          <div class="form-group">
            <label for="step1_modified_intro_text">Intro Text</label>
            <textarea type="text"  class="form-control" id="step1_modified_intro_text" name="step1_modified_intro_text" rows="5"><?php echo get_option('step1_modified_intro_text'); ?></textarea>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label for="book_tab">Book tab</label>
          <input type="text"  class="form-control" id="step1_book_tab" name="step1_book_tab" value="<?php echo get_option('step1_book_tab'); ?>" />
        </div>
        <div class="form-group">
          <label for="step1_modify_tab">Modify tab</label>
          <input type="text"  class="form-control" id="step1_modify_tab" name="step1_modify_tab" value="<?php echo get_option('step1_modify_tab'); ?>" />
        </div>
    </div>
    <!-- form section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step1_pick-up_date_placeholder">Pick-up Date placeholder</label>
              <input type="text"  class="form-control" id="step1_pick-up_date_placeholder" name="step1_pick-up_date_placeholder" value="<?php echo get_option('step1_pick-up_date_placeholder'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step1_pick-up_time_placeholder">Pick-up Time placeholder</label>
              <input type="text"  class="form-control" id="step1_pick-up_time_placeholder" name="step1_pick-up_time_placeholder" value="<?php echo get_option('step1_pick-up_time_placeholder'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step1_drop-off_date_placeholder">Drop-off Date placeholder</label>
              <input type="text"  class="form-control" id="step1_drop-off_date_placeholder" name="step1_drop-off_date_placeholder" value="<?php echo get_option('step1_drop-off_date_placeholder'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step1_drop-off_time_placeholder">Drop-off Time placeholder</label>
              <input type="text"  class="form-control" id="step1_drop-off_time_placeholder" name="step1_drop-off_time_placeholder" value="<?php echo get_option('step1_drop-off_time_placeholder'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step1_pick-up_location_placeholder">Pick-up Location placeholder</label>
              <input type="text"  class="form-control" id="step1_pick-up_location_placeholder" name="step1_pick-up_location_placeholder" value="<?php echo get_option('step1_pick-up_location_placeholder'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step1_drop-off_location_placeholder">Drop-off Location placeholder</label>
              <input type="text"  class="form-control" id="step1_drop-off_location_placeholder" name="step1_drop-off_location_placeholder" value="<?php echo get_option('step1_drop-off_location_placeholder'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step1_select_van_placeholder">Select Van placeholder</label>
              <input type="text"  class="form-control" id="step1_select_van_placeholder" name="step1_select_van_placeholder" value="<?php echo get_option('step1_select_van_placeholder'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step1_different_location">Different location checkbox</label>
              <input type="text"  class="form-control" id="step1_different_location" name="step1_different_location" value="<?php echo get_option('step1_different_location'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step1_discount_label">Discount label</label>
              <input type="text"  class="form-control" id="step1_discount_label" name="step1_discount_label" value="<?php echo get_option('step1_discount_label'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step1_discount_placeholder">Discount placeholder</label>
              <input type="text"  class="form-control" id="step1_discount_placeholder" name="step1_discount_placeholder" value="<?php echo get_option('step1_discount_placeholder'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="reservation_placeholder">Reservation placeholder</label>
              <input type="text"  class="form-control" id="reservation_placeholder" name="reservation_placeholder" value="<?php echo get_option('reservation_placeholder'); ?>" />
          </div>
      </div>
    </div>
</div>