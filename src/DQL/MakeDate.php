<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DQL;

use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\BaseFunction;

class MakeDate extends BaseFunction
{
    protected function customiseFunction(): void
    {
        $this->setFunctionPrototype('MAKE_DATE(%s, %s, %s)');
        $this->addNodeMapping('ArithmeticPrimary');
        $this->addNodeMapping('ArithmeticPrimary');
        $this->addNodeMapping('ArithmeticPrimary');
    }
}
