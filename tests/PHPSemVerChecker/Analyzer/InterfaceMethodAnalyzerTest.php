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
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertNoDifference($report);
	}

	public function testV035PublicClassMethodRemoved()
	{
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$interfaceAfter = new Interface_('tmp');

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Method has been removed.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['interface'][Level::MAJOR][0]->getTarget());
	}

	public function testV034PublicClassMethodAdded()
	{
		$interfaceBefore = new Interface_('tmp');

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod'),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Method has been added.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['interface'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarPublicClassMethodSignature()
	{
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertNoDifference($report, 'interface');
	}

	public function testV063CompareSimilarPublicClassMethodWithDifferentSignatureVariables()
	{
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null),
					],
				]),
			],
		]);

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertDifference($report, 'interface', Level::PATCH);
		$this->assertSame('Method parameter name changed.', $report['interface'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['interface'][Level::PATCH][0]->getTarget());
	}

	public function testV036CompareSimilarPublicClassMethodWithDifferentSignatureTypehint()
	{
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
					],
				]),
			],
		]);

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Method parameter changed.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['interface'][Level::MAJOR][0]->getTarget());
	}

	public function testV036CompareSimilarPublicClassMethodWithDifferentSignatureLength()
	{
		$interfaceBefore = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('a', null, 'A'),
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$interfaceAfter = new Interface_('tmp', [
			'stmts' => [
				new ClassMethod('tmpMethod', [
					'params' => [
						new Param('b', null, 'B'),
					],
				]),
			],
		]);

		$analyzer = new ClassMethodAnalyzer('interface');
		$report = $analyzer->analyze($interfaceBefore, $interfaceAfter);

		Assert::assertDifference($report, 'interface', Level::MAJOR);
		$this->assertSame('Method parameter changed.', $report['interface'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['interface'][Level::MAJOR][0]->getTarget());
	}
}
