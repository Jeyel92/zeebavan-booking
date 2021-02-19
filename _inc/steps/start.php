<div class="container-fluid step-1-container zeeba_form step" id="start_form_container">
    <div class="row">
        <div class="col-md-4">
            <div class="zeeba-lhg-single-input-group col-md-6">
                <label><?php echo get_option('step1_pick-up_location_placeholder'); ?></label>
                <input id="pickupStation" name="pick-up-location-search" class="form-control input-location" type="text" placeholder="Find a location" value="<?= zeeba_get('step_1.pickup_location_text'); ?>" />

                <input type="hidden" id="location" name="pick-up-location-id" value="<?php echo zeeba_get('step_1.pickup_location_select'); ?>" />
                <input type="hidden" id="location-code" name="pick-up-location-code" value="<?php echo zeeba_get('step_1.pickup_location'); ?>" />
            </div>
            <div class="zeeba-lhg-return-location-wrapper col-md-6">
                <div class="zeeba-lhg-return-location-checkbox">
                    <input id="zeeba-lhg-return-location" type="checkbox" id="return-loc" name="returnToPickupStation" <?php echo zeeba_get('step_1.different_location') == 'yes' ? 'checked' : ''; ?>>
                    <label for="zeeba-lhg-return-location"><?php echo get_option('step1_different_location'); ?></label>
                </div>

                <div class="zeeba-lhg-single-input-group zeeba-lhg-single-return-location-group">
                    <label><?php echo get_option('step1_drop-off_location_placeholder'); ?></label>


                    <input id="returnStation" name="drop-off-location-search" class="form-control input-location" type="text" placeholder="Find a location" value="<?= zeeba_get('step_1.dropoff_location_text'); ?>" />

                    <div class="zeeba-lhg-single-return-location-close">
                        <span>
                            <div class="zeeba-lhg-close-button">
                                <svg viewBox="0 0 32 32" fill="white" xmlns="http://www.w3.org/2000/svg">
                                    <polygon fill="#191919" fill-rule="nonzero" points="84 73.237 81.763 71 68.5 84.263 55.237 71 53 73.237 66.263 86.5 53 99.763 55.237 102 68.5 88.737 81.763 102 84 99.763 70.737 86.5" transform="translate(-53 -71)"></polygon>
                                </svg>
                            </div>
                        </span>
                    </div>
                    <input type="hidden" name="drop-off-location-id" disabled value="<?php echo zeeba_get('step_1.dropoff_location_select'); ?>" id="return-location" />
                    <input type="hidden" name="drop-off-location-code" disabled value="<?php echo zeeba_get('step_1.dropoff_location'); ?>" id="return-location-code" />
                </div>

            </div>
        </div>

        <div class="col-md-2">
            <div class="zeeba-lhg-single-input-group col-xs-8 col-md-6">
                <?php
                $pickup_datetime = explode(" ", zeeba_get('step_1.pickup_date'));
                $pickup_date = $pickup_datetime[1] . " " . $pickup_datetime[2] . " " . $pickup_datetime[3];
                $pickup_time = $pickup_datetime[4] . " " . $pickup_datetime[5];

                $dropoff_datetime = explode(" ", zeeba_get('step_1.dropoff_date'));
                $dropoff_date = $dropoff_datetime[1] . " " . $dropoff_datetime[2] . " " . $dropoff_datetime[3];
                $dropoff_time = $dropoff_datetime[4] . " " . $dropoff_datetime[5];
                ?>
                <label><?php echo get_option('step1_pick-up_date_placeholder'); ?></label>
                <input class="form-control" type="text" id="pickup-date" readonly />
                <input type="hidden" id="pickup-datepicker" value="<?php echo zeeba_get('step_1.pickup_date'); ?>" />

            </div>
            <div class="zeeba-lhg-single-input-group col-xs-4 col-md-6">
                <label>&nbsp;</label>
                <input class="form-control" type="text" id="pickup-timepicker" readonly value="<?php echo $pickup_time; ?>" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="zeeba-lhg-single-input-group  col-xs-8 col-md-6">
                <label><?php echo get_option('step1_drop-off_date_placeholder'); ?></label>


                <input class="form-control" type="text" id="dropoff-date" readonly />
                <input type="hidden" id="dropoff-datepicker" value="<?php echo zeeba_get('step_1.dropoff_date'); ?>" />

            </div>
            <div class="zeeba-lhg-single-input-group col-xs-4 col-md-6">
                <label>&nbsp;</label>
                <input class="form-control" type="text" id="dropoff-timepicker" readonly value="<?php echo $dropoff_time; ?>" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="zeeba-lhg-single-input-group col-md-12">
                <label><?php echo get_option('step1_select_van_placeholder'); ?></label>
                <!-- <div class="col-md-12 p-0"> -->
                <select class="form-control" id="vehicle">
                    <option value="" disabled="" selected=""><?php echo get_option('step1_select_van_placeholder'); ?></option>

                    <?php foreach ($vehicles as $vehicle) : ?>
                        <option <?php echo ($vehicle->ID == $selected_id) ? 'selected' : ''; ?> value="<?php echo $vehicle->ID; ?>"><?php echo $vehicle->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- </div> -->
            </div><!-- #End Single input group (Select a van) -->
        </div>
        <div class="col-md-2 mt-5 ">
            <button type="submit" class="btn btn-success px-4 step_1_submit col-md-12 pt-5">Find Vehicle</button>

            <input type="hidden" name="next_url" value="<?php echo $next_page_url; ?>" />
        </div>
    </div>
