<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class Handler
{
    /** @return array<NodeDefinition> */
    public static function stream(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function console(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function firephp(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function browser_console(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function gelf(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function chromephp(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function rotating_file(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function mongo(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function elastic_search(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function elastica(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function redis(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function predis(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function fingers_crossed(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function filter(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function buffer(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function deduplication(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function group(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function whatfailuregroup(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function fallbackgroup(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function syslog(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function syslogudp(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function swift_mailer(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function native_mailer(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function symfony_mailer(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function socket(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function pushover(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function raven(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function sentry(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function newrelic(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function hipchat(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function slack(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function slackwebhook(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function slackbot(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function cube(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function amqp(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function error_log(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function null(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function test(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function debug(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function loggly(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function logentries(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function insightops(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function flowdock(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function rollbar(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function server_log(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function telegram(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }

    /** @return array<NodeDefinition> */
    public static function sampling(): array
    {
        return [
            HandlerNode::level(),
            HandlerNode::bubble(),
            HandlerNode::file_permissions(),
        ];
    }
}
