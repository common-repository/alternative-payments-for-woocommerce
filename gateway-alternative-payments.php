<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
  Plugin Name: Alternative Payments for WooCommerce
  Plugin URI: http://alternativepayments.com/support/plugins.html
  Description: Convert millions of international consumers that don't use credit cards.
  Version: 1.0.9
  Author: Alternative Payments
  Author URI: http://www.alternativepayments.com/
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR')) {
    DEFINE('ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
}

include_once 'AlternativePaymentsWooInclude.php';

// function that call jquery to clean item actions
function alternative_payments_woo_load_custom_wp_admin_style() {
    global $woocommerce, $post;    
    
    $order = wc_get_order($post->ID);

    if (is_admin() && isset($order->post->post_type) && $order->post->post_type == 'shop_order' && isset($order->payment_method) && $order->payment_method == 'alternative') {
        wp_enqueue_script('alternative_peyment_admin_script', ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR . 'js/alternative_peyment_admin_script.js', array('jquery'), 1.0);
        wp_enqueue_script('alternative_peyment_admin_script');
    }
}

add_action('admin_enqueue_scripts', 'alternative_payments_woo_load_custom_wp_admin_style');

function woocommerce_gateway_alternative_payments_woo_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }
    /**
     * Localisation
     */
    load_plugin_textdomain('wc-gateway-name', false, plugin_dir_path(__FILE__) . '/languages');

    function check_response() {
        global $woocommerce;

        $data = json_decode(file_get_contents('php://input'));

        if ($data) {
            AlternativePaymentsWooFunctions::callback_merchant_tool($data);
            exit;
        }
        wp_die(AlternativePaymentsWooText::en('msg_check_response'), "Alternative Payments", array('response' => 500));
    }

    // Hook for getting response from Merchant tool
    add_action('woocommerce_api_wc_gateway_alter', 'check_response');

    /**
     * Gateway class
     */
    class WC_Gateway_Alternative extends WC_Payment_Gateway {

        const PEYMENT_OPTIONS = "SEPA";

        public function __construct() {
            global $woocommerce;
            $this->id = 'alternative';
            $this->icon = apply_filters('woocommerce_cheque_icon', ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR . 'img/alternative_payment_logo.png');
            $this->has_fields = false;
            $this->method_title = AlternativePaymentsWooText::en('method_title');
            $this->method_description = AlternativePaymentsWooText::en('method_description');
            $this->supports = array(
                'products',
                'subscription_cancellation',
                'refunds'
            );

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->sms_verification = $this->get_option('sms_verification', $this->sms_verification);
            $this->api_key = $this->get_option('api_key', $this->api_key);
            $this->product_url = $this->get_option('product_url', $this->product_url);
            $this->api_url = $this->get_option('api_url', $this->api_url);

            $this->return_url = $this->get_option('return_url', $this->return_url);
            $this->cancel_url = $this->get_option('cancel_url', $this->cancel_url);

            $this->description = $this->get_option('description');
            $this->instructions = $this->get_option('instructions', $this->description);


            // Payments fields shown on admin side
            $this->payment_options = get_option('woocommerce_alternative_payment_options', array(
                array(
                    'id' => 'sepa',
                    'enable' => '',
                    'payment_name' => 'SEPA',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'eps',
                    'enable' => '',
                    'payment_name' => 'EPS',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'ideal',
                    'enable' => '',
                    'payment_name' => 'iDEAL',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'giropay',
                    'enable' => '',
                    'payment_name' => 'Giropay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'paysafe',
                    'enable' => '',
                    'payment_name' => 'PaySafeCard',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'teleingreso',
                    'enable' => '',
                    'payment_name' => 'TeleIngreso',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'przelewy24',
                    'enable' => '',
                    'payment_name' => 'Przelewy24',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'mistercash',
                    'enable' => '',
                    'payment_name' => 'MisterCash',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'safetypay',
                    'enable' => '',
                    'payment_name' => 'SafetyPay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'poli',
                    'enable' => '',
                    'payment_name' => 'POLi',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'qiwi',
                    'enable' => '',
                    'payment_name' => 'Qiwi',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'sofortuberweisung',
                    'enable' => '',
                    'payment_name' => 'Sofortuberweisung',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'cashu',
                    'enable' => '',
                    'payment_name' => 'CashU',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'AliPay',
                    'enable' => '',
                    'payment_name' => 'Alipay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'TenPay',
                    'enable' => '',
                    'payment_name' => 'Tenpay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'UnionPay',
                    'enable' => '',
                    'payment_name' => 'UnionPay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'Mangirkart',
                    'enable' => '',
                    'payment_name' => 'Mangirkart',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'verkkopankki',
                    'enable' => '',
                    'payment_name' => 'Verkkopankki',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'trustpay',
                    'enable' => '',
                    'payment_name' => 'TrustPay',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                /*array(
                    'id' => 'creditcard',
                    'enable' => '',
                    'payment_name' => 'Credit card',
                 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', 
                ),*/
                array(
                    'id' => 'brazilpaybanktransfer',
                    'enable' => '',
                    'payment_name' => 'BrazilPay Bank Transfer',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'brazilpayboleto',
                    'enable' => '',
                    'payment_name' => 'BrazilPay Boleto Bancário',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                array(
                    'id' => 'brazilpaychargecard',
                    'enable' => '',
                    'payment_name' => 'BrazilPay Charge Card',
                /* 'processing_status' => '',
                  'product_key' => '',
                  'api_key' => '',
                  'description' => '', */
                ),
                    )
            );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'save_payment_options_details'));
            add_action('woocommerce_thankyou_cheque', array($this, 'thankyou_page'));

            add_action('wp_ajax_alternative_payment_check_phone_verification', 'check_sms_response', 10);
            add_action('wp_ajax_nopriv_alternative_payment_check_phone_verification', 'check_sms_response', 10);

            add_action('wp_ajax_alternative_payment_check_payment_methods_for_country', 'check_payment_methods_for_country', 10);
            add_action('wp_ajax_nopriv_alternative_payment_check_payment_methods_for_country', 'check_payment_methods_for_country', 10);

            add_action('wp_print_scripts', array($this, 'add_inspire_scripts'));
        }

        // Fields on admin side
        public function init_form_fields() {
            global $woocommerce;
            $sms_text = $this->is_sms_active() ? AlternativePaymentsWooText::en('sms_active_true') : AlternativePaymentsWooText::en('sms_active_false');

            $this->form_fields = array(
                'enabled' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_enable_title'),
                    'type' => 'checkbox',
                    'label' => AlternativePaymentsWooText::en('form_field_enable_label'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_title_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_title_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_title_default'),
                    'desc_tip' => true,
                ),
                'sms_verification' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_sms_verification_title'),
                    'type' => 'checkbox',
                    'label' => $sms_text,
                    'default' => 'no',
                    'custom_attributes' => array('disabled' => 'disabled'),
                ),
                'product_key' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_product_key_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_product_key_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_product_key_default'),
                    'desc_tip' => true,
                ),
                'api_key' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_api_key_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_api_key_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_api_key_default'),
                    'desc_tip' => true,
                ),
                'api_url' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_api_url_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_api_url_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_api_url_default'),
                    'desc_tip' => true,
                ),
                'return_url' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_return_url_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_return_url_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_return_url_default'),
                    'desc_tip' => true,
                ),
                'cancel_url' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_cancel_url_title'),
                    'type' => 'text',
                    'description' => AlternativePaymentsWooText::en('form_field_cancel_url_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_cancel_url_default'),
                    'desc_tip' => true,
                ),
                'proccessing_status' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_proccessing_status_title'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select',
                    'description' => AlternativePaymentsWooText::en('form_field_proccessing_status_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_proccessing_status_default'),
                    'desc_tip' => true,
                    'options' => array(
                        'funded' => AlternativePaymentsWooText::en('form_field_proccessing_status_option_funded'),
                        'approved' => AlternativePaymentsWooText::en('form_field_proccessing_status_option_approved'),
                    )
                ),
                'description' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_description_title'),
                    'type' => 'textarea',
                    'description' => AlternativePaymentsWooText::en('form_field_description_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_description_default'),
                    'desc_tip' => true,
                ),
                'instructions' => array(
                    'title' => AlternativePaymentsWooText::en('form_field_instructions_title'),
                    'type' => 'textarea',
                    'description' => AlternativePaymentsWooText::en('form_field_instructions_description'),
                    'default' => AlternativePaymentsWooText::en('form_field_instructions_default'),
                    'desc_tip' => true,
                ),
                'payment_options' => array(
                    'type' => 'payment_options',
                    'description' => AlternativePaymentsWooText::en('form_field_description_description'),
                ),
            );
        }

        /**
         * Generate payment options details html.
         *
         * @return string
         */
        public function generate_payment_options_html() {
            ob_start();
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc"><?= AlternativePaymentsWooText::en('Payment options'); ?>:</th>
                <td class="forminp" id="bacs_accounts">
                    <table class="widefat wc_input_table sortable" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="sort">&nbsp;</th>
                                <th><?= AlternativePaymentsWooText::en('form_field_enable_title'); ?></th>
                                <th><?= AlternativePaymentsWooText::en('Payment name'); ?></th>
                                <!-- th><?= AlternativePaymentsWooText::en('form_field_proccessing_status_title'); ?></th>
                                <th><?= AlternativePaymentsWooText::en('form_field_product_key_title'); ?></th>
                                <th><?= AlternativePaymentsWooText::en('form_field_api_key_title'); ?></th>
                                <th><?= AlternativePaymentsWooText::en('form_field_description_title'); ?></th -->
                            </tr>
                        </thead>
                        <tbody class="accounts">
                            <?php
                            $i = -1;
                            if ($this->payment_options) {
                                foreach ($this->payment_options as $payment_option) {
                                    $i++;

                                    $enable_option = $payment_option['enable'] == 'on' ? 'checked="checked"' : '';
                                    echo '<tr class="account">
                                            <td class="sort"></td>
                                            <td><input type="checkbox" name="alternative_enable[' . $payment_option['id'] . ']" ' . $enable_option . '  /></td>
                                            <td><label>' . esc_attr(wp_unslash($payment_option['payment_name'])) . '</label>
                                               <input type="hidden" name="alternative_payment_id[' . $payment_option['id'] . ']" value="' . $payment_option['id'] . '" />
                                               <input type="hidden" name="alternative_payment_name[' . $payment_option['id'] . ']" value="' . $payment_option['payment_name'] . '" />
                                            </td>
                                        </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        /**
         * Save payment options details table.
         */
        public function save_payment_options_details() {            
            $payment_options = array();
            
            if (!empty($_POST)) {
                $posted = wp_unslash($_POST);
                
                if (isset($posted['alternative_payment_id'])) {
                    $ids = array_map('wc_clean', $posted['alternative_payment_id']);
                    $enables = array_map('wc_clean', $posted['alternative_enable']);
                    $payment_names = array_map('wc_clean', $posted['alternative_payment_name']);

                    foreach ($ids as $i => $option) {
                        if (!isset($ids[$i])) {
                            continue;
                        }
                        $payment_options[] = array(
                            'id' => $ids[$i],
                            'enable' => $enables[$i],
                            'payment_name' => $payment_names[$i],
                        );
                    }
                }
            }
            update_option('woocommerce_alternative_payment_options', $payment_options);
        }

        // Fields on checkout page
        public function payment_fields() {
            echo $this->description;
            $i = -1;
            if ($this->payment_options) {
                echo '<ul class="alternative_payment_options_select" style="margin: 0px; padding: 0px;">';
                foreach ($this->payment_options as $payment_option) {
                    $i++;
                    if ($payment_option['enable'] == 'on') {
                        $file_name = 'AlternativePaymentMetods/' . $payment_option['id'] . '.php';
                        echo '<li style="list-style: none; min-height: 40px; display: none;">
                                <img src="' . ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR . 'img/' . $payment_option['id'] . '.jpg" style="width:60px; max-height:40px; height:40px; float:right;" />
                                <div style="padding-top: 13px;">                                    
                                    <input id="alternative_payment_method_option_' . $payment_option['id'] . '" type="radio" class="input-radio" name="alternative_payment_method_option" value="' . $payment_option['id'] . '" data-order_button_text="" />
                                    <label for="alternative_payment_method_option_' . $payment_option['id'] . '">' . $payment_option['payment_name'] . '</label>
                                </div>
                                <div class="payment_box alternative_payment_method_option_' . $payment_option['id'] . '" style="display:none;">';
                        // Include data fields from folder AlternativePaymentMetods name of file is {payment_id}.php
                        include $file_name;
                        echo '</div>
                             </li>';
                    }
                }
                echo '</ul>';
            }
        }

        // Checking values from checkout form
        public function validate_fields() {
            global $woocommerce;
            
            if (empty($_POST)) {
                return false;
            }
                
            $posted = wp_unslash($_POST);
            
            if ($posted['payment_method'] != 'alternative') {
                wc_add_notice(AlternativePaymentsWooText::en('form_verification_select'), $notice_type = 'error');
                return false;
            }
            
            if (isset($posted['alternative_payment_method_option'])) {
                $selected_payment_option = $posted['alternative_payment_method_option'];

                $holder_names = isset($posted['alternative_payment_holder_name']) ? array_map('wc_clean', $posted['alternative_payment_holder_name']) : '';
                $ibans = isset($posted['alternative_payment_iban']) ? array_map('wc_clean', $posted['alternative_payment_iban']) : '';
                $bics = isset($posted['alternative_payment_bic']) ? array_map('wc_clean', $posted['alternative_payment_bic']) : '';

                $credit_card_numbers = isset($posted['alternative_payment_credit_card_number']) ? array_map('wc_clean', $posted['alternative_payment_credit_card_number']) : '';
                $cvv2s = isset($posted['alternative_payment_cvv2']) ? array_map('wc_clean', $posted['alternative_payment_cvv2']) : '';
                $exp_years = isset($posted['alternative_payment_exp_year']) ? array_map('wc_clean', $posted['alternative_payment_exp_year']) : '';
                $exp_mounths = isset($posted['alternative_payment_exp_mounth']) ? array_map('wc_clean', $posted['alternative_payment_exp_mounth']) : '';
                $credit_card_types = isset($posted['alternative_payment_credit_card_type']) ? array_map('wc_clean', $posted['alternative_payment_credit_card_type']) : '';

                $document_ids = isset($posted['alternative_payment_document_id']) ? array_map('wc_clean', $posted['alternative_payment_document_id']) : '';
                $bank_codes = isset($posted['alternative_payment_bank_code']) ? array_map('wc_clean', $posted['alternative_payment_bank_code']) : '';

                $birth_date_days = isset($posted['alternative_payment_birth_date_day']) ? array_map('wc_clean', $posted['alternative_payment_birth_date_day']) : '';
                $birth_date_mounths = isset($posted['alternative_payment_birth_date_mounth']) ? array_map('wc_clean', $posted['alternative_payment_birth_date_mounth']) : '';
                $birth_date_years = isset($posted['alternative_payment_birth_date_year']) ? array_map('wc_clean', $posted['alternative_payment_birth_date_year']) : '';


                $selected_payment_option_name = '';
                foreach ($this->payment_options as $row) {
                    if ($row['id'] == $selected_payment_option) {
                        $selected_payment_option_name = $row['payment_name'];
                    }
                }
                // Phone verification fields ONLY for SEPA
                if ($selected_payment_option === 'sepa') {
                    if ($this->is_sms_active()) {
                        $phone_numbers = isset($posted['payment_phone_number']) ? array_map('wc_clean', $posted['payment_phone_number']) : '';
                        $respons_phone_numbers = isset($posted['payment_response_phone_number']) ? array_map('wc_clean', $posted['payment_response_phone_number']) : '';
                        $respons_tokens = isset($posted['payment_response_token']) ? array_map('wc_clean', $posted['payment_response_token']) : '';
                        $sms_verification_codes = isset($posted['payment_sms_verification_code']) ? array_map('wc_clean', $posted['payment_sms_verification_code']) : '';
                        // Check phone number
                        if (isset($phone_numbers[$selected_payment_option])) {
                            if (empty($phone_numbers[$selected_payment_option])) {
                                wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_phone_number'), $notice_type = 'error');
                                return false;
                            }
                        }
                        // Check respons phone number
                        if (isset($respons_phone_numbers[$selected_payment_option])) {
                            if (empty($respons_phone_numbers[$selected_payment_option])) {
                                wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_response_phone_number'), $notice_type = 'error');
                                return false;
                            }
                        }
                        // Check respons token
                        if (isset($respons_tokens[$selected_payment_option])) {
                            if (empty($respons_tokens[$selected_payment_option])) {
                                wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_response_token'), $notice_type = 'error');
                                return false;
                            }
                        }
                        // Check respons token
                        if (isset($sms_verification_codes[$selected_payment_option])) {
                            if (empty($sms_verification_codes[$selected_payment_option])) {
                                wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_verification_code'), $notice_type = 'error');
                                return false;
                            }
                        }
                    }
                }
                // Check holder name
                if (isset($holder_names[$selected_payment_option])) {
                    if (empty($holder_names[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_holder'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check iban
                if (isset($ibans[$selected_payment_option])) {
                    if (empty($ibans[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_iban'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check bic
                if (isset($bics[$selected_payment_option])) {
                    if (empty($bics[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_bic'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check credit card number
                if (isset($credit_card_numbers[$selected_payment_option])) {
                    if (empty($credit_card_numbers[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_credit_card_number'), $notice_type = 'error');
                        return false;
                    }
                    if (!ctype_digit($credit_card_numbers[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_credit_card_number_not_numbers'), $notice_type = 'error');
                        return false;
                    }
                    if (strlen($credit_card_numbers[$selected_payment_option]) > 16) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_credit_card_number_more'), $notice_type = 'error');
                        return false;
                    }
                    if (strlen($credit_card_numbers[$selected_payment_option]) < 16) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_credit_card_number_less'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check credit card cvv2
                if (isset($cvv2s[$selected_payment_option])) {
                    if (empty($cvv2s[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_cvv2'), $notice_type = 'error');
                        return false;
                    }
                    if (!ctype_digit($cvv2s[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_cvv2_not_numbers'), $notice_type = 'error');
                        return false;
                    }
                    if (strlen($cvv2s[$selected_payment_option]) > 4) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_cvv2_more'), $notice_type = 'error');
                        return false;
                    }
                    if (strlen($cvv2s[$selected_payment_option]) < 3) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_cvv2_less'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check credit card types
                if (isset($credit_card_types[$selected_payment_option])) {
                    if (empty($credit_card_types[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_credit_card_types'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check exp_mounths
                if (isset($exp_mounths[$selected_payment_option])) {
                    if (empty($exp_mounths[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_exp_mounths'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check exp_years
                if (isset($exp_years[$selected_payment_option])) {
                    if (empty($exp_years[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_exp_years'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check document id
                if (isset($document_ids[$selected_payment_option])) {
                    if (empty($document_ids[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_document_id'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check bank code
                if (isset($bank_codes[$selected_payment_option])) {
                    if (empty($bank_codes[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_bank_code'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check birth day
                if (isset($birth_date_days[$selected_payment_option])) {
                    if (empty($birth_date_days[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_birth_date_day'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check birth month
                if (isset($birth_date_mounths[$selected_payment_option])) {
                    if (empty($birth_date_mounths[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_birth_date_month'), $notice_type = 'error');
                        return false;
                    }
                }
                // Check birth year
                if (isset($birth_date_years[$selected_payment_option])) {
                    if (empty($birth_date_years[$selected_payment_option])) {
                        wc_add_notice($selected_payment_option_name . ' - ' . AlternativePaymentsWooText::en('form_verification_birth_date_year'), $notice_type = 'error');
                        return false;
                    }
                }
            }
            return true;
        }

        public function thankyou_page() {
            if ($this->instructions) {
                echo wpautop(wptexturize($this->instructions));
            }
        }

        // Checking chekout data with Merchant tool
        public function process_payment($order_id) {
            global $woocommerce;
            if (!empty($_POST)) {
                $order = wc_get_order($order_id);
                $posted = wp_unslash($_POST);
                $sms = '';
                if ($posted['alternative_payment_method_option'] == 'sepa') {
                    $sms = ($this->is_sms_active()) ? "phone_verification" : "";
                }
                $posted['order_back_url'] = $this->get_return_url($order);
                $everything_ok = AlternativePaymentsWooFunctions::transaction($order, $posted, $sms);
                if ($everything_ok) {
                    if (is_bool($everything_ok)) {
                        return array(
                            'result' => 'success',
                            'redirect' => $this->get_return_url($order)
                        );
                    } else {
                        return array(
                            'result' => 'success',
                            'redirect' => $everything_ok
                        );
                    }
                }
            }
        }

        // Refunding order clicking on button 'refund' on admin side
        public function process_refund($order_id, $amount = null, $reason = '') {
            return self::refund_alternative($order_id, $amount, $reason);
        }

        // Sending refund data to Merchant tool
        public function refund_alternative($order_id, $amount = null, $reason = '') {
            global $woocommerce;

            if (!empty($order_id)) {
                $order = wc_get_order($order_id);

                $type = ($reason == 'automatic_refund') ? $reason : '';
                $reason = ($reason == 'automatic_refund') ? '' : $reason;

                $arr = (object) array(
                            'amount' => $amount,
                            'reason' => $reason,
                            'refund_type' => $type,
                );

                $everything_ok = AlternativePaymentsWooFunctions::refund_order($order, $arr);

                if ($everything_ok) {
                    return true;
                }
            }
            return false;
        }

        // Sending canceling data to Merchant tool
        public function cancel_alternative($order_id) {
            global $woocommerce;

            if (!empty($order_id)) {
                $order = wc_get_order($order_id);
                $everything_ok = AlternativePaymentsWooFunctions::cancel_order($order);
                if ($everything_ok) {
                    return true;
                }
            }

            return false;
        }

        // Include jQuery and our scripts
        public function add_inspire_scripts() {
            wp_enqueue_script('jquery');
            wp_enqueue_script('alternative_peyment_phone_verification', ALTERNATIVE_PAYMENTS_WOO_PLUGIN_DIR . 'js/alternative_peyment_phone_verification.js', array('jquery'), 1.0);
        }

        // Function called when on checkout form in active phone verification
        public function check_sms_response() {
            if (!empty($_POST)) {
                $posted = wp_unslash($_POST);
                $phone_number = isset($posted['ajax_temp_phone']) ? $posted['ajax_temp_phone'] : "";
                if (is_checkout && $phone_number) {
                    $response = AlternativePaymentsWooFunctions::send_phone_number($phone_number);
                    if ($response) {
                        echo json_decode($response);
                        die();
                    }
                }
            }
            echo 'ERROR';
            die();
        }

        // Checking if inserted public key is valid for phone verification
        public function is_sms_active() {
            // $sms_active = AlternativePaymentsWooFunctions::check_sms_active(self::PEYMENT_OPTIONS);
            // return (isset($sms_active) && isset($sms_active->hasSmsVerification)) ? $sms_active->hasSmsVerification : false;
            try {
                $sms_active = AlternativePaymentsWooFunctions::check_sms_active(self::PEYMENT_OPTIONS);
                $verification = (isset($sms_active) && isset($sms_active->hasSmsVerification)) ? $sms_active->hasSmsVerification : false;
                return $verification;
            } catch (Exception $e) {
                // wc_add_notice($e->getMessage(), 'error');
                return false;
            }
        }

    }

    // Canceling from Customer side
    function status_cancelled($post_id) {
        global $woocommerce;
        $order = wc_get_order($post_id);

        $gw_fnc = new WC_Gateway_Alternative();
        // Return bool, can be used to call js:alert method or for some notification
        $everything_ok = $gw_fnc::cancel_alternative($post_id);

        if (!$everything_ok) {
            if (!is_admin()) {
                wc_add_notice(AlternativePaymentsWooText::en('msg_cancel_false'), $notice_type = 'error');
                $order->add_order_note(AlternativePaymentsWooText::en('msg_note_cancel_false'));
            }
            return false;
        }
    }

    add_action('woocommerce_order_status_cancelled', 'status_cancelled');

    // Canceling from admin side
    function initial_product_data($post_id) {
        global $woocommerce;
        if (!empty($_POST)) {
            $posted = wp_unslash($_POST);
            if ($posted && $posted['post_type'] == 'shop_order' && $posted['_payment_method'] == 'alternative') {
                $gw_fnc = new WC_Gateway_Alternative();
                $order = wc_get_order($post_id);

                $status = isset($posted['order_status']) ? $posted['order_status'] : '';
                $status = str_replace('wc-', '', $status);

                if ($status == 'cancelled') {
                    $type = 'cancel';
                    $check = AlternativePaymentsWooFunctions::get_status_check($post_id, $type);

                    if ($check != 1) {
                        // Return bool, can be used to call js:alert method or for some notification
                        $everything_ok = $gw_fnc::cancel_alternative($post_id);
                    }
                    AlternativePaymentsWooFunctions::set_status_check($post_id, $type);
                }
            }
        }
    }

    add_action('wp_insert_post', 'initial_product_data');

    // AJAX switch phone number field with PIN code field on checkout page
    function check_sms_response() {
        if (!empty($_POST)) {
            $posted = wp_unslash($_POST);
            if (isset($posted['ajax_temp_phone'])) {
                $phone_number = isset($posted['ajax_temp_phone']) ? $posted['ajax_temp_phone'] : "";

                if (is_checkout && $phone_number) {
                    $response = AlternativePaymentsWooFunctions::send_phone_number($phone_number);
                    if ($response) {
                        echo json_encode($response);
                        die();
                    }
                }
            }
        }
        echo "ERROR";
        die();
    }

    // Check ajax phone state
    add_action('wp_ajax_alternative_payment_check_phone_verification', 'check_sms_response', 10);
    add_action('wp_ajax_nopriv_alternative_payment_check_phone_verification', 'check_sms_response', 10);

    // Function called when on checkout form for displa payment method that are aveable for select country
    function check_payment_methods_for_country() {
        if (!empty($_POST)) {
            $posted = wp_unslash($_POST);
            $selected_country = isset($posted['ajax_temp_selected_country']) ? $posted['ajax_temp_selected_country'] : "";
            if (is_checkout && $selected_country) {
                $response = AlternativePaymentsWooFunctions::get_payments_for_country($selected_country);
                if ($response) {
                    echo json_encode($response);
                    die();
                }
            }
        }
        echo 'ERROR';
        die();
    }

    // Check ajax country state
    add_action('wp_ajax_alternative_payment_check_payment_methods_for_country', 'check_payment_methods_for_country', 10);
    add_action('wp_ajax_nopriv_alternative_payment_check_payment_methods_for_country', 'check_payment_methods_for_country', 10);

    // Add the Gateway to WooCommerce
    function woocommerce_add_gateway_name_gateway($methods) {
        $methods[] = 'WC_Gateway_Alternative';
        return $methods;
    }

    // Adding class to payment gateways options
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_name_gateway');
}

add_action('plugins_loaded', 'woocommerce_gateway_alternative_payments_woo_init', 0);

/**
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
 * */
function alternative_payments_woo_register_chargeback_order_status() {
    register_post_status('wc-chargeback', array(
        'label' => 'Chargeback',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Chargeback <span class="count">(%s)</span>', 'Chargeback <span class="count">(%s)</span>')
    ));
}

add_action('init', 'alternative_payments_woo_register_chargeback_order_status');

// Add to list of WC Order statuses
function alternative_payments_woo_add_chargeback_to_order_statuses($order_statuses) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ($order_statuses as $key => $status) {

        $new_order_statuses[$key] = $status;

        if ('wc-processing' === $key) {
            $new_order_statuses['wc-chargeback'] = 'Chargeback';
        }
    }

    return $new_order_statuses;
}

add_filter('wc_order_statuses', 'alternative_payments_woo_add_chargeback_to_order_statuses');
