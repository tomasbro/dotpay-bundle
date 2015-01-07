<?php

namespace Tomasbro\DotpayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class DotpayPaymentPreparingEvent extends Event
{

    /**
     *
     * @var array payment parameters in accordance with DotPay documentation
     */
    private $paymentParams;

    /**
     *
     * @var string url for dotpay service to send payment data
     */
    private $paymentUrl;

    /**
     *
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 
     * @param array $paymentParams payment parameters in accordance with DotPay documentation
     */
    public function setPaymentParams(array $paymentParams = array())
    {
        $this->paymentParams = $paymentParams;
    }

    /**
     * 
     * @return array payment parameters in accordance with DotPay documentation
     */
    public function getPaymentParams()
    {
        return $this->paymentParams;
    }

    /**
     * named payment parameter
     * 
     * @param string $name
     * @return mixed
     */
    public function getPaymentParam($name)
    {
        return isset($this->paymentParams[$name]) ? : null;
    }

    /**
     * 
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->paymentUrl;
    }

    /**
     * 
     * @param string $paymentUrl url where payment data will be send
     */
    public function setPaymentUrl($paymentUrl)
    {
        $this->paymentUrl = $paymentUrl;
    }
}
