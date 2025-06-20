<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class SymfonyMailerHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
            ->children()
                ->fragments()->mailer(HandlerType::SYMFONY_MAILER)
            ->end()
        ;
    }
}
