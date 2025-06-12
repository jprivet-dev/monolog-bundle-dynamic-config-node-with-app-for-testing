<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class GelfHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->arrayNode('publisher')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return ['id' => $v]; })
                    ->end()
                    ->children()
                        ->scalarNode('id')->end()
                        ->scalarNode('hostname')->end()
                        ->scalarNode('port')->defaultValue(12201)->end()
                        ->scalarNode('chunk_size')->defaultValue(1420)->end()
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !isset($v['id']) && !isset($v['hostname']);
                        })
                        ->thenInvalid('What must be set is either the hostname or the id.')
                    ->end()
                ->end()
                ->template('base')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'gelf' === $v['type'] && !isset($v['publisher']); })
                ->thenInvalid('The publisher has to be specified to use a GelfHandler')
            ->end()
        ;
    }
}
