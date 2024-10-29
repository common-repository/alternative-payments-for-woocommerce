/** JavaScript for cleaning all actions that can change order value */

jQuery(document).ready(function () {
    var $ = jQuery;
    var $obj = $('#woocommerce-order-items');

// remove checkbox
    $obj.find('th > .check-column').closest('th').css('display', 'none');
    $obj.find('td.check-column').css('display', 'none');

// remove edit and delete 
    $obj.find('.wc-order-edit-line-item').css('display', 'none');
    $obj.find('.edit-order-item').css('display', 'none');
    $obj.find('.edit').css('display', 'none');

// remove all actions for items
    if( $obj.find('.wc-order-items-editable del').length ){
        $obj.find('.refund-items').css('display', 'none');	
    }

    $obj.find('.add-line-item').css('display', 'none');
    $obj.find('.add-order-tax').css('display', 'none');
    $obj.find('.calculate-tax-action').css('display', 'none');
    $obj.find('.calculate-action').css('display', 'none');

    var $total_val = $obj.find('.wc-order-totals #_order_total').val();

    $obj.find('#refund_amount').val($total_val);
    $obj.find('#refund_amount').attr('value', $total_val);

    var $currency = $obj.find('.refund-actions .do-api-refund .amount').text();
    $currency = $currency.replace("0.00", "");

    $obj.find('.refund-actions .wc-order-refund-amount .amount').html($total_val+$currency);

    $obj.find('.wc-order-refund-items .wc-order-totals').css('display', 'none');
});

jQuery(document).on('click', 'refund-items', function(){
    var $ = jQuery;
    var $obj = $('#woocommerce-order-items');

    $obj.find('.wc-order-refund-items .wc-order-totals').css('display', 'none');
});

jQuery(document).on('click', '.do-api-refund', function(){
    location.reload();
});


