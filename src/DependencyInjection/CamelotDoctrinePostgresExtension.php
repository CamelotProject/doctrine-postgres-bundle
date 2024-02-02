<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use function dirname;

final class CamelotDoctrinePostgresExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__, 2) . '/config'));
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = new ArrayCollection();
        $this->setDbalPostgresTypes($doctrineConfig);
        $container->prependExtensionConfig('doctrine', ['dbal' => $doctrineConfig->toArray()]);
    }

    private function setDbalPostgresTypes(ArrayCollection $doctrineConfig): void
    {
        $mappingTypes = [
            'jsonb' => 'jsonb',
            'jsonb[]' => 'jsonb[]',
            '_jsonb' => 'jsonb[]',
            'smallint[]' => 'smallint[]',
            '_int2' => 'smallint[]',
            'integer[]' => 'integer[]',
            '_int4' => 'integer[]',
            'bigint[]' => 'bigint[]',
            '_int8' => 'bigint[]',
            'text[]' => 'text[]',
            '_text' => 'text[]',
        ];
        $config = [];
        foreach ($mappingTypes as $name => $class) {
            $config[$name] = $class;
        }
        $doctrineConfig->set('mapping_types', $config);
    }
}
