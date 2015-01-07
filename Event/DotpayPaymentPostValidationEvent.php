<?php

namespace Tomasbro\DotpayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DotpayPaymentPostValidationEvent extends Event
{

    /**
     *
     * @var Form
     */
    private $form;

    /**
     *
     * @var Request
     */
    private $request;
    
    /**
     *
     * @var Response
     */
    private $response;

    public function __construct(Request $request, Form $form)
    {
        $this->request = $request;
        $this->form = $form;
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
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * 
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
    
    /**
     * 
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

}
