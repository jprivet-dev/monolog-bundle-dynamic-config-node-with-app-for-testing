<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
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
                            foreach (HandlerType::cases() as $type) {
                                $node->handlerNode($type);
                            }

                            return $node;
                        })
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
