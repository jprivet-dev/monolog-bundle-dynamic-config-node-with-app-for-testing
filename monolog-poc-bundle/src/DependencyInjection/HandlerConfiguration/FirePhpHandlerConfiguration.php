<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class FirePhpHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->channels())
        ;
    }
}
