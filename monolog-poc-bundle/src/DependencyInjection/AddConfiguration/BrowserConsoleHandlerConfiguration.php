<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class BrowserConsoleHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
            ->children()
            ->end()
        ;
    }
}
