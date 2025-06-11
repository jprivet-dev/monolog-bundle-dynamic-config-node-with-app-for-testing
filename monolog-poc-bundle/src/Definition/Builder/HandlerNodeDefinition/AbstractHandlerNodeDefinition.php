<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

abstract class AbstractHandlerNodeDefinition
{
    protected function rootNodeByType(HandlerType $type): ArrayNodeDefinition
    {
        return (new TreeBuilder($type->value))->getRootNode()
            ->canBeUnset()
            ->info(sprintf('All type "%s" handlers', $type->value))
            ->useAttributeAsKey('name');
    }

    abstract public function node(): NodeDefinition;
}
