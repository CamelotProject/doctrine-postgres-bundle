<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use MartinGeorgiev\Doctrine\ORM\Query\AST\Functions\BaseFunction;

class Cast extends BaseFunction
{
    protected function customiseFunction(): void
    {
        $this->setFunctionPrototype('CAST(%s AS %s)');
        $this->addNodeMapping('StringPrimary');
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    protected function feedParserWithNodes(Parser $parser): void
    {
        $nodesMappingCount = \count($this->nodesMapping);
        $lastNode = $nodesMappingCount - 1;
        for ($i = 0; $i < $nodesMappingCount; $i++) {
            $parserMethod = $this->nodesMapping[$i];
            $this->nodes[$i] = $parser->{$parserMethod}();
            if ($i < $lastNode) {
                $parser->match(Lexer::T_AS);
            }
        }
    }
}
