<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class RollbarHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('id')->info('RollbarNotifier service (mandatory if token is not provided).')->end() // service & rollbar
                ->scalarNode('token')->info('Rollbar api token (skip if you provide a RollbarNotifier service id).')->end() // pushover & hipchat & loggly & logentries & flowdock & rollbar & slack & slackbot & insightops & telegram
                 ->arrayNode('config') // rollbar
                    ->canBeUnset()
                    ->info('Config values from https://github.com/rollbar/rollbar-php#configuration-reference.')
                    ->prototype('scalar')->end()
                ->end()
                ->template('base')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => !empty($v['id']) && !empty($v['token']))
                ->thenInvalid('You can not use both an id and a token in a RollbarHandler.')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['id']) && empty($v['token']))
                ->thenInvalid('The id or the token has to be specified to use a RollbarHandler.')
            ->end()
        ;
    }
}
