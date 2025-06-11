<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
                        ->prototype('scalar')->end()
                    ->end()
                ->append(static::handlersNode())
            ->end();

        return $treeBuilder;
    }

    private static function handlersNode(): ArrayNodeDefinition {
        $node = (new TreeBuilder('handlers'))
            ->getRootNode()
            ->canBeUnset();

        foreach (HandlerType::cases() as $type) {
            $node->append(static::handlerNode($type));
        }

        return $node;
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
