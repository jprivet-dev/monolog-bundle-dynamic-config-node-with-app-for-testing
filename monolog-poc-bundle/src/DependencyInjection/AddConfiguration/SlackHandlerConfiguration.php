<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;

class SlackHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node): void
    {
        $node
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
                ->fragments()->level()
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['token']) || empty($v['channel']))
                ->thenInvalid('The token and channel have to be specified to use a SlackHandler.')
            ->end()
        ;
    }
}
