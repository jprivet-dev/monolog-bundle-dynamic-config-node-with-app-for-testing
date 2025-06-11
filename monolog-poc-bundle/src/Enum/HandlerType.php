<?php

namespace Local\Bundle\MonologPocBundle\Enum;

use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition\StreamHandlerNodeDefinition;
use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition\ConsoleHandlerNodeDefinition;
use Local\Bundle\MonologPocBundle\Definition\Builder\HandlerNodeDefinition\FirePhpHandlerNodeDefinition;

enum HandlerType: string
{
    case STREAM = 'stream';
    case CONSOLE = 'console';
    case FIREPHP = 'firephp';
//    case BROWSER_CONSOLE = 'browser_console';
//    case GELF = 'gelf';
//    case CHROMEPHP = 'chromephp';
//    case ROTATING_FILE = 'rotating_file';
//    case MONGO = 'mongo';
//    case ELASTIC_SEARCH = 'elastic_search';
//    case ELASTICA = 'elastica';
//    case REDIS = 'redis';
//    case PREDIS = 'predis';
//    case FINGERS_CROSSED = 'fingers_crossed';
//    case FILTER = 'filter';
//    case BUFFER = 'buffer';
//    case DEDUPLICATION = 'deduplication';
//    case GROUP = 'group';
//    case WHATFAILUREGROUP = 'whatfailuregroup';
//    case FALLBACKGROUP = 'fallbackgroup';
//    case SYSLOG = 'syslog';
//    case SYSLOGUDP = 'syslogudp';
//    case SWIFT_MAILER = 'swift_mailer';
//    case NATIVE_MAILER = 'native_mailer';
//    case SYMFONY_MAILER = 'symfony_mailer';
//    case SOCKET = 'socket';
//    case PUSHOVER = 'pushover';
//    case RAVEN = 'raven';
//    case SENTRY = 'sentry';
//    case NEWRELIC = 'newrelic';
//    case HIPCHAT = 'hipchat';
//    case SLACK = 'slack';
//    case SLACKWEBHOOK = 'slackwebhook';
//    case SLACKBOT = 'slackbot';
//    case CUBE = 'cube';
//    case AMQP = 'amqp';
//    case ERROR_LOG = 'error_log';
//    case NULL = 'null';
//    case TEST = 'test';
//    case DEBUG = 'debug';
//    case LOGGLY = 'loggly';
//    case LOGENTRIES = 'logentries';
//    case INSIGHTOPS = 'insightops';
//    case FLOWDOCK = 'flowdock';
//    case ROLLBAR = 'rollbar';
//    case SERVER_LOG = 'server_log';
//    case TELEGRAM = 'telegram';
//    case SAMPLING = 'sampling';

    public function getHandlerNodeDefinitionClass(): string
    {
        return match ($this) {
            self::STREAM => StreamHandlerNodeDefinition::class,
            self::CONSOLE => ConsoleHandlerNodeDefinition::class,
            self::FIREPHP => FirePhpHandlerNodeDefinition::class,
        };
    }
}
