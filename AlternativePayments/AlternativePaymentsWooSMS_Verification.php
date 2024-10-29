<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooSMS_Verification
{
    const RESOURCE = 'phoneverification';
    
    public static function getIsActive($peyment_option, $sms = false)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->get($peyment_option, null, $sms);
    }
    
    public static function get($url)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->get($url, null);
    }

    public static function post($data, $sms = false)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->post(self::RESOURCE, $data, $sms);
    }
}
