<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeDefinitionAwareInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

abstract class AbstractAddConfiguration implements NodeDefinitionAwareInterface
{
    public function __construct(protected NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node)
    {
    }

    abstract public function __invoke(): void;
}
