<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class MongoHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->arrayNode('mongo')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(static fn ($v) => ['id' => $v])
                    ->end()
                    ->children()
                        ->template('id_host')
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
                ->template('base')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => !isset($v['mongo']))
                ->thenInvalid('The mongo configuration has to be specified to use a MongoHandler.')
            ->end()
        ;
    }
}
