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
        $root = $treeBuilder->getRootNode();

        $root
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
                ->append(static::arrayNodeByName('f', includeUserPassword: false))
                ->append(static::arrayNodeByName('g'))
            ->end();

        $root
            ->children()
                ->booleanNode('h')
                    ->defaultTrue()
                    ->info('info h')
            ->end();

        $root
            ->children()
                ->arrayNode('services')
                ->canBeUnset()
                ->useAttributeAsKey('name')
                ->prototype('boolean')
                ->info('info services')
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
                ->booleanNode('sub_d')->defaultTrue()->end()
            ->end();
    }

    private static function arrayNodeByName(string $name, bool $includeUserPassword = true): ArrayNodeDefinition {
        $node = (new TreeBuilder($name))->getRootNode();

        $node
            ->info(sprintf('info %s', $name))
            ->children()
                ->scalarNode('id')->end()
                ->scalarNode('host')->end()
                ->scalarNode('port')->defaultValue(9200)->end()
                ->scalarNode('transport')->end()
            ->end();

        if($includeUserPassword) {
            $node
                ->children()
                    ->scalarNode('user')->defaultNull()->end()
                    ->scalarNode('password')->defaultNull()->end()
                ->end();
        }

        return $node;
    }
}
