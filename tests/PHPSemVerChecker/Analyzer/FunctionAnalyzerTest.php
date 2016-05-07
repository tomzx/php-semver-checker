<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Analyzer\FunctionAnalyzer;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class FunctionAnalyzerTest extends TestCase {
	public function testSimilarFunction()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp'));

		$after->addFunction(new Function_('tmp'));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testFunctionRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp'));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V001', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function has been removed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testFunctionAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addFunction(new Function_('tmp'));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V003', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function has been added.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionSignature()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testSimilarFunctionWithDifferentParameterName()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('b', null),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::PATCH;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V067', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter name changed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithParameterAdded()
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
				new Param('a', null, 'A'),
				new Param('b', null, 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V002', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter added.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithParameterRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('b', null, 'B'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V068', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter removed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}


	public function testSimilarFunctionWithParameterTypehintAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V069', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter typing added.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithParameterTypehintRemoved()
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
				new Param('a', null),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V070', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter typing removed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithDefaultParameterAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('b', null, 'B'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('b', new String_('someDefaultValue'), 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V071', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter default added.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithDefaultParameterRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('b', new String_('someDefaultValue'), 'B'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('a', null, 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V072', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter default removed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithDefaultParameterValueChanged()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('b', new String_('someDefaultValue'), 'B'),
			],
		]));

		$after->addFunction(new Function_('tmp', [
			'params' => [
				new Param('a', null, 'A'),
				new Param('a', new String_('someNewDefaultValue'), 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V073', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter default value changed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}

	public function testSimilarFunctionWithParameterAddedWithDefault()
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
				new Param('a', null, 'A'),
				new Param('a', new String_('someDefaultValue'), 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		// TODO(tom@tomrochette.com): At some point, we may want to consider new parameter with default as less impactful
		// than new parameter without default
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V002', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function parameter added.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}


	public function testSimilarFunctionImplementation()
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

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testFunctionImplementationChanged()
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

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$expectedLevel = Level::PATCH;
		Assert::assertDifference($report, 'function', $expectedLevel);
		$this->assertSame('V004', $report['function'][$expectedLevel][0]->getCode());
		$this->assertSame('Function implementation changed.', $report['function'][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report['function'][$expectedLevel][0]->getTarget());
	}
}
