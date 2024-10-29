<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<fieldset>
    <p class="form-row form-row-first" style="display: block;">
        <label for="<?php eval('echo $payment_option["id"];'); ?>_payment_holder_name">
            <?php echo AlternativePaymentsWooText::en('form_holder'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text" id="<?php eval('echo $payment_option["id"];'); ?>_payment_holder_name" name="alternative_payment_holder_name[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
    </p>

    <p class="form-row form-row-first" style="display: block;">
        <label for="payment_bank_code">
            <?php echo AlternativePaymentsWooText::en('form_bank_code'); ?> <span class="required">*</span>
        </label>
        <select class="input-text" id="payment_bank_code" name="alternative_payment_bank_code[<?php eval('echo $payment_option["id"];'); ?>]" style="width: 100%">
            <option value=""><?php echo AlternativePaymentsWooText::en('Type'); ?></option>
            <option value="abn_amro">ABN Amro</option>
            <option value="postbank">Postbank</option>
            <option value="rabobank">Rabobank</option>
            <option value="fortis">Fortis</option>
            <option value="friesland_bank">Friesland Bank</option>
        </select>
    </p>  
</fieldset>