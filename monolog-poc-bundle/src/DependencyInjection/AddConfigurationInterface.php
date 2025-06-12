<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

interface AddConfigurationInterface
{
    public function __construct(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node);

    public function __invoke(): void;
}
