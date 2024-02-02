<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection\Compiler;

use Camelot\DoctrinePostgres\DQL;
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
        'MAKE_DATE' => DQL\MakeDate::class,
        'TO_CHAR' => DQL\ToChar::class,
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
