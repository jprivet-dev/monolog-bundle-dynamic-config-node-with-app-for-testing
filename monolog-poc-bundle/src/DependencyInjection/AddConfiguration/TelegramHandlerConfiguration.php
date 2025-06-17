<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class TelegramHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('token')->info('Telegram bot access token provided by BotFather.')->end() // pushover & hipchat & loggly & logentries & flowdock & rollbar & slack & slackbot & insightops & telegram
                ->scalarNode('channel')->defaultNull()->info('Telegram channel name.')->end() // slack & slackwebhook & slackbot & telegram
                ->scalarNode('parse_mode')->defaultNull()->info('Optional the kind of formatting that is used for the message.')->end() // telegram
                ->booleanNode('disable_webpage_preview')->defaultNull()->info('Disables link previews for links in the message.')->end() // telegram
                ->booleanNode('disable_notification')->defaultNull()->info('Sends the message silently. Users will receive a notification with no sound.')->end() // telegram
                ->booleanNode('split_long_messages')->defaultFalse()->info('Split messages longer than 4096 bytes into multiple messages.')->end() // telegram
                ->booleanNode('delay_between_messages')->defaultFalse()->info('Adds a 1sec delay/sleep between sending split messages.')->end() // telegram
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['token']) || empty($v['channel']))
                ->thenInvalid('The token and channel have to be specified to use a TelegramBotHandler.')
            ->end()
        ;
    }
}
