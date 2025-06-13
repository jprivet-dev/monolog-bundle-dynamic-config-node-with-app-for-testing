<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class ChromePhpHandlerConfiguration extends AbstractAddConfiguration
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
