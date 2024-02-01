<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DQL;

use Camelot\DoctrinePostgres\DQL\DatePart;
use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\DateEntity;
use PHPUnit\Framework\Attributes\CoversClass;

/** @internal */
#[CoversClass(DatePart::class)]
final class DatePartTest extends TestCase
{
    protected function getStringFunctions(): array
    {
        return [
            'DATE_PART' => DatePart::class,
        ];
    }

    protected function getExpectedSqlStatements(): array
    {
        return [
            "SELECT DATE_PART('DAY', d0_.start) AS sclr_0 FROM DateEntity d0_",
        ];
    }

    protected function getDqlStatements(): array
    {
        return [
            \sprintf("SELECT DATE_PART('DAY', e.start) FROM %s e", DateEntity::class),
        ];
    }
}
