<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures;

use Camelot\DoctrinePostgres\CamelotDoctrinePostgresBundle;
use Camelot\DoctrinePostgres\Tests\DataFixtures;
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
        return \dirname(__DIR__, 2);
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
                'postgis' => true,
            ],
            'types' => [
                'bool[]' => true,
                'jsonb[]' => true,
                'smallint[]' => true,
                'integer[]' => true,
                'bigint[]' => true,
                'text[]' => true,
                'geography' => true,
                'geometry' => true,
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
                // PostGIS
                'geography' => true,
                'geometry' => true,
                'st_3dclosestpoint' => true,
                'st_3ddfullywithin' => true,
                'st_3ddistance' => true,
                'st_3ddwithin' => true,
                'st_3dintersects' => true,
                'st_3dlength' => true,
                'st_3dlongestline' => true,
                'st_3dmakebox' => true,
                'st_3dmaxdistance' => true,
                'st_3dshortestline' => true,
                'st_addpoint' => true,
                'st_area' => true,
                'st_asbinary' => true,
                'st_asewkb' => true,
                'st_asewkt' => true,
                'st_asgeojson' => true,
                'st_asgml' => true,
                'st_ashexewkb' => true,
                'st_aslatlontext' => true,
                'st_assvg' => true,
                'st_astext' => true,
                'st_azimuth' => true,
                'st_boundary' => true,
                'st_box2dfromgeohash' => true,
                'st_buffer' => true,
                'st_centroid' => true,
                'st_closestpoint' => true,
                'st_collect' => true,
                'st_contains' => true,
                'st_containsproperly' => true,
                'st_coorddim' => true,
                'st_coveredby' => true,
                'st_covers' => true,
                'st_crosses' => true,
                'st_dfullywithin' => true,
                'st_difference' => true,
                'st_dimension' => true,
                'st_disjoint' => true,
                'st_distance' => true,
                'st_distancesphere' => true,
                'st_distancespheroid' => true,
                'st_dwithin' => true,
                'st_endpoint' => true,
                'st_envelope' => true,
                'st_equals' => true,
                'st_extent' => true,
                'st_exteriorring' => true,
                'st_flipcoordinates' => true,
                'st_geogfromtext' => true,
                'st_geogfromwkb' => true,
                'st_geographyfromtext' => true,
                'st_geohash' => true,
                'st_geomcollfromtext' => true,
                'st_geometryfromtext' => true,
                'st_geometryn' => true,
                'st_geometrytype' => true,
                'st_geomfromewkb' => true,
                'st_geomfromewkt' => true,
                'st_geomfromgeohash' => true,
                'st_geomfromgeojson' => true,
                'st_geomfromgml' => true,
                'st_geomfromkml' => true,
                'st_geomfromtext' => true,
                'st_geomfromwkb' => true,
                'st_hasarc' => true,
                'st_hausdorffdistance' => true,
                'st_interiorringn' => true,
                'st_intersection' => true,
                'st_intersects' => true,
                'st_isclosed' => true,
                'st_iscollection' => true,
                'st_isempty' => true,
                'st_isring' => true,
                'st_issimple' => true,
                'st_isvaliddetail' => true,
                'st_isvalid' => true,
                'st_isvalidreason' => true,
                'st_length' => true,
                'st_lengthspheroid' => true,
                'st_linecrossingdirection' => true,
                'st_linefrommultipoint' => true,
                'st_linefromtext' => true,
                'st_linefromwkb' => true,
                'st_linestringfromwkb' => true,
                'st_longestline' => true,
                'st_makebox2d' => true,
                'st_makeenvelope' => true,
                'st_makeline' => true,
                'st_makepointm' => true,
                'st_makepoint' => true,
                'st_makepolygon' => true,
                'st_maxdistance' => true,
                'st_minimumboundingcircle' => true,
                'st_mlinefromtext' => true,
                'st_m' => true,
                'st_mpointfromtext' => true,
                'st_mpolyfromtext' => true,
                'st_multi' => true,
                'st_ndims' => true,
                'st_npoints' => true,
                'st_nrings' => true,
                'st_numgeometries' => true,
                'st_numinteriorring' => true,
                'st_numinteriorrings' => true,
                'st_numpatches' => true,
                'st_numpoints' => true,
                'st_orderingequals' => true,
                'st_overlaps' => true,
                'st_patchn' => true,
                'st_perimeter' => true,
                'st_pointfromgeohash' => true,
                'st_pointfromtext' => true,
                'st_pointfromwkb' => true,
                'st_pointn' => true,
                'st_pointonsurface' => true,
                'st_point' => true,
                'st_polygonfromtext' => true,
                'st_polygon' => true,
                'st_project' => true,
                'st_relate' => true,
                'st_scale' => true,
                'st_setsrid' => true,
                'st_shiftlongitude' => true,
                'st_shortestline' => true,
                'st_snaptogrid' => true,
                'st_split' => true,
                'st_srid' => true,
                'st_startpoint' => true,
                'st_summary' => true,
                'st_symdifference' => true,
                'st_touches' => true,
                'st_transform' => true,
                'st_translate' => true,
                'st_transscale' => true,
                'st_union' => true,
                'st_within' => true,
                'st_xmax' => true,
                'st_xmin' => true,
                'st_x' => true,
                'st_ymax' => true,
                'st_ymin' => true,
                'st_y' => true,
                'st_zmax' => true,
                'st_zmflag' => true,
                'st_zmin' => true,
                'st_z' => true,
                // Camelot custom
                'date_part' => true,
                'distance' => true,
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
        $services->set(DataFixtures\ArrayFixtures::class);
        $services->set(DataFixtures\DateFixtures::class);
        $services->set(DataFixtures\TextFixtures::class);
    }
}
