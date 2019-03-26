<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 * @see https://github.com/Sylius/Sylius/pull/10259
 *
 * Ported from Sylius/Sylius#10259, made by @Zales0123 to ignore Psalm annotations from doctrine/collections
 */
final class IgnoreAnnotationsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $annotationsReader = $container->getDefinition('annotations.reader');
        $annotationsReader->addMethodCall('addGlobalIgnoredName', ['template']);
        $annotationsReader->addMethodCall('addGlobalIgnoredName', ['psalm']);
    }
}
