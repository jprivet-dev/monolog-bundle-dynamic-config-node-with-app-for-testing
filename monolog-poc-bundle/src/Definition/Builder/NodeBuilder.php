<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;
use Local\Bundle\MonologPocBundle\DependencyInjection\TemplateConfiguration;
use Local\Bundle\MonologPocBundle\Enum\HandlerType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder as BaseNodeBuilder;

class NodeBuilder extends BaseNodeBuilder
{
    public function closure(\Closure $closure): static
    {
        $closure($this);

        return $this;
    }

    /**
     * Add a configuration from a class.
     */
    public function add(string $class): static
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(\sprintf('The class "%s" does not exist.', $class));
        }

        $configuration = new $class($this->parent);

        if (!$configuration instanceof AddConfigurationInterface) {
            throw new \RuntimeException(\sprintf('Expected class of type "%s", "%s" given', AddConfigurationInterface::class, \get_debug_type($configuration)));
        }

        $configuration();

        return $this;
    }

    /**
     * Add a handler configuration from a type.
     */
    public function addHandler(HandlerType $type): static
    {
        if (!$type->getHandlerConfigurationClass()) {
            throw new \RuntimeException(\sprintf('The handler configuration "%s" is not registered.', $type->value));
        }

        $class = $type->getHandlerConfigurationClass();
        $this->add($class);

        return $this;
    }

    /**
     * Usage:
     *
     *     $node = new NodeBuilder('name')
     *         ->children()
     *             ->template('myMethod') // Execute TemplateConfiguration::myMethod()
     *         ->end()
     *     ;
     */
    public function template(string $name, mixed ...$arguments): static
    {
        $template = new TemplateConfiguration($this->parent);

        if(!method_exists($template, $name)) {
            throw new \RuntimeException(\sprintf('The method "%s()" on class "%s" is not defined.', $name, \get_debug_type($template)));
        }

        $template->$name(...$arguments);

        return $this;
    }
}
