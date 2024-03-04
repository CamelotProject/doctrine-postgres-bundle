<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

trait GeoJsonTrait
{
    public function getName(): string
    {
        return 'geojson';
    }

    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return sprintf('ST_AsGeoJSON(%s)', $sqlExpr);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value ?? null;
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return sprintf('ST_GeomFromGeoJSON(%s)::%s', $sqlExpr, $this->getCastType());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return parent::convertToDatabaseValue(json_encode($value), $platform);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var array{geometry_type?: string|null, srid?: int|string|null} $column */
        $options = $this->getNormalizedPostGISColumnOptions($column);

        return sprintf(
            '%s(%s, %d)',
            $this->getCastType(),
            $options['geometry_type'],
            $options['srid']
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    abstract protected function getCastType(): string;
}
