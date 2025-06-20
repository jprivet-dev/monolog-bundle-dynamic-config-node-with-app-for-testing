<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class MongoHandlerConfiguration implements HandlerConfigurationInterface
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('mongo')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(static fn ($v) => ['id' => $v])
                    ->end()
                    ->children()
                        //->fragments()->idHost()
                        ->scalarNode('host')->info('Database host name, optional if id is given.')->end()
                        ->scalarNode('port')->defaultValue(27017)->end()
                        ->scalarNode('user')->end()
                        ->scalarNode('pass')->info('Mandatory only if user is present.')->end()
                        ->scalarNode('database')->defaultValue('monolog')->end()
                        ->scalarNode('collection')->defaultValue('logs')->end()
                    ->end()
                    ->validate()
                        ->ifTrue(static fn ($v) => !isset($v['id']) && !isset($v['host']))
                        ->thenInvalid('What must be set is either the host or the id.')
                    ->end()
                    ->validate()
                        ->ifTrue(static fn ($v) => isset($v['user']) && !isset($v['pass']))
                        ->thenInvalid('If you set user, you must provide a password.')
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => !isset($v['mongo']))
                ->thenInvalid('The mongo configuration has to be specified to use a MongoHandler.')
            ->end()
        ;
    }
}
