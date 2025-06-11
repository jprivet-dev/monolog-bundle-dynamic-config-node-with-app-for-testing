<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

interface HandlerNodeDefinitionInterface
{
    public function node(): NodeDefinition;
}
