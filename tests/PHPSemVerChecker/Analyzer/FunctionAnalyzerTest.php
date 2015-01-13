<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Param;
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

	public function testV001FunctionRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addFunction(new Function_('tmp'));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'function', Level::MAJOR);
		$this->assertSame('Function has been removed.', $report['function'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::MAJOR][0]->getTarget());
	}

	public function testV003FunctionAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addFunction(new Function_('tmp'));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'function', Level::MINOR);
		$this->assertSame('Function has been added.', $report['function'][Level::MINOR][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::MINOR][0]->getTarget());
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

	public function testV002SimilarFunctionWithDifferentSignatureVariables()
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

		Assert::assertDifference($report, 'function', Level::PATCH);
		$this->assertSame('Function parameter changed.', $report['function'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::PATCH][0]->getTarget());
	}

	public function testV002SimilarFunctionWithDifferentSignatureTypehint()
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

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'function', Level::MAJOR);
		$this->assertSame('Function parameter changed.', $report['function'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::MAJOR][0]->getTarget());
	}

	public function testV002SimilarFunctionWithDifferentSignatureLength()
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
				new Param('a', null, 'B'),
			],
		]));

		$analyzer = new FunctionAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'function', Level::MAJOR);
		$this->assertSame('Function parameter changed.', $report['function'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::MAJOR][0]->getTarget());
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

	public function testV004FunctionImplementationChanged()
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

		Assert::assertDifference($report, 'function', Level::PATCH);
		$this->assertSame('Function implementation changed.', $report['function'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp', $report['function'][Level::PATCH][0]->getTarget());
	}
}
