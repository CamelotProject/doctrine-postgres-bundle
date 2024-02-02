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

        $container->extension('camelot_doctrine_postgres', [
            'features' => [
                'postgis' => false,
            ],
            'types' => [
                'jsonb' => true,
                'jsonb[]' => true,
                'smallint[]' => true,
                'integer[]' => true,
                'bigint[]' => true,
                'text[]' => true,
            ],
            'functions' => [
                'all_of' => true,
                'any_of' => true,
                'contains' => true,
                'is_contained_by' => true,
                'overlaps' => true,
                'greatest' => true,
                'least' => true,
                'array_append' => true,
                'array_cardinality' => true,
                'array_cat' => true,
                'array_dimensions' => true,
                'array_length' => true,
                'array_number_of_dimensions' => true,
                'array_prepend' => true,
                'array_remove' => true,
                'array_replace' => true,
                'array_to_json' => true,
                'array_to_string' => true,
                'string_to_array' => true,
                'in_array' => true,
                'json_array_length' => true,
                'json_each' => true,
                'json_each_text' => true,
                'json_get_field' => true,
                'json_get_field_as_integer' => true,
                'json_get_field_as_text' => true,
                'json_get_object' => true,
                'json_get_object_as_text' => true,
                'json_object_keys' => true,
                'json_strip_nulls' => true,
                'to_json' => true,
                'jsonb_array_elements' => true,
                'jsonb_array_elements_text' => true,
                'jsonb_array_length' => true,
                'jsonb_each' => true,
                'jsonb_each_text' => true,
                'jsonb_exists' => true,
                'jsonb_insert' => true,
                'jsonb_object_keys' => true,
                'jsonb_set' => true,
                'jsonb_strip_nulls' => true,
                'to_jsonb' => true,
                'to_tsquery' => true,
                'to_tsvector' => true,
                'tsmatch' => true,
                'ilike' => true,
                'date_part' => true,
                'make_date' => true,
                'to_char' => true,
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
