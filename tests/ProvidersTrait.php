<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests;

use Camelot\DoctrinePostgres\DQL;
use Jsor\Doctrine\PostGIS\Functions as PostGISFunctions;
use Jsor\Doctrine\PostGIS\Types as PostGISTypes;
use MartinGeorgiev\Doctrine\DBAL\Types;
use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions;

trait ProvidersTrait
{
    public static function providerTypes(): iterable
    {
        yield ['bool[]', Types\BooleanArray::class];
        yield ['jsonb[]', Types\JsonbArray::class];
        yield ['smallint[]', Types\SmallIntArray::class];
        yield ['integer[]', Types\IntegerArray::class];
        yield ['bigint[]', Types\BigIntArray::class];
        yield ['text[]', Types\TextArray::class];

        yield ['geography', PostGISTypes\GeographyType::class];
        yield ['geometry', PostGISTypes\GeometryType::class];
    }

    public static function providerStringFunctions(): iterable
    {
        // alternative implementation of ALL() and ANY() where subquery is not required, useful for arrays
        yield ['ALL_OF', Functions\All::class];
        yield ['ANY_OF', Functions\Any::class];

        // operators for working with array and json(b) data
        yield ['CONTAINS', Functions\Contains::class];
        yield ['IS_CONTAINED_BY', Functions\IsContainedBy::class];
        yield ['OVERLAPS', Functions\Overlaps::class];
        yield ['GREATEST', Functions\Greatest::class];
        yield ['LEAST', Functions\Least::class];

        // array specific functions
        yield ['ARRAY_APPEND', Functions\ArrayAppend::class];
        yield ['ARRAY_CARDINALITY', Functions\ArrayCardinality::class];
        yield ['ARRAY_CAT', Functions\ArrayCat::class];
        yield ['ARRAY_DIMENSIONS', Functions\ArrayDimensions::class];
        yield ['ARRAY_LENGTH', Functions\ArrayLength::class];
        yield ['ARRAY_NUMBER_OF_DIMENSIONS', Functions\ArrayNumberOfDimensions::class];
        yield ['ARRAY_PREPEND', Functions\ArrayPrepend::class];
        yield ['ARRAY_REMOVE', Functions\ArrayRemove::class];
        yield ['ARRAY_REPLACE', Functions\ArrayReplace::class];
        yield ['ARRAY_TO_JSON', Functions\ArrayToJson::class];
        yield ['ARRAY_TO_STRING', Functions\ArrayToString::class];
        yield ['STRING_TO_ARRAY', Functions\StringToArray::class];
        yield ['IN_ARRAY', Functions\InArray::class];

        // json specific functions
        yield ['JSON_ARRAY_LENGTH', Functions\JsonArrayLength::class];
        yield ['JSON_EACH', Functions\JsonEach::class];
        yield ['JSON_EACH_TEXT', Functions\JsonEachText::class];
        yield ['JSON_GET_FIELD', Functions\JsonGetField::class];
        yield ['JSON_GET_FIELD_AS_INTEGER', Functions\JsonGetFieldAsInteger::class];
        yield ['JSON_GET_FIELD_AS_TEXT', Functions\JsonGetFieldAsText::class];
        yield ['JSON_GET_OBJECT', Functions\JsonGetObject::class];
        yield ['JSON_GET_OBJECT_AS_TEXT', Functions\JsonGetObjectAsText::class];
        yield ['JSON_OBJECT_KEYS', Functions\JsonObjectKeys::class];
        yield ['JSON_STRIP_NULLS', Functions\JsonStripNulls::class];
        yield ['TO_JSON', Functions\ToJson::class];

        // jsonb specific functions
        yield ['JSONB_ARRAY_ELEMENTS', Functions\JsonbArrayElements::class];
        yield ['JSONB_ARRAY_ELEMENTS_TEXT', Functions\JsonbArrayElementsText::class];
        yield ['JSONB_ARRAY_LENGTH', Functions\JsonbArrayLength::class];
        yield ['JSONB_EACH', Functions\JsonbEach::class];
        yield ['JSONB_EACH_TEXT', Functions\JsonbEachText::class];
        yield ['JSONB_EXISTS', Functions\JsonbExists::class];
        yield ['JSONB_INSERT', Functions\JsonbInsert::class];
        yield ['JSONB_OBJECT_KEYS', Functions\JsonbObjectKeys::class];
        yield ['JSONB_SET', Functions\JsonbSet::class];
        yield ['JSONB_STRIP_NULLS', Functions\JsonbStripNulls::class];
        yield ['TO_JSONB', Functions\ToJsonb::class];

        // text search specific
        yield ['TO_TSQUERY', Functions\ToTsquery::class];
        yield ['TO_TSVECTOR', Functions\ToTsvector::class];
        yield ['TSMATCH', Functions\Tsmatch::class];

        // other operators
        yield ['ILIKE', Functions\Ilike::class];

        // Camelot extensions
        yield ['DATE_PART', DQL\DatePart::class];
        yield ['DISTANCE', DQL\Distance::class];
        yield ['MAKE_DATE', DQL\MakeDate::class];
        yield ['TO_CHAR', DQL\ToChar::class];

        // PostGIS
        yield ['GEOGRAPHY', PostGISFunctions\Geography::class];
        yield ['GEOMETRY', PostGISFunctions\Geometry::class];
        yield ['ST_3DCLOSESTPOINT', PostGISFunctions\ST_3DClosestPoint::class];
        yield ['ST_3DDFULLYWITHIN', PostGISFunctions\ST_3DDFullyWithin::class];
        yield ['ST_3DDISTANCE', PostGISFunctions\ST_3DDistance::class];
        yield ['ST_3DDWITHIN', PostGISFunctions\ST_3DDWithin::class];
        yield ['ST_3DINTERSECTS', PostGISFunctions\ST_3DIntersects::class];
        yield ['ST_3DLENGTH', PostGISFunctions\ST_3DLength::class];
        yield ['ST_3DLONGESTLINE', PostGISFunctions\ST_3DLongestLine::class];
        yield ['ST_3DMAKEBOX', PostGISFunctions\ST_3DMakeBox::class];
        yield ['ST_3DMAXDISTANCE', PostGISFunctions\ST_3DMaxDistance::class];
        yield ['ST_3DSHORTESTLINE', PostGISFunctions\ST_3DShortestLine::class];
        yield ['ST_ADDPOINT', PostGISFunctions\ST_AddPoint::class];
        yield ['ST_AREA', PostGISFunctions\ST_Area::class];
        yield ['ST_ASBINARY', PostGISFunctions\ST_AsBinary::class];
        yield ['ST_ASEWKB', PostGISFunctions\ST_AsEWKB::class];
        yield ['ST_ASEWKT', PostGISFunctions\ST_AsEWKT::class];
        yield ['ST_ASGEOJSON', PostGISFunctions\ST_AsGeoJSON::class];
        yield ['ST_ASGML', PostGISFunctions\ST_AsGML::class];
        yield ['ST_ASHEXEWKB', PostGISFunctions\ST_AsHEXEWKB::class];
        yield ['ST_ASLATLONTEXT', PostGISFunctions\ST_AsLatLonText::class];
        yield ['ST_ASSVG', PostGISFunctions\ST_AsSVG::class];
        yield ['ST_ASTEXT', PostGISFunctions\ST_AsText::class];
        yield ['ST_AZIMUTH', PostGISFunctions\ST_Azimuth::class];
        yield ['ST_BOUNDARY', PostGISFunctions\ST_Boundary::class];
        yield ['ST_BOX2DFROMGEOHASH', PostGISFunctions\ST_Box2dFromGeoHash::class];
        yield ['ST_BUFFER', PostGISFunctions\ST_Buffer::class];
        yield ['ST_CENTROID', PostGISFunctions\ST_Centroid::class];
        yield ['ST_CLOSESTPOINT', PostGISFunctions\ST_ClosestPoint::class];
        yield ['ST_COLLECT', PostGISFunctions\ST_Collect::class];
        yield ['ST_CONTAINS', PostGISFunctions\ST_Contains::class];
        yield ['ST_CONTAINSPROPERLY', PostGISFunctions\ST_ContainsProperly::class];
        yield ['ST_COORDDIM', PostGISFunctions\ST_CoordDim::class];
        yield ['ST_COVEREDBY', PostGISFunctions\ST_CoveredBy::class];
        yield ['ST_COVERS', PostGISFunctions\ST_Covers::class];
        yield ['ST_CROSSES', PostGISFunctions\ST_Crosses::class];
        yield ['ST_DFULLYWITHIN', PostGISFunctions\ST_DFullyWithin::class];
        yield ['ST_DIFFERENCE', PostGISFunctions\ST_Difference::class];
        yield ['ST_DIMENSION', PostGISFunctions\ST_Dimension::class];
        yield ['ST_DISJOINT', PostGISFunctions\ST_Disjoint::class];
        yield ['ST_DISTANCE', PostGISFunctions\ST_Distance::class];
        yield ['ST_DISTANCESPHERE', PostGISFunctions\ST_DistanceSphere::class];
        yield ['ST_DISTANCESPHEROID', PostGISFunctions\ST_DistanceSpheroid::class];
        yield ['ST_DWITHIN', PostGISFunctions\ST_DWithin::class];
        yield ['ST_ENDPOINT', PostGISFunctions\ST_EndPoint::class];
        yield ['ST_ENVELOPE', PostGISFunctions\ST_Envelope::class];
        yield ['ST_EQUALS', PostGISFunctions\ST_Equals::class];
        yield ['ST_EXTENT', PostGISFunctions\ST_Extent::class];
        yield ['ST_EXTERIORRING', PostGISFunctions\ST_ExteriorRing::class];
        yield ['ST_FLIPCOORDINATES', PostGISFunctions\ST_FlipCoordinates::class];
        yield ['ST_GEOGFROMTEXT', PostGISFunctions\ST_GeogFromText::class];
        yield ['ST_GEOGFROMWKB', PostGISFunctions\ST_GeogFromWKB::class];
        yield ['ST_GEOGRAPHYFROMTEXT', PostGISFunctions\ST_GeographyFromText::class];
        yield ['ST_GEOHASH', PostGISFunctions\ST_GeoHash::class];
        yield ['ST_GEOMCOLLFROMTEXT', PostGISFunctions\ST_GeomCollFromText::class];
        yield ['ST_GEOMETRYFROMTEXT', PostGISFunctions\ST_GeometryFromText::class];
        yield ['ST_GEOMETRYN', PostGISFunctions\ST_GeometryN::class];
        yield ['ST_GEOMETRYTYPE', PostGISFunctions\ST_GeometryType::class];
        yield ['ST_GEOMFROMEWKB', PostGISFunctions\ST_GeomFromEWKB::class];
        yield ['ST_GEOMFROMEWKT', PostGISFunctions\ST_GeomFromEWKT::class];
        yield ['ST_GEOMFROMGEOHASH', PostGISFunctions\ST_GeomFromGeoHash::class];
        yield ['ST_GEOMFROMGEOJSON', PostGISFunctions\ST_GeomFromGeoJSON::class];
        yield ['ST_GEOMFROMGML', PostGISFunctions\ST_GeomFromGML::class];
        yield ['ST_GEOMFROMKML', PostGISFunctions\ST_GeomFromKML::class];
        yield ['ST_GEOMFROMTEXT', PostGISFunctions\ST_GeomFromText::class];
        yield ['ST_GEOMFROMWKB', PostGISFunctions\ST_GeomFromWKB::class];
        yield ['ST_HASARC', PostGISFunctions\ST_HasArc::class];
        yield ['ST_HAUSDORFFDISTANCE', PostGISFunctions\ST_HausdorffDistance::class];
        yield ['ST_INTERIORRINGN', PostGISFunctions\ST_InteriorRingN::class];
        yield ['ST_INTERSECTION', PostGISFunctions\ST_Intersection::class];
        yield ['ST_INTERSECTS', PostGISFunctions\ST_Intersects::class];
        yield ['ST_ISCLOSED', PostGISFunctions\ST_IsClosed::class];
        yield ['ST_ISCOLLECTION', PostGISFunctions\ST_IsCollection::class];
        yield ['ST_ISEMPTY', PostGISFunctions\ST_IsEmpty::class];
        yield ['ST_ISRING', PostGISFunctions\ST_IsRing::class];
        yield ['ST_ISSIMPLE', PostGISFunctions\ST_IsSimple::class];
        yield ['ST_ISVALIDDETAIL', PostGISFunctions\ST_IsValidDetail::class];
        yield ['ST_ISVALID', PostGISFunctions\ST_IsValid::class];
        yield ['ST_ISVALIDREASON', PostGISFunctions\ST_IsValidReason::class];
        yield ['ST_LENGTH', PostGISFunctions\ST_Length::class];
        yield ['ST_LENGTHSPHEROID', PostGISFunctions\ST_LengthSpheroid::class];
        yield ['ST_LINECROSSINGDIRECTION', PostGISFunctions\ST_LineCrossingDirection::class];
        yield ['ST_LINEFROMMULTIPOINT', PostGISFunctions\ST_LineFromMultiPoint::class];
        yield ['ST_LINEFROMTEXT', PostGISFunctions\ST_LineFromText::class];
        yield ['ST_LINEFROMWKB', PostGISFunctions\ST_LineFromWKB::class];
        yield ['ST_LINESTRINGFROMWKB', PostGISFunctions\ST_LinestringFromWKB::class];
        yield ['ST_LONGESTLINE', PostGISFunctions\ST_LongestLine::class];
        yield ['ST_MAKEBOX2D', PostGISFunctions\ST_MakeBox2D::class];
        yield ['ST_MAKEENVELOPE', PostGISFunctions\ST_MakeEnvelope::class];
        yield ['ST_MAKELINE', PostGISFunctions\ST_MakeLine::class];
        yield ['ST_MAKEPOINTM', PostGISFunctions\ST_MakePointM::class];
        yield ['ST_MAKEPOINT', PostGISFunctions\ST_MakePoint::class];
        yield ['ST_MAKEPOLYGON', PostGISFunctions\ST_MakePolygon::class];
        yield ['ST_MAXDISTANCE', PostGISFunctions\ST_MaxDistance::class];
        yield ['ST_MINIMUMBOUNDINGCIRCLE', PostGISFunctions\ST_MinimumBoundingCircle::class];
        yield ['ST_MLINEFROMTEXT', PostGISFunctions\ST_MLineFromText::class];
        yield ['ST_M', PostGISFunctions\ST_M::class];
        yield ['ST_MPOINTFROMTEXT', PostGISFunctions\ST_MPointFromText::class];
        yield ['ST_MPOLYFROMTEXT', PostGISFunctions\ST_MPolyFromText::class];
        yield ['ST_MULTI', PostGISFunctions\ST_Multi::class];
        yield ['ST_NDIMS', PostGISFunctions\ST_NDims::class];
        yield ['ST_NPOINTS', PostGISFunctions\ST_NPoints::class];
        yield ['ST_NRINGS', PostGISFunctions\ST_NRings::class];
        yield ['ST_NUMGEOMETRIES', PostGISFunctions\ST_NumGeometries::class];
        yield ['ST_NUMINTERIORRING', PostGISFunctions\ST_NumInteriorRing::class];
        yield ['ST_NUMINTERIORRINGS', PostGISFunctions\ST_NumInteriorRings::class];
        yield ['ST_NUMPATCHES', PostGISFunctions\ST_NumPatches::class];
        yield ['ST_NUMPOINTS', PostGISFunctions\ST_NumPoints::class];
        yield ['ST_ORDERINGEQUALS', PostGISFunctions\ST_OrderingEquals::class];
        yield ['ST_OVERLAPS', PostGISFunctions\ST_Overlaps::class];
        yield ['ST_PATCHN', PostGISFunctions\ST_PatchN::class];
        yield ['ST_PERIMETER', PostGISFunctions\ST_Perimeter::class];
        yield ['ST_POINTFROMGEOHASH', PostGISFunctions\ST_PointFromGeoHash::class];
        yield ['ST_POINTFROMTEXT', PostGISFunctions\ST_PointFromText::class];
        yield ['ST_POINTFROMWKB', PostGISFunctions\ST_PointFromWKB::class];
        yield ['ST_POINTN', PostGISFunctions\ST_PointN::class];
        yield ['ST_POINTONSURFACE', PostGISFunctions\ST_PointOnSurface::class];
        yield ['ST_POINT', PostGISFunctions\ST_Point::class];
        yield ['ST_POLYGONFROMTEXT', PostGISFunctions\ST_PolygonFromText::class];
        yield ['ST_POLYGON', PostGISFunctions\ST_Polygon::class];
        yield ['ST_PROJECT', PostGISFunctions\ST_Project::class];
        yield ['ST_RELATE', PostGISFunctions\ST_Relate::class];
        yield ['ST_SCALE', PostGISFunctions\ST_Scale::class];
        yield ['ST_SETSRID', PostGISFunctions\ST_SetSRID::class];
        yield ['ST_SHIFTLONGITUDE', PostGISFunctions\ST_ShiftLongitude::class];
        yield ['ST_SHORTESTLINE', PostGISFunctions\ST_ShortestLine::class];
        yield ['ST_SNAPTOGRID', PostGISFunctions\ST_SnapToGrid::class];
        yield ['ST_SPLIT', PostGISFunctions\ST_Split::class];
        yield ['ST_SRID', PostGISFunctions\ST_SRID::class];
        yield ['ST_STARTPOINT', PostGISFunctions\ST_StartPoint::class];
        yield ['ST_SUMMARY', PostGISFunctions\ST_Summary::class];
        yield ['ST_SYMDIFFERENCE', PostGISFunctions\ST_SymDifference::class];
        yield ['ST_TOUCHES', PostGISFunctions\ST_Touches::class];
        yield ['ST_TRANSFORM', PostGISFunctions\ST_Transform::class];
        yield ['ST_TRANSLATE', PostGISFunctions\ST_Translate::class];
        yield ['ST_TRANSSCALE', PostGISFunctions\ST_TransScale::class];
        yield ['ST_UNION', PostGISFunctions\ST_Union::class];
        yield ['ST_WITHIN', PostGISFunctions\ST_Within::class];
        yield ['ST_XMAX', PostGISFunctions\ST_XMax::class];
        yield ['ST_XMIN', PostGISFunctions\ST_XMin::class];
        yield ['ST_X', PostGISFunctions\ST_X::class];
        yield ['ST_YMAX', PostGISFunctions\ST_YMax::class];
        yield ['ST_YMIN', PostGISFunctions\ST_YMin::class];
        yield ['ST_Y', PostGISFunctions\ST_Y::class];
        yield ['ST_ZMAX', PostGISFunctions\ST_ZMax::class];
        yield ['ST_ZMFLAG', PostGISFunctions\ST_Zmflag::class];
        yield ['ST_ZMIN', PostGISFunctions\ST_ZMin::class];
        yield ['ST_Z', PostGISFunctions\ST_Z::class];
    }
}
