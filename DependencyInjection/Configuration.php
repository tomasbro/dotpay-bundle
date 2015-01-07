<?php

namespace Tomasbro\DotpayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tomasbro_dotpay');

        $availableCurrencies = array('PLN', 'EUR', 'USD', 'GBP', 'JPY', 'CZK', 'SEK', 'DKK');
        $availableLanguages = array('pl','en', 'de', 'it', 'fr', 'es', 'cz', 'ru', 'bg');
        
        $rootNode
            ->children()
                ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('pin')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('payment')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('available_currencies')
        //                ->fixXmlConfig('available-currency')
                            ->prototype('scalar')->end()
                            ->defaultValue($availableCurrencies)
                        ->end()
                        ->arrayNode('available_languages')
        //                    ->fixXmlConfig('available_language')
                            ->prototype('scalar')->end()
                            ->defaultValue($availableLanguages)
                        ->end()
                        ->scalarNode('url')
                            ->defaultValue('https://ssl.dotpay.pl')
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode('params')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('currency')
                                    ->defaultValue('PLN')
                                    ->validate()
                                    ->ifNotInArray($availableCurrencies)
                                        ->thenInvalid(
                                            'The currency %s is not supported. Please choose one of '
                                            . json_encode($availableCurrencies)
                                        )
                                    ->end()
                                ->end()
                                ->scalarNode('description')->end()
                                ->scalarNode('lang')
                                    ->defaultValue('pl')
                                    ->validate()
                                    ->ifNotInArray($availableLanguages)
                                        ->thenInvalid(
                                            'The language %s is not supported. Please choose one of '
                                            . json_encode($availableLanguages)
                                        )
                                    ->end()
                                ->end()
                                ->integerNode('channel')->defaultValue(0)->end()
                                ->enumNode('ch_lock')
                                    ->values(array(0, 1, true, false))
                                    ->treatFalseLike(0)
                                    ->treatTrueLike(1)
                                    ->defaultValue(0)
                                ->end()
                                ->enumNode('onlinetransfer')
                                    ->values(array(0, 1, false, true))
                                    ->treatFalseLike(0)
                                    ->treatTrueLike(1)
                                    ->defaultValue(0)
                                ->end()
                                ->scalarNode('URL')->end()
                                ->enumNode('type')
                                    ->defaultValue(0)
                                    ->values(array(0, 1, 2, 3))
                                ->end()
                                ->scalarNode('buttontext')->defaultValue('Powrót do serwisu')->end()
                                ->scalarNode('URLC')->end()
                                ->scalarNode('code')->end()
                                ->scalarNode('p_info')->end()
                                ->scalarNode('p_email')->end()
                                ->scalarNode('back_button_url')->end()
                            ->end()
                        ->end() //params
                    ->end()
                ->end() //payment
            ->end()
        ;

        return $treeBuilder;
    }
}
