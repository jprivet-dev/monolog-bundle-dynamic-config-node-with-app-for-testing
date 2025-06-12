<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class RotatingFileHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('path')
                ->template('file_permission')
                ->template('use_locking')
                ->scalarNode('filename_format')->defaultValue('{filename}-{date}')->end()
                ->scalarNode('date_format')->defaultValue('Y-m-d')->end()
                ->scalarNode('max_files')->defaultValue(0)->end()
                ->template('base')
            ->end()
        ;
    }
}
