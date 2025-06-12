<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

class StreamHandlerConfiguration extends HandlerConfiguration
{
    public function add(NodeDefinition $node): void
    {
        $node
            ->append($this->options->path())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->file_permission())
            ->append($this->options->use_locking())
            ->append($this->options->channels())
        ;
    }
}
