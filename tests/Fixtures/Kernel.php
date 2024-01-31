<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures;

use Camelot\DoctrinePostgres\CamelotDoctrinePostgresBundle;
use Camelot\DoctrinePostgres\Tests\DataFixtures\TestFixtures;
use Camelot\DoctrinePostgres\Tests\Fixtures\Repository\JsonEntityRepository;
use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new DoctrineBundle();
        yield new DoctrineFixturesBundle();
        yield new DAMADoctrineTestBundle();
        yield new CamelotDoctrinePostgresBundle();
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $container->extension('doctrine', [
            'dbal' => [
                'url' => 'postgres://doctrine_postgres_bundle:doctrine_postgres_bundle@localhost/doctrine_postgres_bundle',
                'use_savepoints' => true,
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'enable_lazy_ghost_objects' => true,
                'report_fields_where_declared' => true,
                'validate_xml_mapping' => true,
                'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                'auto_mapping' => true,
                'mappings' => [
                    'TestAppBundle' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => '%kernel.project_dir%/tests/Fixtures/Entity',
                        'prefix' => 'Camelot\DoctrinePostgres\Tests\Fixtures\Entity',
                        'alias' => 'TestAppBundle',
                    ],
                ],
            ],
        ]);

        $container->extension('framework', [
            'test' => true,
            'http_method_override' => false,
            'session' => [
                'storage_factory_id' => 'session.storage.factory.mock_file',
            ],
            'php_errors' => [
                'log' => true,
            ],
        ]);

        $services = $container->services();
        $services->defaults()
            ->autoconfigure(true)
            ->autowire(true)
            ->public()
        ;
        $services->set(TestFixtures::class);
        $services->set(JsonEntityRepository::class);
    }
}
