<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooPayment extends AlternativePaymentsWooElement
{

    /*
     * @var string
     */
    protected $token;

    /*
     * @var string
     */
    protected $customerCode;

    /*
     * @var string
     */
    protected $paymentOption;

    /*
     * @var string
     */
    protected $holder;

    /*
     * @var string
     */
    protected $IBAN;

    /*
     * @var string
     */
    protected $BIC;
    
    /*
     * @var string
     */
    protected $bankCode;

    /*
     * @var string
     */
    protected $creditCardNumber;

    /*
     * @var string
     */
    protected $cvv2;
    
    /*
     * @var string
     */
    protected $expirationYear;
    
    /*
     * @var string
     */
    protected $expirationMonth;
    
    /*
     * @var string
     */
    protected $creditCardType;
    
    /*
     * @var string
     */
    protected $documentId;    
    
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
    public function getCustomerCode()
    {
        return $this->customerCode;
    }
    /*
     * @param string
     */
    public function setCustomerCode($customerCode)
    {
        $this->customerCode = $customerCode;
    }
    /*
     * @return string
     */
    public function getPaymentOption()
    {
        return $this->paymentOption;
    }
    /*
     * @param string
     */
    public function setPaymentOption($paymentOption)
    {
        $this->paymentOption = $paymentOption;
    }

    /*
     * @return string
     */
    public function getHolder()
    {
        return $this->holder;
    }
    /*
     * @param string
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;
    }

    /*
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }
    /*
     * @param string
     */
    public function setIBAN($IBAN)
    {
        $this->IBAN = $IBAN;
    }

    /*
     * @return string
     */
    public function getBIC()
    {
        return $this->BIC;
    }
    /*
     * @param string
     */
    public function setBIC($BIC)
    {
        $this->BIC = $BIC;
    }
    
    /*
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }
    /*
     * @param string
     */
    public function setBankCode($BankCode)
    {
        $this->bankCode = $BankCode;
    }
    
    /*
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }
    /*
     * @param string
     */
    public function setCreditCardNumber($CreditCardNumber)
    {
        $this->creditCardNumber = $CreditCardNumber;
    }
    
    /*
     * @return string
     */
    public function getCvv2()
    {
        return $this->cvv2;
    }
    /*
     * @param string
     */
    public function setCvv2($Cvv2)
    {
        $this->cvv2 = $Cvv2;
    }
    
    /*
     * @return string
     */
    public function getExpYear()
    {
        return $this->expirationYear;
    }
    /*
     * @param string
     */
    public function setExpYear($ExpirationYear)
    {
        $this->expirationYear = $ExpirationYear;
    }
    
    /*
     * @return string
     */
    public function getExpMounth()
    {
        return $this->expirationMonth;
    }
    /*
     * @param string
     */
    public function setExpMounth($ExpirationMonth)
    {
        $this->expirationMonth = $ExpirationMonth;
    }
    
    /*
     * @return string
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }
    /*
     * @param string
     */
    public function setCreditCardType($CreditCardType)
    {
        $this->creditCardType = $CreditCardType;
    }
 
    /*
     * @return string
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }
    /*
     * @param string
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }
    
}
