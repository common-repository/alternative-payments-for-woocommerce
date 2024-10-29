<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once ("AlternativePaymentsWooFunctions.php");
require_once ("AlternativePaymentsWooText.php");
require_once ("AlternativePayments/AlternativePaymentsWooRequest.php");
require_once ("AlternativePayments/AlternativePaymentsWooConfig.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooElement.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooCustomer.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooPayment.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooPhoneVerification.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooPlan.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooRefundModel.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooReturnReason.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooTransactionModel.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooVoidModel.php");
require_once ("AlternativePayments/Model/AlternativePaymentsWooRedirectUrls.php");
require_once ("AlternativePayments/AlternativePaymentsWooTransaction.php");
require_once ("AlternativePayments/AlternativePaymentsWooRefund.php");
require_once ("AlternativePayments/AlternativePaymentsWooSMS_Verification.php");
require_once ("AlternativePayments/AlternativePaymentsWooVoid.php");



?>