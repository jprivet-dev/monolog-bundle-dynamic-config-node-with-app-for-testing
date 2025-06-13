<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class TelegramHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('token')->end() // pushover & hipchat & loggly & logentries & flowdock & rollbar & slack & slackbot & insightops & telegram
                ->scalarNode('channel')->defaultNull()->end() // slack & slackwebhook & slackbot & telegram
                ->scalarNode('parse_mode')->defaultNull()->end() // telegram
                ->booleanNode('disable_webpage_preview')->defaultNull()->end() // telegram
                ->booleanNode('disable_notification')->defaultNull()->end() // telegram
                ->booleanNode('split_long_messages')->defaultFalse()->end() // telegram
                ->booleanNode('delay_between_messages')->defaultFalse()->end() // telegram
                ->template('base')
            ->end()
            // TODO: validate() from original MonologBundle/src/DependencyInjection/Configuration.php. Adjust ifTrue() conditions.
            ->validate()
                ->ifTrue(function ($v) { return 'telegram' === $v['type'] && (empty($v['token']) || empty($v['channel'])); })
                ->thenInvalid('The token and channel have to be specified to use a TelegramBotHandler')
            ->end()
        ;
    }
}
