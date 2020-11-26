<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
  <div class="list-group">
    <a href="#" class="list-group-item active text-center">Service Day & Time</a>
  </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">
    <!-- service day time section -->
    <div class="bhoechie-tab-content active">
      <div id="day-time-content-container">
        <h3>Select Service Day & Time</h3>
        <table class="table table-bordered table-striped">
          <tbody>
            <tr>
              <TH>Day</TH>
              <TH>From (Time)</TH>
              <TH>o (Time)</TH>
            </tr>
            <?php if(get_option('step5_rd_content_name')):
            foreach(get_option('step5_rd_content_name') as $key => $value): ?>
              <tr>
                <td>
                  <input type="checkbox" class="form-control highlight_checkbox" name="step5_rd_content_highlight_checkbox[]" value="1" <?php if(get_option('step5_rd_content_highlight')[$key] == 1)echo 'checked'; ?>/>
                  Day</td>
                <td><input type="text" class="form-control" name="step5_rd_content_value[]" value="<?php echo get_option('step5_rd_content_value')[$key]; ?>" /></td>
                <td>
                    <input type="text" class="form-control" name="step5_rd_content_value[]" value="<?php echo get_option('step5_rd_content_value')[$key]; ?>" /></td>
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
</div>