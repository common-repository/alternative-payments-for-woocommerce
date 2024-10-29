<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>

<fieldset>
    <p class="form-row" style="display: block;">
        <label for="payment_holder_name">
            <?php echo AlternativePaymentsWooText::en('form_holder'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text" id="payment_holder_name" name="alternative_payment_holder_name[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
    </p>

    <div>
        <p class="form-row form-row-first" style="clear: none; margin-right: 2%; width: 59%">
            <label for="payment_credit_card_number">
                <?php echo AlternativePaymentsWooText::en('form_credit_card_number'); ?> <span class="required">*</span>
            </label>
            <input placeholder="**** **** **** ****" type="text" class="input-text" id="payment_credit_card_number" name="alternative_payment_credit_card_number[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
        </p>

        <p class="form-row form-row-first" style="clear: none; width: 39%">
            <label for="payment_cvv2">
                <?php echo AlternativePaymentsWooText::en('form_cvv2'); ?> <span class="required">*</span>
            </label>
            <input placeholder="****" type="text" class="input-text" id="payment_cvv2" name="alternative_payment_cvv2[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
        </p>
    </div>

    <div>
        <p class="form-row form-row-first" style="clear: none; margin-right: 2%; width: 59%">
            <label for="payment_credit_card_type">
                <?php echo AlternativePaymentsWooText::en('form_credit_card_type'); ?> <span class="required">*</span>
            </label>
            <select class="input-text" id="payment_credit_card_type" name="alternative_payment_credit_card_type[<?php eval('echo $payment_option["id"];'); ?>]" style="width: 100%">
                <option value=""><?php echo AlternativePaymentsWooText::en('Type'); ?></option>
                <option value="visa">Visa</option>
                <option value="mastercard">Master Card</option>
            </select>
        </p>

        <p class="form-row form-row-first" style="clear: none; width: 39%">
            <label for="payment_exp_year">
                <?php echo AlternativePaymentsWooText::en('form_exp_date'); ?> <span class="required">*</span>
            </label>
            <select class="input-text" id="payment_exp_mounth" name="alternative_payment_exp_mounth[<?php eval('echo $payment_option["id"];'); ?>]" style="width: 49%; float: left;">
                <option value=""><?php echo AlternativePaymentsWooText::en('Mounth'); ?></option>
                <?php
                for ($i = 1; $i < 13; $i++) {
                    echo '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . str_pad($i, 2, "0", STR_PAD_LEFT) . '</option>';
                }
                ?>
            </select>
            
            <span style="font-size: large;width: 8%;display: inline-block;text-align: center;">/</span>
            
            <select class="input-text" id="payment_exp_year" name="alternative_payment_exp_year[<?php eval('echo $payment_option["id"];'); ?>]"  style="width: 43%; float: right;">
                <option value=""><?php echo AlternativePaymentsWooText::en('Year'); ?></option>
                <?php
                $year = date('Y');
                for ($i = $year; $i < $year + 7; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </select>
        </p>
    </div>
    
</fieldset>

