<?php

namespace Camelot\DoctrinePostgres\Tests\Fixtures\Entity;

use Camelot\DoctrinePostgres\Tests\Fixtures\Repository\JsonEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JsonEntityRepository::class)]
class JsonEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title;

    #[ORM\Column(type: 'jsonb', nullable: true)]
    private ?array $jsonB = [];

    #[ORM\Column(name: 'json_b_array', type: 'jsonb[]', nullable: true)]
    private ?array $jsonBArray = [];

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getJsonB(): ?array
    {
        return $this->jsonB;
    }

    public function setJsonB(?array $jsonB): self
    {
        $this->jsonB = $jsonB;

        return $this;
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
