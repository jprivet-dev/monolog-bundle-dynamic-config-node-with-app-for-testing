<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class ElasticaConsoleHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->arrayNode('elasticsearch')
                    ->canBeUnset()
                    ->beforeNormalization()
                    ->ifString()
                    ->then(function ($v) { return ['id' => $v]; })
                    ->end()
                    ->children()
                        ->template('id_host')
                        ->scalarNode('host')->info('Elastic search host name - Do not prepend with http(s)://')->end()
                        ->scalarNode('port')->defaultValue(9200)->end()
                        ->scalarNode('transport')->defaultValue('Http')->end()
                        ->scalarNode('user')->defaultNull()->end()
                        ->scalarNode('password')->defaultNull()->end()
                    ->end()
                    ->validate()
                        ->ifTrue(static fn ($v) => !isset($v['id']) && !isset($v['host']))
                        ->thenInvalid('What must be set is either the host or the id.')
                    ->end()
                ->end()
                ->scalarNode('index')->defaultValue('monolog')->end() // elasticsearch & elastic_search & elastica
                ->scalarNode('document_type')->defaultValue('logs')->end() // elasticsearch & elastic_search & elastica
                ->scalarNode('ignore_error')->defaultValue(false)->end() // elasticsearch & elastic_search & elastica
                ->template('base')
            ->end()
        ;
    }
}
