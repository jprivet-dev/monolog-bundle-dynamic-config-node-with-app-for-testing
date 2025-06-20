<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Local\Bundle\MonologPocBundle\DependencyInjection\HandlerConfiguration\HandlerConfigurationInterface;
use Local\Bundle\MonologPocBundle\DependencyInjection\ConfigurationFragments;
use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;

class NodeBuilder extends BaseNodeBuilder
{
    private ?ConfigurationFragments $configurationFragments = null;

    /**
     * Appends a node definition from a "ConfigurationFragments" method.
     *
     * Usage:
     *
     *     $node = new NodeBuilder('name')
     *         ->children()
     *             ->fragments()->foo() // Use ConfigurationFragments::foo()
     *         ->end()
     *     ;
     */
//    public function fragments(): ConfigurationFragments
//    {
//        if($this->configurationFragments === null) {
//            $this->configurationFragments = new ConfigurationFragments($this->parent);
//        }
//
//        return $this->configurationFragments;
//    }

    public function closure(\Closure $closure): static
    {
        $closure($this);

        return $this;
    }

    /**
     * Add a handler configuration from a class.
     *
     * Usage:
     *
     *      $node = new NodeBuilder('name')
     *          ->children()
     *              ->addHandlerConfiguration(FooHandlerConfiguration::class)
     *          ->end()
     *      ;
     */
    public function addHandlerConfiguration(string $class): static
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(\sprintf('The class "%s" does not exist.', $class));
        }

        $configuration = new $class();

        if (!$configuration instanceof HandlerConfigurationInterface) {
            throw new \RuntimeException(\sprintf('Expected class of type "%s", "%s" given', HandlerConfigurationInterface::class, \get_debug_type($configuration)));
        }

        $configuration($this->parent);

        return $this;
    }
}
