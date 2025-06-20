<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeBuilder;
use Local\Bundle\MonologPocBundle\Definition\Builder\NodeDefinitionAwareInterface;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Tests\Fixtures\Builder\VariableNodeDefinition;


class ConfigurationFragments implements NodeDefinitionAwareInterface
{
    public function __construct(protected NodeDefinition|ArrayNodeDefinition|VariableNodeDefinition $node)
    {
    }

    public function base(?HandlerType $type = null): void {

        if ($type !== HandlerType::SERVICE) {
            $this->node
                ->children()
                    ->fragments()->formatter()
                ->end();
        }

        $this->node
            ->children()
                ->fragments()->processPsr3Messages()
                ->fragments()->level()
                ->fragments()->bubble()
                ->fragments()->channels()
                ->fragments()->nested()
            ->end();
    }

    public function processPsr3Messages(): void
    {
        $this->node
            ->children()
                ->arrayNode('process_psr_3_messages')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifTrue(static function ($v) { return !\is_array($v); })
                        ->then(static function ($v) { return ['enabled' => $v]; })
                    ->end()
                    ->children()
                        ->booleanNode('enabled')->defaultNull()->end()
                        ->scalarNode('date_format')->end()
                        ->booleanNode('remove_used_context_fields')->end()
                    ->end()
                ->end()
            ->end();
    }

    public function level(): void
    {
        $this->node
            ->children()
                ->scalarNode('level')
                ->defaultValue('DEBUG')
                ->info('Level name or int value, defaults to DEBUG.')
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

    public function nested(): void
    {
        $this->node
            ->children()
                ->booleanNode('nested')
                    ->defaultFalse()
                    ->info('All handlers can also be marked with `nested: true` to make sure they are never added explicitly to the stack.')
                ->end()
            ->end();
    }

    public function formatter(): void
    {
        $this->node
            ->children()
                ->scalarNode('formatter')->end()
            ->end();
    }

    public function handler(): void
    {
        $this->node
            ->children()
                ->scalarNode('handler')->info('The wrapped handler\'s name.')->end()
            ->end();
    }

    public function filePermission(): void
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

    public function useLocking(): void
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

    public function idHost(): void
    {
        $this->node
            ->children()
                ->scalarNode('id')->info('Optional if host is given.')->end()
            ->end();
    }

    public function verbosityLevels(): void
    {
        $this->node
            ->children()
                ->arrayNode('verbosity_levels') // console
                    ->info('Level => verbosity configuration.')
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

    public function consoleFormatterOptions(): void
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
        $infoOptional = in_array($type, [HandlerType::SWIFT_MAILER, HandlerType::SYMFONY_MAILER])
            ? 'Optional if email_prototype is given.'
            : '';

        $this->node
            ->children()
                ->scalarNode('from_email')->info($infoOptional)->end() // swift_mailer, native_mailer, symfony_mailer and flowdock
                ->arrayNode('to_email') // swift_mailer, native_mailer and symfony_mailer
                    ->prototype('scalar')->end()
                    ->info($infoOptional)
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return [$v]; })
                    ->end()
                ->end()
                ->scalarNode('subject')->info($infoOptional)->end() // swift_mailer, native_mailer and symfony_mailer
                ->closure(static function(NodeBuilder $node) use ($type): void {
                    if(in_array($type, [HandlerType::SWIFT_MAILER, HandlerType::SYMFONY_MAILER])) {
                        $node
                            ->scalarNode('content_type')->defaultNull()->end() // swift_mailer and symfony_mailer
                            ->scalarNode('mailer')->defaultNull()->info('Mailer service id, defaults to mailer.mailer.')->end() // swift_mailer and symfony_mailer
                            ->arrayNode('email_prototype') // swift_mailer and symfony_mailer
                                ->canBeUnset()
                                ->info('Service id of a message, defaults to a default message with the three fields above.')
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
                                ->info("Optional array containing additional headers: ['Foo: Bar', '...'].")
                            ->end();
                    }
                })
                ->fragments()->base()
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
