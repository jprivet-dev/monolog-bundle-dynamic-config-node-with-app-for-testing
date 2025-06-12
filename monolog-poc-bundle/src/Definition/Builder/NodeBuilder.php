<?php

namespace Local\Bundle\MonologPocBundle\Definition\Builder;

use Local\Bundle\MonologPocBundle\DependencyInjection\AddConfigurationInterface;
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

        $addConfiguration = new $class();

        if (!$addConfiguration instanceof AddConfigurationInterface) {
            throw new \RuntimeException(\sprintf('Expected class of type "%s", "%s" given', AddConfigurationInterface::class, \get_debug_type($addConfiguration)));
        }

        (new $class())->add($this->parent);

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
}
