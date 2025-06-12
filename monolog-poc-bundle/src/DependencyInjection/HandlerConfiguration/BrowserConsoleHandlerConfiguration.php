<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class BrowserConsoleHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('base')
            ->end()
        ;
    }
}
