<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooTransactionModel extends AlternativePaymentsWooElement
{

    /*
     * @var customer
     */
    protected $customer;

    /*
     * @var payment
     */
    protected $payment;

    /*
     * @var redirectUrls
     */
    protected $redirectUrls;
    
    /*
     * @var integer
     */
    protected $amount;

    /*
     * @var string
     */
    protected $currency;

    /*
     * @var string
     */
    protected $token;

    /*
     * @var phoneVerification
     */
    protected $phoneVerification;

    /*
     * @var string
     */
    protected $merchantPassThruData;

    /*
     * @var string
     */
    protected $description;

    /*
     * @return customer
     */

    protected $iPAddress;


    public function getCustomer()
    {
        return $this->customer;
    }
    /*
     * @param customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /*
     * @return payment
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
    
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }


    public function getPayment()
    {
        return $this->payment;
    }
    /*
     * @param payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }
/*
     * @param redirectUrls
     */
    public function getRedirectUrls()
    {
        return $this->redirectUrls;
    }
    /*
     * @param redirectUrls
     */
    public function setRedirectUrls($redirect_url)
    {
        $this->redirectUrls = $redirect_url;
    }
    
    /*
     * return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }
    /*
     * @param integer
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /*
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    /*
     * @param string
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
     * @retrun phoneVerification
     */
    public function getPhoneVerification()
    {
        return $this->phoneVerification;
    }
    /*
     * @param phoneVerification
     */
    public function setPhoneVerification($phoneVerification)
    {
        $this->phoneVerification = $phoneVerification;
    }

    /*
     * @param string
     */
    public function setMerchantPassThruData($merchantPassThruData)
    {
        $this->merchantPassThruData = $merchantPassThruData;
    }
    /*
     * @return string
     */
    public function getMerchantPassThruData()
    {
        return $this->merchantPassThruData;
    }

    /*
     * @param string
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /*
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setIPAddress($iPAddress)
    {
        $this->iPAddress = $iPAddress;
    }
    
    public function getIPAddress()
    {
        return $this->iPAddress;
    }
}
