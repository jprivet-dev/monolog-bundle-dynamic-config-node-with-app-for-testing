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
                ->arrayNode('handlers')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->validate()
                        ->ifTrue(static fn ($v): bool => isset($v['debug']))
                        ->thenInvalid('The "debug" name cannot be used as it is reserved for the handler of the profiler')
                    ->end()
                    ->prototype('array')
                    ->children()
                        ->callable(static function(NodeBuilder $node): void {
                            foreach (HandlerType::cases() as $type) {
                                $node
                                    ->arrayNode($type->value)
                                        ->canBeUnset()
                                        ->info(sprintf('"%s" type handler (one type of handler per name and per environment).', $type->value))
                                        ->children()
                                            ->addConfiguration(static::getHandlerConfigurationClassByType($type))
                                            ->template('base', $type)
                                        ->end()
                                    ->end();
                            }
                        })
                    ->end()
                    ->validate()
                        // Overload by environment: keep only last configured type by name
                        ->ifTrue(static fn (array $v): bool => true)
                        ->then(static fn (array $v): array => [\array_key_last($v) => \end($v)])
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
