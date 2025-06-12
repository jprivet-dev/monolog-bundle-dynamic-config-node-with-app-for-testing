<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration;

class NativeMailerHandlerConfiguration extends HandlerConfiguration
{
    public function add(): void
    {
        $this->node
            ->children()
                ->scalarNode('from_email')->end() // swift_mailer, native_mailer, symfony_mailer and flowdock
                ->arrayNode('to_email') // swift_mailer, native_mailer and symfony_mailer
                    ->prototype('scalar')->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return [$v]; })
                    ->end()
                ->end()
                ->scalarNode('subject')->end() // swift_mailer, native_mailer and symfony_mailer
                ->scalarNode('content_type')->defaultNull()->end() // swift_mailer and symfony_mailer
                ->arrayNode('headers') // native_mailer
                    ->canBeUnset()
                    ->scalarPrototype()->end()
                ->end()
                ->scalarNode('mailer')->defaultNull()->end() // swift_mailer and symfony_mailer
                ->arrayNode('email_prototype') // swift_mailer and symfony_mailer
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return ['id' => $v]; })
                    ->end()
                    ->children()
                        ->scalarNode('id')->isRequired()->end()
                        ->scalarNode('method')->defaultNull()->end()
                    ->end()
                ->end()
                ->booleanNode('lazy')->defaultValue(true)->end() // swift_mailer
                ->append($this->options->level())
                ->append($this->options->bubble())
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'swift_mailer' === $v['type'] && empty($v['email_prototype']) && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])); })
                ->thenInvalid('The sender, recipient and subject or an email prototype have to be specified to use a SwiftMailerHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'native_mailer' === $v['type'] && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])); })
                ->thenInvalid('The sender, recipient and subject have to be specified to use a NativeMailerHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'symfony_mailer' === $v['type'] && empty($v['email_prototype']) && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])); })
                ->thenInvalid('The sender, recipient and subject or an email prototype have to be specified to use the Symfony MailerHandler')
            ->end()
        ;
    }
}
