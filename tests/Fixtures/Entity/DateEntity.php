<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @internal */
#[ORM\Entity()]
class DateEntity
{
    #[ORM\Column(type: 'date_immutable')]
    public \DateTimeImmutable $start;

    #[ORM\Column(type: 'date_immutable')]
    public \DateTimeImmutable $finish;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(\DateTimeImmutable $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): \DateTimeImmutable
    {
        return $this->finish;
    }

    public function setFinish(\DateTimeImmutable $finish): self
    {
        $this->finish = $finish;

        return $this;
    }
}
