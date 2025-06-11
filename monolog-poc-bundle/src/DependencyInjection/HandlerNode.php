<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class HandlerNode
{
    private static function node(): NodeBuilder {
        return new NodeBuilder();
    }

    public static function level(): NodeDefinition
    {
        return static::node()->scalarNode('level')->defaultValue('DEBUG');
    }

    public static function bubble(): NodeDefinition
    {
        return static::node()->booleanNode('bubble')->defaultTrue();
    }

    public static function file_permissions(): NodeDefinition
    {
        return static::node()->scalarNode('file_permission')
            ->defaultNull()
            ->beforeNormalization()
                ->ifString()
                ->then(function ($v) {
                    if ('0' === substr($v, 0, 1)) {
                        return octdec($v);
                    }

                    return (int) $v;
                })
            ->end();
    }
}
