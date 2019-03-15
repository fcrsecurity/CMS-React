<?php

namespace CraftKeen\Bundle\ComponentBundle\Tests\Unit\DQL\Functions;

use CraftKeen\Bundle\ComponentBundle\DQL\Functions\RandFunction;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class RandFunctionTest extends \PHPUnit_Framework_TestCase
{
    /** @var RandFunction */
    protected $function;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->function = new RandFunction('rand');
    }

    public function testGetSql()
    {
        $this->assertEquals('RAND()', $this->function->getSql($this->createMock(SqlWalker::class)));
    }
    public function testParse()
    {
        $parser = $this->createMock(Parser::class);
        $parser->expects($this->at(0))->method('match')->with(Lexer::T_IDENTIFIER);
        $parser->expects($this->at(1))->method('match')->with(Lexer::T_OPEN_PARENTHESIS);
        $parser->expects($this->at(2))->method('match')->with(Lexer::T_CLOSE_PARENTHESIS);
        $this->function->parse($parser);
    }
}
