<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/** @internal */
#[ORM\Entity()]
class DateEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'date_immutable')]
    public DateTimeImmutable $start;

    #[ORM\Column(type: 'date_immutable')]
    public DateTimeImmutable $end;
}
