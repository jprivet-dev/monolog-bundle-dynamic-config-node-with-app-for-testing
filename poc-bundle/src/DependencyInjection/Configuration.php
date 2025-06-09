<?php

namespace Local\Bundle\PocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ALLOWED_SERVICE_TYPES = ['type_a', 'type_b'];

    const ALLOWED_KEYS_BY_SERVICE_TYPE = [
        'type_a' => ['option_1', 'option_2'],
        'type_b' => ['option_2', 'option_3'],
    ];

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

        static::addServices($root);

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

    private static function addServices(ArrayNodeDefinition $root): void {
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
                                    ->ifNotInArray(static::ALLOWED_SERVICE_TYPES)
                                    ->thenInvalid('The %s type is not allowed. Allowed types are ' . implode(', ',static::ALLOWED_SERVICE_TYPES) . '.')
                                ->end()
                            ->end()
                            ->booleanNode('option_1')->end()
                            ->booleanNode('option_2')->end()
                            ->booleanNode('option_3')->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) {
                                $activeKeys = array_keys(
                                    array_filter($v, static fn($v, $k) => $k !== 'type' && $v !== '', ARRAY_FILTER_USE_BOTH)
                                );

                                return count(array_diff($activeKeys, static::ALLOWED_KEYS_BY_SERVICE_TYPE[$v['type']])) > 0;
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
    }
}
