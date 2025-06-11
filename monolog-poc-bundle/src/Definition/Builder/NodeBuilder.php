<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class NodeBuilder extends BaseNodeBuilder
{
    public function append(\Closure|NodeDefinition $node): static
    {
        if ($node instanceof \Closure) {
            $node = ($node)();
        }

        return parent::append($node);
    }

    /** @param array<NodeDefinition> $nodes */
    public function appendArray(array $nodes): static
    {
        foreach ($nodes as $node) {
            $this->append($node);
        }

        return $this;
    }
}
