<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class RotatingFileHandlerConfiguration implements HandlerConfigurationInterface
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
            ->children()
                //->fragments()->path()
                //->fragments()->filePermission()
                //->fragments()->useLocking()
                ->scalarNode('filename_format')->defaultValue('{filename}-{date}')->end()
                ->scalarNode('date_format')->defaultValue('Y-m-d')->end()
                ->scalarNode('max_files')->defaultValue(0)->info('Files to keep, defaults to zero (infinite).')->end()
            ->end()
        ;
    }
}
