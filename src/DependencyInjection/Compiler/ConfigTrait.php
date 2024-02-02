<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

trait ConfigTrait
{
    private function getConfig(ContainerBuilder $container): array
    {
        $extension = $container->getExtension('camelot_doctrine_postgres');

        return (new Processor())->processConfiguration(
            $extension->getConfiguration([], $container),
            $container->getExtensionConfig('camelot_doctrine_postgres'),
        );
    }
}
