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
		$node->name = m::mock('\PhpParser\Node\Identifier');
		$node->name->shouldReceive('toString')->andReturn('SomeClass');

		$registry->addClass($node);
		$this->assertSame($node, $registry->data['class']['SomeClass']);
	}

	public function testAddFunction()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Function_');
		$node->name = m::mock('\PhpParser\Node\Identifier');
		$node->name->shouldReceive('toString')->andReturn('SomeFunction');

		$registry->addFunction($node);
		$this->assertSame($node, $registry->data['function']['SomeFunction']);
	}

	public function testAddInterface()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Interface_');
		$node->name = m::mock('\PhpParser\Node\Identifier');
		$node->name->shouldReceive('toString')->andReturn('SomeInterface');

		$registry->addInterface($node);
		$this->assertSame($node, $registry->data['interface']['SomeInterface']);
	}

	public function testAddTrait()
	{
		$registry = new Registry();

		$node = m::mock('\PhpParser\Node\Stmt\Trait_');
		$node->name = m::mock('\PhpParser\Node\Identifier');
		$node->name->shouldReceive('toString')->andReturn('SomeTrait');

		$registry->addTrait($node);
		$this->assertSame($node, $registry->data['trait']['SomeTrait']);
	}
}
