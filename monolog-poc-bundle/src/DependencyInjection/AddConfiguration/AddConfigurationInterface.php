<?php

namespace Local\Bundle\MonologPocBundle\DependencyInjection\AddConfiguration;

use Local\Bundle\MonologPocBundle\Definition\Builder\NodeDefinitionAwareInterface;

interface AddConfigurationInterface extends NodeDefinitionAwareInterface
{
    public function __invoke(): void;
}
