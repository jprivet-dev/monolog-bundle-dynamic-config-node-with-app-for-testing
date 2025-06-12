<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;

class NativeMailerHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('mailer', HandlerType::NATIVE_MAILER)
            ->end()
        ;
    }
}
