<!-- start step03 -->
<div class="zeeba_form step" id="step3" style="display: none;">
    <div class="step3-container my-4" style="max-width: 90%; margin: auto;">
        <div class="row mx-3">
            <div class="col-md-6 col-xs-12" style="padding: 0px 20px;">
                <div class="van-info text-center">
                    <img class="select-van-img" id="step3-van-img" src="" alt="" srcset="">
                    <h3 class="select-van-title text-lg font-bold" id="step3-van-title">12 Passenger Van High Top</h3>
                </div>
                <div class="h-px w-full bg-gray-300 my-4"></div>
                <div class="policies">
                    <h3 class="zeeba_text text-lg mb-3"><?php echo get_option('step3_policy_heading'); ?></h3>
                    <?php if(get_option('step3_policy_list')):
                           foreach(get_option('step3_policy_list') as $key => $value): ?>

                    <div class="policy mb-3 flex">
                        <div class="zeeba_text mr-2 fl-left">
                            <i data-feather="check"></i>
                        </div>
                        <p class="text-xs"><?php echo get_option('step3_policy_list')[$key]; ?></p>
                    </div>
                    <?php endforeach; endif; ?>
                    <a href="<?php echo get_option('step3_policy_download_url'); ?>" target="_blank"
                        class="zeeba_bg text-white font-bold py-1 text-xs px-3 rounded-full my-8 ">
                        <?php echo get_option('step3_policy_download_text'); ?>
                    </a>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <!-- coverage option table -->
                        <div class="row step_3_section">
                    <div class="col-md-12 col-xs-12 coverage_opt_tbl rounded mb-8 recalculatable">
                        <table class="w-full">
                            <thead class="text-left bg-gray-200">
                                <tr>
                                    <th><?php echo get_option('options_col_1'); ?></th>
                                    <th><?php echo get_option('options_col_2'); ?></th>
                                    <th><?php echo get_option('options_col_3'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="coverage-data">
                            </tbody>
                        </table>
                        <p class="zeeba_text mb-3">*<?php echo get_option('rates_warning'); ?></p>
                    </div>
                    <!-- ./coverage option table -->
                    <!-- convenience & safety table -->
                    <div class="col-md-12 col-xs-12 convenience_safety_tbl rounded recalculatable">
                        <div>
                            <table class="w-full">
                                <thead class="text-left bg-gray-200">
                                    <tr>
                                        <th class="w-4/6">CONVENIENCE & SAFETY</th>
                                        <th class="w-1/6">Price</th>
                                        <th class="w-1/6">Add/Remove</th>
                                    </tr>
                                </thead>
                                <tbody id="safety-data">
                                </tbody>
                            </table>
                            <p class="zeeba_text mb-3">*<?php echo get_option('rates_warning'); ?></p>
                        </div>
                    </div>
                    <!-- ./convenience & safety table -->
                    <div
                        class="csm_calculate_btn  text-white font-bold py-2 px-4 rounded-full my-8 float-right">
                        <button
                            class="zeeba_bg text-white font-bold py-2 px-4 rounded-full my-8 float-right"
                            id="step3-rate-calculate">
                            <?php echo get_option('calculate_text'); ?>
                        </button>
                    </div>
                    <div class="">
                        <button
                            class="zeeba_bg text-white font-bold py-2 px-4 rounded-full my-8 float-right displaynone"
                            id="step3-rate-recalculate">
                            <?php echo get_option('recalculate_text'); ?>
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="step3-rate-container" class="displaynone">
                    <!-- ./rate table -->
                    <div id="step3-rate-content" class="col-md-12 col-xs-12 rate_quote mb-3 ">
                        <h3 class="mb-3"><?php echo get_option('rates_heading'); ?> <span class="zeeba_text">*</span>
                        </h3>
                        <div class="rate_quote_tbl">
                            <table class="w-full">
                                <thead class="bg-gray-300 text-left">
                                    <tr>
                                        <th class="w-4/6"><?php echo get_option('rates_col_1'); ?></th>
                                        <th class="w-2/6"><?php echo get_option('rates_col_2'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="w-4/6">Rental Rate
                                            <div id="rental_rate_detail"></div>
                                        </td>
                                        <td class="w-2/6" id="rental_rate_cost"></td>
                                    </tr>
                                    <tr id="rate-append-data">
                                        <td class="w-4/6">Admin Fee</td>
                                        <td class="w-2/6">$9.00</td>
                                    </tr>
                                    <tr>
                                        <td class="w-4/6">Subtotal</td>
                                        <td id="rental_rate_subtotal">$9.00</td>
                                    </tr>
                                    <tr>
                                        <td class="w-4/6">
                                            Discount
                                            <div><span id="promocode"></span> - <span id="rental_rate_discount_percent"></span></div>
                                        </td>
                                        <td class="w-2/6">
                                            <div id="rental_rate_discount">$.70</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-4/6">
                                            Taxes and Fees
                                            <div>Sales Tax - 7.75%</div>
                                        </td>
                                        <td class="w-2/6">
                                            <div id="rental_rate_tax">$.70</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mileage</td>
                                        <td id="rental_rate_mileage"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td class="rental_rate_total" id="rental_rate_total">$9.70</td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" name="rental_rate_total" id="rental_rate_total_input" value=""
                                step="0.01">
                            <input type="hidden" name="charity_total" id="charity_total" value="0" step="0.01">
                            <h3 class="form-check custom-control custom-checkbox  form-check-inline" id="step3-roundup-container">
                              Donate to charity by rounding up your purchase! </h3>
                              <input class="form-check-input " type="checkbox"  id="step3-roundup-checkbox" name="step3-roundup-checkbox" />
                              <label class="form-check-label" for="step3-roundup-checkbox" id="step3-roundup">Donate<span></span>
                              </label>
                            </h3>
                            <!-- <button class="zeeba_bg text-white font-bold py-2 px-4 rounded-full my-8 float-right" id="step3-roundups">Donate <span></span></button> -->
                            <p class="zeeba_text mb-3">*<?php echo get_option('rates_warning'); ?></p>
                        </div>
                    </div>
                    <!-- ./rate table -->
                    <div class="policy_note">
                        <div class="policy_body p-3 border border-gray-300 text-sm">
                            <h3>Cancelation Policy:</h3>
                            <p><?php echo get_option('cancelation_text'); ?></p>
                        </div>
                    </div>
                    <div class="util_btn">
                        <!-- email Button trigger modal -->
                        <button class=" border text-xs text-gray-700 font-bold py-1 px-3 rounded-full my-8 "
                            data-toggle="modal" data-target="#emailModal">
                            <?php echo get_option('btn_email_copy'); ?>
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="emailModal" tabindex="-1" role="dialog"
                            aria-labelledby="emailModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h5><?php echo get_option('ec_heading'); ?></h5>
                                        <input class="form-control mb-2" type="email" name="ec_email" id="ec_email"
                                            placeholder="<?php echo get_option('ec_email'); ?>">
                                        <h4 id="email-copy-msg" class="text-danger"></h4>
                                        <button class="btn zeeba_bg text-xs text-white font-bold py-1 px-3 "
                                            id="email_copy_send"><?php echo get_option('ec_submit'); ?></button>
                                        <button type="button"
                                            class="btn btn-secondary text-xs text-red font-bold py-1 px-3"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- more request Button trigger modal -->
                        <button data-toggle="modal" data-target="#moreRequestModal"
                            class=" border text-xs text-gray-700 font-bold py-1 px-3 rounded-full my-8 ">
                            <?php echo get_option('btn_request_more'); ?>
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="moreRequestModal" tabindex="-1" role="dialog"
                            aria-labelledby="emailModalLabel" aria-hidden="true">
                            <div class="modal-dialog csm_diag" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h5><?php echo get_option('rm_heading'); ?></h5>
                                        <input class="form-control mb-2" type="text" name="first_name"
                                            placeholder="<?php echo get_option('rm_first_name'); ?>"
                                            id="request_first_name">
                                        <input class="form-control mb-2" type="text" name="last_name"
                                            placeholder="<?php echo get_option('rm_last_name'); ?>"
                                            id="request_last_name">
                                        <input class="form-control mb-2" type="tel" name="phone"
                                            placeholder="<?php echo get_option('rm_phone_number'); ?>"
                                            id="request_phone">
                                        <input class="form-control mb-2" type="email" name="email"
                                            placeholder="<?php echo get_option('rm_email'); ?>" id="request_email">
                                        <textarea class="form-control mb-2" name="message"
                                            placeholder="<?php echo get_option('rm_message'); ?>"
                                            id="request_message"></textarea>
                                        <h4 id="request-more-msg" class="text-danger"></h4>
                                        <button class="btn zeeba_bg text-xs text-white font-bold py-1 px-3 "
                                            id="request_more_send"><?php echo get_option('rm_submit'); ?></button>
                                        <button type="button"
                                            class="btn btn-secondary text-xs text-red font-bold py-1 px-3"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button
                            class="zeeba_bg text-xs text-white font-bold py-1 px-3 rounded-full my-8"
                            id="step_3_submit">
                            <?php echo get_option('btn_submit'); ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<!-- end step03 -->