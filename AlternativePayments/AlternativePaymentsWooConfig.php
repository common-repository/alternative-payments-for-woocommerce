<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooConfig
{
    private static $apiKey;
    
    public static function getApiKey()
    {
        $data = get_option('woocommerce_alternative_settings');
        return $data['api_key'];
        //return self::$apiKey;
    }
    
    public static function getProductKey()
    {
        $data = get_option('woocommerce_alternative_settings');
        return $data['product_key'];
        //return self::$apiKey;
    }
    
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    
    private static $apiUrl = 'http://api.dev/api';
    
    public static function getApiUrl()
    {
        $data = get_option('woocommerce_alternative_settings');
        return $data['api_url'];
        //return self::$apiUrl;
    }

    public static function setApiUrl($apiUrl)
    {
        self::$apiUrl = $apiUrl;
    }
}
