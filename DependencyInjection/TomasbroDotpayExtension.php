<?php

namespace Tomasbro\DotpayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TomasbroDotpayExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('dotpay.yml');

        $container->setParameter('tomasbro_dotpay.id', $config['id']);
        $container->setParameter('tomasbro_dotpay.pin', $config['id']);
        $container->setParameter(
            'tomasbro_dotpay.payment.available_languages',
            $config['payment']['available_languages']
        );
        $container->setParameter(
            'tomasbro_dotpay.payment.available_currencies',
            $config['payment']['available_currencies']
        );
        $container->setParameter('tomasbro_dotpay.payment.url', $config['payment']['url']);
        $container->setParameter('tomasbro_dotpay.payment.params', $config['payment']['params']);
        foreach ($config['payment']['params'] as $key => $value) {
            $container->setParameter('tomasbro_dotpay.payment.params.' . $key, $value);
        }
        
    }
}
