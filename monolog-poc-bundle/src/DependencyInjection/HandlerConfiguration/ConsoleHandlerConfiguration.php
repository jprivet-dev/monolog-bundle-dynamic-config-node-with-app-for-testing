<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class ConsoleHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->append($this->options->verbosity_levels())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->console_formatter_options())
            ->append($this->options->channels())
        ;
    }
}
