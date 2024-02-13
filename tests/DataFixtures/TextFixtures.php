<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DataFixtures;

use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\TextEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class TextFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['text'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = new TextEntity();
            $entity->setTitle($data['title']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function getData(): iterable
    {
        yield [
            'title' => 'Basalt',
        ];
        yield [
            'title' => 'Gabbro',
        ];
    }
}
