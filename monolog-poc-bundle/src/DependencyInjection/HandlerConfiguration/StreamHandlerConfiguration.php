<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class StreamHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('path')
                ->template('file_permission')
                ->template('use_locking')
                ->template('base')
            ->end()
        ;
    }
}
