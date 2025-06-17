<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class GelfHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->arrayNode('publisher')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(static fn ($v) => ['id' => $v])
                    ->end()
                    ->children()
                        ->scalarNode('id')->end()
                        ->scalarNode('hostname')->end()
                        ->scalarNode('port')->defaultValue(12201)->end()
                        ->scalarNode('chunk_size')->defaultValue(1420)->end()
                    ->end()
                    ->validate()
                        ->ifTrue(static fn ($v) => !isset($v['id']) && !isset($v['hostname']))
                        ->thenInvalid('What must be set is either the hostname or the id.')
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => 'gelf' === $v['type'] && !isset($v['publisher']))
                ->thenInvalid('The publisher has to be specified to use a GelfHandler.')
            ->end()
        ;
    }
}
