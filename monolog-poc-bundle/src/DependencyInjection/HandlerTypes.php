<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

enum HandlerTypes: string
{
    case amqp = 'amqp';
    case browserConsole = 'browser_console';
    case buffer = 'buffer';
    case chromephp = 'chromephp';
    case console = 'console';
    case cube = 'cube';
    case debug = 'debug';
    case deduplication = 'deduplication';
    case elasticSearch = 'elastic_search';
    case elastica = 'elastica';
    case errorLog = 'error_log';
    case filter = 'filter';
    case fingersCrossed = 'fingers_crossed';
    case firephp = 'firephp';
    case flowdock = 'flowdock';
    case gelf = 'gelf';
    case hipchat = 'hipchat';
    case insightops = 'insightops';
    case logentries = 'logentries';
    case loggly = 'loggly';
    case mongo = 'mongo';
    case nativeMailer = 'native_mailer';
    case newrelic = 'newrelic';
    case null = 'null';
    case predis = 'predis';
    case pushover = 'pushover';
    case raven = 'raven';
    case redis = 'redis';
    case rollbar = 'rollbar';
    case rotatingFile = 'rotating_file';
    case sentry = 'sentry';
    case serverLog = 'server_log';
    case slack = 'slack';
    case slackbot = 'slackbot';
    case socket = 'socket';
    case stream = 'stream';
    case swiftMailer = 'swift_mailer';
    case symfonyMailer = 'symfony_mailer';
    case syslog = 'syslog';
    case telegram = 'telegram';
    case test = 'test';
}
