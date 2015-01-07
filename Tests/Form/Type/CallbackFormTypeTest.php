<?php
namespace Tomasbro\DotpayBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tomasbro\DotpayBundle\Form\Type\CallbackFormType;

class CallbackFormTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();

//       $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
//       $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(
                new FormTypeValidatorExtension(
                    Validation::createValidator()
                )
            )
            ->addTypeGuesser(
                $this->getMockBuilder(
                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
                )
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
            'id' => '1',
            'status' => 'OK',
            'control' => '123',
            't_id' => '1',
            'amount' => '10.00',
            'orginal_amount' => '10.00 PLN',
            'channel' => '1',
            'email' => 'test@email.com',
            'service' => '',
            'code' => '',
            'username' => '',
            'password' => '',
            't_status' => '2',
            'description' => '',
            'md5' => 'somemd5hash',
            'p_info' => '',
            'p_email' => '',
            't_date' => ''
        );

        $type = new CallbackFormType('10.00 PLN');
        $form = $this->factory->create($type);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        //$this->assertEquals($formData, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($children) as $key) {
            $this->assertArrayHasKey($key, $formData);
        }
    }
}