<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition\HandlerNodeDefinitionInterface;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class NodeBuilder extends BaseNodeBuilder
{
    public function closure(\Closure $closure): static
    {
        return $closure($this);
    }

    /**
     * Creates a child handler node.
     */
    public function handlerNode(HandlerType $type): NodeDefinition
    {
        $class = $this->getHandlerNodeDefinitionClass($type);

        /** @var HandlerNodeDefinitionInterface $nodeDefinition */
        $nodeDefinition = new $class();
        $node = $nodeDefinition->node();
        $this->append($node);

        return $node;
    }

    /**
     * Returns the class name of the handler node definition.
     */
    protected function getHandlerNodeDefinitionClass(HandlerType $type): string
    {
        if (!$type->getHandlerNodeDefinitionClass()) {
            throw new \RuntimeException(\sprintf('The handler node type "%s" is not registered.', $type->value));
        }

        $class = $type->getHandlerNodeDefinitionClass();

        if (!class_exists($type->getHandlerNodeDefinitionClass())) {
            throw new \RuntimeException(\sprintf('The handler node class "%s" does not exist.', $class));
        }

        return $class;
    }
}
