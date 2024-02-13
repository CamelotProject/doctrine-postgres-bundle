<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DataFixtures;

use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\ArrayEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class ArrayFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['array'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = new ArrayEntity();

//            $entity->setJsonBArray($data['jsonb']);
            $entity->setBoolArray($data['bool']);
            $entity->setSmallIntArray($data['int2']);
//            $entity->setIntegerArray($data['int4']);
            $entity->setBigIntArray($data['int8']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function getData(): iterable
    {
        yield [
            'jsonb' => [
                ['age' => 22, 'score' => 2],
                ['age' => 44, 'score' => 4],
                ['age' => 66, 'score' => 6],
            ],
            'bool' => [
                true, false,
            ],
            'int2' => [
                1, 2, 3, 4, 5, 6, 7, 8,
            ],
            'int8' => [
                -(2 ** 60),
                2 ** 60,
            ],
        ];
        yield [
            'jsonb' => [
                ['age' => 11, 'score' => 1],
                ['age' => 33, 'score' => 3],
                ['age' => 55, 'score' => 5],
            ],
            'bool' => [
                false, true,
            ],
            'int2' => [
                -32768, 32767,
            ],
            'int8' => [
                -(2 ** 62),
                2 ** 62,
            ],
        ];
    }
}
