<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class ChromePhpHandlerConfiguration extends HandlerConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->template('base')
            ->end()
        ;
    }
}
