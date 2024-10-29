<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooRefund
{
    const RESOURCE = 'transactions';
    
    const ACTION = 'refunds';
    
    public static function getAll($parentCode)
    {
        $newCtrl = self::RESOURCE."/".$parentCode."/".self::ACTION;
        $service = new AlternativePaymentsWooRequest;
        return $service->getAll($newCtrl);
    }

    public static function get($code)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->get(self::RESOURCE, $code);
    }

    public static function post($data, $parentCode)
    {
        $newCtrl = self::RESOURCE."/".$parentCode."/".self::ACTION;
        $service = new AlternativePaymentsWooRequest;
        return $service->post($newCtrl, $data);
    }
}
