<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DQL;

use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\BaseFunction;

class DatePart extends BaseFunction
{
    protected function customiseFunction(): void
    {
        $this->setFunctionPrototype('DATE_PART(%s, %s)');
        $this->addNodeMapping('StringPrimary');
        $this->addNodeMapping('StringPrimary');
    }
}
