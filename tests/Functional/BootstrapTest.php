<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Functional;

use Camelot\DoctrinePostgres\Tests\Fixtures\App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class BootstrapTest extends KernelTestCase
{
    public function testCleanUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('doctrine:schema:drop');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([
            'command' => $command->getName(),
            '--full-database' => true,
            '--force' => true,
        ]);

        self::assertSame(0, $result);
    }

    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
