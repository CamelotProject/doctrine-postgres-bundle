<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres;

use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationDbalPass;
use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationOrmPass;
use Camelot\DoctrinePostgres\DependencyInjection\Compiler\ConfigTrait;
use Jsor\Doctrine\PostGIS;
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
                        ->booleanNode('bool[]')->defaultFalse()->end()
                        ->booleanNode('jsonb[]')->defaultFalse()->end()
                        ->booleanNode('smallint[]')->defaultFalse()->end()
                        ->booleanNode('integer[]')->defaultFalse()->end()
                        ->booleanNode('bigint[]')->defaultFalse()->end()
                        ->booleanNode('text[]')->defaultFalse()->end()
                        // PostGIS
                        ->booleanNode('geography')->defaultFalse()->end()
                        ->booleanNode('geometry')->defaultFalse()->end()
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

                        // PostGIS
                        ->booleanNode('geography')->defaultFalse()->end()
                        ->booleanNode('geometry')->defaultFalse()->end()
                        ->booleanNode('st_3dclosestpoint')->defaultFalse()->end()
                        ->booleanNode('st_3ddfullywithin')->defaultFalse()->end()
                        ->booleanNode('st_3ddistance')->defaultFalse()->end()
                        ->booleanNode('st_3ddwithin')->defaultFalse()->end()
                        ->booleanNode('st_3dintersects')->defaultFalse()->end()
                        ->booleanNode('st_3dlength')->defaultFalse()->end()
                        ->booleanNode('st_3dlongestline')->defaultFalse()->end()
                        ->booleanNode('st_3dmakebox')->defaultFalse()->end()
                        ->booleanNode('st_3dmaxdistance')->defaultFalse()->end()
                        ->booleanNode('st_3dshortestline')->defaultFalse()->end()
                        ->booleanNode('st_addpoint')->defaultFalse()->end()
                        ->booleanNode('st_area')->defaultFalse()->end()
                        ->booleanNode('st_asbinary')->defaultFalse()->end()
                        ->booleanNode('st_asewkb')->defaultFalse()->end()
                        ->booleanNode('st_asewkt')->defaultFalse()->end()
                        ->booleanNode('st_asgeojson')->defaultFalse()->end()
                        ->booleanNode('st_asgml')->defaultFalse()->end()
                        ->booleanNode('st_ashexewkb')->defaultFalse()->end()
                        ->booleanNode('st_aslatlontext')->defaultFalse()->end()
                        ->booleanNode('st_assvg')->defaultFalse()->end()
                        ->booleanNode('st_astext')->defaultFalse()->end()
                        ->booleanNode('st_azimuth')->defaultFalse()->end()
                        ->booleanNode('st_boundary')->defaultFalse()->end()
                        ->booleanNode('st_box2dfromgeohash')->defaultFalse()->end()
                        ->booleanNode('st_buffer')->defaultFalse()->end()
                        ->booleanNode('st_centroid')->defaultFalse()->end()
                        ->booleanNode('st_closestpoint')->defaultFalse()->end()
                        ->booleanNode('st_collect')->defaultFalse()->end()
                        ->booleanNode('st_contains')->defaultFalse()->end()
                        ->booleanNode('st_containsproperly')->defaultFalse()->end()
                        ->booleanNode('st_coorddim')->defaultFalse()->end()
                        ->booleanNode('st_coveredby')->defaultFalse()->end()
                        ->booleanNode('st_covers')->defaultFalse()->end()
                        ->booleanNode('st_crosses')->defaultFalse()->end()
                        ->booleanNode('st_dfullywithin')->defaultFalse()->end()
                        ->booleanNode('st_difference')->defaultFalse()->end()
                        ->booleanNode('st_dimension')->defaultFalse()->end()
                        ->booleanNode('st_disjoint')->defaultFalse()->end()
                        ->booleanNode('st_distance')->defaultFalse()->end()
                        ->booleanNode('st_distancesphere')->defaultFalse()->end()
                        ->booleanNode('st_distancespheroid')->defaultFalse()->end()
                        ->booleanNode('st_dwithin')->defaultFalse()->end()
                        ->booleanNode('st_endpoint')->defaultFalse()->end()
                        ->booleanNode('st_envelope')->defaultFalse()->end()
                        ->booleanNode('st_equals')->defaultFalse()->end()
                        ->booleanNode('st_extent')->defaultFalse()->end()
                        ->booleanNode('st_exteriorring')->defaultFalse()->end()
                        ->booleanNode('st_flipcoordinates')->defaultFalse()->end()
                        ->booleanNode('st_geogfromtext')->defaultFalse()->end()
                        ->booleanNode('st_geogfromwkb')->defaultFalse()->end()
                        ->booleanNode('st_geographyfromtext')->defaultFalse()->end()
                        ->booleanNode('st_geohash')->defaultFalse()->end()
                        ->booleanNode('st_geomcollfromtext')->defaultFalse()->end()
                        ->booleanNode('st_geometryfromtext')->defaultFalse()->end()
                        ->booleanNode('st_geometryn')->defaultFalse()->end()
                        ->booleanNode('st_geometrytype')->defaultFalse()->end()
                        ->booleanNode('st_geomfromewkb')->defaultFalse()->end()
                        ->booleanNode('st_geomfromewkt')->defaultFalse()->end()
                        ->booleanNode('st_geomfromgeohash')->defaultFalse()->end()
                        ->booleanNode('st_geomfromgeojson')->defaultFalse()->end()
                        ->booleanNode('st_geomfromgml')->defaultFalse()->end()
                        ->booleanNode('st_geomfromkml')->defaultFalse()->end()
                        ->booleanNode('st_geomfromtext')->defaultFalse()->end()
                        ->booleanNode('st_geomfromwkb')->defaultFalse()->end()
                        ->booleanNode('st_hasarc')->defaultFalse()->end()
                        ->booleanNode('st_hausdorffdistance')->defaultFalse()->end()
                        ->booleanNode('st_interiorringn')->defaultFalse()->end()
                        ->booleanNode('st_intersection')->defaultFalse()->end()
                        ->booleanNode('st_intersects')->defaultFalse()->end()
                        ->booleanNode('st_isclosed')->defaultFalse()->end()
                        ->booleanNode('st_iscollection')->defaultFalse()->end()
                        ->booleanNode('st_isempty')->defaultFalse()->end()
                        ->booleanNode('st_isring')->defaultFalse()->end()
                        ->booleanNode('st_issimple')->defaultFalse()->end()
                        ->booleanNode('st_isvaliddetail')->defaultFalse()->end()
                        ->booleanNode('st_isvalid')->defaultFalse()->end()
                        ->booleanNode('st_isvalidreason')->defaultFalse()->end()
                        ->booleanNode('st_length')->defaultFalse()->end()
                        ->booleanNode('st_lengthspheroid')->defaultFalse()->end()
                        ->booleanNode('st_linecrossingdirection')->defaultFalse()->end()
                        ->booleanNode('st_linefrommultipoint')->defaultFalse()->end()
                        ->booleanNode('st_linefromtext')->defaultFalse()->end()
                        ->booleanNode('st_linefromwkb')->defaultFalse()->end()
                        ->booleanNode('st_linestringfromwkb')->defaultFalse()->end()
                        ->booleanNode('st_longestline')->defaultFalse()->end()
                        ->booleanNode('st_makebox2d')->defaultFalse()->end()
                        ->booleanNode('st_makeenvelope')->defaultFalse()->end()
                        ->booleanNode('st_makeline')->defaultFalse()->end()
                        ->booleanNode('st_makepointm')->defaultFalse()->end()
                        ->booleanNode('st_makepoint')->defaultFalse()->end()
                        ->booleanNode('st_makepolygon')->defaultFalse()->end()
                        ->booleanNode('st_maxdistance')->defaultFalse()->end()
                        ->booleanNode('st_minimumboundingcircle')->defaultFalse()->end()
                        ->booleanNode('st_mlinefromtext')->defaultFalse()->end()
                        ->booleanNode('st_m')->defaultFalse()->end()
                        ->booleanNode('st_mpointfromtext')->defaultFalse()->end()
                        ->booleanNode('st_mpolyfromtext')->defaultFalse()->end()
                        ->booleanNode('st_multi')->defaultFalse()->end()
                        ->booleanNode('st_ndims')->defaultFalse()->end()
                        ->booleanNode('st_npoints')->defaultFalse()->end()
                        ->booleanNode('st_nrings')->defaultFalse()->end()
                        ->booleanNode('st_numgeometries')->defaultFalse()->end()
                        ->booleanNode('st_numinteriorring')->defaultFalse()->end()
                        ->booleanNode('st_numinteriorrings')->defaultFalse()->end()
                        ->booleanNode('st_numpatches')->defaultFalse()->end()
                        ->booleanNode('st_numpoints')->defaultFalse()->end()
                        ->booleanNode('st_orderingequals')->defaultFalse()->end()
                        ->booleanNode('st_overlaps')->defaultFalse()->end()
                        ->booleanNode('st_patchn')->defaultFalse()->end()
                        ->booleanNode('st_perimeter')->defaultFalse()->end()
                        ->booleanNode('st_pointfromgeohash')->defaultFalse()->end()
                        ->booleanNode('st_pointfromtext')->defaultFalse()->end()
                        ->booleanNode('st_pointfromwkb')->defaultFalse()->end()
                        ->booleanNode('st_pointn')->defaultFalse()->end()
                        ->booleanNode('st_pointonsurface')->defaultFalse()->end()
                        ->booleanNode('st_point')->defaultFalse()->end()
                        ->booleanNode('st_polygonfromtext')->defaultFalse()->end()
                        ->booleanNode('st_polygon')->defaultFalse()->end()
                        ->booleanNode('st_project')->defaultFalse()->end()
                        ->booleanNode('st_relate')->defaultFalse()->end()
                        ->booleanNode('st_scale')->defaultFalse()->end()
                        ->booleanNode('st_setsrid')->defaultFalse()->end()
                        ->booleanNode('st_shiftlongitude')->defaultFalse()->end()
                        ->booleanNode('st_shortestline')->defaultFalse()->end()
                        ->booleanNode('st_snaptogrid')->defaultFalse()->end()
                        ->booleanNode('st_split')->defaultFalse()->end()
                        ->booleanNode('st_srid')->defaultFalse()->end()
                        ->booleanNode('st_startpoint')->defaultFalse()->end()
                        ->booleanNode('st_summary')->defaultFalse()->end()
                        ->booleanNode('st_symdifference')->defaultFalse()->end()
                        ->booleanNode('st_touches')->defaultFalse()->end()
                        ->booleanNode('st_transform')->defaultFalse()->end()
                        ->booleanNode('st_translate')->defaultFalse()->end()
                        ->booleanNode('st_transscale')->defaultFalse()->end()
                        ->booleanNode('st_union')->defaultFalse()->end()
                        ->booleanNode('st_within')->defaultFalse()->end()
                        ->booleanNode('st_xmax')->defaultFalse()->end()
                        ->booleanNode('st_xmin')->defaultFalse()->end()
                        ->booleanNode('st_x')->defaultFalse()->end()
                        ->booleanNode('st_ymax')->defaultFalse()->end()
                        ->booleanNode('st_ymin')->defaultFalse()->end()
                        ->booleanNode('st_y')->defaultFalse()->end()
                        ->booleanNode('st_zmax')->defaultFalse()->end()
                        ->booleanNode('st_zmflag')->defaultFalse()->end()
                        ->booleanNode('st_zmin')->defaultFalse()->end()
                        ->booleanNode('st_z')->defaultFalse()->end()

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

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();

        if ($config['features']['postgis']) {
            if (class_exists(PostGIS\Schema\SchemaManagerFactory::class)) {
                $services->set(PostGIS\Schema\SchemaManagerFactory::class);
            }

            if (class_exists(PostGIS\Event\ORMSchemaEventListener::class)) {
                $services->set(PostGIS\Event\ORMSchemaEventListener::class)
                    ->tag('doctrine.event_listener', ['event' => 'postGenerateSchemaTable', 'connection' => 'default'])
                ;
            }

            if (class_exists(PostGIS\Driver\Middleware::class)) {
                $services->set(PostGIS\Driver\Middleware::class)
                    ->tag('doctrine.middleware')
                ;
            }
        }
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
                $type === 'bool[]' => Postgres\Types\BooleanArray::class,
                $type === 'smallint[]' => Postgres\Types\SmallIntArray::class,
                $type === 'integer[]' => Postgres\Types\IntegerArray::class,
                $type === 'bigint[]' => Postgres\Types\BigIntArray::class,
                $type === 'text[]' => Postgres\Types\TextArray::class,
                $type === 'jsonb' => Postgres\Types\Jsonb::class,
                $type === 'jsonb[]' => Postgres\Types\JsonbArray::class,

                $type === 'geography' => PostGIS\Types\GeographyType::class,
                $type === 'geometry' => PostGIS\Types\GeometryType::class,

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
