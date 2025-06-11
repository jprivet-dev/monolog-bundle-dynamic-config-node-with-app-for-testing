<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class StreamHandlerNodeDefinition extends AbstractHandlerNodeDefinition
{
    public function node(): NodeDefinition
    {
        return $this
            ->rootNodeByType(HandlerType::STREAM)
            ->prototype('array')
                ->children()
                    ->append($this->option->path())
                    ->append($this->option->level())
                    ->append($this->option->bubble())
                    ->append($this->option->file_permission())
                    ->append($this->option->use_locking())
                ->end()
            ->end();
    }
}
