<?php

namespace Camelot\DoctrinePostgres\Tests\Fixtures\App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Camelot\DoctrinePostgres\Tests\Fixtures\App\Repository\JsonEntityRepository")
 */
class JsonEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var array|null
     * @ORM\Column(type="jsonb", nullable=true)
     */
    private $jsonB = [];

    /**
     * @var array|null
     * @ORM\Column(type="jsonb[]", nullable=true, name="json_b_array")
     */
    private $jsonBArray = [];

    /**
     * @var array|null
     * @ORM\Column(type="smallint[]", nullable=true)
     */
    private $smallIntArray = [];

    /**
     * @var array|null
     * @ORM\Column(type="integer[]", nullable=true)
     */
    private $integerArray = [];

    /**
     * @var array|null
     * @ORM\Column(type="bigint[]", nullable=true)
     */
    private $bigIntArray = [];

    /**
     * @var array|null
     * @ORM\Column(type="text[]", nullable=true)
     */
    private $textArray = [];

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
