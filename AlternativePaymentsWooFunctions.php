<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include_once 'AlternativePaymentsWooInclude.php';

if (!defined('ALTERNATIVE_PAYMENTS_WOO_META_KEY_NAME')) {
    DEFINE('ALTERNATIVE_PAYMENTS_WOO_META_KEY_NAME', 'alternative_payment_id');
}
if (!defined('ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME')) {
    DEFINE('ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME', 'alternative_payment_cancel_order_status');
}

/**
 * Handles refunds
 */
class AlternativePaymentsWooFunctions {

    /**
     *
     * @param type $order
     * @param type $posted
     * @param type $sms
     * @return type
     *
     * Send filled form to Merchant tool
     * return answer
     */
    public static function transaction($order, $posted, $sms = false) {
        $order->alternative_payment_method_option = $posted['alternative_payment_method_option'];

        switch ($sms) {
            case 'phone_verification':
                $transaction = self::make_transaction_phone_verification($order, $posted);
                break;
            default :
                $transaction = self::make_transaction($order, $posted);
                break;
        }
        
        $response = AlternativePaymentsWooTransaction::post($transaction);
        
        $everything_ok = self::check_response_data($response, $order);
        //$everything_ok = false;
        return $everything_ok;
    }

    /**
     * Restore Order Stock !!!
     * @param  int     $order_id   WC Order ID
     * @return mixed               Return type
     */
    public function restore_order_stock($order) {
        if (!get_option('woocommerce_manage_stock') == 'yes' && !sizeof($order->get_items()) > 0) {
            return;
        }

        foreach ($order->get_items() as $item) {
            if ($item['product_id'] > 0) {
                $_product = $order->get_product_from_item($item);
                if ($_product && $_product->exists() && $_product->managing_stock()) {
                    $old_stock = $_product->stock;
                    $qty = apply_filters('woocommerce_order_item_quantity', $item['qty'], $order, $item);
                    $new_quantity = $_product->increase_stock($qty);
                    do_action('woocommerce_auto_stock_restored', $_product, $item);
                    $order->add_order_note(sprintf(__('Item #%s stock incremented from %s to %s.', 'woocommerce'), $item['product_id'], $old_stock, $new_quantity));
                    $order->send_stock_notifications($_product, $new_quantity, $item['qty']);
                }
            }
        }
    }

// End restore_order_stock()

    /**
     *
     * @param type $data
     * @return boolean
     *
     * Recieving all data sent from Merchant tool (Info for change status)
     */
    public static function callback_merchant_tool($data) {
        $transaction_id = self::get_transaction_id($data);
        $order = self::get_order_from_transaction_id($transaction_id);
        $everything_ok = self::check_response_data($data, $order);

        return false;
    }

    /**
     *
     * @param type $order
     * @param type $data
     * @return type
     *
     * Send data to see if order can be refunded
     */
    public static function refund_order($order, $data) {
        $data->reason = AlternativePaymentsWooReturnReason::UNSATISFIED_CUSTOMER;

        $data_model = self::make_transaction_refund($order, $data);
        $parentCode = self::get_transaction_id($order->id, 'woo');

        $get_transaction_data = AlternativePaymentsWooTransaction::get($parentCode);

        // Check if transaction is allready refunded or voided
        if (isset($get_transaction_data->status) && $get_transaction_data->status == "Refunded") {
            $order->add_order_note(AlternativePaymentsWooText::en('msg_refund_refunded'));
            return false;
        }

        // Proccess to refund transaction
        $response = AlternativePaymentsWooRefund::post($data_model, $parentCode);

        // If status is Approved, transaction is refunded, and chang status for displaying message
        if (isset($response->status) && $response->status == "Pending") {
            $response->status = 'refund.pending';
        }

        $everything_ok = self::check_response_data($response, $order);

        return $everything_ok;
    }