</div>
</div>



<!-- </div> -->
</div>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        let locations = [
            <?php
            foreach ($locations as $location) {

                echo "{ value: '"  . $location->post_title . "', data: { category: ' ";
                echo  gf('sys_state', $location->ID);
                echo "', id:" . $location->ID . ", code:'";
                echo tf('sys_trn_id', $location->ID);
                echo "' } },";
            }
            ?>
        ];

        jQuery('#pickupStation').autocomplete({
            lookup: locations,
            minChars: 0,
            groupBy: 'category',
            // forceFixPosition: true,
            // appendTo:  jQuery('#pickupStation'),
            onSelect: function(suggestion) {
                jQuery('input[name=pick-up-location-id]').val(suggestion.data.id);
                jQuery('input[name=pick-up-location-code]').val(suggestion.data.code);
            }
        });
        jQuery('#returnStation').autocomplete({
            lookup: locations,
            minChars: 0,
            groupBy: 'category',
            onSelect: function(suggestion) {
                jQuery('input[name=drop-off-location-id]').val(suggestion.data.id);
                jQuery('input[name=drop-off-location-code]').val(suggestion.data.code);
            }
        });

        var picker = new Litepicker({
            element: document.getElementById('pickup-date'),
            elementEnd: document.getElementById('dropoff-date'),
            // firstDay:1,
            numberOfMonths: 3,
            numberOfColumns: 3,
            autoApply: true,
            singleMode: false,
            selectForward: true,
            selectBackward: false,
            minDate: new Date() - 1,
            format: "MMM DD",
            mobileFriendly: true,
            autoRefresh: true,
            startDate: new Date('<?= $pickup_date; ?>'),
            // allowRepick: true,
            endDate: new Date('<?= $dropoff_date; ?>'),
            setup: (picker) => {
                picker.on('selected', (date1, date2) => {
                    // debugger;
                   jQuery('#pickup-datepicker').val(date1.toDateString());
                   jQuery('#dropoff-datepicker').val(date2.toDateString());

                });

            },

        });
        // document.getElementById('pickup-datepicker').value = '<?= $pickup_date; ?>';
        jQuery.datetimepicker.setDateFormatter('moment');

        jQuery('#pickup-timepicker').datetimepicker({
            datepicker: false,
            format: 'hh:mm A',
            formatTime: 'hh:mm a',
            step: 30,
            minTime: '09:00',
            maxTime: '18:01',
            scrollTime: false
        });
        jQuery('#dropoff-timepicker').datetimepicker({
            datepicker: false,
            format: 'hh:mm A',
            formatTime: 'hh:mm a',
            step: 30,
            minTime: '09:00',
            maxTime: '18:01'
        });
    });
</script>