<?php

namespace Tomasbro\DotpayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class DotpayCallbackPostValidationEvent extends Event
{

    /**
     *
     * @var Request
     */
    private $request;

    /**
     *
     * @var Form
     */
    private $form;

    /**
     * 
     * @param Request $request
     * @param Form $form
     */
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
     * Returns true if form is validated or false
     * 
     * @return boolean
     */
    public function isValidCallback()
    {
        return $this->form->isValid();
    }
}
