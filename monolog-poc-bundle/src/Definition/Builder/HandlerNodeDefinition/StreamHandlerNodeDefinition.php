<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Definition\Builder\TreeBuilder;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class StreamHandlerNodeDefinition implements HandlerNodeDefinitionInterface
{
    public function node(): NodeDefinition
    {
        return (new TreeBuilder(HandlerType::STREAM->value))->getRootNode()
            ->canBeUnset()
            ->info(sprintf('All type "%s" handlers', HandlerType::STREAM->value))
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                ->end()
            ->end();
    }
}
