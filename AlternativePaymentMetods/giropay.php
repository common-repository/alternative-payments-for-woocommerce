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
    <p class="form-row form-row-first">
        <label for="<?php eval('echo $payment_option["id"];'); ?>_payment_bic">
            <?php echo AlternativePaymentsWooText::en('form_bic'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text" id="<?php eval('echo $payment_option["id"];'); ?>_payment_bic" name="alternative_payment_bic[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
    </p>
</fieldset>
