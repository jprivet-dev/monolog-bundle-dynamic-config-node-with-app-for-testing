<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;

class NativeMailerHandlerConfiguration extends HandlerConfiguration
{
    public function add(NodeBuilder $node): void
    {
        $node
            ->append($this->options->level())
            ->append($this->options->bubble());
    }
}
