<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

abstract class AbstractAddConfiguration
{
    abstract public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void;
}
