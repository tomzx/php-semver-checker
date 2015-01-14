<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Analyzer\ClassMethodAnalyzer;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class InterfaceMethodAnalyzerTest extends TestCase {
	public function testCompareSimilarPublicClassMethod()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$afterClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertNoDifference($report);
	}

	public function testV035PublicClassMethodRemoved()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$afterClass = new Interface_('tmp');

		$analyzer = new ClassMethodAnalyzer();
		$report = $analyzer->analyze($beforeClass, $afterClass);

		Assert::assertDifference($report, 'method', Level::MAJOR);
		$this->assertSame('Method has been removed.', $report['method'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['method'][Level::MAJOR][0]->getTarget());
	}

	public function testV034PublicClassMethodAdded()
	{
		$beforeClass = new Interface_('tmp');

		$afterClass = new Interface_('tmp', [
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

	public function testSimilarPublicClassMethodSignature()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$afterClass = new Interface_('tmp', [
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

	public function testV036CompareSimilarPublicClassMethodWithDifferentSignatureVariables()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$afterClass = new Interface_('tmp', [
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

	public function testV036CompareSimilarPublicClassMethodWithDifferentSignatureTypehint()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$afterClass = new Interface_('tmp', [
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

	public function testV036CompareSimilarPublicClassMethodWithDifferentSignatureLength()
	{
		$beforeClass = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$afterClass = new Interface_('tmp', [
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
}
