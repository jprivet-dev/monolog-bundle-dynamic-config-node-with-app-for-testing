<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface AddConfigurationInterface
{
    public function add(NodeDefinition $node): void;
}
