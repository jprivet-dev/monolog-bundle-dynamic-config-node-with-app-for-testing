<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class ElasticsearchConsoleHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('elasticsearch')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return ['id' => $v]; })
                    ->end()
                    ->children()
                        ->fragments()->idHost()
                        ->scalarNode('host')->info('Elastic search host name, with scheme (e.g. "https://127.0.0.1:9200").')->end()
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
            ->end()
        ;
    }
}
