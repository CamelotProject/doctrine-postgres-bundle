<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DependencyInjection\Compiler;

use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationOrmPass;
use Camelot\DoctrinePostgres\Tests\ProvidersTrait;
use Doctrine\ORM\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @internal */
#[CoversClass(BeforeOptimizationOrmPass::class)]
final class BeforeOptimizationOrmPassTest extends KernelTestCase
{
    use ProvidersTrait;

    #[DataProvider('providerStringFunctions')]
    public function testProcess(string $name, string $class): void
    {
        $container = self::getContainer();
        /** @var Configuration $configuration */
        $configuration = $container->get('doctrine.orm.default_configuration');

        self::assertSame($class, $configuration->getCustomStringFunction($name));
    }
}
