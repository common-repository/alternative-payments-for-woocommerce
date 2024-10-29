
jQuery(document).ready(function ($) {
    $('.wc_payment_method.payment_method_alternative label[for="payment_method_alternative"]').css('width', '94%');
    
    $(document).on('click', '.alternative_peyment_send_phone_number_btn', function () {
        var $ = jQuery;

        // wc_checkout_params is required to continue, ensure the object exists
        if (typeof wc_checkout_params === 'undefined') {
            return false;
        }

        var $phone_number = $('.alternative_payment_phone_number_txt').val();

        if (!$phone_number) {
            $('.alternative_payment_message_holder .required').html('Please fill phone number.');
            $('.alternative_payment_message_holder').removeAttr('style');
            return false;
        } else {
            $('.alternative_payment_message_holder').attr('style', "display:none;");
        }

        var data = {
            action: 'alternative_payment_check_phone_verification',
            security: wc_checkout_params.apply_state_nonce,
            ajax_temp_phone: $phone_number
        };

        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: data,
            success: function ($data) {
                if ($data.status === "OK") {
                    if ($data.message) {
                        $('.alternative_payment_message_holder .required').html('Please fill phone number.');
                    }

                    $('.alternative_peyment_phone_number').attr('style', 'display:none;');
                    $('.alternative_peyment_verification_code').removeAttr('style');

                    $('.alternative_peyment_payment_response_phone_number').attr('value', $data.phone);
                    $('.alternative_peyment_payment_response_token').attr('value', $data.token);
                }
                if ($data.status === "ERROR") {
                    if ($data.message) {
                        $('.alternative_payment_message_holder .required').html('Please fill phone number.');
                    }
                }
            },
            dataType: 'json'
        });
    });

    $(document).on('keyup', 'input[name="payment_sms_verification_code"]', function () {
        $(this).attr('value', $(this).val());
    });
    
    $(document).on('change', '.alternative_payment_options_select input[type="radio"]', function () {

        $(this).closest('ul').find('.payment_box').css('display', 'none');

        var $obj = $(this).closest('li').find('.payment_box');

        if ($obj.css('display') === 'none') {
            $obj.css('display', 'block');
        } else {
            $obj.css('display', 'none');
        }
    });

    $(document).ajaxComplete(function (event, request, settings) {
        if (settings.url.toLowerCase().indexOf("wc-ajax=update_order_review") >= 0){
            selected_country();
        }
        /*if(settings.url === '/checkout/?wc-ajax=update_order_review'){
            selected_country();
        }*/
    });
});

function selected_country() {
    var $ = jQuery;

    $('.wc_payment_method.payment_method_alternative label[for="payment_method_alternative"]').css('width', '94%');

    var $selected_country = $('#billing_country').val();

    var data = {
        action: 'alternative_payment_check_payment_methods_for_country',
        security: wc_checkout_params.apply_state_nonce,
        ajax_temp_selected_country: $selected_country
    };

    $.ajax({
        type: 'POST',
        url: wc_checkout_params.ajax_url,
        data: data,
        success: function ($data) {
            //console.log('$data', $data);
            if ($data.status === "OK") {
                $(".alternative_payment_options_select  li").each(function ( ) {
                    var $li_id = $(this).find('.input-radio').val();
                    $(this).css('display', 'block');
                    if ($.inArray($li_id.toLowerCase(), $data.payments) < 0) {
                        //$(this).find('.input-radio').attr('disabled', 'disabled');
                        $(this).css('display', 'none');
                    } else {
                        //console.log('none');
                    }
                });
            }
            if ($data.status === "ERROR") {

            }
        },
        dataType: 'json'
    });
}


