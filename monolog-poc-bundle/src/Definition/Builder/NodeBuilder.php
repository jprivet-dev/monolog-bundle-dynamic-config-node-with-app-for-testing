<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration\AbstractAddConfiguration;
use Local\Bundle\MonologPocBundle\DependencyInjection\TemplateConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;

class NodeBuilder extends BaseNodeBuilder
{
    public function callable(callable $callable): static
    {
        $callable($this);

        return $this;
    }

    /**
     * Add a configuration from a class.
     *
     * Usage:
     *
     *      $node = new NodeBuilder('name')
     *          ->children()
     *              ->addConfiguration(FooConfiguration::class)
     *          ->end()
     *      ;
     */
    public function addConfiguration(string $class): static
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(\sprintf('The class "%s" does not exist.', $class));
        }

        $configuration = new $class($this->parent);

        if (!$configuration instanceof AbstractAddConfiguration) {
            throw new \RuntimeException(\sprintf('Expected class of type "%s", "%s" given', AbstractAddConfiguration::class, \get_debug_type($configuration)));
        }

        $configuration();

        return $this;
    }

    /**
     * Appends a node definition from a "TemplateConfiguration" method.
     *
     * Usage:
     *
     *     $node = new NodeBuilder('name')
     *         ->children()
     *             ->template('foo') // Use TemplateConfiguration::foo()
     *         ->end()
     *     ;
     */
    public function template(string $name, mixed ...$arguments): static
    {
        $template = new TemplateConfiguration($this->parent);

        if (!method_exists($template, $name)) {
            throw new \RuntimeException(\sprintf('The method "%s()" on class "%s" is not defined.', $name, \get_debug_type($template)));
        }

        $template->$name(...$arguments);

        return $this;
    }
}
