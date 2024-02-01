<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DQL;

use Camelot\DoctrinePostgres\DQL\ToChar;
use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\DateEntity;
use PHPUnit\Framework\Attributes\CoversClass;

/** @internal */
#[CoversClass(ToChar::class)]
final class ToCharTest extends TestCase
{
    protected function getStringFunctions(): array
    {
        return [
            'TO_CHAR' => ToChar::class,
        ];
    }

    protected function getExpectedSqlStatements(): array
    {
        return [
            "SELECT d0_.start AS start_0, d0_.finish AS finish_1 FROM DateEntity d0_ WHERE TO_CHAR(d0_.start, 'DD-MM-YYYY') = '31-12-1999'",
        ];
    }

    protected function getDqlStatements(): array
    {
        return [
            sprintf("SELECT e.start, e.finish FROM %s e WHERE TO_CHAR(e.start, 'DD-MM-YYYY') = '31-12-1999'", DateEntity::class),
        ];
    }
}
