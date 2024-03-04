<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DependencyInjection\Compiler;

use Doctrine\Common\Collections\ArrayCollection;
use Jsor\Doctrine\PostGIS;
use MartinGeorgiev\Doctrine\DBAL\Types as PostgresTypes;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BeforeOptimizationDbalPass implements CompilerPassInterface
{
    use ConfigTrait;

    private const TYPES_FACTORY = 'doctrine.dbal.connection_factory.types';

    private array $types = [
        'bool[]' => PostgresTypes\BooleanArray::class,
        'jsonb[]' => PostgresTypes\JsonbArray::class,
        'smallint[]' => PostgresTypes\SmallIntArray::class,
        'integer[]' => PostgresTypes\IntegerArray::class,
        'bigint[]' => PostgresTypes\BigIntArray::class,
        'text[]' => PostgresTypes\TextArray::class,
        'geography' => PostGIS\Types\GeographyType::class,
        'geometry' => PostGIS\Types\GeometryType::class,
    ];

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(self::TYPES_FACTORY)) {
            return;
        }

        $config = $this->getConfig($container);
        $types = new ArrayCollection($container->getParameter(self::TYPES_FACTORY));

        foreach ($config['types'] as $name => $enabled) {
            if (!isset($this->types[$name])) {
                throw new \RuntimeException(sprintf('Unknown DBAL type "%s"', $name));
            }

            if ($enabled && !$types->containsKey($name)) {
                $types->set($name, ['class' => $this->types[$name]]);
            }
        }
        $container->setParameter(self::TYPES_FACTORY, $types->toArray());
    }
}
