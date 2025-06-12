<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class StreamHandlerConfiguration implements AddConfiguration
{
    public function add(NodeBuilder $node): void
    {
        $node
            ->booleanNode('option_3')->end()
            ->booleanNode('option_5')->end();
    }
}
