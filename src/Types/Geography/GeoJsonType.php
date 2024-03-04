<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Types\Geography;

use Camelot\DoctrinePostgres\Types\GeoJsonTrait;
use Jsor\Doctrine\PostGIS\Types\GeographyType;

final class GeoJsonType extends GeographyType
{
    use GeoJsonTrait;

    protected function getCastType(): string
    {
        return 'geography';
    }
}
