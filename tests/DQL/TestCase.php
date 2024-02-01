<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DQL;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @internal
 *
 * Adapted from \Tests\MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\TestCase
 */
abstract class TestCase extends BaseTestCase
{
    protected const FIXTURES_DIRECTORY = __DIR__ . '/../Fixtures/Entity';

    private Configuration $configuration;

    protected function setUp(): void
    {
        $configuration = ORMSetup::createAttributeMetadataConfiguration([static::FIXTURES_DIRECTORY]);
        $configuration->setProxyDir(static::FIXTURES_DIRECTORY . '/Proxies');
        $configuration->setProxyNamespace('Camelot\DoctrinePostgres\Tests\Fixtures\Entity\Proxy');
        $configuration->setAutoGenerateProxyClasses(true);
        $this->setConfigurationCache($configuration);

        $this->configuration = $configuration;

        $this->registerFunction();
    }

    #[Test]
    public function dql_is_transformed_to_valid_sql(): void
    {
        $expectedSqls = $this->getExpectedSqlStatements();
        $dqls = $this->getDqlStatements();
        if (\count($expectedSqls) !== \count($dqls)) {
            throw new \LogicException(\sprintf('You need ot provide matching expected SQL for every DQL, currently there are %d SQL statements for %d DQL statements', \count($expectedSqls), \count($dqls)));
        }
        foreach ($expectedSqls as $key => $expectedSql) {
            $this->assertSqlFromDql($expectedSql, $dqls[$key], \sprintf('Assertion failed for expected SQL statement "%s"', $expectedSql));
        }
    }

    /** @return array<string, string> */
    protected function getStringFunctions(): array
    {
        return [];
    }

    /** @return array<int, string> */
    abstract protected function getExpectedSqlStatements(): array;

    /** @return array<int, string> */
    abstract protected function getDqlStatements(): array;

    private function setConfigurationCache(Configuration $configuration): void
    {
        $symfonyArrayAdapterClass = '\\' . ArrayAdapter::class;
        $configuration->setMetadataCache(new $symfonyArrayAdapterClass());
        $configuration->setQueryCache(new $symfonyArrayAdapterClass());
    }

    private function registerFunction(): void
    {
        /** @var class-string<FunctionNode> $functionClassName */
        foreach ($this->getStringFunctions() as $dqlFunction => $functionClassName) {
            $this->configuration->addCustomStringFunction($dqlFunction, $functionClassName);
        }
    }

    private function assertSqlFromDql(string $expectedSql, string $dql, string $message = ''): void
    {
        $query = $this->buildEntityManager()->createQuery($dql);
        self::assertSame($expectedSql, $query->getSQL(), $message);
    }

    private function buildEntityManager(): EntityManager
    {
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true], $this->configuration);

        return new EntityManager($connection, $this->configuration);
    }
}
