<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres;

use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationDbalPass;
use Camelot\DoctrinePostgres\DependencyInjection\Compiler\BeforeOptimizationOrmPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CamelotDoctrinePostgresBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BeforeOptimizationDbalPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass(new BeforeOptimizationOrmPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
