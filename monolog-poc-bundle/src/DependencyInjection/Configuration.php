<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
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
                        ->callable(static fn(NodeBuilder $node) => static::addHandlerConfigurationByTypes($node))
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    static public function addHandlerConfigurationByTypes(NodeBuilder $node): void {
        foreach (HandlerType::cases() as $type) {
            $node
                ->arrayNode($type->value)
                    ->canBeUnset()
                    ->info(sprintf('All "%s" type handlers', $type->value))
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->addConfiguration(static::getHandlerConfigurationClassByType($type))
                        ->end()
                    ->end()
                ->end();
        }
    }

    /**
     * Add a handler configuration from a type.
     */
    static public function getHandlerConfigurationClassByType(HandlerType $type): string
    {
        $class = $type->getHandlerConfigurationClass();

        if (!$class) {
            throw new \RuntimeException(\sprintf('The handler configuration "%s" is not registered.', $type->value));
        }

        return $class;
    }
}
