<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;

class NativeMailerHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->template('mailer', HandlerType::NATIVE_MAILER)
            ->end()
        ;
    }
}
