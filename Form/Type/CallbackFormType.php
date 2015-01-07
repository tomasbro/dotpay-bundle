<?php

namespace Tomasbro\DotpayBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Tomasbro\DotpayBundle\Validator\Constraints\DotpayMd5;

class CallbackFormType extends AbstractType
{

    private $orginalAmount;

    public function __construct($orginalAmount = null)
    {
        $this->orginalAmount = $orginalAmount;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('control')
            ->add('t_id')
            ->add('amount')
            ->add('email')
            ->add('service')
            ->add('code')
            ->add('username')
            ->add('password')
            ->add('t_status')
            ->add('md5', 'text', array(
                'constraints' => new DotpayMd5()
        ));

        if ($this->orginalAmount !== null) {
            $builder->add('orginal_amount', 'text', array(
                'constraints' => new IdenticalTo(array(
                    'value'   => $this->orginalAmount,
                    'message' => 'Kwota powinna wynosić {{comparedValue}}'
                ))
            ));
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            foreach (array_keys($data) as $key) {
                if (!isset($form[$key])) {
                    unset($data[$key]);
                }
            }
            $event->setData($data);
        });
    }

    public function getName()
    {
        return 'callback_form';
    }
}
