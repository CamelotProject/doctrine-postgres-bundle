<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CamelotDoctrinePostgresBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
    }
}
