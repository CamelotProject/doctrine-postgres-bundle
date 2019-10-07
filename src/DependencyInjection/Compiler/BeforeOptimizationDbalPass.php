<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection\Compiler;

use Doctrine\Common\Collections\ArrayCollection;
use MartinGeorgiev\Doctrine\DBAL\Types as PostgresTypes;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BeforeOptimizationDbalPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $arr = [
            'jsonb' => PostgresTypes\Jsonb::class,
            'jsonb[]' => PostgresTypes\JsonbArray::class,
            'smallint[]' => PostgresTypes\SmallIntArray::class,
            'integer[]' => PostgresTypes\IntegerArray::class,
            'bigint[]' => PostgresTypes\BigIntArray::class,
            'text[]' => PostgresTypes\TextArray::class,
        ];
        $types = new ArrayCollection($container->getParameter('doctrine.dbal.connection_factory.types'));
        foreach ($arr as $name => $class) {
            $types->set($name, ['class' => $class, 'commented' => false]);
        }
        $container->setParameter('doctrine.dbal.connection_factory.types', $types->toArray());
    }
}
