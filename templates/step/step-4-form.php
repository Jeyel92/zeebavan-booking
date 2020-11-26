<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Titles</a>
    <a href="#" class="list-group-item text-center">Renter Information</a>
    <a href="#" class="list-group-item text-center">Driver Details</a>
    <a href="#" class="list-group-item text-center">Payment Details</a>
    <a href="#" class="list-group-item text-center">Further Information</a>
    <a href="#" class="list-group-item text-center">Document Upload</a>
    <a href="#" class="list-group-item text-center">Agreement</a>
    <a href="#" class="list-group-item text-center">Other</a>
    <a href="#" class="list-group-item text-center">Policies</a>
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- title section -->
    <div class="bhoechie-tab-content active">
        <div class="form-group">
          <label for="step4_intro">Intro</label>
          <input type="text" class="form-control" id="step4_intro" name="step4_intro" value="<?php echo get_option('step4_intro'); ?>" />
        </div>
        <div class="form-group">
          <label for="step4_intro_text">Intro Text</label>
          <textarea type="text"  class="form-control" id="step4_intro_text" name="step4_intro_text" rows="5"><?php echo get_option('step4_intro_text'); ?></textarea>
        </div>
        <hr>
        <h3>Modified Intro</h3>
        <div class="box">
          <div class="form-group">
            <label for="step4_modified_into">Intro</label>
            <input type="text" class="form-control" id="step4_modified_into" name="step4_modified_into" value="<?php echo get_option('step4_modified_into'); ?>" />
          </div>
          <div class="form-group">
            <label for="step4_modified_intro_text">Intro Text</label>
            <textarea type="text"  class="form-control" id="step4_modified_intro_text" name="step4_modified_intro_text" rows="5"><?php echo get_option('step4_modified_intro_text'); ?></textarea>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label for="step4_change_dates">Change booking dates</label>
          <input type="text"  class="form-control" id="step4_change_dates" name="step4_change_dates" value="<?php echo get_option('step4_change_dates'); ?>" />
        </div>
    </div>
    <!-- Renter Information section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_ri_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_ri_heading" name="step4_ri_heading" value="<?php echo get_option('step4_ri_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_first_name">First Name</label>
              <input type="text"  class="form-control" id="step4_ri_first_name" name="step4_ri_first_name" value="<?php echo get_option('step4_ri_first_name'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_last_name">Last Name</label>
              <input type="text"  class="form-control" id="step4_ri_last_name" name="step4_ri_last_name" value="<?php echo get_option('step4_ri_last_name'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_company_name">Company Name</label>
              <input type="text"  class="form-control" id="step4_ri_company_name" name="step4_ri_company_name" value="<?php echo get_option('step4_ri_company_name'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_phone_number">Phone Number</label>
              <input type="text"  class="form-control" id="step4_ri_phone_number" name="step4_ri_phone_number" value="<?php echo get_option('step4_ri_phone_number'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_email">Email</label>
              <input type="text"  class="form-control" id="step4_ri_email" name="step4_ri_email" value="<?php echo get_option('step4_ri_email'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_email_confirm">Email Confirm</label>
              <input type="text"  class="form-control" id="step4_ri_email_confirm" name="step4_ri_email_confirm" value="<?php echo get_option('step4_ri_email_confirm'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_country">Country</label>
              <input type="text"  class="form-control" id="step4_ri_country" name="step4_ri_country" value="<?php echo get_option('step4_ri_country'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_ri_address">Address</label>
              <input type="text"  class="form-control" id="step4_ri_address" name="step4_ri_address" value="<?php echo get_option('step4_ri_address'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-4 col-xs-12">
              <label for="step4_ri_city">City</label>
              <input type="text"  class="form-control" id="step4_ri_city" name="step4_ri_city" value="<?php echo get_option('step4_ri_city'); ?>" />
          </div>
          <div class="col-md-4 col-xs-12">
              <label for="step4_ri_state">State</label>
              <input type="text"  class="form-control" id="step4_ri_state" name="step4_ri_state" value="<?php echo get_option('step4_ri_state'); ?>" />
          </div>
          <div class="col-md-4 col-xs-12">
              <label for="step4_ri_zip">Zip</label>
              <input type="text"  class="form-control" id="step4_ri_zip" name="step4_ri_zip" value="<?php echo get_option('step4_ri_zip'); ?>" />
          </div>
      </div>
    </div>
    <!-- Driver Details section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_dd_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_dd_heading" name="step4_dd_heading" value="<?php echo get_option('step4_dd_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_dd_tip">Tip</label>
              <textarea type="text"  class="form-control" id="step4_dd_tip" name="step4_dd_tip" rows="5"><?php echo get_option('step4_dd_tip'); ?></textarea>
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_a_driver_first_name">Additional Driver First Name</label>
              <input type="text"  class="form-control" id="step4_a_driver_first_name" name="step4_a_driver_first_name" value="<?php echo get_option('step4_a_driver_first_name'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_a_driver_last_name">Additional Driver Last Name</label>
              <input type="text"  class="form-control" id="step4_a_driver_last_name" name="step4_a_driver_last_name" value="<?php echo get_option('step4_a_driver_last_name'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_a_driver_add">Add additional driver</label>
              <input type="text"  class="form-control" id="step4_a_driver_add" name="step4_a_driver_add" value="<?php echo get_option('step4_a_driver_add'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_a_driver_allow">Allow pickup</label>
              <input type="text"  class="form-control" id="step4_a_driver_allow" name="step4_a_driver_allow" value="<?php echo get_option('step4_a_driver_allow'); ?>" />
          </div>
      </div>
    </div>
    <!-- payment details section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_pd_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_pd_heading" name="step4_pd_heading" value="<?php echo get_option('step4_pd_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_pd_text">Text</label>
              <input type="text"  class="form-control" id="step4_pd_text" name="step4_pd_text" value="<?php echo get_option('step4_pd_text'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_pd_card_number">Card Number</label>
              <input type="text"  class="form-control" id="step4_pd_card_number" name="step4_pd_card_number" value="<?php echo get_option('step4_pd_card_number'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_pd_card_name">Name on Card</label>
              <input type="text"  class="form-control" id="step4_pd_card_name" name="step4_pd_card_name" value="<?php echo get_option('step4_pd_card_name'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-4 col-xs-12">
              <label for="step4_pd_card_valid_until">Valid untill</label>
              <input type="text"  class="form-control" id="step4_pd_card_valid_until" name="step4_pd_card_valid_until" value="<?php echo get_option('step4_pd_card_valid_until'); ?>" />
          </div>
          <div class="col-md-4 col-xs-12">
              <label for="step4_pd_card_month">Month</label>
              <input type="text"  class="form-control" id="step4_pd_card_month" name="step4_pd_card_month" value="<?php echo get_option('step4_pd_card_month'); ?>" />
          </div>
          <div class="col-md-4 col-xs-12">
              <label for="step4_pd_card_year">Year</label>
              <input type="text"  class="form-control" id="step4_pd_card_year" name="step4_pd_card_year" value="<?php echo get_option('step4_pd_card_year'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_pd_card_cvv">CVV</label>
              <input type="text"  class="form-control" id="step4_pd_card_cvv" name="step4_pd_card_cvv" value="<?php echo get_option('step4_pd_card_cvv'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_pd_card_cvv_help">CVV Help</label>
              <input type="text"  class="form-control" id="step4_pd_card_cvv_help" name="step4_pd_card_cvv_help" value="<?php echo get_option('step4_pd_card_cvv_help'); ?>" />
          </div>
      </div>
    </div>
    <!-- Further Information section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_fi_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_fi_heading" name="step4_fi_heading" value="<?php echo get_option('step4_fi_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_flight_enabled_title">Flight Checkbox Heading</label>
              <input type="text"  class="form-control" id="step4_fi_flight_enabled_title" name="step4_fi_flight_enabled_title" value="<?php echo get_option('step4_fi_flight_enabled_title'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_flight_enabled">Flight Checkbox Text</label>
              <input type="text"  class="form-control" id="step4_fi_flight_enabled" name="step4_fi_flight_enabled" value="<?php echo get_option('step4_fi_flight_enabled'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_flight_number">Flight Number</label>
              <input type="text"  class="form-control" id="step4_fi_flight_number" name="step4_fi_flight_number" value="<?php echo get_option('step4_fi_flight_number'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_flight_airline">Airline Name</label>
              <input type="text"  class="form-control" id="step4_fi_flight_airline" name="step4_fi_flight_airline" value="<?php echo get_option('step4_fi_flight_airline'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_another_country">Another country</label>
              <input type="text"  class="form-control" id="step4_fi_another_country" name="step4_fi_another_country" value="<?php echo get_option('step4_fi_another_country'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_fi_special">Special Remarks</label>
              <input type="text"  class="form-control" id="step4_fi_special" name="step4_fi_special" value="<?php echo get_option('step4_fi_special'); ?>" />
          </div>
      </div>
    </div>
    <!-- Document Upload section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_du_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_du_heading" name="step4_du_heading" value="<?php echo get_option('step4_du_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_du_text">Text</label>
              <textarea type="text"  class="form-control" id="step4_du_text" name="step4_du_text" rows="5"><?php echo get_option('step4_du_text'); ?></textarea>
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="du_warning">Warning</label>
              <textarea type="text"  class="form-control" id="du_warning" name="du_warning" rows="3"><?php echo get_option('du_warning'); ?></textarea>
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_du_button">Button Text</label>
              <input type="text"  class="form-control" id="step4_du_button" name="step4_du_button" value="<?php echo get_option('step4_du_button'); ?>" />
          </div>
      </div>
    </div>
    <!-- Agreement section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_ag_heading">Heading</label>
              <input type="text"  class="form-control" id="step4_ag_heading" name="step4_ag_heading" value="<?php echo get_option('step4_ag_heading'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_ag_modal_heading">Modal heading</label>
              <input type="text"  class="form-control" id="step4_ag_modal_heading" name="step4_ag_modal_heading" value="<?php echo get_option('step4_ag_modal_heading'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_ag_primary_label">Primary Checkbox Label</label>
              <input type="text"  class="form-control" id="step4_ag_primary_label" name="step4_ag_primary_label" value="<?php echo get_option('step4_ag_primary_label'); ?>" />
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_ag_secondary_label">Secondary Checkbox Label</label>
              <input type="text"  class="form-control" id="step4_ag_secondary_label" name="step4_ag_secondary_label" value="<?php echo get_option('step4_ag_secondary_label'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step4_ag_text">Agreement Text</label>
              <textarea type="text"  class="form-control" id="step4_ag_text" name="step4_ag_text" rows="5"><?php echo get_option('step4_ag_text'); ?></textarea>
          </div>
      </div>
    </div>
    <!-- other section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_total_price">Total Price</label>
              <input type="text"  class="form-control" id="step4_total_price" name="step4_total_price" value="<?php echo get_option('step4_total_price'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_rental_period">Rental Period</label>
              <input type="text"  class="form-control" id="step4_rental_period" name="step4_rental_period" value="<?php echo get_option('step4_rental_period'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step4_submit_text">Submit Text</label>
              <input type="text"  class="form-control" id="step4_submit_text" name="step4_submit_text" value="<?php echo get_option('step4_submit_text'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step4_submit_note">Submit Note</label>
              <input type="text"  class="form-control" id="step4_submit_note" name="step4_submit_note" value="<?php echo get_option('step4_submit_note'); ?>" />
          </div>
      </div>
    </div>
    <!-- policy section -->
    <div class="bhoechie-tab-content">
        <div id="step4-more-policy-container">
            <div class="form-group">
                <label for="step4_policy_heading">Heading</label>
                <input type="text" class="form-control" id="step4_policy_heading" name="step4_policy_heading" value="<?php echo get_option('step4_policy_heading'); ?>" />
            </div>
            <h3>List</h3>
            <?php if(get_option('step4_policy_list')):
              foreach(get_option('step4_policy_list') as $key => $value): ?>
                <div class="step4-more-policy-content row">
                    <div class="col-md-10 col-xs-10 form-group">
                        <input type="text" class="form-control" name="step4_policy_list[]" value="<?php echo get_option('step4_policy_list')[$key]; ?>" />
                    </div>
                    <a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a>
                    
                </div>
            <?php endforeach; endif; ?>
        </div>
        <hr>
        <a class="button button-primary" id="step4-policy-add-btn">Add Row</a>
        <br/>
        <hr>
        <div class="form-group row">
            <div class="col-md-6 col-xs-12">
                <label for="step4_policy_download_text">Download Text</label>
                <input type="text"  class="form-control" id="step4_policy_download_text" name="step4_policy_download_text" value="<?php echo get_option('step4_policy_download_text'); ?>" />
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="step4_policy_download_url">Download URL</label>
                <input type="text"  class="form-control" id="step4_policy_download_url" name="step4_policy_download_url" value="<?php echo get_option('step4_policy_download_url'); ?>" />
            </div>
        </div>
    </div>
</div>