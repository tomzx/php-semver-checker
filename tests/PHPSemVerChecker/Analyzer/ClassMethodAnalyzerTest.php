<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Analyzer\ClassMethodAnalyzer;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class ClassMethodAnalyzerTest extends TestCase {
	public function testCompareSimilarClassMethod()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report);
	}

	public function testV006PublicClassMethodRemoved()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$classAfter = new Class_('tmp');

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('[public] Method has been removed.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testV015ClassMethodAdded()
	{
		$classBefore = new Class_('tmp');

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('[public] Method has been added.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarClassMethodSignature()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report, 'class');
	}

	public function testV060CompareSimilarClassMethodWithDifferentSignatureVariables()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::PATCH);
		$this->assertSame('[public] Method parameter name changed.', $report['class'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::PATCH][0]->getTarget());
	}

	public function testV010CompareSimilarClassMethodWithDifferentSignatureTypehint()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('[public] Method parameter changed.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testCompareSimilarClassMethodWithDifferentSignatureLength()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('[public] Method parameter changed.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarClassMethodImplementation()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report);
	}

	public function testV023ClassMethodImplementationChanged()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someOtherMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::PATCH);
		$this->assertSame('[public] Method implementation changed.', $report['class'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['class'][Level::PATCH][0]->getTarget());
	}
}
