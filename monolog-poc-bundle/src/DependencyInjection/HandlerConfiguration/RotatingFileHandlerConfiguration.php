<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class RotatingFileHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('path')
                ->template('level')
                ->template('bubble')
                ->template('file_permission')
                ->template('use_locking')
                ->template('filename_format')
                ->template('date_format')
                ->template('channels')
        ;
    }
}
