<?php

namespace Tomasbro\DotpayBundle\Tests\DependencyInjection;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use Tomasbro\DotpayBundle\DependencyInjection\TomasbroDotpayExtension;

class TomasbroDotpayExtensionTest extends PHPUnit_Framework_TestCase
{

    /** @var ContainerBuilder */
    protected $configuration;

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessDotpayIdSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getEmptyConfig();
        unset($config['id']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessDotpayPinSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getEmptyConfig();
        unset($config['pin']);
        $loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithEmptyPaymentUrlSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['url'] = '';
        $loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithInvalidParameterChLockSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['params']['ch_lock'] = '5';
        $loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithInvalidParameterOnlinetransferSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['params']['onlinetransfer'] = '5';
        $loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithInvalidParameterTypeSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['params']['type'] = '5';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithInvalidCurrencySet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['params']['currency'] = 'XXX';
        $loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionWithInvalidLanguageSet()
    {
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $config['payment']['params']['lang'] = 'xx';
        $loader->load(array($config), new ContainerBuilder());
    }
    
    public function testLoadWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParemeterExists('tomasbro_dotpay.payment.params');
        $this->assertParameter(
            array('pl', 'en', 'de', 'it', 'fr', 'es', 'cz', 'ru', 'bg'),
            'tomasbro_dotpay.payment.available_languages'
        );
        $this->assertParameter(
            array('PLN', 'EUR', 'USD', 'GBP', 'JPY', 'CZK', 'SEK', 'DKK'),
            'tomasbro_dotpay.payment.available_currencies'
        );
        $this->assertParameter('https://ssl.dotpay.pl', 'tomasbro_dotpay.payment.url');
        $this->assertParameter('PLN', 'tomasbro_dotpay.payment.params.currency');
        $this->assertParameter('pl', 'tomasbro_dotpay.payment.params.lang');
        $this->assertParameter(0, 'tomasbro_dotpay.payment.params.channel');
        $this->assertParameter(0, 'tomasbro_dotpay.payment.params.ch_lock');
        $this->assertParameter(0, 'tomasbro_dotpay.payment.params.onlinetransfer');
        $this->assertParameter(0, 'tomasbro_dotpay.payment.params.type');
        $this->assertParameter('Powrót do serwisu', 'tomasbro_dotpay.payment.params.buttontext');
    }
    
    public function testLoadWithFullConfig()
    {
        $this->createFullConfiguration();

        $this->assertParemeterExists('tomasbro_dotpay.payment.params');
        $this->assertParameter(array('pl', 'en'), 'tomasbro_dotpay.payment.available_languages');
        $this->assertParameter(array('PLN', 'USD'), 'tomasbro_dotpay.payment.available_currencies');
        $this->assertParameter('https://ssl.dotpay.pl/someparam', 'tomasbro_dotpay.payment.url');
        $this->assertParameter('USD', 'tomasbro_dotpay.payment.params.currency');
        $this->assertParameter('Opis zakupów', 'tomasbro_dotpay.payment.params.description');
        $this->assertParameter('en', 'tomasbro_dotpay.payment.params.lang');
        $this->assertParameter(1, 'tomasbro_dotpay.payment.params.channel');
        $this->assertParameter(1, 'tomasbro_dotpay.payment.params.ch_lock');
        $this->assertParameter(1, 'tomasbro_dotpay.payment.params.onlinetransfer');
        $this->assertParameter('https://www.test.com', 'tomasbro_dotpay.payment.params.URL');
        $this->assertParameter(1, 'tomasbro_dotpay.payment.params.type');
        $this->assertParameter('Przejdź do serwisu', 'tomasbro_dotpay.payment.params.buttontext');
        $this->assertParameter('https://www.test.com/dotpay/callback', 'tomasbro_dotpay.payment.params.URLC');
        $this->assertParameter('sample_p_info', 'tomasbro_dotpay.payment.params.p_info');
        $this->assertParameter('p_email@test.com', 'tomasbro_dotpay.payment.params.p_email');
        $this->assertParameter('Powrót do serwisu', 'tomasbro_dotpay.payment.params.back_button_url');
    }
    
    protected function createEmptyConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new TomasbroDotpayExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new TomasbroDotpayExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
id: 1234
pin: 9999
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
id: 1234
pin: 9999
payment:
    available_currencies: [PLN, USD]
    available_languages: [pl, en]
    url: https://ssl.dotpay.pl/someparam
    params:
        currency: USD
        description: Opis zakupów
        lang: en
        channel: 1
        ch_lock: 1
        onlinetransfer: 1
        URL: https://www.test.com
        type: 1
        buttontext: Przejdź do serwisu
        URLC: https://www.test.com/dotpay/callback
        code: ABCD
        p_info: sample_p_info
        p_email:  p_email@test.com
        back_button_url: Powrót do serwisu
            
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(
            ($this->configuration->hasDefinition($id) ? : $this->configuration->hasAlias($id)),
            sprintf('%s service has definition', $id)
        );
    }

    /**
     * @param string $name
     */
    private function assertParemeterExists($name)
    {
        $this->assertTrue($this->configuration->hasParameter($name), sprintf('%s parameter does\'nt exists', $name));
    }
    
    /**
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertFalse(($this->configuration->hasDefinition($id) ? : $this->configuration->hasAlias($id)));
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }
}
