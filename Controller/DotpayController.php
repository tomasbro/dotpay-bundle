<?php

namespace Tomasbro\DotpayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tomasbro\DotpayBundle\Event\DotpayCallbackPostValidationEvent;
use Tomasbro\DotpayBundle\Event\DotpayCallbackPreValidationEvent;
use Tomasbro\DotpayBundle\Event\DotpayPaymentPostValidationEvent;
use Tomasbro\DotpayBundle\Event\DotpayPaymentPreparingEvent;
use Tomasbro\DotpayBundle\Form\Type\CallbackFormType;
use Tomasbro\DotpayBundle\TomasbroDotpayEvents;

class DotpayController extends Controller
{

    public function paymentAction(Request $request)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $container = $this->container;
        
        $preparingEvent = new DotpayPaymentPreparingEvent($request);
        $dispatcher->dispatch(TomasbroDotpayEvents::DOTPAY_PAYMENT_PREPARING, $preparingEvent);

        $form = $this->createForm($container->get('tomasbro_dotpay.payment_form'));
        
        $formData = array_merge(
            $container->getParameter('tomasbro_dotpay.payment.params'),
            $preparingEvent->getPaymentParams()
        );
        $form->submit($formData);
        
        $postValidationEvent = new DotpayPaymentPostValidationEvent($request, $form);
        if ($form->isValid()) {
            $dispatcher->dispatch(TomasbroDotpayEvents::DOTPAY_PAYMENT_VALIDATION_SUCCESS, $postValidationEvent);
            $urlParams = http_build_query($form->getData());
            $response = $this->redirect($container->getParameter('tomasbro_dotpay.payment.url') . '?' . $urlParams);
        } else {
            $dispatcher->dispatch(TomasbroDotpayEvents::DOTPAY_PAYMENT_VALIDATION_FAIL, $postValidationEvent);
            if (!$response = $postValidationEvent->getResponse()) {
                throw new \Exception('Invalid DotPay payment parameters.' . $form->getErrorsAsString());
            }
        }
        return $response;
    }

    public function callbackAction(Request $request)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
       
        $preValidationEvent = new DotpayCallbackPreValidationEvent($request);
        $dispatcher->dispatch(TomasbroDotpayEvents::DOTPAY_CALLBACK_PRE_VALIDATION, $preValidationEvent);

        $form = $this->createForm(new CallbackFormType($preValidationEvent->getOriginalAmount()));
        $form->handleRequest($request);

        $postValidationEvent = new DotpayCallbackPostValidationEvent($request, $form);
        $dispatcher->dispatch(TomasbroDotpayEvents::DOTPAY_CALLBACK_POST_VALIDATION, $postValidationEvent);

        return new Response('OK');
    }
}
