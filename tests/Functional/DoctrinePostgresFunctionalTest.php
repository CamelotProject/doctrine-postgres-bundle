<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\Tests\DataFixtures\TestFixtures;
use Camelot\DoctrinePostgres\Tests\Fixtures\App\Entity\JsonEntity;
use Camelot\DoctrinePostgres\Tests\Fixtures\App\Kernel;
use Camelot\DoctrinePostgres\Tests\Fixtures\App\Repository\JsonEntityRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function mb_stripos;
use const PHP_EOL;

/**
 * @group functional
 */
final class DoctrinePostgresFunctionalTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function providerExpectedTypes(): iterable
    {
        yield ['jsonb'];
        yield ['jsonb[]'];
        yield ['smallint[]'];
        yield ['integer[]'];
        yield ['bigint[]'];
        yield ['text[]'];
    }

    /**
     * @dataProvider providerExpectedTypes
     */
    public function testTypes(string $type): void
    {
        self::assertTrue(Type::hasType($type));
    }

    public function testCreateExtendedSchema(): void
    {
        $application = new Application(self::$kernel);
        $command = $application->find('doctrine:schema:update');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--dump-sql' => true,
            '--force' => true,
        ]);
        $display = $commandTester->getDisplay();

        $expected = [
            'json_b jsonb DEFAULT NULL',
            'json_b_array jsonb[] DEFAULT NULL',
            'small_int_array smallint[] DEFAULT NULL',
            'integer_array integer[] DEFAULT NULL',
            'big_int_array bigint[] DEFAULT NULL',
            'text_array text[] DEFAULT NULL',
        ];
        $failures = [];
        foreach ($expected as $item) {
            if (mb_stripos($display, $item) === false) {
                $failures[] = $item;
            } else {
                $this->addToAssertionCount(1);
            }
        }
        if ($failures) {
            static::fail(sprintf(
                'The following entries were missing from the SQL:%s - %s%s%s',
                PHP_EOL,
                implode("\n - ", $failures),
                PHP_EOL,
                $display
            ));
        }
    }

    /**
     * @depends testCreateExtendedSchema
     */
    public function testLoadFixtures(): void
    {
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::$container->get('doctrine');
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $doctrine->getManager();

        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute([self::$container->get(TestFixtures::class)]);
        $entities = $em->getRepository(JsonEntity::class)->findAll();

        self::assertNotEmpty($entities);
    }

    public function providerCustomStringFunctions(): iterable
    {
        yield 'ALL_OF' => ['all_of', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\All'];
        yield 'ANY_OF' => ['any_of', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Any'];
        yield 'CONTAINS' => ['contains', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Contains'];
        yield 'IS_CONTAINED_BY' => ['is_contained_by', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\IsContainedBy'];
        yield 'OVERLAPS' => ['overlaps', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Overlaps'];
        yield 'GREATEST' => ['greatest', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Greatest'];
        yield 'LEAST' => ['least', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Least'];
        yield 'ARRAY_APPEND' => ['array_append', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayAppend'];
        yield 'ARRAY_CARDINALITY' => ['array_cardinality', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayCardinality'];
        yield 'ARRAY_CAT' => ['array_cat', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayCat'];
        yield 'ARRAY_DIMENSIONS' => ['array_dimensions', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayDimensions'];
        yield 'ARRAY_LENGTH' => ['array_length', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayLength'];
        yield 'ARRAY_NUMBER_OF_DIMENSIONS' => ['array_number_of_dimensions', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayNumberOfDimensions'];
        yield 'ARRAY_PREPEND' => ['array_prepend', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayPrepend'];
        yield 'ARRAY_REMOVE' => ['array_remove', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayRemove'];
        yield 'ARRAY_REPLACE' => ['array_replace', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayReplace'];
        yield 'ARRAY_TO_JSON' => ['array_to_json', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayToJson'];
        yield 'ARRAY_TO_STRING' => ['array_to_string', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ArrayToString'];
        yield 'STRING_TO_ARRAY' => ['string_to_array', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\StringToArray'];
        yield 'IN_ARRAY' => ['in_array', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\InArray'];
        yield 'JSON_ARRAY_LENGTH' => ['json_array_length', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonArrayLength'];
        yield 'JSON_EACH' => ['json_each', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonEach'];
        yield 'JSON_EACH_TEXT' => ['json_each_text', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonEachText'];
        yield 'JSON_GET_FIELD' => ['json_get_field', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonGetField'];
        yield 'JSON_GET_FIELD_AS_INTEGER' => ['json_get_field_as_integer', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonGetFieldAsInteger'];
        yield 'JSON_GET_FIELD_AS_TEXT' => ['json_get_field_as_text', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonGetFieldAsText'];
        yield 'JSON_GET_OBJECT' => ['json_get_object', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonGetObject'];
        yield 'JSON_GET_OBJECT_AS_TEXT' => ['json_get_object_as_text', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonGetObjectAsText'];
        yield 'JSON_OBJECT_KEYS' => ['json_object_keys', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonObjectKeys'];
        yield 'JSON_STRIP_NULLS' => ['json_strip_nulls', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonStripNulls'];
        yield 'TO_JSON' => ['to_json', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ToJson'];
        yield 'JSONB_ARRAY_ELEMENTS' => ['jsonb_array_elements', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbArrayElements'];
        yield 'JSONB_ARRAY_ELEMENTS_TEXT' => ['jsonb_array_elements_text', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbArrayElementsText'];
        yield 'JSONB_ARRAY_LENGTH' => ['jsonb_array_length', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbArrayLength'];
        yield 'JSONB_EACH' => ['jsonb_each', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbEach'];
        yield 'JSONB_EACH_TEXT' => ['jsonb_each_text', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbEachText'];
        yield 'JSONB_EXISTS' => ['jsonb_exists', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbExists'];
        yield 'JSONB_INSERT' => ['jsonb_insert', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbInsert'];
        yield 'JSONB_OBJECT_KEYS' => ['jsonb_object_keys', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbObjectKeys'];
        yield 'JSONB_SET' => ['jsonb_set', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbSet'];
        yield 'JSONB_STRIP_NULLS' => ['jsonb_strip_nulls', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\JsonbStripNulls'];
        yield 'TO_JSONB' => ['to_jsonb', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ToJsonb'];
        yield 'TO_TSQUERY' => ['to_tsquery', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ToTsquery'];
        yield 'TO_TSVECTOR' => ['to_tsvector', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\ToTsvector'];
        yield 'TSMATCH' => ['tsmatch', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Tsmatch'];
        yield 'ILIKE' => ['ilike', 'MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\Ilike'];
    }

    /**
     * @dataProvider providerCustomStringFunctions
     */
    public function testCustomStringFunctions(string $name, string $class): void
    {
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::$container->get('doctrine');
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $doctrine->getManager();

        static::assertSame($class, $em->getConfiguration()->getCustomStringFunction($name));
    }

    /**
     * @depends testLoadFixtures
     */
    public function testILikeQuery(): void
    {
        /** @var JsonEntityRepository $repo */
        $repo = self::$container->get(JsonEntityRepository::class);
        $entities = $repo->findAllLike('%as%');
        $entity = $repo->findOneLike('%as%');

        self::assertCount(1, $entities);
        self::assertSame('basalt', $entity->getTitle());
    }

    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
