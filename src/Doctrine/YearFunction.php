<?php

namespace App\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class YearFunction extends FunctionNode
{
    private $dateExpression;

    public function parse(Parser $parser): void
    {
        // Cambia Lexer::T_IDENTIFIER por TokenType::T_IDENTIFIER
        $parser->match(TokenType::T_IDENTIFIER); // Nombre de la función YEAR
        $parser->match(TokenType::T_OPEN_PARENTHESIS); // Paréntesis de apertura (
        $this->dateExpression = $parser->ArithmeticPrimary(); // La expresión de fecha
        $parser->match(TokenType::T_CLOSE_PARENTHESIS); // Paréntesis de cierre )
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'YEAR(' . $this->dateExpression->dispatch($sqlWalker) . ')';
    }
}
