<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

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
