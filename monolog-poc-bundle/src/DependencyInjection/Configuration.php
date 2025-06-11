<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\Enum\HandlerTypes;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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

        foreach (HandlerTypes::cases() as $typeCase) {
            $type = $typeCase->value;

            if(!method_exists(Handler::class, $type)) {
                throw new \RuntimeException(sprintf('Handler type "%s" does not exist.', $type));
            }

            $handlers
                ->children()
                    ->append((new NodeBuilder())
                        ->arrayNode($type)
                        ->canBeUnset()
                        ->info(sprintf('All type "%s" handlers', $type))
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->appendArray(Handler::$type())
                            ->end()
                        ->end())
                ->end();
        }

        return $handlers;
    }
}
