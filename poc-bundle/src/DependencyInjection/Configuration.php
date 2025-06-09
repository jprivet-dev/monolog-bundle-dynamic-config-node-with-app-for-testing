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
                ->end()
            ->end();

        $allowedTypes = ['type_a', 'type_b'];

        $root
            ->children()
                ->arrayNode('services')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->info('info services')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')
                                ->isRequired()
                                ->validate()
                                    ->ifNotInArray($allowedTypes)
                                    ->thenInvalid('The %s type is not allowed. Allowed types are ' . implode(', ', $allowedTypes) . '.')
                                ->end()
                            ->end()
                            ->booleanNode('option_1')->end()
                            ->booleanNode('option_2')->end()
                            ->booleanNode('option_3')->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) {
                                $allowedKeysByType = [
                                    'type_a' => ['option_1', 'option_2'],
                                    'type_b' => ['option_2', 'option_3'],
                                ];

                                $activeKeys = array_keys(
                                    array_filter($v, static fn($v, $k) => $k !== 'type' && $v !== '', ARRAY_FILTER_USE_BOTH)
                                );

                                return count(array_diff($activeKeys, $allowedKeysByType[$v['type']])) > 0;
                            })
                            ->thenInvalid('An option does not allowed (%s).')
                        ->end()
                    ->end()
                    ->example([
                        'service_1' => [
                            'type' => 'type_a',
                        ],
                        'service_2' => [
                            'type' => 'type_b',
                        ]
                    ])
                ->end()
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
