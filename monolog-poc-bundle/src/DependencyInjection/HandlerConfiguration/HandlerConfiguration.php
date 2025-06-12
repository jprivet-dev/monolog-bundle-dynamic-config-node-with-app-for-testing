<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerOptionsNodeDefinition;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

abstract class HandlerConfiguration implements AddConfigurationInterface
{
    protected HandlerOptionsNodeDefinition $options;

    public function __construct(protected NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node)
    {
        $this->options = new HandlerOptionsNodeDefinition();
    }
}
