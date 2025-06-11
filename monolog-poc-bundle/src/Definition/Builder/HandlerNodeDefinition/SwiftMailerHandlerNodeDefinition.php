<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class SwiftMailerHandlerNodeDefinition extends AbstractHandlerNodeDefinition
{
    public function node(): NodeDefinition
    {
        return $this
            ->rootNodeByType(HandlerType::SWIFT_MAILER)
            ->prototype('array')
                ->children()
                    ->append($this->option->level())
                    ->append($this->option->bubble())
                ->end()
            ->end();
    }
}
