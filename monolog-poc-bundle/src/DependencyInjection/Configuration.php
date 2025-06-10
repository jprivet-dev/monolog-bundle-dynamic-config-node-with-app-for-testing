<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('monolog-poc');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->scalarNode('use_microseconds')
                    ->defaultTrue()
                ->end()
                ->arrayNode('channels')
                    ->canBeUnset()
                    ->prototype('scalar')
                ->end()
            ->end()
            ->append(static::handlers());

        return $treeBuilder;
    }

    private static function handlers(): NodeDefinition
    {
         $handlers = (new NodeBuilder())
             ->arrayNode('handlers')
                ->canBeUnset();

        foreach (HandlerTypes::cases() as $type) {
            $handlerByType = (new NodeBuilder())
                ->arrayNode($type->value)
                ->canBeUnset()
                ->info(sprintf('All type "%s" handlers', $type->value))
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->append(static::level())
                        ->append(static::bubble())
                    ->end()
                ->end();

            $handlers
                ->children()
                    ->append($handlerByType)
                ->end();
        }

        return $handlers;
    }

    private static function level(): NodeDefinition
    {
        return (new NodeBuilder())->scalarNode('level')->defaultValue('DEBUG');
    }

    private static function bubble(): NodeDefinition
    {
        return (new NodeBuilder())->booleanNode('bubble')->defaultTrue();
    }
}
