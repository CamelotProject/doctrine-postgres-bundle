<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\Tests\ProvidersTrait;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\Attributes\DataProvider;

final class TypesFunctionalTest extends FunctionalTestCase
{
    use ProvidersTrait;

    #[DataProvider('providerTypes')]
    public function testTypes(string $type, string $class): void
    {
        self::bootKernel();

        static::assertInstanceOf($class, Type::getType($type));
    }
}
