<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Customer
{
    const RESOURCE = 'customers';
    
    public static function getAll()
    {
        $service = new Request;
        return $service->getAll(self::RESOURCE);
    }

    public static function get($code)
    {
        $service = new Request;
        return $service->get(self::RESOURCE, $code);
    }

    public static function post($data)
    {
        $service = new Request;
        return $service->post(self::RESOURCE, $data);
    }
}
