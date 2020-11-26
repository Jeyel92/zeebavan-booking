function flightEnabled() {
    var flight = jQuery('#step4_fi_flight_enabled');
    var flight_content = jQuery('#step4_fi_flight_enabled_content');
    flight.on('change', function () {
        if (flight.is(':checked')) {
            flight_content.removeClass('displaynone');
        }else{
            flight_content.addClass('displaynone');
        }
    });
}
function step_4_agreement() {
    var submit = jQuery('#step4_submit');
    var agree = jQuery('#agree');
    var agree_fake = jQuery('#agree_fake');
    var agreement = jQuery('#agreement');
    submit.addClass('disabled');
    agree.on('change', function () {
        if (agree.is(':checked')) {
            agree.prop('checked', false).trigger('refresh');
        }
        submit.addClass('disabled');
    });
    agree_fake.on('change', function () {
        if (agree_fake.is(':checked')) {
            setTimeout(function () {
                jQuery('#agreementModal').modal('hide');
                agree_fake.prop('checked', false).trigger('refresh');
            }, 500);
            agree.prop('checked', true).trigger('refresh');
            submit.removeClass('disabled');
        }
    });
}


function step_4_add_driver() {
    var add_driver = jQuery('.add_driver');
    add_driver.on('click', function () {
        var par = jQuery('.additional_drivers');
        var cnt = par.children('.a_driver').length;
        if (cnt > 1) {
            return;
        } else if (cnt == 1) {
            add_driver.hide();
        }
        var i = jQuery('#a_driver-first_name-1').length + 1;
        par.append('<div class="form-group row a_driver">\
                      <div class="col-md-6 col-xs-12 mt-1">\
                          <input type="text" name="a_driver-first_name-' + i + '" id="a_driver-first_name-' + i + '" class="form-control" placeholder="First Name" />\
                      </div>\
                      <div class="col-md-5 col-xs-11 mt-1">\
                          <input type="text" name="a_driver-last_name-' + i + '" id="a_driver-last_name-' + i + '" class="form-control" placeholder="Last Name" />\
                      </div>\
                      <div class="col-md-1 col-xs-1 mt-1 fl-right remove_driver_icon">\
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square">\
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg></div>\
                  </div>');
        // var placeholder_first_name = par.data('placeholder-first-name');
        // var placeholder_last_name = par.data('placeholder-last-name');
        // var i = jQuery('#a_driver-first_name-1').length + 1;
        // var el = jQuery('<div class="input-group a_driver"></div>')
        //     .append(jQuery('<div class="input"></div>').append('<input type="text" name="a_driver-first_name-' + i + '" id="a_driver-first_name-' + i + '" required placeholder="' + placeholder_first_name + '" />'))
        //     .append(jQuery('<div class="input last"></div>').append('<input type="text" name="a_driver-last_name-' + i + '" id="a_driver-last_name-' + i + '" required placeholder="' + placeholder_last_name + '" />'))
        //     .append('<button type="button" class="remove"><i class="icon-cross"></i></button>');
        // el.children('.remove').on('click', function () {
        //     jQuery(this).parents('.input-group').remove();
        //     add_driver.show();
        // });
        // par.append(el);

        jQuery('.remove_driver_icon').on('click', function () {
            jQuery(this).parent().remove();
            add_driver.show();
        });
    });
}

function step_4_fileupload() {
    var i = 0;
    var files_container = jQuery('#files');
    var attach_container = jQuery('#attached-files');
    let url = '/wp-admin/admin-ajax.php';
    jQuery('#choose-files').fileupload({
        url: url,
        formData: {
            action: 'file_upload'
        },
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|docx?|xlsx?|rtf|pdf)$/i,
        sequentialUploads: true,
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery('#file-' + data.files[0]['pi']).find('.file-progress').css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            // jQuery('#file-msg').text('success');
            var response = jQuery.parseJSON(data.result);
            if (response.result.attach_id) {
                var list;
                try {
                    list = JSON.parse(attach_container.val());
                } catch (e) {
                    list = [];
                }
                list.push(response.result.attach_id);
                attach_container.val(JSON.stringify(list));
                var el = jQuery('#file-' + data.files[0]['pi']);
                el.data('attach-id', response.result.attach_id);
                el.find('.file-progress-bar').remove();
                el.append(
                    jQuery('<div></div>', {
                        'class': 'file-remove'
                    })
                        .text('Remove')
                        .on('click', function () {
                            var list;
                            try {
                                list = JSON.parse(attach_container.val());
                            } catch (e) {
                                list = [];
                            }
                            var index = list.indexOf(response.result.attach_id);
                            if (index > -1) {
                                list.splice(index, 1);
                            }
                            attach_container.val(JSON.stringify(list));
                            el.remove();
                        })
                );
            } else {
                var el = jQuery('#file-' + data.files[0]['pi']);
                el.data('attach-id', response.result.attach_id);
                el.find('.file-progress-bar').remove();
                el.append(
                    jQuery('<div></div>', {
                        'class': 'file-error'
                    })
                        .text('Error ocured')
                );
            }
        },
        add: function (e, data) {
            jQuery.each(data.files, function (index, el) {
                data.files[index]['pi'] = i;
                var file_block = jQuery('<div></div>', {
                    'class': 'file-item',
                    'id': 'file-' + i
                }).append(
                    jQuery('<div></div>', {
                        'class': 'file-name'
                    }).text(el.name)
                ).append(
                    jQuery('<div></div>', {
                        'class': 'file-icon ' + extension(data.files[index].name),
                        'data-type': extension(data.files[index].name)
                    })
                ).append(
                    jQuery('<div></div>', {
                        'class': 'file-progress-bar'
                    }).append(
                        jQuery('<div></div>', {
                            'class': 'file-progress',
                            'style': 'width: 0%'
                        })
                    )
                );
                files_container.append(file_block);

                data.submit();
                i++;
            });
        }
    });
}


