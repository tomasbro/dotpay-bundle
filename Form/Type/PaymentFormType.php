<?php

namespace Tomasbro\DotpayBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PaymentFormType extends AbstractType
{
    private $availableLanguages;
    private $availableCurrencies;
    
    public function __construct($availableLanguages, $availableCurrencies)
    {
        $this->availableLanguages = $availableLanguages;
        $this->availableCurrencies = $availableCurrencies;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array('constraints' => array(new NotBlank())))
            ->add('amount', 'hidden', array(
                'constraints' => array(
                    new NotBlank(),
                    new Regex(array(
                        'pattern' => '/^\d+\.\d{2}/',
                        'message' => 'Amount must have "." as decimal separator and two decimal digits'
                    ))
                )
            ))
            ->add('currency', 'hidden', array(
                'constraints' => new Choice(array(
                    'choices' => $this->availableCurrencies
                ))
            ))
            ->add('description', 'hidden', array('constraints' => new NotBlank()))
            ->add('lang', 'hidden', array(
                'constraints' => new Choice(array(
                    'choices' => $this->availableLanguages
                ))
            ))
            ->add('channel', 'hidden')
            ->add('ch_lock', 'hidden')
            ->add('onlinetransfer', 'hidden')
            ->add('URL', 'hidden')
            ->add('type', 'hidden')
            ->add('buttontext', 'hidden')
            ->add('URLC', 'hidden')
            ->add('control', 'hidden', array('constraints' => new NotBlank()))
            ->add('firstname', 'hidden')
            ->add('lastname', 'hidden')
            ->add('email', 'hidden')
            ->add('street', 'hidden')
            ->add('street_n1', 'hidden')
            ->add('street_n2', 'hidden')
            ->add('addr2', 'hidden')
            ->add('addr3', 'hidden')
            ->add('city', 'hidden')
            ->add('postcode', 'hidden')
            ->add('phone', 'hidden')
            ->add('country', 'hidden')
            ->add('code', 'hidden')
            ->add('p_info', 'hidden')
            ->add('p_email', 'hidden')
            ->add('tax', 'hidden')
            ->add('back_button_url', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
    
    public function getName()
    {
        return 'payment_form';
    }
}
