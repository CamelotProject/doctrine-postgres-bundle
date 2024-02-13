<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\Tests\Fixtures\Entity\TextEntity;
use PHPUnit\Framework\Attributes\DataProvider;

/** @internal */
final class TextFunctionalTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->resetSchema();
        $this->loadFixtures(['text']);
    }

    public static function providerILikeQuery(): iterable
    {
        yield ['Basalt', '%as%'];
        yield ['Basalt', '%As%'];
        yield ['Basalt', '%aS%'];
        yield ['Basalt', '%AS%'];

        yield ['Gabbro', '%bb%'];
        yield ['Gabbro', '%Bb%'];
        yield ['Gabbro', '%bB%'];
        yield ['Gabbro', '%BB%'];
    }

    #[DataProvider('providerILikeQuery')]
    public function testILikeQuery(string $title, string $query): void
    {
        $entities = $this->findILike($query, false);
        $entity = $this->findILike($query, true);

        self::assertCount(1, $entities);
        self::assertSame($title, $entity->getTitle());
        self::assertContains($entity, $entities);
    }

    private function findILike(string $value, bool $single): array|TextEntity
    {
        $container = self::getContainer();
        $repo = $container->get('doctrine')->getRepository(TextEntity::class);
        $query = $repo->createQueryBuilder('o')
            ->andWhere('ILIKE(o.title, :val) = TRUE')
            ->setParameter('val', $value)
            ->getQuery()
        ;

        if ($single) {
            return $query->getSingleResult();
        }

        return $query->getResult();
    }
}