// Extract extension from filename
function extension(filename) {
    var re = /(?:\.([^.]+))?$/;
    return re.exec(filename)[1];
}

function step4Submit(){
    jQuery('#step4_submit').click( function(e){
        e.preventDefault();
        var submit = jQuery('#step4_submit');
        submit.addClass('disabled');
        jQuery('#final_step_spinner').css('display', 'inline-block');

        let url = '/wp-admin/admin-ajax.php';
        var form_data = serializeForm(jQuery('#step-4-form'));
        let data = {
                'action': 'step4_submit',
                'data': {
                    'form': form_data,
                }
        };
        jQuery.post(url, data, function(response) {
            response = jQuery.parseJSON(response);
            if(response['ok'] == "false"){
                alert(response['error']);
                submit.removeClass('disabled');
                jQuery('#final_step_spinner').css('display', 'none');
            }else{
                jQuery('.stepper-container').css({display:'none'});
                jQuery('.zeeba_form.step').eq(3).css({display:'none'});
                jQuery('.zeeba_form.step').eq(4).css({display:'block'});

                submit.removeClass('disabled');
                jQuery('#final_step_spinner').css('display', 'none');

                // jQuery('#stepper3').addClass('completed');
                // jQuery('#stepper3').removeClass('active');
                // jQuery('#stepper4').addClass('active');

                // // start step3 show data
                // jQuery('#step4-van-title').text(response['rate']['vehicle_title']);
                // jQuery('#step4-van-img').attr('src',response['rate']['side_image']);

                // jQuery('#step4_rental_period').text(response['bill']['days']);
                // jQuery('#step4_total_price').text('$' + response['bill']['total']);
                $step5_content = '';
                if(response['step5_rd_content_name']){
                    $key = 0;
                    for($value in response['step5_rd_content_name'] ){
                        if(response['step5_rd_content_value'][$key] == "card_name"){
                            $c=0;
                        }else if(response['step5_rd_content_value'][$key] == "card_number"){
                            $c=0;
                        }else if(response['step5_rd_content_value'][$key] == "card_exp"){
                            $c=0;
                        }else if(response['step5_rd_content_value'][$key] == "card_type"){
                            $c=0;
                        }else{// if(value_loc == "pickup_location"){
                            if(response['step5_rd_content_highlight'][$key] == "1"){
                                $step5_content += '<tr class="highlight">\
                                  <td>'+response['step5_rd_content_name'][$key]+'</td>\
                                  <td>'+response['form-data'][response['step5_rd_content_value'][$key]]+'</td>\
                                </tr>';
                            }else{
                                $step5_content += '<tr class="">\
                                  <td>'+response['step5_rd_content_name'][$key]+'</td>\
                                  <td>'+response['form-data'][response['step5_rd_content_value'][$key]]+'</td>\
                                </tr>';
                            }
                        }
                        $key++;
                    }
                }

                jQuery('#step5-renter-data').html($step5_content);
                jQuery('.TopLayout__topMenu').hide();
                jQuery('.zeeba_form.step').eq(0).hide();
                $bill = response['bill'];

                $tax_sum = 0;
                if($bill && $bill['taxes']){
                  $bill['taxes'].forEach(function($tax) {
                    $tax_sum += parseFloat($tax['charge']);
                  })
                }

                dataLayer.push({
                    'event':'ecomm_event',
                    'transactionId':  response['form_id'], // Transaction ID - this is normally generated by your system.
                    'transactionAffiliation': 'Zeebavans', // Affiliation or store name
                    'transactionTotal': $bill['total'] - $tax_sum, // Grand Total
                    'transactionTax': $tax_sum, // Tax.
                    'transactionShipping':'0', // Shipping cost
                    'transactionProducts': [
                    {
                        'sku': response['v_id'], // SKU/code.
                        'name': response['vehicle_title'], // Product name.
                        'category': response['vehicle_title'], // Category or variation.
                        'price':parseFloat($bill['rate']).toFixed(2), // Unit price.
                        'quantity': $bill['days']
                    }]
                });

                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                setTimeout(function() {

                  ga('require', 'ecommerce');
                  ga('ecommerce:clear');
                  ga('ecommerce:addItem', {
                    'id': response['form_id'],
                    'name': response['vehicle_title'],
                    'sku': response['v_id'],
                    'category': response['vehicle_title'],
                    'price': $bill['rate'],
                    'quantity': $bill['days'],
                  });
                  $bill['extra'].forEach(function($extra){
                    ga('ecommerce:addItem', {
                      'id': response['form_id'],
                      'name': $extra['desc'],
                      'sku': $extra['id'],
                      'category': 'Extras',
                      'price': $extra['amount'],
                      'quantity': '1',
                    });
                  })
                  ga('ecommerce:addTransaction', {
                    'id': response['form_id'],
                    'affiliation': 'Zeebavans',
                    'revenue': $bill['total'] - $tax_sum,
                    'tax': $tax_sum
                  });
                  ga('ecommerce:send');
                  console.log('sent');
                }, 300);
            }

        });
    });
}

function serializeForm(form) {
    var data = {};
    jQuery.each(form.serializeArray(), function (_, kv) {
        if (data.hasOwnProperty(kv.name)) {
            data[kv.name] = jQuery.makeArray(data[kv.name]);
            data[kv.name].push(kv.value);
        } else {
            data[kv.name] = kv.value;
        }
    });
    return data;
}
