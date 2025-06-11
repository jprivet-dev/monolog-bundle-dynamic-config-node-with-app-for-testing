<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\TreeBuilder as BaseTreeBuilder;

class TreeBuilder extends BaseTreeBuilder
{
    public function __construct(string $name, string $type = 'array', ?NodeBuilder $builder = null)
    {
        $builder ??= new NodeBuilder();
        parent::__construct($name, $type, $builder);
    }
}
