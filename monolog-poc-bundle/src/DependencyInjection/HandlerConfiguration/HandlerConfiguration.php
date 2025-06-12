<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerOptionsNodeDefinition;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;

abstract class HandlerConfiguration implements AddConfigurationInterface
{
    protected HandlerOptionsNodeDefinition $options;

    public function __construct()
    {
        $this->options = new HandlerOptionsNodeDefinition();
    }
}
