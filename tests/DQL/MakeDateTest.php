<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DQL;

use Camelot\DoctrinePostgres\DQL\MakeDate;
use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\DateEntity;
use PHPUnit\Framework\Attributes\CoversClass;

/** @internal */
#[CoversClass(MakeDate::class)]
final class MakeDateTest extends TestCase
{
    protected function getStringFunctions(): array
    {
        return [
            'MAKE_DATE' => MakeDate::class,
        ];
    }

    protected function getExpectedSqlStatements(): array
    {
        return [
            'SELECT d0_.start AS start_0, d0_.finish AS finish_1 FROM DateEntity d0_ WHERE d0_.start = MAKE_DATE(1999, 12, 31)',
        ];
    }

    protected function getDqlStatements(): array
    {
        return [
            sprintf('SELECT e.start, e.finish FROM %s e WHERE e.start = MAKE_DATE(1999, 12, 31)', DateEntity::class),
        ];
    }
}
