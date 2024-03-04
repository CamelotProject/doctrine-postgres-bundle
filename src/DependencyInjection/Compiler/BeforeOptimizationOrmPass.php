<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection\Compiler;

use Camelot\DoctrinePostgres\DQL;
use Jsor\Doctrine\PostGIS;
use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions as PostgresFunctions;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class BeforeOptimizationOrmPass implements CompilerPassInterface
{
    use ConfigTrait;

    private ?Definition $defaultConfiguration = null;
    private array $functions = [
        // alternative implementation of ALL() and ANY() where subquery is not required, useful for arrays
        'ALL_OF' => PostgresFunctions\All::class,
        'ANY_OF' => PostgresFunctions\Any::class,

        // operators for working with array and json(b) data
        'CONTAINS' => PostgresFunctions\Contains::class,
        'IS_CONTAINED_BY' => PostgresFunctions\IsContainedBy::class,
        'OVERLAPS' => PostgresFunctions\Overlaps::class,
        'GREATEST' => PostgresFunctions\Greatest::class,
        'LEAST' => PostgresFunctions\Least::class,

        // array specific functions
        'ARRAY_APPEND' => PostgresFunctions\ArrayAppend::class,
        'ARRAY_CARDINALITY' => PostgresFunctions\ArrayCardinality::class,
        'ARRAY_CAT' => PostgresFunctions\ArrayCat::class,
        'ARRAY_DIMENSIONS' => PostgresFunctions\ArrayDimensions::class,
        'ARRAY_LENGTH' => PostgresFunctions\ArrayLength::class,
        'ARRAY_NUMBER_OF_DIMENSIONS' => PostgresFunctions\ArrayNumberOfDimensions::class,
        'ARRAY_PREPEND' => PostgresFunctions\ArrayPrepend::class,
        'ARRAY_REMOVE' => PostgresFunctions\ArrayRemove::class,
        'ARRAY_REPLACE' => PostgresFunctions\ArrayReplace::class,
        'ARRAY_TO_JSON' => PostgresFunctions\ArrayToJson::class,
        'ARRAY_TO_STRING' => PostgresFunctions\ArrayToString::class,
        'STRING_TO_ARRAY' => PostgresFunctions\StringToArray::class,
        'IN_ARRAY' => PostgresFunctions\InArray::class,

        // json specific functions
        'JSON_ARRAY_LENGTH' => PostgresFunctions\JsonArrayLength::class,
        'JSON_EACH' => PostgresFunctions\JsonEach::class,
        'JSON_EACH_TEXT' => PostgresFunctions\JsonEachText::class,
        'JSON_GET_FIELD' => PostgresFunctions\JsonGetField::class,
        'JSON_GET_FIELD_AS_INTEGER' => PostgresFunctions\JsonGetFieldAsInteger::class,
        'JSON_GET_FIELD_AS_TEXT' => PostgresFunctions\JsonGetFieldAsText::class,
        'JSON_GET_OBJECT' => PostgresFunctions\JsonGetObject::class,
        'JSON_GET_OBJECT_AS_TEXT' => PostgresFunctions\JsonGetObjectAsText::class,
        'JSON_OBJECT_KEYS' => PostgresFunctions\JsonObjectKeys::class,
        'JSON_STRIP_NULLS' => PostgresFunctions\JsonStripNulls::class,
        'TO_JSON' => PostgresFunctions\ToJson::class,

        // jsonb specific functions
        'JSONB_ARRAY_ELEMENTS' => PostgresFunctions\JsonbArrayElements::class,
        'JSONB_ARRAY_ELEMENTS_TEXT' => PostgresFunctions\JsonbArrayElementsText::class,
        'JSONB_ARRAY_LENGTH' => PostgresFunctions\JsonbArrayLength::class,
        'JSONB_EACH' => PostgresFunctions\JsonbEach::class,
        'JSONB_EACH_TEXT' => PostgresFunctions\JsonbEachText::class,
        'JSONB_EXISTS' => PostgresFunctions\JsonbExists::class,
        'JSONB_INSERT' => PostgresFunctions\JsonbInsert::class,
        'JSONB_OBJECT_KEYS' => PostgresFunctions\JsonbObjectKeys::class,
        'JSONB_SET' => PostgresFunctions\JsonbSet::class,
        'JSONB_STRIP_NULLS' => PostgresFunctions\JsonbStripNulls::class,
        'TO_JSONB' => PostgresFunctions\ToJsonb::class,

        // text search specific
        'TO_TSQUERY' => PostgresFunctions\ToTsquery::class,
        'TO_TSVECTOR' => PostgresFunctions\ToTsvector::class,
        'TSMATCH' => PostgresFunctions\Tsmatch::class,

        // other operators
        'ILIKE' => PostgresFunctions\Ilike::class,

        // Camelot extensions
        'DATE_PART' => DQL\DatePart::class,
        'DISTANCE' => DQL\Distance::class,
        'MAKE_DATE' => DQL\MakeDate::class,
        'TO_CHAR' => DQL\ToChar::class,

        // PostGIS
        'GEOGRAPHY' => PostGIS\Functions\Geography::class,
        'GEOMETRY' => PostGIS\Functions\Geometry::class,
        'ST_3DCLOSESTPOINT' => PostGIS\Functions\ST_3DClosestPoint::class,
        'ST_3DDFULLYWITHIN' => PostGIS\Functions\ST_3DDFullyWithin::class,
        'ST_3DDISTANCE' => PostGIS\Functions\ST_3DDistance::class,
        'ST_3DDWITHIN' => PostGIS\Functions\ST_3DDWithin::class,
        'ST_3DINTERSECTS' => PostGIS\Functions\ST_3DIntersects::class,
        'ST_3DLENGTH' => PostGIS\Functions\ST_3DLength::class,
        'ST_3DLONGESTLINE' => PostGIS\Functions\ST_3DLongestLine::class,
        'ST_3DMAKEBOX' => PostGIS\Functions\ST_3DMakeBox::class,
        'ST_3DMAXDISTANCE' => PostGIS\Functions\ST_3DMaxDistance::class,
        'ST_3DSHORTESTLINE' => PostGIS\Functions\ST_3DShortestLine::class,
        'ST_ADDPOINT' => PostGIS\Functions\ST_AddPoint::class,
        'ST_AREA' => PostGIS\Functions\ST_Area::class,
        'ST_ASBINARY' => PostGIS\Functions\ST_AsBinary::class,
        'ST_ASEWKB' => PostGIS\Functions\ST_AsEWKB::class,
        'ST_ASEWKT' => PostGIS\Functions\ST_AsEWKT::class,
        'ST_ASGEOJSON' => PostGIS\Functions\ST_AsGeoJSON::class,
        'ST_ASGML' => PostGIS\Functions\ST_AsGML::class,
        'ST_ASHEXEWKB' => PostGIS\Functions\ST_AsHEXEWKB::class,
        'ST_ASLATLONTEXT' => PostGIS\Functions\ST_AsLatLonText::class,
        'ST_ASSVG' => PostGIS\Functions\ST_AsSVG::class,
        'ST_ASTEXT' => PostGIS\Functions\ST_AsText::class,
        'ST_AZIMUTH' => PostGIS\Functions\ST_Azimuth::class,
        'ST_BOUNDARY' => PostGIS\Functions\ST_Boundary::class,
        'ST_BOX2DFROMGEOHASH' => PostGIS\Functions\ST_Box2dFromGeoHash::class,
        'ST_BUFFER' => PostGIS\Functions\ST_Buffer::class,
        'ST_CENTROID' => PostGIS\Functions\ST_Centroid::class,
        'ST_CLOSESTPOINT' => PostGIS\Functions\ST_ClosestPoint::class,
        'ST_COLLECT' => PostGIS\Functions\ST_Collect::class,
        'ST_CONTAINS' => PostGIS\Functions\ST_Contains::class,
        'ST_CONTAINSPROPERLY' => PostGIS\Functions\ST_ContainsProperly::class,
        'ST_COORDDIM' => PostGIS\Functions\ST_CoordDim::class,
        'ST_COVEREDBY' => PostGIS\Functions\ST_CoveredBy::class,
        'ST_COVERS' => PostGIS\Functions\ST_Covers::class,
        'ST_CROSSES' => PostGIS\Functions\ST_Crosses::class,
        'ST_DFULLYWITHIN' => PostGIS\Functions\ST_DFullyWithin::class,
        'ST_DIFFERENCE' => PostGIS\Functions\ST_Difference::class,
        'ST_DIMENSION' => PostGIS\Functions\ST_Dimension::class,
        'ST_DISJOINT' => PostGIS\Functions\ST_Disjoint::class,
        'ST_DISTANCE' => PostGIS\Functions\ST_Distance::class,
        'ST_DISTANCESPHERE' => PostGIS\Functions\ST_DistanceSphere::class,
        'ST_DISTANCESPHEROID' => PostGIS\Functions\ST_DistanceSpheroid::class,
        'ST_DWITHIN' => PostGIS\Functions\ST_DWithin::class,
        'ST_ENDPOINT' => PostGIS\Functions\ST_EndPoint::class,
        'ST_ENVELOPE' => PostGIS\Functions\ST_Envelope::class,
        'ST_EQUALS' => PostGIS\Functions\ST_Equals::class,
        'ST_EXTENT' => PostGIS\Functions\ST_Extent::class,
        'ST_EXTERIORRING' => PostGIS\Functions\ST_ExteriorRing::class,
        'ST_FLIPCOORDINATES' => PostGIS\Functions\ST_FlipCoordinates::class,
        'ST_GEOGFROMTEXT' => PostGIS\Functions\ST_GeogFromText::class,
        'ST_GEOGFROMWKB' => PostGIS\Functions\ST_GeogFromWKB::class,
        'ST_GEOGRAPHYFROMTEXT' => PostGIS\Functions\ST_GeographyFromText::class,
        'ST_GEOHASH' => PostGIS\Functions\ST_GeoHash::class,
        'ST_GEOMCOLLFROMTEXT' => PostGIS\Functions\ST_GeomCollFromText::class,
        'ST_GEOMETRYFROMTEXT' => PostGIS\Functions\ST_GeometryFromText::class,
        'ST_GEOMETRYN' => PostGIS\Functions\ST_GeometryN::class,
        'ST_GEOMETRYTYPE' => PostGIS\Functions\ST_GeometryType::class,
        'ST_GEOMFROMEWKB' => PostGIS\Functions\ST_GeomFromEWKB::class,
        'ST_GEOMFROMEWKT' => PostGIS\Functions\ST_GeomFromEWKT::class,
        'ST_GEOMFROMGEOHASH' => PostGIS\Functions\ST_GeomFromGeoHash::class,
        'ST_GEOMFROMGEOJSON' => PostGIS\Functions\ST_GeomFromGeoJSON::class,
        'ST_GEOMFROMGML' => PostGIS\Functions\ST_GeomFromGML::class,
        'ST_GEOMFROMKML' => PostGIS\Functions\ST_GeomFromKML::class,
        'ST_GEOMFROMTEXT' => PostGIS\Functions\ST_GeomFromText::class,
        'ST_GEOMFROMWKB' => PostGIS\Functions\ST_GeomFromWKB::class,
        'ST_HASARC' => PostGIS\Functions\ST_HasArc::class,
        'ST_HAUSDORFFDISTANCE' => PostGIS\Functions\ST_HausdorffDistance::class,
        'ST_INTERIORRINGN' => PostGIS\Functions\ST_InteriorRingN::class,
        'ST_INTERSECTION' => PostGIS\Functions\ST_Intersection::class,
        'ST_INTERSECTS' => PostGIS\Functions\ST_Intersects::class,
        'ST_ISCLOSED' => PostGIS\Functions\ST_IsClosed::class,
        'ST_ISCOLLECTION' => PostGIS\Functions\ST_IsCollection::class,
        'ST_ISEMPTY' => PostGIS\Functions\ST_IsEmpty::class,
        'ST_ISRING' => PostGIS\Functions\ST_IsRing::class,
        'ST_ISSIMPLE' => PostGIS\Functions\ST_IsSimple::class,
        'ST_ISVALIDDETAIL' => PostGIS\Functions\ST_IsValidDetail::class,
        'ST_ISVALID' => PostGIS\Functions\ST_IsValid::class,
        'ST_ISVALIDREASON' => PostGIS\Functions\ST_IsValidReason::class,
        'ST_LENGTH' => PostGIS\Functions\ST_Length::class,
        'ST_LENGTHSPHEROID' => PostGIS\Functions\ST_LengthSpheroid::class,
        'ST_LINECROSSINGDIRECTION' => PostGIS\Functions\ST_LineCrossingDirection::class,
        'ST_LINEFROMMULTIPOINT' => PostGIS\Functions\ST_LineFromMultiPoint::class,
        'ST_LINEFROMTEXT' => PostGIS\Functions\ST_LineFromText::class,
        'ST_LINEFROMWKB' => PostGIS\Functions\ST_LineFromWKB::class,
        'ST_LINESTRINGFROMWKB' => PostGIS\Functions\ST_LinestringFromWKB::class,
        'ST_LONGESTLINE' => PostGIS\Functions\ST_LongestLine::class,
        'ST_MAKEBOX2D' => PostGIS\Functions\ST_MakeBox2D::class,
        'ST_MAKEENVELOPE' => PostGIS\Functions\ST_MakeEnvelope::class,
        'ST_MAKELINE' => PostGIS\Functions\ST_MakeLine::class,
        'ST_MAKEPOINTM' => PostGIS\Functions\ST_MakePointM::class,
        'ST_MAKEPOINT' => PostGIS\Functions\ST_MakePoint::class,
        'ST_MAKEPOLYGON' => PostGIS\Functions\ST_MakePolygon::class,
        'ST_MAXDISTANCE' => PostGIS\Functions\ST_MaxDistance::class,
        'ST_MINIMUMBOUNDINGCIRCLE' => PostGIS\Functions\ST_MinimumBoundingCircle::class,
        'ST_MLINEFROMTEXT' => PostGIS\Functions\ST_MLineFromText::class,
        'ST_M' => PostGIS\Functions\ST_M::class,
        'ST_MPOINTFROMTEXT' => PostGIS\Functions\ST_MPointFromText::class,
        'ST_MPOLYFROMTEXT' => PostGIS\Functions\ST_MPolyFromText::class,
        'ST_MULTI' => PostGIS\Functions\ST_Multi::class,
        'ST_NDIMS' => PostGIS\Functions\ST_NDims::class,
        'ST_NPOINTS' => PostGIS\Functions\ST_NPoints::class,
        'ST_NRINGS' => PostGIS\Functions\ST_NRings::class,
        'ST_NUMGEOMETRIES' => PostGIS\Functions\ST_NumGeometries::class,
        'ST_NUMINTERIORRING' => PostGIS\Functions\ST_NumInteriorRing::class,
        'ST_NUMINTERIORRINGS' => PostGIS\Functions\ST_NumInteriorRings::class,
        'ST_NUMPATCHES' => PostGIS\Functions\ST_NumPatches::class,
        'ST_NUMPOINTS' => PostGIS\Functions\ST_NumPoints::class,
        'ST_ORDERINGEQUALS' => PostGIS\Functions\ST_OrderingEquals::class,
        'ST_OVERLAPS' => PostGIS\Functions\ST_Overlaps::class,
        'ST_PATCHN' => PostGIS\Functions\ST_PatchN::class,
        'ST_PERIMETER' => PostGIS\Functions\ST_Perimeter::class,
        'ST_POINTFROMGEOHASH' => PostGIS\Functions\ST_PointFromGeoHash::class,
        'ST_POINTFROMTEXT' => PostGIS\Functions\ST_PointFromText::class,
        'ST_POINTFROMWKB' => PostGIS\Functions\ST_PointFromWKB::class,
        'ST_POINTN' => PostGIS\Functions\ST_PointN::class,
        'ST_POINTONSURFACE' => PostGIS\Functions\ST_PointOnSurface::class,
        'ST_POINT' => PostGIS\Functions\ST_Point::class,
        'ST_POLYGONFROMTEXT' => PostGIS\Functions\ST_PolygonFromText::class,
        'ST_POLYGON' => PostGIS\Functions\ST_Polygon::class,
        'ST_PROJECT' => PostGIS\Functions\ST_Project::class,
        'ST_RELATE' => PostGIS\Functions\ST_Relate::class,
        'ST_SCALE' => PostGIS\Functions\ST_Scale::class,
        'ST_SETSRID' => PostGIS\Functions\ST_SetSRID::class,
        'ST_SHIFTLONGITUDE' => PostGIS\Functions\ST_ShiftLongitude::class,
        'ST_SHORTESTLINE' => PostGIS\Functions\ST_ShortestLine::class,
        'ST_SNAPTOGRID' => PostGIS\Functions\ST_SnapToGrid::class,
        'ST_SPLIT' => PostGIS\Functions\ST_Split::class,
        'ST_SRID' => PostGIS\Functions\ST_SRID::class,
        'ST_STARTPOINT' => PostGIS\Functions\ST_StartPoint::class,
        'ST_SUMMARY' => PostGIS\Functions\ST_Summary::class,
        'ST_SYMDIFFERENCE' => PostGIS\Functions\ST_SymDifference::class,
        'ST_TOUCHES' => PostGIS\Functions\ST_Touches::class,
        'ST_TRANSFORM' => PostGIS\Functions\ST_Transform::class,
        'ST_TRANSLATE' => PostGIS\Functions\ST_Translate::class,
        'ST_TRANSSCALE' => PostGIS\Functions\ST_TransScale::class,
        'ST_UNION' => PostGIS\Functions\ST_Union::class,
        'ST_WITHIN' => PostGIS\Functions\ST_Within::class,
        'ST_XMAX' => PostGIS\Functions\ST_XMax::class,
        'ST_XMIN' => PostGIS\Functions\ST_XMin::class,
        'ST_X' => PostGIS\Functions\ST_X::class,
        'ST_YMAX' => PostGIS\Functions\ST_YMax::class,
        'ST_YMIN' => PostGIS\Functions\ST_YMin::class,
        'ST_Y' => PostGIS\Functions\ST_Y::class,
        'ST_ZMAX' => PostGIS\Functions\ST_ZMax::class,
        'ST_ZMFLAG' => PostGIS\Functions\ST_Zmflag::class,
        'ST_ZMIN' => PostGIS\Functions\ST_ZMin::class,
        'ST_Z' => PostGIS\Functions\ST_Z::class,
    ];

    public function process(ContainerBuilder $container): void
    {
        $config = $this->getConfig($container);
        $functions = [];

        foreach ($config['functions'] as $name => $enabled) {
            $name = strtoupper($name);
            if (!isset($this->functions[$name])) {
                throw new \RuntimeException(sprintf('Unknown ORM function "%s"', $name));
            }

            if ($enabled) {
                $functions[$name] = $this->functions[$name];
            }
        }
        $this->getDefaultConfigDefinition($container)
            ->addMethodCall('setCustomStringFunctions', [$functions])
        ;
    }

    private function getDefaultConfigDefinition(ContainerBuilder $container): Definition
    {
        if ($this->defaultConfiguration === null) {
            $this->defaultConfiguration = $container->getDefinition('doctrine.orm.default_configuration');
        }

        return $this->defaultConfiguration;
    }
}
