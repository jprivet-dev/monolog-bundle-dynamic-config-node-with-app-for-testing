<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

interface NodeDefinitionAwareInterface
{
    public function __construct(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node);
}
