<?php

namespace DoctrineExtensions\Query\Postgresql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class Age
 * @package DoctrineExtensions\Query\Postgresql
 */
class Age extends FunctionNode
{
    public $date1 = null;

    public $date2 = null;

    /**
     * @override
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        $result = 'AGE(' . $this->date1->dispatch($sqlWalker);

        if ($this->date2) {
            $result .= ', ' . $this->date2->dispatch($sqlWalker);
        }

        $result .= ')';

        return $result;
    }

    /**
     * @override
     * @param Parser $parser
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->date1 = $parser->ArithmeticPrimary();

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->date2 = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
