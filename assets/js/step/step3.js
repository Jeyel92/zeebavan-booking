let step3_ajax_url = '/wp-admin/admin-ajax.php';
function step_3_collect() {

    var options= [];
    var checkboxes = jQuery('.recalculatable').find('input[type="checkbox"]:checked');
    checkboxes.each(function (index, el) {
        if (jQuery(el).data('opt') != '') {
            options.push(jQuery(el).data('opt'));
        }
    });
    return options;
}

function roundupCalculationBtn(){
    jQuery('input[name=step3-roundup-checkbox]').on('change', function(){
        if(jQuery(this).is(':checked')){
            $pre_total = jQuery('#rental_rate_total').text().replace('$','');
            $cal_data = roundupCalculation($pre_total);
            jQuery('#rental_rate_total').text('$'+$cal_data['round_total']);
            jQuery('#rental_rate_total_input').val($cal_data['round_total']);
            jQuery('#charity_total').val($cal_data['charity']);
            jQuery('#rental_rate_total').parent().before('<tr class="donation-td><td>Donation</td><td>$'+$cal_data['charity']+'</td></tr>');

            jQuery('.rental_rate_total').text('$'+$cal_data['round_total']);
            jQuery('.rental_rate_total').parent().before('<tr class="donation-td"><td>Donation</td><td>$'+$cal_data['charity']+'</td></tr>');
        }else{
            $pre_total = jQuery('#rental_rate_total_input').val();
            $cal_data = $pre_total - jQuery('#charity_total').val();
            jQuery('#rental_rate_total').text('$'+$cal_data);
            jQuery('#rental_rate_total_input').val($cal_data);
            jQuery('#charity_total').val(0);
            jQuery('.donation-td').remove();

            jQuery('.rental_rate_total').text('$'+$cal_data);
        }
    });
}

function roundupCalculation($pre_total){
    $round_total = Math.ceil($pre_total);
    $charity = $round_total - $pre_total;
    $data = [];
    $data['round_total'] = $round_total.toFixed(2);
    $data['charity'] = $charity.toFixed(2);
    return $data;
}

jQuery('#request_more_send').on('click', function () {
    var btn = jQuery(this);
    jQuery('#request-more-msg').html('');
    btn.addClass('loading');
    $first_name = jQuery('#request_first_name').val();
    $last_name = jQuery('#request_last_name').val();
    $phone = jQuery('#request_phone').val();
    $email = jQuery('#request_email').val();
    $message = jQuery('#request_message').val();
    if ($first_name.length < 1 || $first_name == '')
        alert('First Name is required');
    else if ($last_name.length < 1 || $last_name == '')
        alert('Last Name is required');
    else if ($phone.length < 1 || $phone == '')
        alert('Phone is required');
    else if ($email.length < 1 || $email == '')
        alert('Email is required');
    else if (!validateEmail($email))
        alert('Email is invalid');
    else if ($message.length < 1 || $message == '')
        alert('Message is required');
    else{
        data = {};
        data.options = step_3_collect();
        data.first_name = $first_name
        data.last_name = $last_name;
        data.phone = $phone;
        data.email = $email;
        data.message = $message;

        let submit_data = {
            'action': 'step3_more',
            'data': {
                'form' :data,
                'charity' : jQuery('#charity_total').val()
            }
        };

        jQuery.post(step3_ajax_url, submit_data, function(response) {
            btn.removeClass('loading');
            if(response){
                response = jQuery.parseJSON(response);
                if (response['error']== true) {
                    alert(response['msg']);
                } else if(response['error'] == false) {
                    jQuery('#request-more-msg').html(response['msg']);
                    jQuery('#request_first_name,#request_last_name,#request_phone,#request_email,#request_message').val('');
                }
            }
        });
    }
});

function validateEmail(email) {
    let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


function sendRateQuoteEmail(){
    jQuery('#email_copy_send').click( function(){
        jQuery('#email-copy-msg').html('');
        $ec_email = jQuery('#emailModal input#ec_email').val();
        if($ec_email.length > 0){
            if (!validateEmail($ec_email)) {
                jQuery('#email-copy-msg').html('Email you entered is not valid');
                jQuery(this).addClass('red-border');
            }else{
                jQuery('#emailModal .modal-body p').next().remove();
                jQuery(this).removeClass('red-border');

                data = {};
                data.options = step_3_collect();
                data.email = $ec_email;
                data.charity = jQuery('#charity_total').val();

                let submit_data = {
                    'action': 'step3_send_email',
                    'data': data
                    
                };
                // send mail ajax
                let url = '/wp-admin/admin-ajax.php';
                jQuery.post(url, submit_data, function(response) {
                    response = jQuery.parseJSON(response);
                    //console.log(response);
                    jQuery('#email-copy-msg').html(response['msg']);

                });

            }
        }

    });
}