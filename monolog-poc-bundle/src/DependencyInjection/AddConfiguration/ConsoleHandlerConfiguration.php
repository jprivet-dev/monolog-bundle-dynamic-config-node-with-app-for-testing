<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class ConsoleHandlerConfiguration extends AbstractAddConfiguration
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
