<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DataFixtures;

use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\JsonEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = new JsonEntity();
            $entity->setTitle($data['title']);
            $entity->setJsonB($data['jsonb_data']);
            $entity->setBoolArray($data['bool_data']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function getData(): iterable
    {
        yield [
            'title' => 'basalt',
            'jsonb_data' => [
                ['age' => 22, 'score' => 2],
                ['age' => 44, 'score' => 4],
                ['age' => 66, 'score' => 6],
            ],
            'bool_data' => [
                true, false,
            ],
        ];
        yield [
            'title' => 'gabbro',
            'jsonb_data' => [
                ['age' => 11, 'score' => 1],
                ['age' => 33, 'score' => 3],
                ['age' => 55, 'score' => 5],
            ],
            'bool_data' => [
                false, true,
            ],
        ];
    }
}
