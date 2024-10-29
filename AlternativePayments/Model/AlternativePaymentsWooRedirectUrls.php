<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AlternativePaymentsWooRedirectUrls extends AlternativePaymentsWooElement
{

    /*
     * @var string
     */
    protected $returnUrl;

     /*
      * @var string
      */
    protected $cancelUrl;


    /*
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }
    /*
     * @param string
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /*
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }
    /*
     * @param string
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;
    }
}
