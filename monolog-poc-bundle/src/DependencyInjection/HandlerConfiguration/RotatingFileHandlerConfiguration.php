<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class RotatingFileHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->append($this->options->path())
            ->append($this->options->level())
            ->append($this->options->bubble())
            ->append($this->options->file_permission())
            ->append($this->options->use_locking())
            ->append($this->options->filename_format())
            ->append($this->options->date_format())
            ->append($this->options->channels())
        ;
    }
}
