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
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertNoDifference($report);
	}

	public function testV006PublicClassMethodRemoved()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$afterClass = new Class_('tmp');

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::MAJOR);
		$this->assertSame('Method has been removed.', $report['method'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::MAJOR][0]->getTarget());
	}

	public function testV015ClassMethodAdded()
	{
		$beforeClass = new Class_('tmp');

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::MINOR);
		$this->assertSame('Method has been added.', $report['method'][Level::MINOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::MINOR][0]->getTarget());
	}

	public function testSimilarClassMethodSignature()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertNoDifference($report, 'method');
	}

	public function testCompareSimilarClassMethodWithDifferentSignatureVariables()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::PATCH);
		$this->assertSame('Method parameter changed.', $report['method'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::PATCH][0]->getTarget());
	}

	public function testCompareSimilarClassMethodWithDifferentSignatureTypehint()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::MAJOR);
		$this->assertSame('Method parameter changed.', $report['method'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::MAJOR][0]->getTarget());
	}

	public function testCompareSimilarClassMethodWithDifferentSignatureLength()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::MAJOR);
		$this->assertSame('Method parameter changed.', $report['method'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarClassMethodImplementation()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertNoDifference($report);
	}

	public function testV023ClassMethodImplementationChanged()
	{
		$beforeClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
			],
		]);

		$afterClass = new Class_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someOtherMethod'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::PATCH);
		$this->assertSame('Method implementation changed.', $report['method'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::PATCH][0]->getTarget());
	}
}
