<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
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
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('handlers')
                    ->canBeUnset()
                    ->children()
                        ->closure(static function(NodeBuilder $node): NodeParentInterface {
                            foreach (HandlerType::cases() as $type) {
                                $node->append(static::handlerNode($type));
                            }

                            return $node;
                        })
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }


    /**
     * Creates a child handler node.
     */
    private static function handlerNode(HandlerType $type): NodeDefinition
    {
        $class = static::getHandlerNodeDefinitionClass($type);

        return (new $class())->node();
    }

    /**
     * Returns the class name of the handler node definition.
     */
    private static function getHandlerNodeDefinitionClass(HandlerType $type): string
    {
        if (!$type->getHandlerNodeDefinitionClass()) {
            throw new \RuntimeException(\sprintf('The handler node type "%s" is not registered.', $type->value));
        }

        $class = $type->getHandlerNodeDefinitionClass();

        if (!class_exists($type->getHandlerNodeDefinitionClass())) {
            throw new \RuntimeException(\sprintf('The handler node class "%s" does not exist.', $class));
        }

        return $class;
    }
}
