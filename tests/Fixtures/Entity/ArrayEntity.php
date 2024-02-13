<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @internal */
#[ORM\Entity()]
class ArrayEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(name: 'jsonb_array', type: 'jsonb[]', nullable: true)]
    private ?array $jsonBArray = [];

    #[ORM\Column(type: 'bool[]', nullable: true)]
    private ?array $boolArray = [];

    #[ORM\Column(type: 'smallint[]', nullable: true)]
    private ?array $smallIntArray = [];

    #[ORM\Column(type: 'integer[]', nullable: true)]
    private ?array $integerArray = [];

    #[ORM\Column(type: 'bigint[]', nullable: true)]
    private ?array $bigIntArray = [];

    #[ORM\Column(type: 'text[]', nullable: true)]
    private ?array $textArray = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJsonBArray(): ?array
    {
        return $this->jsonBArray;
    }

    public function setJsonBArray(?array $jsonBArray): self
    {
        $this->jsonBArray = $jsonBArray;

        return $this;
    }

    public function getBoolArray(): ?array
    {
        return $this->boolArray;
    }

    public function setBoolArray(?array $boolArray): self
    {
        $this->boolArray = $boolArray;

        return $this;
    }

    public function getSmallIntArray(): ?array
    {
        return $this->smallIntArray;
    }

    public function setSmallIntArray(?array $smallIntArray): self
    {
        $this->smallIntArray = $smallIntArray;

        return $this;
    }

    public function getIntegerArray(): ?array
    {
        return $this->integerArray;
    }

    public function setIntegerArray(?array $integerArray): self
    {
        $this->integerArray = $integerArray;

        return $this;
    }

    public function getBigIntArray(): ?array
    {
        return $this->bigIntArray;
    }

    public function setBigIntArray(?array $bigIntArray): self
    {
        $this->bigIntArray = $bigIntArray;

        return $this;
    }

    public function getTextArray(): ?array
    {
        return $this->textArray;
    }

    public function setTextArray(?array $textArray): self
    {
        $this->textArray = $textArray;

        return $this;
    }
}
