<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Types\Geometry;

use Camelot\DoctrinePostgres\Types\GeoJsonTrait;
use Jsor\Doctrine\PostGIS\Types\GeometryType;

final class GeoJsonType extends GeometryType
{
    use GeoJsonTrait;

    protected function getCastType(): string
    {
        return 'geometry';
    }
}
