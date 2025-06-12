<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;

interface AddConfigurationInterface
{
    public function add(NodeBuilder $node): void;
}
