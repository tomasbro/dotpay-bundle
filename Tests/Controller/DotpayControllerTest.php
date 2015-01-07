<?php

namespace Tomasbro\DotpayBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DemoControllerTest extends WebTestCase
{
    public function testCallbackActionWithValidRequest()
    {
        $client = static::createClient();
        $client->request('POST', '/dotpay/callback', array(
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
            'md5' => '3745ba029f5fd97ff0ff3715f6b52d54',
            'p_info' => '',
            'p_email' => '',
            't_date' => ''
        ), array(), array(
            'REMOTE_ADDR' => '195.150.9.37'
        ));

        $this->assertEquals('OK', $client->getResponse()->getContent());
    }
    
    public function testCallbackActionIfItDoesntRunForGetMethod()
    {
        $client = static::createClient();
        $client->request('GET', '/dotpay/callback');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }
    
    public function testCallbackActionIfItDoesntRunForInvalidIp()
    {
        $client = static::createClient();
        $client->request('POST', '/dotpay/callback', array(
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
            'md5' => '3745ba029f5fd97ff0ff3715f6b52d54',
            'p_info' => '',
            'p_email' => '',
            't_date' => ''
        ), array(), array(
            'REMOTE_ADDR' => '0.0.0.0'
        ));

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }
    
       
    public function testPaymentActionPaymentMethod()
    {
        $client = static::createClient();
        $client->request('GET', '/dotpay/payment');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
