<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;

class NodeBuilder extends BaseNodeBuilder
{
    public function closure(\Closure $closure): static
    {
        return $closure($this);
    }
}
