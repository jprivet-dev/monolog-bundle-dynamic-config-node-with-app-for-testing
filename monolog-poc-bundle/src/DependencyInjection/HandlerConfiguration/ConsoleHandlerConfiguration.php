<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class ConsoleHandlerConfiguration implements AddConfiguration
{
    public function add(NodeBuilder $node): void
    {
        $node
            ->booleanNode('option_7')->end()
            ->booleanNode('option_8')->end();
    }
}
