<?php

namespace Local\Bundle\MonologPocBundle\Enum;

use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\BrowserConsoleHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\ChromePhpHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\ConsoleHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\ElasticaConsoleHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\ElasticsearchConsoleHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\FilterHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\FingerCrossedHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\FirePhpHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\GelfHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\MongoHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\NativeMailerHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\RollbarHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\RotatingFileHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\ServiceHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\SlackHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\StreamHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\SwiftMailerHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\SymfonyMailerHandlerConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\TelegramHandlerConfiguration;

enum HandlerType: string
{
    case STREAM = 'stream';
    case CONSOLE = 'console';
    case FIREPHP = 'firephp';
    case BROWSER_CONSOLE = 'browser_console';
    case GELF = 'gelf';
    case CHROMEPHP = 'chromephp';
    case ROTATING_FILE = 'rotating_file';
    case MONGO = 'mongo';
    case ELASTICSEARCH = 'elasticsearch';
    case ELASTICA = 'elastica';
//    case REDIS = 'redis';
//    case PREDIS = 'predis';
    case FINGERS_CROSSED = 'fingers_crossed';
    case FILTER = 'filter';
//    case BUFFER = 'buffer';
//    case DEDUPLICATION = 'deduplication';
//    case GROUP = 'group';
//    case WHATFAILUREGROUP = 'whatfailuregroup';
//    case FALLBACKGROUP = 'fallbackgroup';
//    case SYSLOG = 'syslog';
//    case SYSLOGUDP = 'syslogudp';
    case SWIFT_MAILER = 'swift_mailer';
    case NATIVE_MAILER = 'native_mailer';
    case SYMFONY_MAILER = 'symfony_mailer';
//    case SOCKET = 'socket';
//    case PUSHOVER = 'pushover';
//    case RAVEN = 'raven';
//    case SENTRY = 'sentry';
//    case NEWRELIC = 'newrelic';
//    case HIPCHAT = 'hipchat';
    case SLACK = 'slack';
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
    case ROLLBAR = 'rollbar';
//    case SERVER_LOG = 'server_log';
    case TELEGRAM = 'telegram';
//    case SAMPLING = 'sampling';
    case SERVICE = 'service';

    public function getHandlerConfigurationClass(): string
    {
        return match ($this) {
            self::STREAM => StreamHandlerConfiguration::class,
            self::CONSOLE => ConsoleHandlerConfiguration::class,
            self::FIREPHP => FirePhpHandlerConfiguration::class,
            self::BROWSER_CONSOLE => BrowserConsoleHandlerConfiguration::class,
            self::GELF => GelfHandlerConfiguration::class,
            self::ROTATING_FILE => RotatingFileHandlerConfiguration::class,
            self::MONGO => MongoHandlerConfiguration::class,
            self::ELASTICSEARCH => ElasticsearchConsoleHandlerConfiguration::class,
            self::ELASTICA => ElasticaConsoleHandlerConfiguration::class,
            self::FINGERS_CROSSED => FingerCrossedHandlerConfiguration::class,
            self::FILTER => FilterHandlerConfiguration::class,
            self::CHROMEPHP => ChromePhpHandlerConfiguration::class,
            self::SWIFT_MAILER => SwiftMailerHandlerConfiguration::class,
            self::NATIVE_MAILER => NativeMailerHandlerConfiguration::class,
            self::SYMFONY_MAILER => SymfonyMailerHandlerConfiguration::class,
            self::SLACK => SlackHandlerConfiguration::class,
            self::ROLLBAR => RollbarHandlerConfiguration::class,
            self::TELEGRAM => TelegramHandlerConfiguration::class,
            self::SERVICE => ServiceHandlerConfiguration::class,
        };
    }
}
