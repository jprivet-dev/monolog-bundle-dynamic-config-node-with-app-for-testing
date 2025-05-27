<?php

namespace Local\Bundle\PocBundle\Tests\DependencyInjection;

use Local\Bundle\PocBundle\DependencyInjection\PocExtension;
use Local\Bundle\PocBundle\PocBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PocExtensionTest extends TestCase
{
    public function testLoadWithDefault()
    {
        $container = $this->getContainer();
        $this->assertTrue($container->hasExtension('poc'));
        //$this->assertTrue($container->hasDefinition('poc')); // Why is false?
    }

    protected function getRawContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);

        $poc = new PocExtension();
        $container->registerExtension($poc);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);

        $bundle = new PocBundle();
        $bundle->build($container);

        return $container;
    }

    protected function getContainer(): ContainerBuilder
    {
        $container = $this->getRawContainer();
        $container->compile();

        return $container;
    }
}
