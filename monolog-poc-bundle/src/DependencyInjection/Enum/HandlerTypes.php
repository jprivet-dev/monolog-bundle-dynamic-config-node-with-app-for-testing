<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\Enum;

enum HandlerTypes: string
{
    case AMQP = 'amqp';
    case BROWSER_CONSOLE = 'browser_console';
    case BUFFER = 'buffer';
    case CHROMEPHP = 'chromephp';
    case CONSOLE = 'console';
    case CUBE = 'cube';
    case DEBUG = 'debug';
    case DEDUPLICATION = 'deduplication';
    case ELASTICA = 'elastica';
    case ELASTIC_SEARCH = 'elastic_search';
    case ERROR_LOG = 'error_log';
    case FALLBACKGROUP = 'fallbackgroup';
    case FILTER = 'filter';
    case FINGERS_CROSSED = 'fingers_crossed';
    case FIREPHP = 'firephp';
    case GELF = 'gelf';
    case GROUP = 'group';
    case HIPCHAT = 'hipchat';
    case LOGGLY = 'loggly';
    case MONGO = 'mongo';
    case NATIVE_MAILER = 'native_mailer';
    case NEWRELIC = 'newrelic';
    case NULL = 'null';
    case PREDIS = 'predis';
    case PUSHOVER = 'pushover';
    case RAVEN = 'raven';
    case REDIS = 'redis';
    case ROTATING_FILE = 'rotating_file';
    case SENTRY = 'sentry';
    case SLACK = 'slack';
    case SLACKBOT = 'slackbot';
    case SLACKWEBHOOK = 'slackwebhook';
    case SOCKET = 'socket';
    case STREAM = 'stream';
    case SWIFT_MAILER = 'swift_mailer';
    case SYMFONY_MAILER = 'symfony_mailer';
    case SYSLOG = 'syslog';
    case SYSLOGUDP = 'syslogudp';
    case TEST = 'test';
    case WHATFAILUREGROUP = 'whatfailuregroup';
}
