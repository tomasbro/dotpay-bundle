<?php

namespace Tomasbro\DotpayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class DotpayCallbackPreValidationEvent extends Event
{

    /**
     *
     * @var string original amount with currency which was sent to DotPay 
     * Setting this value is recommended for websites which do payments in diffrent currencies, 
     * because DotPay counts md5 hash only for amount without currency, and $10.00 != 10.00 PLN.
     * So better is to check the original amount with currency which has been passed to Dotpay.
     */
    private $originalAmount;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setOriginalAmount($originalAmount)
    {
        $this->originalAmount = $originalAmount;
    }

    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }
}
