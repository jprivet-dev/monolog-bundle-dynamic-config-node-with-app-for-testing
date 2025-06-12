<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

use Local\Bundle\MonologPocBundle\Enum\HandlerType;

class SymfonyMailerHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->template('mailer', HandlerType::SYMFONY_MAILER)
            ->end()
        ;
    }
}
