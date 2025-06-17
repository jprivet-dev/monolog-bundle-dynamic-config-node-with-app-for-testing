<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class BrowserConsoleHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
            ->end()
        ;
    }
}
