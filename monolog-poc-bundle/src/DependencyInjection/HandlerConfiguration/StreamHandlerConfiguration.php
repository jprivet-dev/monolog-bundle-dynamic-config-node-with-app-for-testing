<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class StreamHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->append($this->options->path())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->file_permission())
            ->append($this->options->use_locking())
            ->append($this->options->channels())
        ;
    }
}
