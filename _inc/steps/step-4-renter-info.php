<!-- start step04 -->
  <div class="zeeba_form step" id="step4" style="display: none;">
      <div class="step4-container my-4" style="max-width: 90%; margin: auto;">
        <div class="row mx-3">
          <div class="col-md-6 col-xs-12 block-left">
            <div class="van-info text-center">
                <img class="select-van-img" id="step4-van-img" src="" alt="" srcset="">
                <h3 class="select-van-title text-lg font-bold" id="step4-van-title"></h3>
            </div>
            <div class="h-px  bg-gray-300 my-4"></div>
            <div class="policies">
                <h3 class="zeeba_text text-lg mb-3"><?php echo get_option('step4_policy_heading'); ?></h3>
                <?php if(get_option('step4_policy_list')):
                     foreach(get_option('step4_policy_list') as $key => $value): ?>

                        <div class="policy mb-3 flex">
                            <div class="zeeba_text mr-2 fl-left">
                                <i data-feather="check"></i>
                            </div>
                            <p class="text-xs"><?php echo get_option('step4_policy_list')[$key]; ?></p>
                        </div>
                <?php endforeach; endif; ?>
                <a href="<?php echo get_option('step4_policy_download_url'); ?>" target="_blank" class="zeeba_bg hover:bg-red-700 text-white font-bold py-1 text-xs px-3 rounded-full my-8 " >
                <?php echo get_option('step4_policy_download_text'); ?>
                </a>
            </div>
          </div>
          <div class="col-md-6 col-xs-12 bg-light-gray pt-3 pb-3 pl-4 pr-4">
            <form id="step-4-form">
            <div class="row">
              <!-- Renter Information section -->
                <div class="col-md-12 col-xs-12 mb-4 renter-info-content">
                  <div class="form-group row">
                      <div class="col-md-12 col-xs-12">
                          <h3 style="text-align:center; margin-top: 10px;">Fill Out the Form to Complete Your Booking</h3>
                        
                      </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-xs-12 mb-4 renter-info-content">
                      <h5><?php echo get_option('step4_ri_heading'); ?></h5>
                    </div>
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_first_name" name="first_name" placeholder="<?php echo get_option('step4_ri_first_name'); ?>" required />
                      </div>
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_last_name" name="last_name" placeholder="<?php echo get_option('step4_ri_last_name'); ?>"  required />
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_company_name" name="company_name" placeholder="<?php echo get_option('step4_ri_company_name'); ?>"  required />
                      </div>
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_phone_number" name="phone_number" placeholder="<?php echo get_option('step4_ri_phone_number'); ?>"  required />
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_email" name="email" placeholder="<?php echo get_option('step4_ri_email'); ?>"  required />
                      </div>
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_email_confirm" name="email_confirm" placeholder="<?php echo get_option('step4_ri_email_confirm'); ?>"  required />
                      </div>
                  </div>
                  
                  <div class="form-group row">
                    <div class="col-md-12 col-xs-12 mt-1">
                        <input type="text"  class="form-control" id="step4_ri_address" name="address" placeholder="<?php echo get_option('step4_ri_address'); ?>"  required />
                    </div>
                      <div class="col-md-6 col-xs-12 mt-1">
                          <select name="country" id="step4_ri_country" class="form-control zeeba_select2" placeholder="<?php echo get_option('step4_ri_country'); ?>" required>
                            <option value=""><?php echo get_option('step4_ri_country'); ?></option>
                            <? foreach(countries_list() as $k => $v): ?>
                              <option value="<?= $k ?>" <?php if($k=="US") echo 'selected'; ?>><?= $v ?></option>
                            <? endforeach ?>
                          </select>
                      </div>
                    
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_state" name="state" placeholder="<?php echo get_option('step4_ri_state'); ?>"  required />
                      </div>
                    
                  </div>
                  <div class="form-group row">
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_city" name="city" placeholder="<?php echo get_option('step4_ri_city'); ?>"  required />
                      </div>
                      
                      <div class="col-md-6 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_ri_zip" name="zip" placeholder="<?php echo get_option('step4_ri_zip'); ?>"  required />
                      </div>
                  </div>
                </div>
              <!-- ./Renter Information section -->
              <!-- document section -->
                <div class="col-md-12 col-xs-12 mb-4 documents document-content">
                  <div class="document-heading"><h5><?php echo get_option('step4_du_heading'); ?></h5></div>
                  <div class="document-text"><p><?php echo get_option('step4_du_text'); ?></p></div>
                  <div class="document-warning"><h6><span class="text-danger">*</span><?php echo get_option('du_warning'); ?></h6></div>
                  <div class="document-file">
                    <div class="custom-file">
                      <div id="files"></div>
                      <input type="file" class="custom-file-inputs" name="docs" multiple="" id="choose-files">
                      <input type="hidden" name="attached" id="attached-files" value="[]">
                      <!-- <label class="custom-file-label" for="docs">Choose file</label> -->
                      <p id="file-msg" class="text-danger"></p>
                    </div>
                  </div>
                </div>
              <!-- ./document section -->
              <!-- driver Information section -->
                <div class="col-md-12 col-xs-12 mt-5 mb-4 driver-info-content">
                  <div class="form-group row">
                      <div class="col-md-12 col-xs-12">
                          <h5  class="fl-left"><?php echo get_option('step4_dd_heading'); ?></h5>
                        <!--  <div title='<?php echo get_option('step4_dd_tip'); ?>'><i data-feather="help-circle"></i></div> -->
                      </div>
                  </div>
                  <div id="additional-driver-data" class="additional_drivers">
                  </div>
                  <div class="add_driver">
                      <div class="fl-left add_driver_icon"><i data-feather="plus-square"></i></div>
                      <div class="btn-label"><?php echo get_option('step4_a_driver_add'); ?></div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-xs-12 mt-1">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="auth_drivers" id="auth_drivers">
                        <label class="custom-control-label" for="auth_drivers"><?php echo get_option('step4_a_driver_allow'); ?></label>
                      </div>
                                            
                    </div>
                  </div>
                </div>
              <!-- ./driver Information section -->
              <!-- payment section -->
                <div class="col-md-12 col-xs-12 mb-4 payment-info-content">
                  <div class="document-heading">
                    <h5><?php echo get_option('step4_pd_heading'); ?><br/>
                      <small><?php echo get_option('step4_pd_text'); ?></small></h5>
                  </div>
                  <!-- ...xnote -->
                    <?php if (STRIPE_ENABLED):  ?>
                        <!-- Stripe -->
                        <div class="input-group">
                            <div class="input-stripe" style="margin: 0 0 19px 0;">
                                <?php if (STRIPE_LIVE): ?>
                                    <input type="hidden" name="stripe_token" data-stripe-key="<?php echo STRIPE_LIVE_PUBLIC_KEY; ?>">
                                <?php else: ?>
                                    <input type="hidden" name="stripe_token" data-stripe-key="<?php echo STRIPE_TEST_PUBLIC_KEY; ?>">
                                <?php endif; ?>
                                <div id="card-number" class="txt-field"></div>

                                <!--
                                <div style="margin-top:10px;text-align:right">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logos/powered_by_stripe.png" alt="Powered by Stripe">
                                </div>
                                -->
                            </div>
                        </div>

                        <style>
                            /**
                             * The CSS shown here will not be introduced in the Quickstart guide, but shows
                             * how you can use CSS to style your Element's container.
                             */
                            .StripeElement {
                                box-sizing: border-box;

                                height: 46px;

                                padding: 16px 25px 15px;

                                border: 1px solid #d6d8d9;
                                border-radius: 6px;
                                background-color: transparent;

                                -webkit-transition: box-shadow 150ms ease;
                                transition: box-shadow 150ms ease;
                            }

                            .StripeElement--focus {
                                border-color: #fd58a7;
                                box-shadow: 0px 0px 0px 3px #f7d0d3;
                            }

                            .StripeElement--invalid {
                              border-color: #fa755a;
                            }

                            .StripeElement--webkit-autofill {
                              background-color: #fefde5 !important;
                            }
                        </style>

                        <!-- Stripe -->
                    <?php else: ?>
                  <!-- ...xnote -->
                      <div class="form-group row">
                        <div class="col-md-6 col-xs-12 mt-1">
                            <input type="text"  class="form-control" id="step4_pd_card_number" name="card_number" placeholder="<?php echo get_option('step4_pd_card_number'); ?>"  required />
                        </div>
                        <div class="col-md-6 col-xs-12 mt-1">
                            <input type="text"  class="form-control" id="step4_pd_card_name" name="card_name" placeholder="<?php echo get_option('step4_pd_card_name'); ?>"  required />
                        </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-md-12 col-xs-12">
                              <label for="step4_pd_card_valid_until"><?php echo get_option('step4_pd_card_valid_until'); ?></label>
                          </div>
                          <div class="col-md-5 col-xs-12 mt-1">
                              <select class="form-control" name="card_month" id="card_month" required="" data-placeholder="<?php echo get_option('step4_pd_card_month'); ?>">
                                    <option value=""><?php echo get_option('step4_pd_card_month'); ?></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                              </select>
                          </div>
                          <div class="col-md-5 col-xs-12 mt-1">
                              <select class="form-control" name="card_year" id="card_year" required data-placeholder="<?php echo get_option('step4_pd_card_year'); ?>" required >
                                  <option value=""><?php echo get_option('step4_pd_card_year'); ?></option>
                                <? for ( $i = date( 'Y' ); $i <= date( 'Y' ) + 13; $i ++ ): ?>
                                      <option value="<?= $i ?>"><?= $i ?></option>
                                <? endfor; ?>
                              </select>
                          </div>
                          <div class="col-md-4 col-xs-12 mt-1">
                              <input type="text"  class="fl-left" id="step4_pd_card_cvv" name="card_cvv" placeholder="<?php echo get_option('step4_pd_card_cvv'); ?>"  required />
                              <!--<div title="<?php echo get_option('step4_pd_card_cvv_help'); ?>"><i data-feather="help-circle"></i></div>-->
                          </div>
                      </div>
                    <?php endif; ?>
                </div>
              <!-- ./payment section -->
              <!-- further info section -->
                <div class="col-md-12 col-xs-12 mb-4 further-info-content">
                  <div class="further-heading">
                    <h5><?php echo get_option('step4_fi_heading'); ?></h5>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6 col-xs-12 mt-1">
                      <p class="mb-0" for="step4_fi_flight_enabled"><?php echo get_option('step4_fi_flight_enabled_title'); ?></p>
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="step4_fi_flight_enabled" id="step4_fi_flight_enabled">
                        <label class="custom-control-label" for="step4_fi_flight_enabled" style="margin-left: 10px;"><?php echo get_option('step4_fi_flight_enabled'); ?></label>
                      </div>
                                            
                    </div>
                    <div class="col-md-6 col-xs-12 mt-1">
                      <p class="mb-0" for="step4_fi_another_country"><?php echo get_option('step4_fi_another_country'); ?></p>
                      <div class="custom-control custom-checkbox custom-control-inline" style="padding-left: 15px;">
                        <input type="checkbox" class="custom-control-input" name="other_country_mexico" id="other_country_mexico" value="yes">
                        <label class="custom-control-label" for="other_country_mexico">Mexico</label>
                      </div>
                      <div class="custom-control custom-checkbox custom-control-inline" style="padding-left: 15px;">
                        <input type="checkbox" class="custom-control-input" name="other_country_canada" id="other_country_canada" value="yes">
                        <label class="custom-control-label" for="other_country_canada">Canada</label>
                      </div>
                    </div>
                  </div>
                  <div id="step4_fi_flight_enabled_content" class="form-group row displaynone">
                      <div class="col-md-12 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_fi_flight_number" name="step4_fi_flight_number" placeholder="<?php echo get_option('step4_fi_flight_number'); ?>" />
                      </div>
                      <div class="col-md-12 col-xs-12 mt-1">
                          <input type="text"  class="form-control" id="step4_fi_flight_airline" name="step4_fi_flight_airline" placeholder="<?php echo get_option('step4_fi_flight_airline'); ?>" />
                      </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-xs-12 mt-1">
                        <textarea class="form-control" id="step4_fi_special" name="step4_fi_special" placeholder="<?php echo get_option('step4_fi_special'); ?>" ></textarea>
                    </div>
                  </div>
                </div>
              <!-- ./further info section -->
              <!-- agreement section -->
                <div class="col-md-12 col-xs-12 mb-4 agreement-content">
                  <div class="agreement-heading">
                    <h5><?php echo get_option('step4_ag_heading'); ?></h5>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12 col-xs-12 mt-1">
                      <div class="custom-control custom-checkbox custom-control-inline" style="padding-left: 10px;">
                        <input type="checkbox" class="custom-control-input" name="agree" id="agree" value="yes" data-toggle="modal" data-target="#agreementModal">
                        <label class="custom-control-label" for="agree" style="margin-left: 10px;"><?php echo get_option('step4_ag_primary_label'); ?></label>
                      </div>
                    </div>
                  </div>
                  <!-- Modal agreement-->
                    <div class="modal fade" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementLabel" aria-hidden="true">
                      <div class="modal-dialog" role="agreement">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title" id="agreementModalLabel"><?php echo get_option('step4_ag_modal_heading'); ?></h4>
                            <a class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </a>
                          </div>
                          <div class="modal-body">
                            <div id="agreement" style="margin-bottom: 10px;">
                              <!--<h5><?php echo get_option('step4_ag_modal_heading'); ?></h5>
                              <?php echo get_option('step4_ag_text'); ?> -->
                              <p>All renters must be 21 or older, have a valid driver’s license, proof of insurance ID card, and a major credit card/debit card in renters name.</p>
                              
                              <p>You can find the full renter's agreement <a href="/renters-agreement"><em>here</em></a>.</p>
                              <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" name="agree_fake" id="agree_fake" value="yes" data-toggle="modal" data-target="#agreementModal">
                                <label class="custom-control-label" for="agree_fake"><?php echo get_option('step4_ag_primary_label'); ?></label>
                              </div>
                            </div>
                            <button type="button" class="btn btn-secondary text-xs text-red font-bold py-1 px-3" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              <!-- ./agreement info section -->
              <!-- total price section -->
                <div class="col-md-12 col-xs-12 mb-4 total-price-content">
                  <div class="agreement-heading">
                    <h5><?php echo get_option('step4_total_price'); ?></h5>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6 col-xs-12 mt-1">
                      <label><?php echo get_option('step4_rental_period'); ?> <span id="step4_rental_period"></span> Day(s)
                      </label>
                    </div>
                    <div class="col-md-6 col-xs-12 mt-1">
                      <label class="fl-right" id="step4_total_price">$154.46
                      </label>
                    </div>
                  </div>
                </div>
              <!-- ./total price section -->
              <!-- button section -->
                <div class="col-md-12 col-xs-12 mb-4 total-price-content">
                  <div class="submit-wrap">
                       <a class="btn btn-primary disabled" type="submit" id="step4_submit"><?php echo get_option('step4_submit_text'); ?></a>
                       <div class="spinner-border text-danger" role="status" id="final_step_spinner" style="display:none;">
                          <span class="sr-only">Loading...</span>
                        </div>
                  </div>
                  <small class="desc"><?php echo get_option('step4_submit_note'); ?></small>
                </div>
              <!-- ./button section -->
              
            </div>
            </form>
          </div>
        </div>
      </div>
  </div>
<!-- end step04 -->

