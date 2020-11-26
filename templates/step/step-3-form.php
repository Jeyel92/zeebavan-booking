<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Titles</a>
    <a href="#" class="list-group-item text-center">Available Options</a>
    <a href="#" class="list-group-item text-center">Coverage Table</a>
    <a href="#" class="list-group-item text-center">Convenience & Safety Table</a>
    <a href="#" class="list-group-item text-center">Rates</a>
    <a href="#" class="list-group-item text-center">Cancelation</a>
    <a href="#" class="list-group-item text-center">Buttons</a>
    <a href="#" class="list-group-item text-center">Request More Information</a>
    <a href="#" class="list-group-item text-center">Email Cop</a>
    <a href="#" class="list-group-item text-center">Policies</a>
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- title section -->
    <div class="bhoechie-tab-content active">
        <div class="form-group">
          <label for="step3_intro">Intro</label>
          <input type="text" class="form-control" id="step3_intro" name="step3_intro" value="<?php echo get_option('step3_intro'); ?>" />
        </div>
        <div class="form-group">
          <label for="step3_intro_text">Intro Text</label>
          <textarea type="text"  class="form-control" id="step3_intro_text" name="step3_intro_text" rows="5"><?php echo get_option('step3_intro_text'); ?></textarea>
        </div>
        <hr>
        <h3>Modified Intro</h3>
        <div class="box">
          <div class="form-group">
            <label for="step3_modified_into">Intro</label>
            <input type="text" class="form-control" id="step3_modified_into" name="step3_modified_into" value="<?php echo get_option('step3_modified_into'); ?>" />
          </div>
          <div class="form-group">
            <label for="step3_modified_intro_text">Intro Text</label>
            <textarea type="text"  class="form-control" id="step3_modified_intro_text" name="step3_modified_intro_text" rows="5"><?php echo get_option('step3_modified_intro_text'); ?></textarea>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label for="step3_change_dates">Change booking dates</label>
          <input type="text"  class="form-control" id="step3_change_dates" name="step3_change_dates" value="<?php echo get_option('step3_change_dates'); ?>" />
        </div>
    </div>
    <!-- Available Options bundle section -->
    <div class="bhoechie-tab-content">
        <div id="more-bundle-container">
            <h3>Bundles</h3>
            <?php if(get_option('bundle_name')):
                 foreach(get_option('bundle_name') as $key => $value): ?>
                <div class="more-bundle-content">
                    <div class="form-group">
                        <label for="bundle_name">Name</label>
                        <input type="text" class="form-control" name="bundle_name[]" value="<?php echo get_option('bundle_name')[$key]; ?>" />
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 col-xs-12">
                            <label for="bundle_code">Codes</label>
                            <small>SD option codes separated with space</small>
                            <input type="text"  class="form-control" name="bundle_code[]" value="<?php echo get_option('bundle_code')[$key]; ?>" />
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label for="bundle_price">Price</label>
                            <small>(per day)</small>
                            <input type="text" class="form-control" name="bundle_price[]" value="<?php echo get_option('bundle_price')[$key]; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bundle_tooltip">Tooltips</label>
                        <textarea class="form-control" name="bundle_tooltip[]" rows="5"><?php echo get_option('bundle_tooltip')[$key]; ?> </textarea>
                    </div>
                    <a class="button button-danger remove-btn">Remove Row</a>
                </div>
            <?php endforeach; endif?>
        </div>
        <hr>
        <a class="button button-primary" id="bundle-add-btn">Add Row</a>
        <br/>
        <br/>
        <hr>
        <div id="more-rename-container">
            <h3>Renaming Options</h3>
            <div class="more-rename-content">
                <div class="form-group row">
                    <div class="col-xs-6"><label for="name">Code</label></div>
                    <div class="col-xs-6"><label for="name">Text</label></div>
                </div>
            </div>
            <?php if(get_option('renaming_options_code')):
                 foreach(get_option('renaming_options_code') as $key => $value): ?>
            <div class="more-rename-content">
                <div class="form-group row">
                    <div class="col-xs-6">
                        <input type="text" class="form-control" name="renaming_options_code[]" value="<?php echo get_option('renaming_options_code')[$key]; ?>" />
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control" name="renaming_options_text[]" value="<?php echo get_option('renaming_options_text')[$key]; ?>" />
                    </div>
                </div>
                <a class="button button-danger remove-btn">Remove Row</a>
            </div>
            <?php endforeach; endif;?>
        </div>
        <hr>
        <a class="button button-primary" id="rename-add-btn">Add Row</a>
        <br/><br/>
        <div class="form-group">
            <label for="hidden_options">Hidden options</label>
            <small>TSD option codes separated by space</small>
            <input type="text" class="form-control" id="hidden_options" name="hidden_options" value="<?php echo get_option('hidden_options'); ?>" />
        </div>
    </div>

    <!-- coverage table section-->
    <div class="bhoechie-tab-content">
        <div class="form-group">
            <label for="options_col_1">Option heading</label>
            <input type="text" class="form-control" id="options_col_1" name="options_col_1" value="<?php echo get_option('options_col_1'); ?>" />
        </div>
        <div class="form-group">
            <label for="options_col_2">Price heading</label>
            <input type="text"  class="form-control" id="options_col_2" name="options_col_2" value="<?php echo get_option('options_col_2'); ?>" />
        </div>
        <div class="form-group">
            <label for="options_col_3">Add/Remove heading</label>
            <input type="text"  class="form-control" id="options_col_3" name="options_col_3" value="<?php echo get_option('options_col_3'); ?>" />
        </div>
        <div class="form-group">
            <label for="options_empty">Not available for one day rentals</label>
            <input type="text"  class="form-control" id="options_empty" name="options_empty" value="<?php echo get_option('options_empty'); ?>" />
        </div>
    </div>
    <!-- coverage safety section-->
    <div class="bhoechie-tab-content"></div>
    <!-- rates section -->
    <div class="bhoechie-tab-content">
        <label for="rates_heading">Heading</label>
        <input type="text" class="form-control" id="rates_heading" name="rates_heading" value="<?php echo get_option('rates_heading'); ?>" />

        <label for="rates_warning">Warning</label>
        <input type="text"  class="form-control" id="rates_warning" name="rates_warning" value="<?php echo get_option('rates_warning'); ?>" />

        <label for="rates_col_1">Details heading</label>
        <input type="text"  class="form-control" id="rates_col_1" name="rates_col_1" value="<?php echo get_option('rates_col_1'); ?>" />

        <label for="rates_col_2">Cost heading</label>
        <input type="text"  class="form-control" id="rates_col_2" name="rates_col_2" value="<?php echo get_option('rates_col_2'); ?>" />

        <label for="rates_total">Total label</label>
        <input type="text"  class="form-control" id="rates_total" name="rates_total" value="<?php echo get_option('rates_total'); ?>" />

        <label for="calculate_text">Calculate Button Text</label>
        <input type="text"  class="form-control" id="calculate_text" name="calculate_text" value="<?php echo get_option('calculate_text'); ?>" />

        <label for="recalculate_text">Recalculate Button Text</label>
        <input type="text"  class="form-control" id="recalculate_text" name="recalculate_text" value="<?php echo get_option('recalculate_text'); ?>" />
        <br/>
    </div>
    <!-- cancelation section -->
    <div class="bhoechie-tab-content">
        <div class="form-group">
            <label for="cancelation_text">Text</label>
            <textarea class="form-control" id="cancelation_text" name="cancelation_text" ><?php echo get_option('cancelation_text'); ?> </textarea>
        </div>
    </div>
    <!-- buttons section -->
    <div class="bhoechie-tab-content">
        <div class="form-group">
            <label for="btn_request_more">Request More Information</label>
            <input type="text" class="form-control" id="btn_request_more" name="btn_request_more" value="<?php echo get_option('btn_request_more'); ?>" />
        </div>
        <div class="form-group">
            <label for="btn_email_copy">Email Copy</label>
            <input type="text"  class="form-control" id="btn_email_copy" name="btn_email_copy" value="<?php echo get_option('btn_email_copy'); ?>" />
        </div>
        <div class="form-group">
            <label for="btn_submit">Submit</label>
            <input type="text"  class="form-control" id="btn_submit" name="btn_submit" value="<?php echo get_option('btn_submit'); ?>" />
        </div>
    </div>
    <!-- Request More Information section -->
    <div class="bhoechie-tab-content">
        <label for="rm_heading">Heading</label>
        <input type="text" class="form-control" id="rm_heading" name="rm_heading" value="<?php echo get_option('rm_heading'); ?>" />

        <label for="rm_first_name">First Name</label>
        <input type="text"  class="form-control" id="rm_first_name" name="rm_first_name" value="<?php echo get_option('rm_first_name'); ?>" />

        <label for="rm_last_name">Last Name</label>
        <input type="text"  class="form-control" id="rm_last_name" name="rm_last_name" value="<?php echo get_option('rm_last_name'); ?>" />

        <label for="rm_phone_number">Phone Number</label>
        <input type="text"  class="form-control" id="rm_phone_number" name="rm_phone_number" value="<?php echo get_option('rm_phone_number'); ?>" />

        <label for="rm_email">Email</label>
        <input type="text"  class="form-control" id="rm_email" name="rm_email" value="<?php echo get_option('rm_email'); ?>" />

        <label for="rm_message">Message</label>
        <input type="text"  class="form-control" id="rm_message" name="rm_message" value="<?php echo get_option('rm_message'); ?>" />

        <label for="rm_submit">Submit</label>
        <input type="text"  class="form-control" id="rm_submit" name="rm_submit" value="<?php echo get_option('rm_submit'); ?>" />
    </div>
    <!-- Email cop section -->
    <div class="bhoechie-tab-content">
        <label for="ec_heading">Heading</label>
        <input type="text" class="form-control" id="ec_heading" name="ec_heading" value="<?php echo get_option('ec_heading'); ?>" />

        <label for="ec_email">Email</label>
        <input type="text"  class="form-control" id="ec_email" name="ec_email" value="<?php echo get_option('ec_email'); ?>" />

        <label for="ec_submit">Submit</label>
        <input type="text"  class="form-control" id="ec_submit" name="ec_submit" value="<?php echo get_option('ec_submit'); ?>" />
    </div>
    <!-- policy section -->
    <div class="bhoechie-tab-content">
        <div id="more-policy-container">
            <div class="form-group">
                <label for="step3_policy_heading">Heading</label>
                <input type="text" class="form-control" id="step3_policy_heading" name="step3_policy_heading" value="<?php echo get_option('step3_policy_heading'); ?>" />
            </div>
            <h3>List</h3>
            <?php if(get_option('step3_policy_list')):
                 foreach(get_option('step3_policy_list') as $key => $value): ?>
                <div class="more-policy-content row">
                    <div class="col-md-10 col-xs-10 form-group">
                        <input type="text" class="form-control" name="step3_policy_list[]" value="<?php echo get_option('step3_policy_list')[$key]; ?>" />
                    </div>
                    <a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a>
                    
                </div>
            <?php endforeach; endif; ?>
        </div>
        <hr>
        <a class="button button-primary" id="policy-add-btn">Add Row</a>
        <br/>
        <hr>
        <div class="form-group row">
            <div class="col-md-6 col-xs-12">
                <label for="step3_policy_download_text">Download Text</label>
                <input type="text"  class="form-control" id="step3_policy_download_text" name="step3_policy_download_text" value="<?php echo get_option('step3_policy_download_text'); ?>" />
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="step3_policy_download_url">Download URL</label>
                <input type="text"  class="form-control" id="step3_policy_download_url" name="step3_policy_download_url" value="<?php echo get_option('step3_policy_download_url'); ?>" />
            </div>
        </div>
    </div>
</div>