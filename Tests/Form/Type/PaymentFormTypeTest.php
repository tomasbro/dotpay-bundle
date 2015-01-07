<?php
namespace Tomasbro\DotpayBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tomasbro\DotpayBundle\Form\Type\PaymentFormType;

class PaymentFormTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(new FormTypeValidatorExtension(Validation::createValidator()))
            ->addTypeGuesser(
                $this->getMockBuilder('Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser')
                    ->disableOriginalConstructor()
                    ->getMock()
            )
            ->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }
    
    public function testSubmitValidData()
    {
        $formData = array(
            'id' => '1234',
            'amount' => '10.00',
            'control' => '1234',
            'description' => 'Zakupy w sklepie'
        );
        
        $defaultData = array(
            'currency'       => 'PLN',
            'lang'           => 'pl',
            'channel'        => '0',
            'ch_lock'        => '0',
            'onlinetransfer' => '0',
            'typ'            => '0',
            'buttontext'     => 'Powrót do serwisu'
        );

        $type = new PaymentFormType(
            array('pl','en', 'de', 'it', 'fr', 'es', 'cz', 'ru', 'bg'),
            array('PLN', 'EUR', 'USD', 'GBP', 'JPY', 'CZK', 'SEK', 'DKK')
        );
        $form = $this->factory->create($type, $defaultData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}