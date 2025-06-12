<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class SwiftMailerHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->append($this->options->level())
            ->append($this->options->bubble())
        ;
    }
}
