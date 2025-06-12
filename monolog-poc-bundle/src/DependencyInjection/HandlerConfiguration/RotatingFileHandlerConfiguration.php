<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;

class RotatingFileHandlerConfiguration extends HandlerConfiguration
{
    public function add(NodeBuilder $node): void
    {
        $node
            ->append($this->options->path())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->file_permission())
            ->append($this->options->use_locking())
            ->append($this->options->filename_format())
            ->append($this->options->date_format())
            ->append($this->options->channels());
    }
}
