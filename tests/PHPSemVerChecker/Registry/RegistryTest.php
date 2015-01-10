<?php

namespace PHPSemVerChecker\Test;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Registry\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase {

	protected function assertDifferences($differences, $fn = 0, $fp = 0, $fmi = 0, $fma = 0, $cn = 0, $cp = 0, $cmi = 0, $cma = 0)
	{
		$this->assertCount($fn, $differences['function'][Registry::NONE], 'Function None count does not match');
		$this->assertCount($fp, $differences['function'][Registry::PATCH], 'Function Patch count does not match');
		$this->assertCount($fmi, $differences['function'][Registry::MINOR], 'Function Minor count does not match');
		$this->assertCount($fma, $differences['function'][Registry::MAJOR], 'Function Major count does not match');
		$this->assertCount($cn, $differences['class'][Registry::NONE], 'Class None count does not match');
		$this->assertCount($cp, $differences['class'][Registry::PATCH], 'Class Patch count does not match');
		$this->assertCount($cmi, $differences['class'][Registry::MINOR], 'Class Minor count does not match');
		$this->assertCount($cma, $differences['class'][Registry::MAJOR], 'Class Major count does not match');
	}

	public function testCompareEmptyRegistries()
	{
		$before = new Registry();
		$after = new Registry();

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareMissingClass()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addClass(new Class_('tmp'));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Class was removed.', $differences['class'][Registry::MAJOR][0]['reason']);
	}

	public function testCompareNewClass()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addClass(new Class_('tmp'));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Class was added.', $differences['class'][Registry::MINOR][0]['reason']);
	}

	public function testCompareSimilarClasses()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp');
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp');
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareSimilarClassMethod()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp'),
			],
		]);
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp'),
			],
		]);
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareSimilarClassMethodWithDifferentSignature()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Method parameter mismatch.', $differences['class'][Registry::MAJOR][0]['reason']);
	}

	public function testCompareMissingClassMethod()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp'),
			],
		]);
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp');
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Method has been removed.', $differences['class'][Registry::MAJOR][0]['reason']);
	}

	public function testCompareNewClassMethod()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp');
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp'),
			],
		]);
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Method has been added.', $differences['class'][Registry::MINOR][0]['reason']);
	}

	public function testCompareSimilarClassMethodImplementation()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareDifferentClassMethodImplementation()
	{
		$before = new Registry();
		$after = new Registry();

		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmp', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someOtherMethod'),
					],
				]),
			],
		]);
		$after->addClass($afterClass);

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 0, 0, 1);
		$this->assertSame('Method implementation changed.', $differences['class'][Registry::PATCH][0]['reason']);
	}

	public function testCompareMissingFunction()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp'));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 1);
		$this->assertSame('Function has been removed.', $differences['function'][Registry::MAJOR][0]['reason']);
	}

	public function testCompareNewFunction()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addFunction(new Function_('tmp'));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 1);
		$this->assertSame('Function has been added.', $differences['function'][Registry::MINOR][0]['reason']);
	}

	public function testCompareSimilarFunction()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp'));

		$after->addFunction(new Function_('tmp'));

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareSimilarFunctionWithDifferentSignature()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'B'),
			],
		]));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 0, 0, 1);
		$this->assertSame('Function parameter mismatch.', $differences['function'][Registry::MAJOR][0]['reason']);
	}

	public function testCompareSimilarFunctionImplementation()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'stmts' => [
				new FuncCall('someFunction'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'stmts' => [
				new FuncCall('someFunction'),
			],
		]));

		$differences = $before->compare($after);

		$this->assertDifferences($differences);
	}

	public function testCompareDifferentFunctionImplementation()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'stmts' => [
				new FuncCall('someFunction'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'stmts' => [
				new FuncCall('someOtherFunction'),
			],
		]));

		$differences = $before->compare($after);

		$this->assertDifferences($differences, 0, 1);
		$this->assertSame('Function implementation changed.', $differences['function'][Registry::PATCH][0]['reason']);
	}
}