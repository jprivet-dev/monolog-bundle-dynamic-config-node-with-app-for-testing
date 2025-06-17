<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class FirePhpHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
            ->end()
        ;
    }
}
