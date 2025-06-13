<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

class FingerCrossedHandlerConfiguration extends AbstractAddConfiguration
{
    public function __invoke(): void
    {
        $this->node
            ->children()
                ->scalarNode('handler')->end() // fingers_crossed and buffer
                ->scalarNode('action_level')->defaultValue('WARNING')->end() // fingers_crossed
                ->scalarNode('activation_strategy')->defaultNull()->end() // fingers_crossed
                ->booleanNode('stop_buffering')->defaultTrue()->end()// fingers_crossed
                ->scalarNode('passthru_level')->defaultNull()->end() // fingers_crossed
                ->arrayNode('excluded_404s') // fingers_crossed
                    ->canBeUnset()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('excluded_http_codes') // fingers_crossed
                    ->canBeUnset()
                    ->beforeNormalization()
                        ->always(function ($values) {
                            return array_map(function ($value) {
                                /*
                                 * Allows YAML:
                                 *   excluded_http_codes: [403, 404, { 400: ['^/foo', '^/bar'] }]
                                 *
                                 * and XML:
                                 *   <monolog:excluded-http-code code="403">
                                 *     <monolog:url>^/foo</monolog:url>
                                 *     <monolog:url>^/bar</monolog:url>
                                 *   </monolog:excluded-http-code>
                                 *   <monolog:excluded-http-code code="404" />
                                 */

                                if (\is_array($value)) {
                                    return isset($value['code']) ? $value : ['code' => key($value), 'urls' => current($value)];
                                }

                                return ['code' => $value, 'urls' => []];
                            }, $values);
                        })
                    ->end()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('code')->end()
                            ->arrayNode('urls')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('buffer_size')->defaultValue(0)->end() // fingers_crossed and buffer
                ->template('bubble')
            ->end()
            // TODO: validate() from original MonologBundle/src/DependencyInjection/Configuration.php. Adjust ifTrue() conditions.
            ->validate()
                ->ifTrue(function ($v) { return ('fingers_crossed' === $v['type'] || 'buffer' === $v['type'] || 'filter' === $v['type'] || 'sampling' === $v['type']) && empty($v['handler']); })
                ->thenInvalid('The handler has to be specified to use a FingersCrossedHandler or BufferHandler or FilterHandler or SamplingHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'fingers_crossed' === $v['type'] && !empty($v['excluded_404s']) && !empty($v['activation_strategy']); })
                ->thenInvalid('You can not use excluded_404s together with a custom activation_strategy in a FingersCrossedHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'fingers_crossed' === $v['type'] && !empty($v['excluded_http_codes']) && !empty($v['activation_strategy']); })
                ->thenInvalid('You can not use excluded_http_codes together with a custom activation_strategy in a FingersCrossedHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'fingers_crossed' === $v['type'] && !empty($v['excluded_http_codes']) && !empty($v['excluded_404s']); })
                ->thenInvalid('You can not use excluded_http_codes together with excluded_404s in a FingersCrossedHandler')
            ->end()
            ->validate()
                ->ifTrue(function ($v) { return 'fingers_crossed' !== $v['type'] && (!empty($v['excluded_http_codes']) || !empty($v['excluded_404s'])); })
                ->thenInvalid('You can only use excluded_http_codes/excluded_404s with a FingersCrossedHandler definition')
            ->end()
       ;
    }
}
