<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class ServiceHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('id')->end()
                ->scalarNode('formatter')->end()
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['formatter']))
                ->thenInvalid('Service handlers can not have a formatter configured in the bundle, you must reconfigure the service itself instead')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => !isset($v['id']))
                ->thenInvalid('The id has to be specified to use a service as handler')
            ->end()
        ;
    }
}