    /**
     *
     * @param type $order
     * @param type $data
     * @return type
     *
     * Send data to see if order can be canceled
     */
    public static function cancel_order($order) {
        $post_meta_data = get_post_meta($order->id, ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME);
        if ($post_meta_data[0] == 'cancelled') {
            update_post_meta($order->id, ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME, 'done');
            return true;
        }

        $data->reason = AlternativePaymentsWooReturnReason::UNSATISFIED_CUSTOMER;

        $data_model = self::make_transaction_cancel($order, $data);
        $parentCode = self::get_transaction_id($order->id, 'woo');

        $get_transaction_data = AlternativePaymentsWooTransaction::get($parentCode);

        // Check if transaction is allready refunded or voided
        if (isset($get_transaction_data->status) && $get_transaction_data->status == "Voided") {
            $order->add_order_note(AlternativePaymentsWooText::en('msg_cancel_failed'));
            return false;
        }

        // Proccess to refund transaction
        $response = AlternativePaymentsWooVoid::post($data_model, $parentCode);
        return true;
    }

    /**
     *
     * @param type $response
     * @param type $order
     * @return boolean
     *
     * Connect status from Merchant tool with Wordpress
     */
    public static function check_response_data($response, $order) {
        $everything_ok = false;
        $respone_type = isset($response->type) ? $response->type : $response->status;
        $respone_type = (!$respone_type && isset($response->Code)) ? $response->Code : $respone_type;

        $redirectUrl = isset($response->redirectUrl) && $response->redirectUrl ? $response->redirectUrl : true;

        $status = ($respone_type) ? self::status_arr($respone_type) : 'error';

        $payment_name = self::get_selected_payment_method($order, 'name');
        $payment_id = self::get_selected_payment_method($order);

        switch ($status) {
            case 'pending':
                $transaction_id = self::get_transaction_id($response);
                $bool = self::saveTransactionData($order->id, $transaction_id, $status);
                if ($bool) {
                    if ($payment_name) {
                        $order->add_order_note(AlternativePaymentsWooText::en('Payment option used was ') . $payment_name);
                    }

                    $order->add_order_note(AlternativePaymentsWooText::en('msg_pending') . $transaction_id);
                    // Change status
                    $order->update_status($status, AlternativePaymentsWooText::en('msg_pending_status'));
                    // Reduce stock levels
                    $order->reduce_order_stock();
                    // Remove cart
                    WC()->cart->empty_cart();
                    $everything_ok = $redirectUrl;
                }
                break;
            case 'on-hold':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_on_hold'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_on_hold_status'));
                $everything_ok = $redirectUrl;
                break;
            case 'processing':
                //$order->add_order_note(AlternativePaymentsWooText::en('msg_proccessing'));
                //$order->update_status($status, AlternativePaymentsWooText::en('msg_proccessing_status'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_proccessing'));
                $everything_ok = $redirectUrl;
                break;
            case 'approved_as_founded':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_approved_as_founded'));
                $everything_ok = $redirectUrl;
                break;
            case 'failed':
                wc_add_notice(AlternativePaymentsWooText::en('msg_failed_customer'), $notice_type = 'error');
                $order->add_order_note(AlternativePaymentsWooText::en('msg_failed'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_failed_status'));
                break;
            case 'cancelled':
                // save status that came direct from API because when status is changed call function cancel_order
                $post_meta_data = get_post_meta($order->id, ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME);
                if ($post_meta_data) {
                    update_post_meta($order->id, ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME, 'cancelled');
                } else {
                    add_post_meta($order->id, ALTERNATIVE_PAYMENTS_WOO_CANCEL_KEY_NAME, 'cancelled', true);
                }


                $order->add_order_note(AlternativePaymentsWooText::en('msg_canceled'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_canceled_status'));
                $everything_ok = $redirectUrl;
                break;
            case 'refunded':
                self::restore_order_stock($order);
                $order->add_order_note(AlternativePaymentsWooText::en('msg_refunded'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_refunded_status'));
                $everything_ok = $redirectUrl;
                break;
            case 'error':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_error'));
                break;
            case 'refund_failed':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_refund_failed'));
                break;
            case 'refund_pending':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_refund_pending'));
                $order->update_status('on-hold', AlternativePaymentsWooText::en('msg_on_hold_status'));
                $everything_ok = $redirectUrl;
                break;
            case 'canceled_failed':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_canceled_failed'));
                break;
            case 'customer.created':
                //$order->add_order_note(AlternativePaymentsWooText::en('msg_canceled_failed'));
                break;
            case 'isf_failed':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_isf_failed'));
                wc_add_notice(AlternativePaymentsWooText::en('msg_isf_failed_status'), $notice_type = 'error');
                $order->update_status('failed', AlternativePaymentsWooText::en('msg_invalid_limit_status'));
                break;
            case 'invalid_failed':
                $order->add_order_note(AlternativePaymentsWooText::en('msg_invalid_failed'));
                wc_add_notice(AlternativePaymentsWooText::en('msg_invalid_failed_status'), $notice_type = 'error');
                $order->update_status('failed', AlternativePaymentsWooText::en('msg_invalid_limit_status'));
                break;
            case 'invalid_limit':
                wc_add_notice(AlternativePaymentsWooText::en('msg_invalid_limit'), $notice_type = 'error');
                $order->add_order_note(AlternativePaymentsWooText::en('msg_invalid_limit'));
                $order->update_status('failed', AlternativePaymentsWooText::en('msg_invalid_limit_status'));
                break;
            case 'invalid_form_data':
                wc_add_notice(AlternativePaymentsWooText::en('invalid_form_data'), $notice_type = 'error');
                break;
            case 'invalid_parameter_error':
                $msg = AlternativePaymentsWooText::en('Invalid value for ') . $response->Param;
                if ($response->Param == 'Country') {
                    /* if ($payment_id == 'teleingreso') {
                      $msg = $payment_name . AlternativePaymentsWooText::en(' can be used only in Spain');
                      }
                      if ($payment_id == 'ideal') {
                      $msg = $payment_name . AlternativePaymentsWooText::en(' can be used only in Nederland');
                      } */
                    $msg = 'Unsupported country for selected payment option';
                }

                wc_add_notice($msg, $notice_type = 'error');
                break;
            case 'chargeback':
                self::restore_order_stock($order);
                // wc_add_notice(AlternativePaymentsWooText::en('msg_chargeback'), $notice_type = 'error');
                $order->add_order_note(AlternativePaymentsWooText::en('msg_chargeback'));
                $order->update_status($status, AlternativePaymentsWooText::en('msg_chargeback_status'));
                $everything_ok = $redirectUrl;
                break;
        }
        return $everything_ok;
    }

    public static function check_sms_active($peyment_option) {
        return AlternativePaymentsWooSMS_Verification::getIsActive($peyment_option, 'check_is_active');
    }

    public static function send_phone_number($phone_number) {
        $data = self::make_transaction_phone_get_code($phone_number, self::get_api_product_key());
        $respons = AlternativePaymentsWooSMS_Verification::post($data, 'send_verification_code');

        $status = (isset($respons->token) && $respons->token) ? "OK" : "ERROR";
        $message = (isset($respons->token) && $respons->token) ? "" : AlternativePaymentsWooText::en('msg_failed_phone_number');

        if ($status == "OK") {
            $return = array(
                'status' => $status,
                'message' => $message,
                'token' => $respons->token,
                'phone' => $respons->phone,
            );
        } else {
            $return = array(
                'status' => $status,
                'message' => $message
            );
        }
        return $return;
    }

    public static function get_payments_for_country($country_code) {
        $respons = AlternativePaymentsWooRequest::get(false, false, false, array('country' => $country_code));
        $payment_methods = array();

        if (isset($respons->paymentOptions) && $respons->paymentOptions) {
            foreach ($respons->paymentOptions as $opt) {
                $payment_methods[] = strtolower($opt->id);
            }
        }

        if ($respons) {
            $return = array(
                'status' => 'OK',
                'payments' => $payment_methods,
            );
        } else {
            $return = array(
                'status' => 'ERROR',
                'message' => 'No data'
            );
        }
        return $return;
    }

    public static function make_transaction($order, $posted) {
        $data = get_option('woocommerce_alternative_settings');

        $selected_payment_option = $posted['alternative_payment_method_option'];

        $customer = new AlternativePaymentsWooCustomer();

        $customer->setEmail($posted['billing_email']);
        $customer->setFirstName($posted['billing_first_name']);
        $customer->setLastName($posted['billing_last_name']);
        $customer->setAddress($posted['billing_address_1']);
        $customer->setAddress2($posted['billing_address_2']);
        $customer->setCity($posted['billing_city']);
        $customer->setZip($posted['billing_postcode']);
        $customer->setCountry($posted['billing_country']);
        $customer->setState($posted['billing_state']);
        $customer->setPhone($posted['billing_phone']);

        $birth_date_days = isset($_POST['alternative_payment_birth_date_day']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_day']) : '';
        $birth_date_mounths = isset($_POST['alternative_payment_birth_date_mounth']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_mounth']) : '';
        $birth_date_years = isset($_POST['alternative_payment_birth_date_year']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_year']) : '';

        if (isset($birth_date_days[$selected_payment_option]) &&
                isset($birth_date_mounths[$selected_payment_option]) &&
                isset($birth_date_years[$selected_payment_option])) {

            $bd = $birth_date_mounths[$selected_payment_option] . "/" . $birth_date_days[$selected_payment_option] . "/" . $birth_date_years[$selected_payment_option];
            $customer->setBirthDate($bd);
        }

        $payment = new AlternativePaymentsWooPayment();

        $holder_names = isset($posted['alternative_payment_holder_name']) ? array_map('wc_clean', $posted['alternative_payment_holder_name']) : '';
        $ibans = isset($posted['alternative_payment_iban']) ? array_map('wc_clean', $posted['alternative_payment_iban']) : '';
        $bics = isset($posted['alternative_payment_bic']) ? array_map('wc_clean', $posted['alternative_payment_bic']) : '';

        $credit_card_numbers = isset($_POST['alternative_payment_credit_card_number']) ? array_map('wc_clean', $_POST['alternative_payment_credit_card_number']) : '';
        $cvv2s = isset($_POST['alternative_payment_cvv2']) ? array_map('wc_clean', $_POST['alternative_payment_cvv2']) : '';
        $exp_years = isset($_POST['alternative_payment_exp_year']) ? array_map('wc_clean', $_POST['alternative_payment_exp_year']) : '';
        $exp_mounths = isset($_POST['alternative_payment_exp_mounth']) ? array_map('wc_clean', $_POST['alternative_payment_exp_mounth']) : '';
        $credit_card_types = isset($_POST['alternative_payment_credit_card_type']) ? array_map('wc_clean', $_POST['alternative_payment_credit_card_type']) : '';

        $document_ids = isset($_POST['alternative_payment_document_id']) ? array_map('wc_clean', $_POST['alternative_payment_document_id']) : '';
        $bank_codes = isset($_POST['alternative_payment_bank_code']) ? array_map('wc_clean', $_POST['alternative_payment_bank_code']) : '';

        $payment->setPaymentOption($selected_payment_option);

        if (isset($holder_names[$selected_payment_option])) {
            $payment->setHolder($holder_names[$selected_payment_option]);
        }
        if (isset($ibans[$selected_payment_option])) {
            $payment->setIBAN($ibans[$selected_payment_option]);
        }
        if (isset($bics[$selected_payment_option])) {
            $payment->setBIC($bics[$selected_payment_option]);
        }

        if (isset($credit_card_numbers[$selected_payment_option])) {
            $payment->setCreditCardNumber($credit_card_numbers[$selected_payment_option]);
        }
        if (isset($cvv2s[$selected_payment_option])) {
            $payment->setCvv2($cvv2s[$selected_payment_option]);
        }
        if (isset($exp_years[$selected_payment_option])) {
            $payment->setExpYear($exp_years[$selected_payment_option]);
        }
        if (isset($exp_mounths[$selected_payment_option])) {
            $payment->setExpMounth($exp_mounths[$selected_payment_option]);
        }
        if (isset($credit_card_types[$selected_payment_option])) {
            $payment->setCreditCardType($credit_card_types[$selected_payment_option]);
        }

        if (isset($document_ids[$selected_payment_option])) {
            $payment->setDocumentId($document_ids[$selected_payment_option]);
        }
        if (isset($bank_codes[$selected_payment_option])) {
            $payment->setBankCode($bank_codes[$selected_payment_option]);
        }

        $redirect_url = new AlternativePaymentsWooRedirectUrls();
        $redirect_url->setReturnUrl(self::get_return_url($posted['order_back_url']));
        $redirect_url->setCancelUrl(self::get_cancel_url());

        $transaction = new AlternativePaymentsWooTransactionModel();
        $transaction->setCustomer($customer);
        $transaction->setPayment($payment);
        $transaction->setRedirectUrls($redirect_url);

        $transaction->setMerchantPassThruData($order->id);
        $transaction->setDescription(self::get_order_items($order));

        $transaction->setAmount(self::decode_amount($order->get_total()));
        $transaction->setCurrency($order->get_order_currency());
        $transaction->setIPAddress(self::get_user_ip());

        return $transaction;
    }

    public static function make_transaction_phone_verification($order, $posted) {
        $data = get_option('woocommerce_alternative_settings');

        $selected_payment_option = $posted['alternative_payment_method_option'];

        $customer = new AlternativePaymentsWooCustomer();

        $customer->setEmail($posted['billing_email']);
        $customer->setFirstName($posted['billing_first_name']);
        $customer->setLastName($posted['billing_last_name']);
        $customer->setAddress($posted['billing_address_1']);
        $customer->setAddress2($posted['billing_address_2']);
        $customer->setCity($posted['billing_city']);
        $customer->setZip($posted['billing_postcode']);
        $customer->setCountry($posted['billing_country']);
        $customer->setState($posted['billing_state']);
        $customer->setPhone($posted['billing_phone']);

        $birth_date_days = isset($_POST['alternative_payment_birth_date_day']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_day']) : '';
        $birth_date_mounths = isset($_POST['alternative_payment_birth_date_mounth']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_mounth']) : '';
        $birth_date_years = isset($_POST['alternative_payment_birth_date_year']) ? array_map('wc_clean', $_POST['alternative_payment_birth_date_year']) : '';

        if (isset($birth_date_days[$selected_payment_option]) &&
                isset($birth_date_mounths[$selected_payment_option]) &&
                isset($birth_date_years[$selected_payment_option])) {

            $bd = $birth_date_mounths[$selected_payment_option] . "/" . $birth_date_days[$selected_payment_option] . "/" . $birth_date_years[$selected_payment_option];
            $customer->setBirthDate($bd);
        }

        $payment = new AlternativePaymentsWooPayment();

        $holder_names = isset($posted['alternative_payment_holder_name']) ? array_map('wc_clean', $posted['alternative_payment_holder_name']) : '';
        $ibans = isset($posted['alternative_payment_iban']) ? array_map('wc_clean', $posted['alternative_payment_iban']) : '';
        $bics = isset($posted['alternative_payment_bic']) ? array_map('wc_clean', $posted['alternative_payment_bic']) : '';

        $credit_card_numbers = isset($_POST['alternative_payment_credit_card_number']) ? array_map('wc_clean', $_POST['alternative_payment_credit_card_number']) : '';
        $cvv2s = isset($_POST['alternative_payment_cvv2']) ? array_map('wc_clean', $_POST['alternative_payment_cvv2']) : '';
        $exp_years = isset($_POST['alternative_payment_exp_year']) ? array_map('wc_clean', $_POST['alternative_payment_exp_year']) : '';
        $exp_mounths = isset($_POST['alternative_payment_exp_mounth']) ? array_map('wc_clean', $_POST['alternative_payment_exp_mounth']) : '';
        $credit_card_types = isset($_POST['alternative_payment_credit_card_type']) ? array_map('wc_clean', $_POST['alternative_payment_credit_card_type']) : '';

        $document_ids = isset($_POST['alternative_payment_document_id']) ? array_map('wc_clean', $_POST['alternative_payment_document_id']) : '';
        $bank_codes = isset($_POST['alternative_payment_bank_code']) ? array_map('wc_clean', $_POST['alternative_payment_bank_code']) : '';

        $payment->setPaymentOption($selected_payment_option);

        if (isset($holder_names[$selected_payment_option])) {
            $payment->setHolder($holder_names[$selected_payment_option]);
        }
        if (isset($ibans[$selected_payment_option])) {
            $payment->setIBAN($ibans[$selected_payment_option]);
        }
        if (isset($bics[$selected_payment_option])) {
            $payment->setBIC($bics[$selected_payment_option]);
        }

        if (isset($credit_card_numbers[$selected_payment_option])) {
            $payment->setCreditCardNumber($credit_card_numbers[$selected_payment_option]);
        }
        if (isset($cvv2s[$selected_payment_option])) {
            $payment->setCvv2($cvv2s[$selected_payment_option]);
        }
        if (isset($exp_years[$selected_payment_option])) {
            $payment->setExpYear($exp_years[$selected_payment_option]);
        }
        if (isset($exp_mounths[$selected_payment_option])) {
            $payment->setExpMounth($exp_mounths[$selected_payment_option]);
        }
        if (isset($credit_card_types[$selected_payment_option])) {
            $payment->setCreditCardType($credit_card_types[$selected_payment_option]);
        }

        if (isset($document_ids[$selected_payment_option])) {
            $payment->setDocumentId($document_ids[$selected_payment_option]);
        }
        if (isset($bank_codes[$selected_payment_option])) {
            $payment->setBankCode($bank_codes[$selected_payment_option]);
        }

        $redirect_url = new AlternativePaymentsWooRedirectUrls();
        $redirect_url->setReturnUrl(self::get_return_url($posted['order_back_url']));
        $redirect_url->setCancelUrl(self::get_cancel_url());

        $transaction = new AlternativePaymentsWooTransactionModel();
        $transaction->setCustomer($customer);
        $transaction->setPayment($payment);
        $transaction->setRedirectUrls($redirect_url);

        $transaction->setMerchantPassThruData($order->id);
        $transaction->setDescription(self::get_order_items($order));

        $transaction->setAmount(self::decode_amount($order->get_total()));
        $transaction->setCurrency($order->get_order_currency());
        $transaction->setIPAddress(self::get_user_ip());

        $transaction->setMerchantPassThruData($order->id);
        $transaction->setDescription(self::get_order_items($order));

        //$phone_numbers = isset($posted['payment_phone_number']) ? array_map('wc_clean', $posted['payment_phone_number']) : '';
        //$respons_phone_numbers = isset($posted['payment_response_phone_number']) ? array_map('wc_clean', $posted['payment_response_phone_number']) : '';
        $respons_tokens = isset($posted['payment_response_token']) ? array_map('wc_clean', $posted['payment_response_token']) : '';
        $sms_verification_codes = isset($posted['payment_sms_verification_code']) ? array_map('wc_clean', $posted['payment_sms_verification_code']) : '';

        $phoneVerification = new AlternativePaymentsWooPhoneVerification();
        $phoneVerification->setToken($respons_tokens[$selected_payment_option]);
        $phoneVerification->setPin($sms_verification_codes[$selected_payment_option]);
        $transaction->setPhoneVerification($phoneVerification);


        return $transaction;
    }

    public static function make_transaction_phone_get_code($phone_number, $product_key) {
        $phoneVerification = new AlternativePaymentsWooPhoneVerification();
        $phoneVerification->setPhoneNumber($phone_number);
        $phoneVerification->setProductKey($product_key);

        return $phoneVerification;
    }

    public static function make_transaction_refund($order, $posted) {
        $posted = (object) $posted;

        $refund = new AlternativePaymentsWooRefundModel();
        $refund->setReason($posted->reason);

        return $refund;
    }

    public static function make_transaction_cancel($order, $posted) {
        $posted = (object) $posted;

        $void = new AlternativePaymentsWooVoidModel();
        $void->setReason($posted->reason);

        return $void;
    }

    public static function saveTransactionData($order_id, $transaction_id, $status, $key_name) {
        // wc_add_order_item_meta( $item_id, $meta_key, $meta_value, $unique );        
        $item_meta_id = wc_add_order_item_meta($order_id, ALTERNATIVE_PAYMENTS_WOO_META_KEY_NAME, $transaction_id, true);

        if ($item_meta_id < 1) {
            $order = wc_get_order($order_id);
            $order->add_order_note(AlternativePaymentsWooText::en('msg_save_transaction_failed') . $order_id);
            wc_add_notice(AlternativePaymentsWooText::en('msg_save_transaction_failed_notice'), $notice_type = 'error');
            return false;
        }
        return true;
    }

    public static function get_transaction_id($response, $data_base = '') {
        if ($data_base) {
            // $response is transaction_id
            $id = wc_get_order_item_meta($response, ALTERNATIVE_PAYMENTS_WOO_META_KEY_NAME, true);
        } else {
            $id = isset($response->resource->id) ? $response->resource->id : $response->id;
            if (substr($id, 0, 3) == 'ref') {
                if (isset($response->resource->originalTransactionId)) {
                    add_post_meta('999999999', $id, $response->resource->originalTransactionId, true);
                } else {
                    $old_id = $id;
                    $temp_id = get_post_meta('999999999', $id, true);
                    $id = json_encode($temp_id);
                    $id = str_replace('"', '', $id);
                }
            }

            if (substr($id, 0, 4) == 'void') {
                if (isset($response->resource->originalTransaction->merchantPassThruData)) {
                    $id = $response->resource->originalTransaction->merchantPassThruData;
                }
            }

            if (substr($id, 0, 3) == 'trn') {
                if (isset($response->resource->merchantPassThruData)) {
                    $id = $response->resource->merchantPassThruData;
                }
            }
        }
        return $id;
    }

    public static function get_order_from_transaction_id($transaction_id) {
        if (substr($transaction_id, 0, 3) == 'ref' || substr($transaction_id, 0, 3) == 'trn' || substr($transaction_id, 0, 4) == 'void') {
            global $wpdb;
            $sql = "SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key LIKE 'alternative_payment_id' AND meta_value = '$transaction_id'";
            $res = $wpdb->get_results($sql);
            $order = new WC_Order($res[0]->order_item_id);
        } else {
            $order = new WC_Order($transaction_id);
        }
        return $order;
    }

    public static function get_order_items($order) {
        $items = $order->get_items();
        $return = '';
        foreach ($items as $item) {
            $return .=
                    sprintf(AlternativePaymentsWooText::en('send_merchant_tool_items'), $item['name'], $item['item_meta']['_product_id'][0], $item['item_meta']['_qty'][0], $item['item_meta']['_line_total'][0] . $order->get_order_currency()
            );
        }
        return $return;
    }

    public static function get_api_key() {
        $data = get_option('woocommerce_alternative_settings');
        return $data['api_key'];
    }

    public static function get_api_product_key() {
        $data = get_option('woocommerce_alternative_settings');
        return $data['product_key'];
    }

    public static function get_api_url() {
        $data = get_option('woocommerce_alternative_settings');
        return $data['api_url'];
    }

    public static function get_return_url($back_url) {
        $data = get_option('woocommerce_alternative_settings');

        if (isset($data['return_url']) && $data['return_url']) {
            $return = $data['return_url'];
        } else {
            $return = $back_url;
        }
        return $return;
    }

    public static function get_cancel_url() {
        $data = get_option('woocommerce_alternative_settings');
        return $data['cancel_url'];
    }

    public static function get_status_check($post_id, $type) {
        global $wpdb;
        $meta_key = 'alternative_payment_check_status_temp_' . $type;
        $sql = "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '$meta_key' AND post_id = '$post_id'";
        $res = $wpdb->get_results($sql);
        $return = ($res[0]->meta_value) ? $res[0]->meta_value : 0;
        return $return;
    }

    public static function set_status_check($post_id, $type) {
        global $wpdb;
        $meta_key = 'alternative_payment_check_status_temp_' . $type;
        $meta_value = self::get_status_check($post_id, $type);
        $meta_value += 1;
        if ($meta_value > 1) {
            $sql = "UPDATE {$wpdb->postmeta} SET meta_value = '$meta_value' WHERE meta_key = '$meta_key' AND post_id = '$post_id'";
            $wpdb->query($sql);
        } else {
            $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUE ($post_id, '$meta_key', '$meta_value' )";
            $wpdb->query($sql);
        }
        return false;
    }

    public static function get_selected_payment_method($response, $type = 'id') {
        $selected_payment_option = isset($response->alternative_payment_method_option) ? $response->alternative_payment_method_option : '';

        if ($type == 'name') {
            $payment_methods = get_option('woocommerce_alternative_payment_options');
            foreach ($payment_methods as $row) {
                if ($row['id'] == $selected_payment_option) {
                    $selected_payment_option_name = $row['payment_name'];
                }
            }
        }

        switch ($type) {
            case 'name':
                $return = $selected_payment_option_name;
                break;
            default :
                $return = $selected_payment_option;
                break;
        }

        return $return;
    }

    public static function status_arr($status) {
        $arr = array(
            'transaction.pending' => 'pending',
            'transaction.approved' => 'on-hold',
            'transaction.funded' => 'processing',
            'transaction.declined' => 'failed',
            'void.succeeded' => 'cancelled',
            'void.declined' => 'failed',
            'refund.pending' => 'refund_pending',
            'refund.succeeded' => 'refunded',
            'refund.declined' => 'failed',
            // APWOO-23
            'transaction.chargeback' => 'chargeback',
            // 'transaction.chargeback' => 'refunded',
            'transaction.isf' => 'isf_failed',
            'transaction.invalid' => 'invalid_failed',
            'transaction_can_not_be_voided' => 'refund_failed',
            'canceled_failed' => 'canceled_failed',
            'refund.approved' => 'refunded',
            'customer.created' => 'customer.created',
            'volume_of_transaction_exceeded_limit' => 'invalid_limit',
            'invalid_iban' => 'invalid_form_data',
            'information_on_black_list' => 'invalid_form_data',
            'Created' => 'pending',
            'Pending' => 'pending',
            'Approved' => 'on-hold',
            'Funded' => 'processing',
            'Declined' => 'failed',
            'invalid_parameter_error' => 'invalid_parameter_error',
            'missing_required_parameter' => 'invalid_parameter_error',
            'payment_error' => 'invalid_parameter_error',
            'approved_as_founded' => 'approved_as_founded',
        );

        // Get data from setting form
        $data = get_option('woocommerce_alternative_settings');
        $proccessing_status = $data['proccessing_status'];

        if (($status == 'transaction.approved' || $status == 'Approved' ) && $proccessing_status == 'approved') {
            //$status = 'transaction.funded';
            $status = 'approved_as_founded';
        }

        return isset($arr[$status]) ? $arr[$status] : '';
    }

    public static function decode_amount($data) {
        $data = number_format($data, 2, '.', ',');
        $data = str_replace(',', '', $data);
        return $data * 100;
    }

    public static function encode_amount($data) {
        $data = $data / 100;
        return number_format($data, 2, '.', ',');
    }

    public static function get_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}
