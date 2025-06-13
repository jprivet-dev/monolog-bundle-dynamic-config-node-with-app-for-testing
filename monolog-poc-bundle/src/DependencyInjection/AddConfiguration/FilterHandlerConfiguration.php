<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class FilterHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->template('handler')
                ->arrayNode('accepted_levels') // filter
                    ->canBeUnset()
                    ->prototype('scalar')->end()
                    ->info('List of levels to accept')
                ->end()
                ->scalarNode('min_level')->defaultValue('DEBUG')->info('Minimum level to accept (only used if accepted_levels not specified).')->end() // filter
                ->scalarNode('max_level')->defaultValue('EMERGENCY')->info('Maximum level to accept (only used if accepted_levels not specified).')->end() // filter
                ->template('bubble')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => empty($v['handler']))
                ->thenInvalid('The handler has to be specified to use a FingersCrossedHandler or BufferHandler or FilterHandler or SamplingHandler.')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => 'DEBUG' !== $v['min_level'] && !empty($v['accepted_levels']))
                ->thenInvalid('You can not use min_level together with accepted_levels in a FilterHandler.')
            ->end()
            ->validate()
                ->ifTrue(static fn ($v) => 'EMERGENCY' !== $v['max_level'] && !empty($v['accepted_levels']))
                ->thenInvalid('You can not use max_level together with accepted_levels in a FilterHandler.')
            ->end()
       ;
    }
}
