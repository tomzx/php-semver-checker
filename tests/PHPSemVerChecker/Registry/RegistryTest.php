<?php

namespace PHPSemVerChecker\Test\Registry;

use Mockery as m;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Test\TestCase;

class RegistryTest extends TestCase {
	public function testAddClass()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Class_');

		$registry->addClass($node);
		$this->assertSame($node, $registry->data['class'][null]);
	}

	public function testAddFunction()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Function_');

		$registry->addFunction($node);
		$this->assertSame($node, $registry->data['function'][null]);
	}

	public function testAddInterface()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Interface_');

		$registry->addInterface($node);
		$this->assertSame($node, $registry->data['interface'][null]);
	}

	public function testAddTrait()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Trait_');

		$registry->addTrait($node);
		$this->assertSame($node, $registry->data['trait'][null]);
	}
}
