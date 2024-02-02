<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres;

use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationDbalPass;
use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationOrmPass;
use Camelot\DoctrinePostgres\DependencyInjection\Compiler\ConfigTrait;
use MartinGeorgiev\Doctrine\DBAL as Postgres;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class CamelotDoctrinePostgresBundle extends AbstractBundle
{
    use ConfigTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BeforeOptimizationDbalPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass(new BeforeOptimizationOrmPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('features')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('postgis')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('types')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('jsonb[]')->defaultFalse()->end()
                        ->booleanNode('smallint[]')->defaultFalse()->end()
                        ->booleanNode('integer[]')->defaultFalse()->end()
                        ->booleanNode('bigint[]')->defaultFalse()->end()
                        ->booleanNode('text[]')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('functions')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // alternative implementation of ALL() and ANY() where subquery is not required, useful for arrays
                        ->booleanNode('all_of')->defaultFalse()->end()
                        ->booleanNode('any_of')->defaultFalse()->end()

                        // operators for working with array and json(b) data
                        ->booleanNode('contains')->defaultFalse()->end()
                        ->booleanNode('is_contained_by')->defaultFalse()->end()
                        ->booleanNode('overlaps')->defaultFalse()->end()
                        ->booleanNode('greatest')->defaultFalse()->end()
                        ->booleanNode('least')->defaultFalse()->end()

                        // array specific functions
                        ->booleanNode('array_append')->defaultFalse()->end()
                        ->booleanNode('array_cardinality')->defaultFalse()->end()
                        ->booleanNode('array_cat')->defaultFalse()->end()
                        ->booleanNode('array_dimensions')->defaultFalse()->end()
                        ->booleanNode('array_length')->defaultFalse()->end()
                        ->booleanNode('array_number_of_dimensions')->defaultFalse()->end()
                        ->booleanNode('array_prepend')->defaultFalse()->end()
                        ->booleanNode('array_remove')->defaultFalse()->end()
                        ->booleanNode('array_replace')->defaultFalse()->end()
                        ->booleanNode('array_to_json')->defaultFalse()->end()
                        ->booleanNode('array_to_string')->defaultFalse()->end()
                        ->booleanNode('string_to_array')->defaultFalse()->end()
                        ->booleanNode('in_array')->defaultFalse()->end()

                        // json specific functions
                        ->booleanNode('json_array_length')->defaultFalse()->end()
                        ->booleanNode('json_each')->defaultFalse()->end()
                        ->booleanNode('json_each_text')->defaultFalse()->end()
                        ->booleanNode('json_get_field')->defaultFalse()->end()
                        ->booleanNode('json_get_field_as_integer')->defaultFalse()->end()
                        ->booleanNode('json_get_field_as_text')->defaultFalse()->end()
                        ->booleanNode('json_get_object')->defaultFalse()->end()
                        ->booleanNode('json_get_object_as_text')->defaultFalse()->end()
                        ->booleanNode('json_object_keys')->defaultFalse()->end()
                        ->booleanNode('json_strip_nulls')->defaultFalse()->end()
                        ->booleanNode('to_json')->defaultFalse()->end()

                        // jsonb specific functions
                        ->booleanNode('jsonb_array_elements')->defaultFalse()->end()
                        ->booleanNode('jsonb_array_elements_text')->defaultFalse()->end()
                        ->booleanNode('jsonb_array_length')->defaultFalse()->end()
                        ->booleanNode('jsonb_each')->defaultFalse()->end()
                        ->booleanNode('jsonb_each_text')->defaultFalse()->end()
                        ->booleanNode('jsonb_exists')->defaultFalse()->end()
                        ->booleanNode('jsonb_insert')->defaultFalse()->end()
                        ->booleanNode('jsonb_object_keys')->defaultFalse()->end()
                        ->booleanNode('jsonb_set')->defaultFalse()->end()
                        ->booleanNode('jsonb_strip_nulls')->defaultFalse()->end()
                        ->booleanNode('to_jsonb')->defaultFalse()->end()

                        // text search specific
                        ->booleanNode('to_tsquery')->defaultFalse()->end()
                        ->booleanNode('to_tsvector')->defaultFalse()->end()
                        ->booleanNode('tsmatch')->defaultFalse()->end()

                        // other operators
                        ->booleanNode('ilike')->defaultFalse()->end()

                        // Camelot extensions
                        ->booleanNode('date_part')->defaultFalse()->end()
                        ->booleanNode('distance')->defaultFalse()->end()
                        ->booleanNode('make_date')->defaultFalse()->end()
                        ->booleanNode('to_char')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $config = $this->getConfig($builder);

        $builder->prependExtensionConfig('doctrine', [
            'dbal' => [
                'types' => $this->getTypes($config['types']),
                'mapping_types' => $this->getMappingTypes($config['types']),
            ],
        ]);
    }

    private function getTypes(array $types): array
    {
        $addedTypes = [];
        foreach ($types as $type => $enabled) {
            if (!$enabled) {
                continue;
            }

            $addedTypes[$type] = match (true) {
                $type === 'smallint[]' => Postgres\Types\SmallIntArray::class,
                $type === 'integer[]' => Postgres\Types\IntegerArray::class,
                $type === 'bigint[]' => Postgres\Types\BigIntArray::class,
                $type === 'text[]' => Postgres\Types\TextArray::class,
                $type === 'jsonb' => Postgres\Types\Jsonb::class,
                $type === 'jsonb[]' => Postgres\Types\JsonbArray::class,
                default => $type,
            };
        }

        return $addedTypes;
    }

    private function getMappingTypes(array $types): array
    {
        $mappingTypes = [];
        foreach ($types as $type => $enabled) {
            if (!$enabled || !str_contains($type, '[]')) {
                continue;
            }

            $_type = match (true) {
                $type === 'smallint[]' => '_int2',
                $type === 'integer[]' => '_int4',
                $type === 'bigint[]' => '_int8',
                default => '_' . rtrim($type, '[]'),
            };

            $mappingTypes[$type] = $type;
            $mappingTypes[$_type] = $type;
        }

        return $mappingTypes;
    }
}
