<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class AlternativePaymentsWooText{

    public static function en($id){
        $t = array(
            'method_title' => 'Alternative Payments',
            'method_description' => 'Alternative Payments helps connect your business to the World. We offer global payment solutions that will help you unlock new marketplaces. Our products are built with an international consumer in mind.',
            'sms_active_true' => sprintf('SMS Verification activated. For more information visit <a href="%s">Alternative Payments support page</a>.', "http://www.alternativepayments.com/support/plugins-and-integrations.html"),
            'sms_active_false' => sprintf('SMS Verification is not activated. For more information visit <a href="%s">Alternative Payments support page</a>.', "http://www.alternativepayments.com/support/plugins-and-integrations.html"),

            'form_field_error_title' => 'Error',
            'form_field_error_label' => 'Error connecting to API server. Please check API Url & API keys',

            'form_field_enable_title' => 'Enable/Disable',
            'form_field_enable_label' => 'Alternative Payments',

            'form_field_title_title' => 'Title',
            'form_field_title_description' => 'Enter the title to show for this payment option during checkout.',
            'form_field_title_default' => 'Buy with your local alternative',

            'form_field_sms_verification_title' => 'SMS Verification',

            'form_field_product_key_title' => 'PUBLIC KEY',
            'form_field_product_key_description' => 'Enter the Public API Key you obtained from your Website Profile in the Alternative Payments Merchant Portal',
            'form_field_product_key_default' => '',

            'form_field_api_key_title' => 'SECRET KEY',
            'form_field_api_key_description' => 'Enter the Secret API Key you obtained from your Website Profile in the Alternative Payments Merchant Portal',
            'form_field_api_key_default' => '',

            'form_field_api_url_title' => 'API URL',
            'form_field_api_url_description' => 'API URL to Alternative Payments',
            'form_field_api_url_default' => 'https://api.alternativepayments.com/api',

            'form_field_return_url_title' => 'RETURN URL',
            'form_field_return_url_description' => 'For redirect payment options (not SEPA), where do you want the consumer to be returned to once he completes payment.',
            'form_field_return_url_default' => 'http://plugins.alternativepayments.com/message/success.html',

            'form_field_cancel_url_title' => 'CANCEL URL',
            'form_field_cancel_url_description' => 'For redirect payment options (not SEPA), where do you want the consumer to be returned to if he cancels the payment process.',
            'form_field_cancel_url_default' => 'http://plugins.alternativepayments.com/message/failure.html',

            'form_field_proccessing_status_title' => 'Change SEPA order status to proccessing when:',
            'form_field_proccessing_status_description' => 'Select which Alternative Payments status you want the Woo Commerce Processing status to use.',
            'form_field_proccessing_status_default' => 'funded',
            'form_field_proccessing_status_option_funded' => 'Merchant Funded status',
            'form_field_proccessing_status_option_approved' => 'Merchant Approved status',

            'form_field_description_title' => 'Description',
            'form_field_description_description' => 'Payment method description that the customer will see on your checkout.',
            'form_field_description_default' => 'Pay using local payment options you are familiar and comfortable with. Safe and no credit card needed!',

            'form_field_instructions_title' => 'Instructions',
            'form_field_instructions_description' => 'Instructions that will be added to the thank you page and emails.',
            'form_field_instructions_default' => 'We need to input text here',

            'form_phone_number' => 'Mobile Phone Number',
            'form_phone_number_description' => 'A PIN code will be send to the number above and will need to be entered on the next screen to complate you purchase.',
            'form_btn_value' => 'Request PIN',
            'form_phone_verification_code' => 'Phone verification code',
            'form_phone_verification_code_description' => 'Enter PIN code you received via SMS.',
            'form_holder' => 'Account Holder\'s Full Name',
            'form_iban' => 'IBAN',
            'form_bic' => 'BIC',
            
            'select' => 'Select',

            // MESSAGES
            // gateway-alternative-payments.php
            'form_verification_select' => 'There was an issue with the verification process. Please try again or contact support.',
            'form_verification_phone_number' => 'Phone number is not valid. Please try again or contact support.',
            'form_verification_response_phone_number' => 'Problem with response phone number.',
            'form_verification_response_token' => 'There was an issue with the verification process. Please try again or contact support.',
            'form_verification_verification_code' => 'Phone verification code is invalid.',
            'form_verification_holder' => 'Holder name is invalid.',
            'form_verification_iban' => 'IBAN is invalid.',
            'form_verification_bic' => 'BIC is invalid.',

            'msg_cancel_false' => 'Alternative Payments transaction can\'t be cancelled. Please, contact support for more details.',
            'msg_note_cancel_false' => 'Customer received information, that Alternative Payments transaction can\'t be cancelled. Contact customer for more details.',

            // gateway-alternative-payments-handler.php
            'msg_check_response' => 'Alternative Payments Request Failure',

            // functions.php
            'msg_refund_refunded' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments refund failed. Transaction has already been refunded.</span>',
            'msg_refund_failed_amount' => "Alternative Payments transaction not refunded. Order amount doesn't match Transaction amount. You must refund order manual. In field amount set amount: ",
            'msg_refund_failed_partial_amount' => "Alternative Payments transaction not refunded. Alternative Payments doesn't support partial payments. Correct amount is: ",
            'msg_cancel_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments cancel failed. Transaction is already cancelled.</span>',
            'msg_pending' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments successfully created transaction. Payment pending. Transaction ID: </span>',
            'msg_pending_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments status change - Pending</span>',
            'msg_on_hold' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction received. Payment on-hold.</span>',
            'msg_on_hold_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments status change - On-hold</span>',
            //'msg_proccessing' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments successfully processed the transaction. Payment processing.</span>',
            'msg_proccessing' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments successfully processed the transaction.</span>',
            'msg_proccessing_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments status change - Processing</span>',
            'msg_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction failed. Payment failed.</span>',
            'msg_failed_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments status change - Failed</span>',
            'msg_failed_customer' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments failed</span>',
            'msg_canceled' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction cancelled. Payment cancelled.</span>',
            'msg_canceled_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction cancelled.</span>',
            'msg_refunded' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction refunded. Payment refunded.</span>',
            'msg_refunded_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments status change - Refunded</span>',
            'msg_error' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments encountered some error.</span>',
            'msg_refund_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments refund failed.Transaction is no longer in pending status.</span>',
            'msg_refund_pending' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments refund is on hold. You will get notified about refund status.</span>',
            'msg_canceled_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments cancelled failed. Transaction is no longer in pending status.</span>',
            'msg_failed_phone_number' => '<span style="background-color: #FCFFD5; padding: 3px;">There was an issue with response data. Please try again later.</span>',
            'msg_save_transaction_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments already created transaction for this order. Order ID: </span>',
            'msg_save_transaction_failed_notice' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction has already been created for this order.</span>',
            'msg_isf_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction is ISF failed.</span>',
            'msg_isf_failed_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction is ISF failed.</span>',
            'msg_invalid_failed' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payment transaction is invalid.</span>',
            'msg_invalid_failed_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payment transaction is invalid.</span>',
            'msg_invalid_limit' => '<span style="background-color: #FCFFD5; padding: 3px;">Payment limit reached</span>',
            'msg_invalid_limit_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Status changed to failed.</span>',
            'invalid_form_data' => '<span style="background-color: #FCFFD5; padding: 3px;">Invalid form data.</span>',
            'msg_chargeback' => '<span style="background-color: #FCFFD5; padding: 3px;">Important. Alternative Payments transaction returned! Transaction chargeback.</span>',
            'msg_chargeback_status' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments transaction returned! Transaction chargeback.</span>',

            'msg_approved_as_founded' => '<span style="background-color: #FCFFD5; padding: 3px;">Alternative Payments successfully processed the transaction. Payment processing.</span>',
            // %s will be replaced with data
            'send_merchant_tool_items' => 'Product name: %s, Product ID: %s, Quantity: %s, Total price: %s.',
            
            // Validation
            'form_verification_credit_card_number' => '<strong>Card Number</strong> is a required field.',
            'form_verification_credit_card_number_not_numbers' => '<strong>Card Number</strong> can be only number.',
            'form_verification_credit_card_number_more' => '<strong>Card Number</strong> must have 16 digits.',
            'form_verification_credit_card_number_less' => '<strong>Card Number</strong> must have 16 digits.',
            'form_verification_cvv2' => '<strong>Security Code</strong> is a required field.',
            'form_verification_cvv2_not_numbers' => '<strong>Security Code</strong> can be only number.',
            'form_verification_cvv2_more' => '<strong>Security Code</strong> must have 3 or 4 digits.',
            'form_verification_cvv2_less' => '<strong>Security Code</strong> must have 3 or 4 digits.',
            'form_verification_credit_card_types' => '<strong>Card Type</strong> is a required field.',
            'form_verification_exp_mounths' => '<strong>Mounth</strong> is a required field.',
            'form_verification_exp_years' => '<strong>Year</strong> is a required field.',
            
            // cc form
            'form_credit_card_number' => 'Card Number',
            'form_cvv2' => 'Security Code',
            'form_credit_card_type' => 'Select your Card Type',
            'Type' => 'SELECT',
            'form_exp_date' => 'Expiration date',
            'Mounth' => 'MONTH',
            'Year' => 'YEAR',
            'Day' => 'DAY',
            
            'form_document_id' => 'Document Id',
            'form_bank_code' => 'Bank Code',
            'form_verification_document_id' => '<strong>Document Id</strong> is a required field.',
            'form_verification_bank_code' => '<strong>Bank Code</strong> is a required field.',
            
            'form_birth_date' => 'Date of birth',
            'form_verification_birth_date_day' => '<strong>Date of birth</strong> choose day.',
            'form_verification_birth_date_month' => '<strong>Date of birth</strong> choose month.',
            'form_verification_birth_date_year' => '<strong>Date of birth</strong> choose year.',
            
            'Payment option used was' => 'Payment option used was',
        );

        return isset($t[$id])?$t[$id]:$id;
    }
}
?>
