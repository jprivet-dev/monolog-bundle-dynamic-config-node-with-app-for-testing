<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** @var array<string> */
    const HANDLER_TYPES = [
        'amqp',
        'browser_console',
        'buffer',
        'chromephp',
        'console',
        'cube',
        'debug',
        'deduplication',
        'elastic_search',
        'elastica',
        'error_log',
        'filter',
        'fingers_crossed',
        'firephp',
        'flowdock',
        'gelf',
        'hipchat',
        'insightops',
        'logentries',
        'loggly',
        'mongo',
        'native_mailer',
        'newrelic',
        'null',
        'predis',
        'pushover',
        'raven',
        'redis',
        'rollbar',
        'rotating_file',
        'sentry',
        'server_log',
        'slack',
        'slackbot',
        'socket',
        'stream',
        'swift_mailer',
        'symfony_mailer',
        'syslog',
        'telegram',
        'test',
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('monolog-poc');
        $root = $treeBuilder->getRootNode();
        static::addHandlers($root);

        return $treeBuilder;
    }

    private static function addHandlers(ArrayNodeDefinition $root): void
    {
         $handlers = $root
            ->children()
                ->arrayNode('handlers')
                    ->canBeUnset();

        foreach (static::HANDLER_TYPES as $type) {
            $handlers
                ->children()
                    ->append(static::handlerByType($type)
                        ->children()
                            ->booleanNode('option_1')->end()
                            ->append(static::option_2())
                            ->booleanNode('option_4')->end()
                            ->append(static::option_6())
                        ->end()
                    ->end())
                ->end();
        }
    }

    private static function handlerByType(string $type): NodeDefinition
    {
        return (new NodeBuilder())
            ->arrayNode($type)
            ->canBeUnset()
            ->info(sprintf('All type "%s" handlers', $type))
            ->useAttributeAsKey('name')
            ->prototype('array');
    }

    private static function option_2(): NodeDefinition
    {
        return (new NodeBuilder())
            ->booleanNode('option_2');
    }

    private static function option_6(): NodeDefinition
    {
        return (new NodeBuilder())
            ->booleanNode('option_6');
    }
}
