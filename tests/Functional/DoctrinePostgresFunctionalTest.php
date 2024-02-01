<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\DQL;
use Camelot\DoctrinePostgres\Tests\DataFixtures\TestFixtures;
use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\JsonEntity;
use Camelot\DoctrinePostgres\Tests\Fixtures\Kernel;
use Camelot\DoctrinePostgres\Tests\Fixtures\Repository\JsonEntityRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Types\Type;
use Doctrine\Persistence\ManagerRegistry;
use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function mb_stripos;
use const PHP_EOL;

#[Group('functional')]
final class DoctrinePostgresFunctionalTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public static function providerExpectedTypes(): iterable
    {
        yield ['jsonb'];
        yield ['jsonb[]'];
        yield ['smallint[]'];
        yield ['integer[]'];
        yield ['bigint[]'];
        yield ['text[]'];
    }

    #[DataProvider('providerExpectedTypes')]
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
                $display,
            ));
        }
    }

    #[Depends('testCreateExtendedSchema')]
    public function testLoadFixtures(): void
    {
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get('doctrine');
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $doctrine->getManager();

        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute([self::getContainer()->get(TestFixtures::class)]);
        $entities = $em->getRepository(JsonEntity::class)->findAll();

        self::assertNotEmpty($entities);
    }

    public static function providerCustomStringFunctions(): iterable
    {
        yield 'ALL_OF' => ['all_of', Functions\All::class];
        yield 'ANY_OF' => ['any_of', Functions\Any::class];
        yield 'CONTAINS' => ['contains', Functions\Contains::class];
        yield 'IS_CONTAINED_BY' => ['is_contained_by', Functions\IsContainedBy::class];
        yield 'OVERLAPS' => ['overlaps', Functions\Overlaps::class];
        yield 'GREATEST' => ['greatest', Functions\Greatest::class];
        yield 'LEAST' => ['least', Functions\Least::class];
        yield 'ARRAY_APPEND' => ['array_append', Functions\ArrayAppend::class];
        yield 'ARRAY_CARDINALITY' => ['array_cardinality', Functions\ArrayCardinality::class];
        yield 'ARRAY_CAT' => ['array_cat', Functions\ArrayCat::class];
        yield 'ARRAY_DIMENSIONS' => ['array_dimensions', Functions\ArrayDimensions::class];
        yield 'ARRAY_LENGTH' => ['array_length', Functions\ArrayLength::class];
        yield 'ARRAY_NUMBER_OF_DIMENSIONS' => ['array_number_of_dimensions', Functions\ArrayNumberOfDimensions::class];
        yield 'ARRAY_PREPEND' => ['array_prepend', Functions\ArrayPrepend::class];
        yield 'ARRAY_REMOVE' => ['array_remove', Functions\ArrayRemove::class];
        yield 'ARRAY_REPLACE' => ['array_replace', Functions\ArrayReplace::class];
        yield 'ARRAY_TO_JSON' => ['array_to_json', Functions\ArrayToJson::class];
        yield 'ARRAY_TO_STRING' => ['array_to_string', Functions\ArrayToString::class];
        yield 'STRING_TO_ARRAY' => ['string_to_array', Functions\StringToArray::class];
        yield 'IN_ARRAY' => ['in_array', Functions\InArray::class];
        yield 'JSON_ARRAY_LENGTH' => ['json_array_length', Functions\JsonArrayLength::class];
        yield 'JSON_EACH' => ['json_each', Functions\JsonEach::class];
        yield 'JSON_EACH_TEXT' => ['json_each_text', Functions\JsonEachText::class];
        yield 'JSON_GET_FIELD' => ['json_get_field', Functions\JsonGetField::class];
        yield 'JSON_GET_FIELD_AS_INTEGER' => ['json_get_field_as_integer', Functions\JsonGetFieldAsInteger::class];
        yield 'JSON_GET_FIELD_AS_TEXT' => ['json_get_field_as_text', Functions\JsonGetFieldAsText::class];
        yield 'JSON_GET_OBJECT' => ['json_get_object', Functions\JsonGetObject::class];
        yield 'JSON_GET_OBJECT_AS_TEXT' => ['json_get_object_as_text', Functions\JsonGetObjectAsText::class];
        yield 'JSON_OBJECT_KEYS' => ['json_object_keys', Functions\JsonObjectKeys::class];
        yield 'JSON_STRIP_NULLS' => ['json_strip_nulls', Functions\JsonStripNulls::class];
        yield 'TO_JSON' => ['to_json', Functions\ToJson::class];
        yield 'JSONB_ARRAY_ELEMENTS' => ['jsonb_array_elements', Functions\JsonbArrayElements::class];
        yield 'JSONB_ARRAY_ELEMENTS_TEXT' => ['jsonb_array_elements_text', Functions\JsonbArrayElementsText::class];
        yield 'JSONB_ARRAY_LENGTH' => ['jsonb_array_length', Functions\JsonbArrayLength::class];
        yield 'JSONB_EACH' => ['jsonb_each', Functions\JsonbEach::class];
        yield 'JSONB_EACH_TEXT' => ['jsonb_each_text', Functions\JsonbEachText::class];
        yield 'JSONB_EXISTS' => ['jsonb_exists', Functions\JsonbExists::class];
        yield 'JSONB_INSERT' => ['jsonb_insert', Functions\JsonbInsert::class];
        yield 'JSONB_OBJECT_KEYS' => ['jsonb_object_keys', Functions\JsonbObjectKeys::class];
        yield 'JSONB_SET' => ['jsonb_set', Functions\JsonbSet::class];
        yield 'JSONB_STRIP_NULLS' => ['jsonb_strip_nulls', Functions\JsonbStripNulls::class];
        yield 'TO_JSONB' => ['to_jsonb', Functions\ToJsonb::class];
        yield 'TO_TSQUERY' => ['to_tsquery', Functions\ToTsquery::class];
        yield 'TO_TSVECTOR' => ['to_tsvector', Functions\ToTsvector::class];
        yield 'TSMATCH' => ['tsmatch', Functions\Tsmatch::class];
        yield 'ILIKE' => ['ilike', Functions\Ilike::class];

        yield 'DATE_PART' => ['date_part', DQL\DatePart::class];
        yield 'MAKE_DATE' => ['make_date', DQL\MakeDate::class];
        yield 'TO_CHAR' => ['to_char', DQL\ToChar::class];
    }

    #[DataProvider('providerCustomStringFunctions')]
    public function testCustomStringFunctions(string $name, string $class): void
    {
        /** @var ManagerRegistry $doctrine */
        $doctrine = self::getContainer()->get('doctrine');
        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $doctrine->getManager();

        static::assertSame($class, $em->getConfiguration()->getCustomStringFunction($name));
    }

    public static function providerILikeQuery(): iterable
    {
        yield ['basalt', '%as%'];
        yield ['basalt', '%As%'];
        yield ['basalt', '%aS%'];
        yield ['basalt', '%AS%'];

        yield ['gabbro', '%bb%'];
        yield ['gabbro', '%Bb%'];
        yield ['gabbro', '%bB%'];
        yield ['gabbro', '%BB%'];
    }

    #[Depends('testLoadFixtures')]
    #[DataProvider('providerILikeQuery')]
    public function testILikeQuery(string $title, string $query): void
    {
        /** @var JsonEntityRepository $repo */
        $repo = self::getContainer()->get(JsonEntityRepository::class);
        $entities = $repo->findAllLike($query);
        $entity = $repo->findOneLike($query);

        self::assertCount(1, $entities);
        self::assertSame($title, $entity->getTitle());
    }
}
