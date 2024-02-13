<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\DataFixtures;

use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\DateEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class DateFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['date'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = new DateEntity();
            $entity->setStart($data['start']);
            $entity->setFinish($data['finish']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    private function getData(): iterable
    {
        yield [
            'start' => new \DateTimeImmutable('1999-01-01 00:00'),
            'finish' => new \DateTimeImmutable('1999-12-31 00:00'),
        ];
        yield [
            'start' => new \DateTimeImmutable('2021-03-13 00:00'),
            'finish' => new \DateTimeImmutable('2021-04-14 00:00'),
        ];
    }
}
