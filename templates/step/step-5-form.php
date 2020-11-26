<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Titles</a>
    <a href="#" class="list-group-item text-center">Step Info</a>
    <a href="#" class="list-group-item text-center">Renter Details</a>
    <a href="#" class="list-group-item text-center">Rate Quote</a>
    <a href="#" class="list-group-item text-center">Contacts</a>
    <a href="#" class="list-group-item text-center">Policies</a>
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- title section -->
    <div class="bhoechie-tab-content active">
        <div class="form-group">
          <label for="step5_intro">Intro</label>
          <input type="text" class="form-control" id="step5_intro" name="step5_intro" value="<?php echo get_option('step5_intro'); ?>" />
        </div>
        <div class="form-group">
          <label for="step5_intro_text">Intro Text</label>
          <textarea type="text"  class="form-control" id="step5_intro_text" name="step5_intro_text" rows="5"><?php echo get_option('step5_intro_text'); ?></textarea>
        </div>
        <hr>
        <h3>Modified Intro</h3>
        <div class="box">
          <div class="form-group">
            <label for="step5_modified_intro">Intro</label>
            <input type="text" class="form-control" id="step5_modified_intro" name="step5_modified_intro" value="<?php echo get_option('step5_modified_intro'); ?>" />
          </div>
          <div class="form-group">
            <label for="step5_modified_intro_text">Intro Text</label>
            <textarea type="text"  class="form-control" id="step5_modified_intro_text" name="step5_modified_intro_text" rows="5"><?php echo get_option('step5_modified_intro_text'); ?></textarea>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label for="step5_confirm_text">Confirm Text</label>
          <input type="text"  class="form-control" id="step5_confirm_text" name="step5_confirm_text" value="<?php echo get_option('step5_confirm_text'); ?>" />
        </div>
    </div>
    <!-- Step Info section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step5_si_success">Success</label>
              <input type="text"  class="form-control" id="step5_si_success" name="step5_si_success" value="<?php echo get_option('step5_si_success'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_start">Start</label>
              <input type="text"  class="form-control" id="step5_si_start" name="step5_si_start" value="<?php echo get_option('step5_si_start'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_start_sub">Start Sub</label>
              <input type="text"  class="form-control" id="step5_si_start_sub" name="step5_si_start_sub" value="<?php echo get_option('step5_si_start_sub'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_view_modify">Modify</label>
              <input type="text"  class="form-control" id="step5_si_view_modify" name="step5_si_view_modify" value="<?php echo get_option('step5_si_view_modify'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_view_modify_sub">Modify Sub</label>
              <input type="text"  class="form-control" id="step5_si_view_modify_sub" name="step5_si_view_modify_sub" value="<?php echo get_option('step5_si_view_modify_sub'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_print">Print</label>
              <input type="text"  class="form-control" id="step5_si_print" name="step5_si_print" value="<?php echo get_option('step5_si_print'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_si_print_sub">Print Sub</label>
              <input type="text"  class="form-control" id="step5_si_print_sub" name="step5_si_print_sub" value="<?php echo get_option('step5_si_print_sub'); ?>" />
          </div>
      </div>
    </div>
    <!-- Renter Details section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step5_rd_heading">Heading</label>
              <input type="text"  class="form-control" id="step5_rd_heading" name="step5_rd_heading" value="<?php echo get_option('step5_rd_heading'); ?>" />
          </div>
      </div>
      <div id="step5-more-content-container">
        <h3>Content</h3>
        <table class="table table-bordered table-striped">
          <tbody>
            <tr>
              <TH>Name</TH>
              <TH>Value</TH>
              <TH>Hightlight</TH>
              <TH></TH>
            </tr>
            <?php if(get_option('step5_rd_content_name')):
            foreach(get_option('step5_rd_content_name') as $key => $value): ?>
              <tr>
                <td><input type="text" class="form-control" name="step5_rd_content_name[]" value="<?php echo get_option('step5_rd_content_name')[$key]; ?>" /></td>
                <td><input type="text" class="form-control" name="step5_rd_content_value[]" value="<?php echo get_option('step5_rd_content_value')[$key]; ?>" /></td>
                <td>
                    <input type="checkbox" class="form-control highlight_checkbox" name="step5_rd_content_highlight_checkbox[]" value="1" <?php if(get_option('step5_rd_content_highlight')[$key] == 1)echo 'checked'; ?>/>
                    <input type="hidden" class="form-control" name="step5_rd_content_highlight[]" value="<?php echo get_option('step5_rd_content_highlight')[$key]; ?>" />
                </td>
                <td><a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a></td>
              </tr>
            <?php endforeach; endif;?>
          </tbody>
        </table>
        <hr>
        <a class="button button-primary" id="step5-content-add-btn">Add Row</a>
        <br/><br/>
      </div>
    </div>
    <!-- Rate Quote section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_heading">Heading</label>
              <input type="text"  class="form-control" id="step5_rq_heading" name="step5_rq_heading" value="<?php echo get_option('step5_rq_heading'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_warning">Warning</label>
              <input type="text"  class="form-control" id="step5_rq_warning" name="step5_rq_warning" value="<?php echo get_option('step5_rq_warning'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_col1_details">Details Column</label>
              <input type="text"  class="form-control" id="step5_rq_col1_details" name="step5_rq_col1_details" value="<?php echo get_option('step5_rq_col1_details'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_col2_cost">Cost Column</label>
              <input type="text"  class="form-control" id="step5_rq_col2_cost" name="step5_rq_col2_cost" value="<?php echo get_option('step5_rq_col2_cost'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_rental_rate">Rental Rate</label>
              <input type="text"  class="form-control" id="step5_rq_rental_rate" name="step5_rq_rental_rate" value="<?php echo get_option('step5_rq_rental_rate'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_taxes">Taxes and Fees</label>
              <input type="text"  class="form-control" id="step5_rq_taxes" name="step5_rq_taxes" value="<?php echo get_option('step5_rq_taxes'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_mileage">Mileage</label>
              <input type="text"  class="form-control" id="step5_rq_mileage" name="step5_rq_mileage" value="<?php echo get_option('step5_rq_mileage'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_unlimited">Mileage Unlimited</label>
              <input type="text"  class="form-control" id="step5_rq_unlimited" name="step5_rq_unlimited" value="<?php echo get_option('step5_rq_unlimited'); ?>" />
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_subtotal">Subtotal</label>
              <input type="text"  class="form-control" id="step5_rq_subtotal" name="step5_rq_subtotal" value="<?php echo get_option('step5_rq_subtotal'); ?>" />
          </div>
          <div class="col-md-6 col-xs-12">
              <label for="step5_rq_total">Total</label>
              <input type="text"  class="form-control" id="step5_rq_total" name="step5_rq_total" value="<?php echo get_option('step5_rq_total'); ?>" />
          </div>
      </div>
    </div>
    <!-- Contacts section -->
    <div class="bhoechie-tab-content">
      <div class="form-group row">
          <div class="col-md-12 col-xs-12">
              <label for="step5_contacts_title">Title</label>
              <input type="text"  class="form-control" id="step5_contacts_title" name="step5_contacts_title" value="<?php echo get_option('step5_contacts_title'); ?>" />
          </div>
      </div>
      <div class="form-group">
        <label for="step5_contacts_content">Content</label>
        <textarea type="text"  class="form-control" id="step5_contacts_content" name="step5_contacts_content" rows="5"><?php echo get_option('step5_contacts_content'); ?></textarea>
      </div>
    </div>
    <!-- policy section -->
    <div class="bhoechie-tab-content">
        <div id="step5-more-policy-container">
            <div class="form-group">
                <label for="step5_policy_heading">Heading</label>
                <input type="text" class="form-control" id="step5_policy_heading" name="step5_policy_heading" value="<?php echo get_option('step5_policy_heading'); ?>" />
            </div>
            <h3>List</h3>
            <?php if(get_option('step5_policy_list')):
              foreach(get_option('step5_policy_list') as $key => $value): ?>
                <div class="step5-more-policy-content row">
                    <div class="col-md-10 col-xs-10 form-group">
                        <input type="text" class="form-control" name="step5_policy_list[]" value="<?php echo get_option('step5_policy_list')[$key]; ?>" />
                    </div>
                    <a class="button button-danger remove-btn" title="Remove row"><i class="glyphicon glyphicon-remove"></i></a>
                    
                </div>
            <?php endforeach; endif; ?>
        </div>
        <hr>
        <a class="button button-primary" id="step5-policy-add-btn">Add Row</a>
        <br/>
        <hr>
        <div class="form-group row">
            <div class="col-md-6 col-xs-12">
                <label for="step5_policy_download_text">Download Text</label>
                <input type="text"  class="form-control" id="step5_policy_download_text" name="step5_policy_download_text" value="<?php echo get_option('step5_policy_download_text'); ?>" />
            </div>
            <div class="col-md-6 col-xs-12">
                <label for="step5_policy_download_url">Download URL</label>
                <input type="text"  class="form-control" id="step5_policy_download_url" name="step5_policy_download_url" value="<?php echo get_option('step5_policy_download_url'); ?>" />
            </div>
        </div>
    </div>
</div>