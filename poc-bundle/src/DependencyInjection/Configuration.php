<?php

namespace Local\Bundle\PocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('poc');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('a')
                    ->defaultTrue()
                    ->info('info a')
                ->end()
                ->scalarNode('b')
                    ->defaultValue('default')
                    ->info('info b')
                ->end()
                ->append(static::booleanNodeC())
                ->append(static::arrayNodeD())
                ->append(static::arrayNodeByName('e'))
                ->append(static::arrayNodeByName('f', exclude: ['user', 'password']))
                ->append(static::arrayNodeByName('g'))
            ->end();

        return $treeBuilder;
    }

    private static function booleanNodeC(): NodeDefinition {
        return (new NodeBuilder())
            ->booleanNode('c')
                ->defaultTrue()
                ->info('info c');
    }

    private static function arrayNodeD(): ArrayNodeDefinition {
        return (new TreeBuilder('d'))->getRootNode()
            ->info('info d')
            ->children()
                ->booleanNode('sub_d')
                    ->defaultTrue()
                    ->info('info sub_d')
                ->end()
            ->end();
    }

    private static function arrayNodeByName(string $name, array $exclude = []): ArrayNodeDefinition {
        return (new TreeBuilder($name))->getRootNode()
            ->info(sprintf('info %s', $name))
            ->children()
                ->scalarNode('id')
                    ->info('info id')
                ->end()
                ->scalarNode('host')
                    ->info('info host')
                ->end()
                ->scalarNode('port')
                    ->defaultValue(9200)
                    ->info('info port')
                ->end()
                ->scalarNode('transport')
                    ->defaultValue('Http')
                    ->info('info transport')
                ->end()
                ->scalarNode('user')
                    ->defaultNull()
                    ->info('info user')
                ->end()
                ->scalarNode('password')
                    ->defaultNull()
                    ->info('info password')
                ->end()
            ->end();
    }
}
