<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests;

use Camelot\DoctrinePostgres\DQL;
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
        yield ['MAKE_DATE', DQL\MakeDate::class];
        yield ['TO_CHAR', DQL\ToChar::class];
    }
}
