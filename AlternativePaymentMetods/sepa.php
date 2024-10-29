<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ($this->is_sms_active()) {
    ?>
    <fieldset>
        <p class="form-row form-row-first alternative_payment_message_holder" style="display:none;">
            <span class="required">*</span>
        </p>

        <!-- Phone number -->
        <div>
            <p class="form-row form-row-first alternative_peyment_phone_number">
                <label for="payment_phone_number">
                    <?php echo AlternativePaymentsWooText::en('form_phone_number'); ?>
                    <span class="required">*</span>
                </label>
                <input type="text" class="input-text alternative_payment_phone_number_txt" id="payment_phone_number" name="payment_phone_number[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
            </p>
            <p class="form-row form-row-first alternative_peyment_phone_number">
                <input type="button" value="<?php echo AlternativePaymentsWooText::en('form_btn_value'); ?>" 
                       class="button-primary alternative_peyment_send_phone_number_btn" 
                       style="color: white; background-color: rgb(0, 61, 92); width: 50%; margin-top: 30px; font-weight: bold;"/>
            </p>
            <p class="form-row alternative_peyment_phone_number" style="display: inline-block;">
                <span>
                    <?php echo AlternativePaymentsWooText::en('form_phone_number_description'); ?>
                </span>
            </p>
        </div>
        <!-- ./Phone number -->

        <!-- Phone PIN -->
        <div>
            <p class="form-row form-row-first alternative_peyment_verification_code" style="display: none;">
                <label for="payment_sms_verification_code">
                    <?php echo AlternativePaymentsWooText::en('form_phone_verification_code'); ?> <span class="required">*</span>
                </label>
                <input type="text" class="input-text" id="payment_sms_verification_code" name="payment_sms_verification_code[<?php eval('echo $payment_option["id"];'); ?>]" autocomplete="off" />
                <span><?php echo AlternativePaymentsWooText::en('form_phone_verification_code_description'); ?></span>

                <input type="hidden" class="input-text alternative_peyment_payment_response_phone_number" id="payment_response_phone_number" name="payment_response_phone_number[<?php eval('echo $payment_option["id"];'); ?>]" value="" />
                <input type="hidden" class="input-text alternative_peyment_payment_response_token" id="payment_response_token" name="payment_response_token[<?php eval('echo $payment_option["id"];'); ?>]" value="" />
            </p>
        </div>
        <!-- ./Phone PIN -->
        
    </fieldset>
<?php } ?>

<fieldset>
    <p class="form-row form-row-first" style="display: block;">
        <label for="payment_holder_name">
            <?php echo AlternativePaymentsWooText::en('form_holder'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text" id="payment_holder_name" name="alternative_payment_holder_name[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
    </p>
    <p class="form-row form-row-first">
        <label for="payment_iban">
            <?php echo AlternativePaymentsWooText::en('form_iban'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text" id="payment_iban" name="alternative_payment_iban[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
    </p>
</fieldset>

