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
        <p class="form-row form-row-first">
            <label for="payment_document_id">
                <?php echo AlternativePaymentsWooText::en('form_document_id'); ?> <span class="required">*</span>
            </label>
            <input placeholder="xxx.xxx.xxx-xx" type="text" class="input-text" id="payment_document_id" name="alternative_payment_document_id[<?php eval('echo $payment_option["id"];'); ?>]" value="" autocomplete="off" />
        </p>      
    </div>
    
    <div>
        <p class="form-row form-row-first" style="clear: none; width: 100%">
            <label for="payment_birth_date_day">
                <?php echo AlternativePaymentsWooText::en('form_birth_date'); ?> <span class="required">*</span>
            </label>
            <select class="input-text" id="payment_birth_date_day" name="alternative_payment_birth_date_day[<?php eval('echo $payment_option["id"];'); ?>]" style="width: 22%;float: left;">
                <option value=""><?php echo AlternativePaymentsWooText::en('Day'); ?></option>
                <?php
                for ($i = 1; $i < 32; $i++) {
                    echo '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . str_pad($i, 2, "0", STR_PAD_LEFT) . '</option>';
                }
                ?>
            </select>
            
            <span style="font-size: large;width: 8%;display: inline-block;text-align: center;float: left;">/</span>
            
            <select class="input-text" id="payment_birth_date_mounth" name="alternative_payment_birth_date_mounth[<?php eval('echo $payment_option["id"];'); ?>]" style="width: 22%;float: left;">
                <option value=""><?php echo AlternativePaymentsWooText::en('Mounth'); ?></option>
                <?php
                for ($i = 1; $i < 13; $i++) {
                    echo '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . str_pad($i, 2, "0", STR_PAD_LEFT) . '</option>';
                }
                ?>
            </select>
            
            <span style="font-size: large;width: 8%;display: inline-block;text-align: center;float: left;">/</span>
            
            <select class="input-text" id="payment_birth_date_year" name="alternative_payment_birth_date_year[<?php eval('echo $payment_option["id"];'); ?>]"  style="width: 40%;float: left;">
                <option value=""><?php echo AlternativePaymentsWooText::en('Year'); ?></option>
                <?php
                $year_end = date('Y') - 18;
                $year = 1930;
                for ($i = $year_end; $i > $year; $i--) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </select>
        </p>
    </div>

</fieldset>

