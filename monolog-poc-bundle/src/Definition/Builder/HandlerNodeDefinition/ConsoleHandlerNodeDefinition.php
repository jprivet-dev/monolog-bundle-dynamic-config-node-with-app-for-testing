<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class ConsoleHandlerNodeDefinition extends AbstractHandlerNodeDefinition
{
    public function node(): NodeDefinition
    {
        return $this
            ->rootNodeByType(HandlerType::CONSOLE)
            ->prototype('array')
                ->children()
                    ->append($this->option->verbosity_levels())
                    ->append($this->option->level())
                    ->append($this->option->bubble())
                    ->append($this->option->console_formatter_options())
                    ->append($this->option->channels())
                ->end()
            ->end();
    }
}
