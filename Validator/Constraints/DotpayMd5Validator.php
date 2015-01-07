<?php

namespace Tomasbro\DotpayBundle\Validator\Constraints;

use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DotpayMd5Validator extends ConstraintValidator
{

    private $id;

    private $pin;

    public function __construct($dotpayId, $dotpayPin)
    {
        $this->id = $dotpayId;
        $this->pin = $dotpayPin;
    }

    public function validate($value, Constraint $constraint)
    {
        $form = $this->context->getRoot();

        $calculatedMd5 = $this->calculateMd5($form);

        if ($calculatedMd5 !== $value) {
            $this->context->addViolation(
                $constraint->message,
                array('%calculatedMd5%' => $calculatedMd5, '%callbackMd5%' => $value)
            );
        }
    }

    private function calculateMd5(Form $form)
    {
        $string = $this->pin . ':'
            . $this->id . ':'
            . trim($form->get('control')->getData()) . ':'
            . trim($form->get('t_id')->getData()) . ':'
            . trim($form->get('amount')->getData()) . ':'
            . trim($form->get('email')->getData()) . ':'
            . trim($form->get('service')->getData()) . ':'
            . trim($form->get('code')->getData()) . ':'
            . trim($form->get('username')->getData()) . ':'
            . trim($form->get('password')->getData()) . ':'
            . trim($form->get('t_status')->getData());
        return md5($string);
    }
}
