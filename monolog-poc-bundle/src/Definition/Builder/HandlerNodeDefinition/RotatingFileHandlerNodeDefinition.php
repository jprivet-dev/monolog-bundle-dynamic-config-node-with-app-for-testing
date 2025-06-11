<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class RotatingFileHandlerNodeDefinition extends AbstractHandlerNodeDefinition
{
    public function node(): NodeDefinition
    {
        return $this
            ->rootNodeByType(HandlerType::ROTATING_FILE)
            ->prototype('array')
                ->children()
                    ->append($this->option->path())
                    ->append($this->option->level())
                    ->append($this->option->bubble())
                    ->append($this->option->file_permission())
                    ->append($this->option->use_locking())
                    ->append($this->option->filename_format())
                    ->append($this->option->date_format())
                    ->append($this->option->channels())
                ->end()
            ->end();
    }
}
