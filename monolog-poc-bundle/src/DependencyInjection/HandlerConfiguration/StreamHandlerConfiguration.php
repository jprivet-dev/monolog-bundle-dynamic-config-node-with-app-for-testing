<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;

class StreamHandlerConfiguration extends HandlerConfiguration
{
    public function add(NodeBuilder $node): void
    {
        $node
            ->append($this->options->path())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->file_permission())
            ->append($this->options->use_locking())
            ->append($this->options->channels());
    }
}
