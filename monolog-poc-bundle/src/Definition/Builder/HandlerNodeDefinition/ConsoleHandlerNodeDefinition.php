<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConsoleHandlerNodeDefinition implements HandlerNodeDefinitionInterface
{
    public function node(): NodeDefinition
    {
        return (new TreeBuilder(HandlerType::CONSOLE->value))->getRootNode()
            ->canBeUnset()
            ->info(sprintf('All type "%s" handlers', HandlerType::CONSOLE->value))
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                ->end()
            ->end();
    }
}
