<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class HandlerOptionNodeDefinition
{
    protected function node(): NodeBuilder
    {
        return new NodeBuilder();
    }

    public function level(): NodeDefinition
    {
        return $this->node()
            ->scalarNode('level')->defaultValue('DEBUG');
    }

    public function bubble(): NodeDefinition
    {
        return $this->node()
            ->booleanNode('bubble')->defaultTrue();
    }

    public function file_permission(): NodeDefinition
    {
        return $this->node()
            ->scalarNode('file_permission')
            ->defaultNull()
            ->beforeNormalization()
                ->ifString()
                ->then(function ($v) {
                    if ('0' === substr($v, 0, 1)) {
                        return octdec($v);
                    }

                    return (int)$v;
                })
            ->end();
    }

    public function use_locking(): NodeDefinition
    {
        return $this->node()
                ->booleanNode('use_locking')->defaultFalse();
    }

    public function path(): NodeDefinition
    {
        return $this->node()
                ->scalarNode('path')->defaultValue('%kernel.logs_dir%/%kernel.environment%.log');
    }

    public function verbosity_levels(): NodeDefinition
    {
        return $this->node()
            ->arrayNode('verbosity_levels') // console
                ->beforeNormalization()
                    ->ifArray()
                    ->then(function ($v) {
                        $map = [];
                        $verbosities = ['VERBOSITY_QUIET', 'VERBOSITY_NORMAL', 'VERBOSITY_VERBOSE', 'VERBOSITY_VERY_VERBOSE', 'VERBOSITY_DEBUG'];
                        // allow numeric indexed array with ascendning verbosity and lowercase names of the constants
                        foreach ($v as $verbosity => $level) {
                            if (\is_int($verbosity) && isset($verbosities[$verbosity])) {
                                $map[$verbosities[$verbosity]] = strtoupper($level);
                            } else {
                                $map[strtoupper($verbosity)] = strtoupper($level);
                            }
                        }

                        return $map;
                    })
                ->end()
                ->children()
                    ->scalarNode('VERBOSITY_QUIET')->defaultValue('ERROR')->end()
                    ->scalarNode('VERBOSITY_NORMAL')->defaultValue('WARNING')->end()
                    ->scalarNode('VERBOSITY_VERBOSE')->defaultValue('NOTICE')->end()
                    ->scalarNode('VERBOSITY_VERY_VERBOSE')->defaultValue('INFO')->end()
                    ->scalarNode('VERBOSITY_DEBUG')->defaultValue('DEBUG')->end()
                ->end()
                ->validate()
                    ->always(function ($v) {
                        $map = [];
                        foreach ($v as $verbosity => $level) {
                            $verbosityConstant = 'Symfony\Component\Console\Output\OutputInterface::'.$verbosity;

                            if (!\defined($verbosityConstant)) {
                                throw new InvalidConfigurationException(\sprintf('The configured verbosity "%s" is invalid as it is not defined in Symfony\Component\Console\Output\OutputInterface.', $verbosity));
                            }

                            try {
                                if (Logger::API === 3) {
                                    $level = Logger::toMonologLevel($level)->value;
                                } else {
                                    $level = Logger::toMonologLevel(is_numeric($level) ? (int) $level : $level);
                                }
                            } catch (\Psr\Log\InvalidArgumentException $e) {
                                throw new InvalidConfigurationException(\sprintf('The configured minimum log level "%s" for verbosity "%s" is invalid as it is not defined in Monolog\Logger.', $level, $verbosity));
                            }

                            $map[\constant($verbosityConstant)] = $level;
                        }

                        return $map;
                    })
                ->end();
    }

    public function console_formatter_options(): NodeDefinition
    {
        return $this->node()
            ->variableNode('console_formatter_options')
                ->defaultValue([])
                ->validate()
                    ->ifTrue(static function ($v) { return !\is_array($v); })
                    ->thenInvalid('The console_formatter_options must be an array.')
                ->end();
    }
}
