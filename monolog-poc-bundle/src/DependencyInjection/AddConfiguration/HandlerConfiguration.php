<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

abstract class HandlerConfiguration implements AddConfigurationInterface
{
    public function __construct(protected NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node)
    {
    }
}
