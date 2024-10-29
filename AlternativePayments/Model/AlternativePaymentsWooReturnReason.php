<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooReturnReason
{
    const CHARGEBACK_AVOIDANCE = "CHARGEBACK_AVOIDANCE";

    const END_USER_ERROR = "END_USER_ERROR";

    const FRAUD = "FRAUD";

    const UNSATISFIED_CUSTOMER = "UNSATISFIED_CUSTOMER";

    const INVALID_TRANSACTION = "INVALID_TRANSACTION";
}
