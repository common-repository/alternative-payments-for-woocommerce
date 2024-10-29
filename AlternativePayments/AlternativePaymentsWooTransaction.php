<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooTransaction
{
    const RESOURCE = 'transactions';
    
    public static function getAll()
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->getAll(self::RESOURCE);
    }

    public static function get($code)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->get(self::RESOURCE, $code);
    }

    public static function post($data)
    {
        $service = new AlternativePaymentsWooRequest;
        return $service->post(self::RESOURCE, $data);
    }
}

