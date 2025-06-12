<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class ConsoleHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('verbosity_levels')
                ->template('level')
                ->template('bubble')
                ->template('console_formatter_options')
                ->template('channels')
        ;
    }
}
