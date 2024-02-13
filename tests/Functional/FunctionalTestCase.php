<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class FunctionalTestCase extends KernelTestCase
{
    protected function resetSchema(): void
    {
        self::bootKernel();
        /** @var EntityManagerInterface */
        $manager = static::getContainer()->get('doctrine')->getManager();
        /** @var ClassMetadata[] $classes */
        $classes = $manager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($manager);

        @$schemaTool->dropSchema($classes);
        @$schemaTool->createSchema($classes);
    }

    protected function loadFixtures(array $groups): void
    {
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $loader = $container->get('doctrine.fixtures.loader');
        $factory = $container->get('doctrine.fixtures.purger.orm_purger_factory');
        $purger = $factory->createForEntityManager('default', $em);
        $fixtures = $loader->getFixtures($groups);

        (new ORMExecutor($em, $purger))->execute($fixtures, false);
    }
}
