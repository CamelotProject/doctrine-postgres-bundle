<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DependencyInjection\Compiler;

use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationDbalPass;
use Camelot\DoctrinePostgres\Tests\ProvidersTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @internal */
#[CoversClass(BeforeOptimizationDbalPass::class)]
final class BeforeOptimizationDbalPassTest extends KernelTestCase
{
    use ProvidersTrait;

    #[DataProvider('providerTypes')]
    public function testProcess(string $type, string $class): void
    {
        $container = self::getContainer();
        $types = $container->getParameter('doctrine.dbal.connection_factory.types');

        self::assertArrayHasKey($type, $types);
        self::assertSame($class, $types[$type]['class']);
    }
}
