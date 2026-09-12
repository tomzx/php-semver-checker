<?php

namespace PHPSemVerChecker\Test\Comparator;

use PhpParser\ParserFactory;
use PHPSemVerChecker\Comparator\Node;
use PHPSemVerChecker\Test\TestCase;

class NodeTest extends TestCase
{
	/**
	 * @dataProvider isEqualProvider
	 */
	public function testIsEqual($codeA, $codeB)
	{
		$this->assertTrue(Node::isEqual($this->parseExpression($codeA), $this->parseExpression($codeB)));
	}

	public function isEqualProvider()
	{
		return [
			'scalar' => ['1', '1'],
			'string' => ["'*'", "'*'"],
			'array' => ["['*']", "['*']"],
			'empty array' => ['[]', '[]'],
			'array with multiple items' => ["['*', 'id']", "['*', 'id']"],
			'nested array' => ['[[1, 2], [3]]', '[[1, 2], [3]]'],
			'associative array' => ["['a' => 1, 'b' => 2]", "['a' => 1, 'b' => 2]"],
			'array containing a constant' => ['[self::FIRST, self::SECOND]', '[self::FIRST, self::SECOND]'],
			'array with a trailing comma' => ["['*']", "['*',]"],
			'array using the long syntax' => ["array('*')", "array('*')"],
		];
	}

	/**
	 * @dataProvider isNotEqualProvider
	 */
	public function testIsNotEqual($codeA, $codeB)
	{
		$this->assertFalse(Node::isEqual($this->parseExpression($codeA), $this->parseExpression($codeB)));
	}

	public function isNotEqualProvider()
	{
		return [
			'scalar' => ['1', '2'],
			'array value' => ["['*']", "['id']"],
			'array emptied' => ["['*']", '[]'],
			'array filled' => ['[]', "['*']"],
			'array item added' => ["['*']", "['*', 'id']"],
			'array item removed' => ["['*', 'id']", "['*']"],
			'array item order' => ["['*', 'id']", "['id', '*']"],
			'nested array value' => ['[[1, 2], [3]]', '[[1, 2], [4]]'],
			'nested array depth' => ['[[1]]', '[1]'],
			'associative array key' => ["['a' => 1]", "['b' => 1]"],
			'associative array value' => ["['a' => 1]", "['a' => 2]"],
			'array key added' => ["[1]", "['a' => 1]"],
			'array item unpacked' => ["['*']", "[...['*']]"],
			'array against scalar' => ["['*']", "'*'"],
			'array containing a constant' => ['[self::FIRST]', '[self::SECOND]'],
		];
	}

	public function testIsEqualIgnoresLineNumbers()
	{
		$nodeA = $this->parseExpression("['*']");
		$nodeB = $this->parseExpression("['*']", 5);

		$this->assertSame(1, $nodeA->getLine());
		$this->assertSame(5, $nodeB->getLine());
		$this->assertTrue(Node::isEqual($nodeA, $nodeB));
	}

	public function testIsEqualIgnoresComments()
	{
		$nodeA = $this->parseExpression("['*']");
		$nodeB = $this->parseExpression("/* a comment */ ['*']");

		$this->assertTrue(Node::isEqual($nodeA, $nodeB));
	}

	/**
	 * @param string $code
	 * @param int $line
	 * @return \PhpParser\Node\Expr
	 */
	protected function parseExpression($code, $line = 1)
	{
		$parser = (new ParserFactory())->createForNewestSupportedVersion();
		$statements = $parser->parse('<?php ' . str_repeat("\n", $line - 1) . $code . ';');

		return $statements[0]->expr;
	}
}
