<?php
/**
 * Booking form template
 * @package zeebavans-booking
 * @since 1.0
 */
?>

<?php

$locations = get_posts( [
    'post_type'      => 'location',
    'posts_per_page' => - 1,
    'meta_key'       => 'sys_state_code',
    'orderby'        => 'meta_value'
] );
$vehicles    = get_posts( [
    'post_type' => 'vehicle',
    'meta_key'  => 'display_order',
    'orderby'   => 'meta_value',
    'numberposts'=>-1
] );

$selected_id = zeeba_get( 'step1.van_type' );

?>
<div class="zeeba_loader">
    <div class="lds-css ng-scope">
        <div style="width:100%;height:100%" class="lds-double-ring">
            <div></div>
            <div></div>
            <div>
                <div></div>
            </div>
            <div>
                <div></div>
            </div>
        </div>
    </div>
</div>
<div class="zeeba_booking_form_wrapper">
    <div class="zeeba_booking_form">
        <div class="step-container">
            <!-- start steps -->
            <div class="zeeba_step my-4" id="start_page">
                <input type="hidden" id="page_input" value="start" />
                <?php
                    include ZEEBAVAN_DIR . '_inc/steps/start.php';
                ?>

            </div>
        </div>
    </div>
</div>
