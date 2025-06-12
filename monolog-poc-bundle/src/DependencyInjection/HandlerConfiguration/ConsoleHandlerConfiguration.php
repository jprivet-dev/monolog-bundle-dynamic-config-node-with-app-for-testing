<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

class ConsoleHandlerConfiguration extends HandlerConfiguration
{
    public function add(NodeDefinition $node): void
    {
        $node
            ->append($this->options->verbosity_levels())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->console_formatter_options())
            ->append($this->options->channels())
        ;
    }
}
