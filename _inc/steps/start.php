<div class="container-fluid" id="start_form_container">
    <div class="row">
        <div class="col-md-4">
            <div class="zeeba-lhg-single-input-group col-md-6">
                <label><?php echo get_option('step1_pick-up_location_placeholder'); ?></label>
                <input id="pickupStation" name="pick-up-location-search" class="form-control input-location" type="text" placeholder="Find a location" />

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


                    <input id="returnStation" name="drop-off-location-search" class="form-control input-location" type="text" placeholder="Find a location" />

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
                <label><?php echo get_option('step1_pick-up_date_placeholder'); ?></label>
                <input class="form-control" type="text" id="pickup-datepicker" readonly value="<?php echo zeeba_get('step_1.pickup_date'); ?>">
            </div>
            <div class="zeeba-lhg-single-input-group col-xs-4 col-md-6">
                <label>&nbsp;</label>
                <input class="form-control" type="text" id="pickup-timepicker" readonly />
            </div>
        </div>
        <div class="col-md-2">
            <div class="zeeba-lhg-single-input-group  col-xs-8 col-md-6">
                <label><?php echo get_option('step1_drop-off_date_placeholder'); ?></label>


                <input class="form-control" type="text" id="dropoff-datepicker" readonly value="<?php echo zeeba_get('step_1.dropoff_date'); ?>">
            </div>
            <div class="zeeba-lhg-single-input-group col-xs-4 col-md-6">
                <label>&nbsp;</label>
                <input class="form-control" type="text" id="dropoff-timepicker" readonly />
            </div>
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
            element: document.getElementById('pickup-datepicker'),
            elementEnd: document.getElementById('dropoff-datepicker'),
            // firstDay:1,
            numberOfMonths: 3,
            numberOfColumns: 3,
            autoApply: true,
            singleMode: false,
            selectForward: true,
            selectBackward: false,
            minDate: new Date() - 1,
            format: "MMM DD",
            mobileFriendly:true
        });

        jQuery.datetimepicker.setDateFormatter('moment');

        jQuery('#pickup-timepicker').datetimepicker({
            datepicker: false,
            format: 'hh:mm A',
            formatTime: 'hh:mm a',
            step: 30,
            minTime: '09:00',
            maxTime: '18:01',
            scrollTime:false
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