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
        $treeBuilder = new TreeBuilder('monolog_poc');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('use_microseconds')
                    ->defaultTrue()
                ->end()
                ->arrayNode('channels')
                    ->canBeUnset()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('handlers_by_type')
                    ->canBeUnset()
                    ->children()
                        ->callable(static function(NodeBuilder $node): void {
                            foreach (HandlerType::cases() as $type) {
                                $node
                                    ->arrayNode($type->value)
                                        ->canBeUnset()
                                        ->info(sprintf('All "%s" type handlers.', $type->value))
                                        ->useAttributeAsKey('name')
                                        ->prototype('array')
                                            ->children()
                                                ->addConfiguration(static::getHandlerConfigurationClassByType($type))
                                                ->template('nested')
                                            ->end()
                                        ->end()
                                    ->end();
                            }
                        })
                    ->end()
                ->end()
                ->arrayNode('handlers')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->validate()
                        ->ifTrue(function ($v) { return isset($v['debug']); })
                        ->thenInvalid('The "debug" name cannot be used as it is reserved for the handler of the profiler')
                    ->end()
                    ->prototype('array')
                    ->children()
                        ->scalarNode('type')
                            ->isRequired()
                            ->treatNullLike('null')
                            ->beforeNormalization()
                                ->always()
                                ->then(static function ($v): string {
                                    return strtolower($v);
                                })
                            ->end()
                        ->end()
                        ->callable(static function(NodeBuilder $node): void {
                        })
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    static public function getHandlerConfigurationClassByType(HandlerType $type): string
    {
        $class = $type->getHandlerConfigurationClass();

        if (!$class) {
            throw new \RuntimeException(\sprintf('The handler configuration "%s" is not registered.', $type->value));
        }

        return $class;
    }
}
