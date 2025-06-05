<?php

namespace Local\Bundle\PocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        //dump('PocBundle\Configuration::getConfigTreeBuilder');
        $treeBuilder = new TreeBuilder('poc');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('one')
                    ->defaultTrue()
                ->end()
                ->scalarNode('two')
                    ->defaultValue('default')
                ->end()
                ->arrayNode('three')
                    ->canBeUnset()
                    ->prototype('scalar')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
