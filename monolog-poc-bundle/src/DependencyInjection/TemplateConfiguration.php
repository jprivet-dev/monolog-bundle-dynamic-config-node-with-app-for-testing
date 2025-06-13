<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\NodeDefinitionAwareInterface;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class TemplateConfiguration implements NodeDefinitionAwareInterface
{
    public function __construct(protected NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node)
    {
    }

    public function base(): void {
        $this->node
            ->children()
                ->template('level')
                ->template('bubble')
                ->template('channels')
            ->end();
    }

    public function level(): void
    {
        $this->node
            ->children()
                ->scalarNode('level')
                ->defaultValue('DEBUG')
            ->end();
    }

    public function bubble(): void
    {
        $this->node
            ->children()
                ->booleanNode('bubble')
                ->defaultTrue()
            ->end();
    }

    public function channels(): void
    {
        $this->node
            ->children()
                ->arrayNode('channels')
                    ->fixXmlConfig('channel', 'elements')
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return ['elements' => [$v]]; })
                    ->end()
                    ->beforeNormalization()
                        ->ifTrue(function ($v) { return \is_array($v) && is_numeric(key($v)); })
                        ->then(function ($v) { return ['elements' => $v]; })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) { return empty($v); })
                        ->thenUnset()
                    ->end()
                    ->validate()
                        ->always(function ($v) {
                            $isExclusive = null;
                            if (isset($v['type'])) {
                                $isExclusive = 'exclusive' === $v['type'];
                            }

                            $elements = [];
                            foreach ($v['elements'] as $element) {
                                if (0 === strpos($element, '!')) {
                                    if (false === $isExclusive) {
                                        throw new InvalidConfigurationException('Cannot combine exclusive/inclusive definitions in channels list.');
                                    }
                                    $elements[] = substr($element, 1);
                                    $isExclusive = true;
                                } else {
                                    if (true === $isExclusive) {
                                        throw new InvalidConfigurationException('Cannot combine exclusive/inclusive definitions in channels list');
                                    }
                                    $elements[] = $element;
                                    $isExclusive = false;
                                }
                            }

                            if (!\count($elements)) {
                                return null;
                            }

                            // de-duplicating $elements here in case the handlers are redefined, see https://github.com/symfony/monolog-bundle/issues/433
                            return ['type' => $isExclusive ? 'exclusive' : 'inclusive', 'elements' => array_unique($elements)];
                        })
                    ->end()
                    ->children()
                        ->scalarNode('type')
                            ->validate()
                                ->ifNotInArray(['inclusive', 'exclusive'])
                                ->thenInvalid('The type of channels has to be inclusive or exclusive')
                            ->end()
                        ->end()
                        ->arrayNode('elements')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function file_permission(): void
    {
        $this->node
            ->children()
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
                ->end()
            ->end();
    }

    public function use_locking(): void
    {
        $this->node
            ->children()
                ->booleanNode('use_locking')->defaultFalse()
            ->end();
    }

    public function path(): void
    {
        $this->node
            ->children()
                ->scalarNode('path')->defaultValue('%kernel.logs_dir%/%kernel.environment%.log')
            ->end();
    }

    public function verbosity_levels(): void
    {
        $this->node
            ->children()
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
                    ->end()
                ->end()
            ->end();
    }

    public function console_formatter_options(): void
    {
        $this->node
            ->children()
                ->variableNode('console_formatter_options')
                    ->defaultValue([])
                    ->validate()
                        ->ifTrue(static function ($v) { return !\is_array($v); })
                        ->thenInvalid('The console_formatter_options must be an array.')
                    ->end()
                ->end()
            ->end();
    }

    public function mailer(HandlerType $type): void
    {
        $this->node
            ->children()
                ->scalarNode('from_email')->end() // swift_mailer, native_mailer, symfony_mailer and flowdock
                ->arrayNode('to_email') // swift_mailer, native_mailer and symfony_mailer
                    ->prototype('scalar')->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return [$v]; })
                    ->end()
                ->end()
                ->scalarNode('subject')->end() // swift_mailer, native_mailer and symfony_mailer
                ->callable(static function(NodeBuilder $node) use ($type): void {
                    if(in_array($type, [HandlerType::SWIFT_MAILER, HandlerType::SYMFONY_MAILER])) {
                        $node
                            ->scalarNode('content_type')->defaultNull()->end() // swift_mailer and symfony_mailer
                            ->scalarNode('mailer')->defaultNull()->end() // swift_mailer and symfony_mailer
                            ->arrayNode('email_prototype') // swift_mailer and symfony_mailer
                                ->canBeUnset()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) { return ['id' => $v]; })
                                ->end()
                                ->children()
                                    ->scalarNode('id')->isRequired()->end()
                                    ->scalarNode('method')->defaultNull()->end()
                                ->end()
                            ->end();
                    }

                    if ($type === HandlerType::SWIFT_MAILER) {
                        $node
                            ->booleanNode('lazy')->defaultValue(true)->end(); // swift_mailer
                    }

                    if ($type === HandlerType::NATIVE_MAILER) {
                        $node
                            ->arrayNode('headers') // native_mailer
                                ->canBeUnset()
                                ->scalarPrototype()->end()
                            ->end();
                    }
                })
                ->template('base')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v): bool => HandlerType::SWIFT_MAILER === $type && empty($v['email_prototype']) && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])))
                ->thenInvalid('The sender, recipient and subject or an email prototype have to be specified to use a SwiftMailerHandler')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v): bool => HandlerType::NATIVE_MAILER === $type && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])))
                ->thenInvalid('The sender, recipient and subject have to be specified to use a NativeMailerHandler')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v): bool => HandlerType::SYMFONY_MAILER === $type && empty($v['email_prototype']) && (empty($v['from_email']) || empty($v['to_email']) || empty($v['subject'])))
                ->thenInvalid('The sender, recipient and subject or an email prototype have to be specified to use the Symfony MailerHandler')
            ->end()
        ;
    }
}
