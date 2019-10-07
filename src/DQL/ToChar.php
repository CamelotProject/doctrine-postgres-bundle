<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DQL;

use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\BaseFunction;

class ToChar extends BaseFunction
{
    protected function customiseFunction(): void
    {
        $this->setFunctionPrototype('TO_CHAR(%s, %s)');
        $this->addNodeMapping('StringPrimary');
        $this->addNodeMapping('StringPrimary');
    }
}
