<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\Tests\DataFixtures\TestFixtures;
use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\JsonEntity;
use Camelot\DoctrinePostgres\Tests\Fixtures\Kernel;
use Camelot\DoctrinePostgres\Tests\Fixtures\Repository\JsonEntityRepository;
use Camelot\DoctrinePostgres\Tests\ProvidersTrait;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Types\Type;
use Doctrine\Persistence\ManagerRegistry;
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
    use ProvidersTrait;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    #[DataProvider('providerTypes')]
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
            '--complete' => true,
        ]);
        $display = $commandTester->getDisplay();

        $expected = [
            'jsonb_array jsonb[] DEFAULT NULL',
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

    #[DataProvider('providerStringFunctions')]
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
