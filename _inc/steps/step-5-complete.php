<!-- start step05 -->
  <div class="zeeba_form step" style="display: none; margin-top: 50px;">
      <div class="step5-container my-4" style="max-width: 90%; margin: auto;">
        <div class="row mx-3">
          <div class="col-md-6 col-xs-12">
            <div class="van-info text-center">
                      <img class="select-van-img" src="" alt="" srcset="">
                      <h3 class="select-van-title text-lg font-bold"></h3>
                  </div>
                  <div class="h-px w-full bg-gray-300 my-4"></div>
                  <div class="policies">
                      <h3 class="zeeba_text text-lg mb-3"><?php echo get_option('step5_policy_heading'); ?></h3>
                      <?php if(get_option('step5_policy_list')):
                           foreach(get_option('step5_policy_list') as $key => $value): ?>

                              <div class="policy mb-3 flex">
                                  <div class="zeeba_text mr-2 fl-left">
                                      <i data-feather="check"></i>
                                  </div>
                                  <p class="text-xs"><?php echo get_option('step5_policy_list')[$key]; ?></p>
                              </div>
                      <?php endforeach; endif; ?>
                      <a href="<?php echo get_option('step5_policy_download_url'); ?>" target="_blank" class="zeeba_bg hover:bg-red-700 text-white font-bold py-1 text-xs px-3 rounded-full my-8 " >
                      <?php echo get_option('step5_policy_download_text'); ?>
                      </a>
                  </div>
          </div>
          <div class="col-md-6 col-xs-12">
            <h5 class="reservation-msg text-center" style="font-weight: 600;"><?php echo get_option('step5_confirm_text'); ?></h5>
              <!-- renter info table -->
                <!--<div class="col-md-12 col-xs-12 convenience_safety_tbl rounded recalculatable">
                    <div>
                      <table class="w-full">
                            <thead class="text-left bg-gray-200">
                              <tr>
                                <th colspan="2"><?php echo get_option('step5_rd_heading'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="step5-renter-data">
                            </tbody>
                        </table>
                    </div>
                </div>-->
              <!-- ./renter info table -->
              <!-- rate quote table -->
                <div class="col-md-12 col-xs-12 step5-rate rounded mb-8 ">
                  <h5 class="rate_quote_tbl reservation-msg text-center"><?php echo get_option('step5_rq_heading'); ?></h5>
                  <table class="w-full">
                      <thead class="text-left bg-gray-200">
                          <tr>
                              <th><?php echo get_option('step5_rq_col1_details'); ?></th>
                              <th><?php echo get_option('step5_rq_col2_cost'); ?></th>
                          </tr>
                      </thead>
                      <tbody id="step5-rate-data" class="rate-data">
                        <tr>
                            <td>Rental Rate
                                <div class="rental_rate_detail"></div>
                            </td>
                            <td class="rental_rate_cost"></td>
                        </tr>
                        <tr class="rate-append-data">
                            <td class="w-4/6">Admin Fee</td>
                            <td class="w-2/6">$9.00</td>
                        </tr>
                        <tr>
                            <td class="w-4/6">Subtotal</td>
                            <td class="rental_rate_subtotal"></td>
                        </tr>
                        <tr id="discount">
                            <td class="w-4/6">
                              Discount
                              <div><span class="promocode"></span> - <span class="rental_rate_discount_percent"></span></div>
                            </td>
                            <td class="w-2/6">
                              <div class="rental_rate_discount">$.70</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-4/6">
                                Taxes and Fees
                                <div>Sales Tax - 7.75%</div>
                            </td>
                            <td class="w-2/6">
                                <div class="rental_rate_tax"></div>
                            </td>
                        </tr>
                        <tr>
                            <td >Mileage</td>
                            <td class="rental_rate_mileage"></td>
                        </tr>
                        <tr>
                            <td class=""><b>Total</b></td>
                            <td class="rental_rate_total" ></td>
                        </tr>
                    </tbody>
                  </table>
                  <p class="zeeba_text mb-3">*<?php echo get_option('step5_rq_warning'); ?></p>
                </div>
              <!-- ./rate quote table -->
              <!-- contacts -->
                <div class="row">
                  <div class="col-md-12 col-xs-12 step5-contacts rounded mb-8">
                    <div id="contacts_block">
                        <div class="rates_title"><?php echo get_option('step5_contacts_title'); ?></div>
                        <?php echo get_option('step5_contacts_content'); ?>
                    </div>
                  </div>
              <!-- ./contacts -->

                  <button class="zeeba_bg text-white font-bold py-2 px-4 rounded-full my-8" id="step5-print"  onclick="window.print();">
                  <?php echo get_option('step5_si_print'); ?>
                  </button>
                  <a href="/" class="zeeba_bg text-white font-bold mx-2 py-2 px-4 my-8" id="backhome">Back to home</a>

            </div>
          </div>
        </div>
      </div>
  </div>
<!-- end step05 -->