<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooPhoneVerification extends AlternativePaymentsWooElement
{

    /*
     * @var string
     */
    protected $pin;

    /*
     * @var string
     */
    protected $token;
    
    /*
     * @var string
     */
    protected $Phone;
    
    /*
     * @var string
     */
    protected $Key;

    /*
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }
    /*
     * @param string
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    /*
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    /*
     * @param string
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
    
    /*
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->Phone;
    }
    /*
     * @param string
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->Phone = $phoneNumber;
    }
    
    /*
     * @return string
     */
    public function getProductKey()
    {
        return $this->Key;
    }
    /*
     * @param string
     */
    public function setProductKey($productKey)
    {
        $this->Key = $productKey;
    }
}
