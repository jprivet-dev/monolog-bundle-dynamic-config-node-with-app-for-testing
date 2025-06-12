<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class ConsoleHandlerConfiguration extends HandlerConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->template('verbosity_levels')
                ->template('console_formatter_options')
                ->template('base')
            ->end()
        ;
    }
}
