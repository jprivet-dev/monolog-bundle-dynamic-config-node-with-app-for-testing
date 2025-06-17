<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class SlackHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('token')->info('Slack api token')->end() // pushover & hipchat & loggly & logentries & flowdock & rollbar & slack & slackbot & insightops & telegram
                ->scalarNode('channel')->defaultNull()->info('Channel name (with starting #).')->end() // slack & slackwebhook & slackbot & telegram
                ->scalarNode('bot_name')->defaultValue('Monolog')->end() // slack & slackwebhook
                ->scalarNode('icon_emoji')->defaultNull()->end() // slack & slackwebhook
                ->scalarNode('use_attachment')->defaultTrue()->end() // slack & slackwebhook
                ->scalarNode('use_short_attachment')->defaultFalse()->end() // slack & slackwebhook
                ->scalarNode('include_extra')->defaultFalse()->end() // slack & slackwebhook
                ->scalarNode('timeout')->end() // socket_handler, logentries, pushover, hipchat & slack
                ->scalarNode('connection_timeout')->end() // socket_handler, logentries, pushover, hipchat & slack
                ->template('level')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['token']) || empty($v['channel']))
                ->thenInvalid('The token and channel have to be specified to use a SlackHandler.')
            ->end()
        ;
    }
}
