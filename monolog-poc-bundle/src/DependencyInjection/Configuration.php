<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\Enum\HandlerTypes;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return (new TreeBuilder('monolog-poc'))
            ->getRootNode()
                ->children()
                    ->scalarNode('use_microseconds')
                        ->defaultTrue()
                    ->end()
                    ->arrayNode('channels')
                        ->canBeUnset()
                        ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('handlers')
                    ->canBeUnset()
                    ->children()
                        ->closure(static function(NodeBuilder $node): NodeParentInterface {
                            foreach (HandlerTypes::cases() as $type) {
                                $node
                                    ->arrayNode($type->value)
                                        ->canBeUnset()
                                        ->info(sprintf('All type "%s" handlers', $type->value))
                                        ->useAttributeAsKey('name')
                                        ->prototype('array')
                                            ->children()
                                                //->appendArray(Handler::$type())
                                            ->end()
                                        ->end()
                                    ->end();
                            }

                            return $node;
                        })
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
