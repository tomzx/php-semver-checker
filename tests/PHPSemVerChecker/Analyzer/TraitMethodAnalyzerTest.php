<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Analyzer\ClassMethodAnalyzer;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class TraitMethodAnalyzerTest extends TestCase {
	public function testCompareSimilarPublicClassMethod()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod'),
		]);

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod'),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertNoDifference($report);
	}

	public function testV038PublicClassMethodRemoved()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod'),
		]);

		$traitAfter = new Trait_('tmp');

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('[public] Method has been removed.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testV047PublicClassMethodAdded()
	{
		$traitBefore = new Trait_('tmp');

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod'),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('[public] Method has been added.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarPublicClassMethodSignature()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('a', null),
				],
			]),
		]);

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('a', null),
				],
			]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertNoDifference($report, 'trait');
	}

	public function testV064CompareSimilarPublicClassMethodWithDifferentSignatureVariables()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('a', null),
				],
			]),
		]);

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('b', null),
				],
			]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::PATCH);
		$this->assertSame('[public] Method parameter name changed.', $report['trait'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::PATCH][0]->getTarget());
	}

	public function testV042CompareSimilarPublicClassMethodWithDifferentSignatureTypehint()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('a', null, 'A'),
				],
			]),
		]);

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('b', null, 'B'),
				],
			]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('[public] Method parameter changed.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testV042CompareSimilarPublicClassMethodWithDifferentSignatureLength()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('a', null, 'A'),
					new Param('b', null, 'B'),
				],
			]),
		]);

		$traitAfter = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'params' => [
					new Param('b', null, 'B'),
				],
			]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('[public] Method parameter changed.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testSimilarPublicClassMethodImplementation()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
				'stmts' => [
					new MethodCall(new Variable('test'), 'someMethod'),
				],
			]),
		]);

		$traitAfter = new Trait_('tmp', [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertNoDifference($report);
	}

	public function testV052PublicClassMethodImplementationChanged()
	{
		$traitBefore = new Trait_('tmp', [
			new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someMethod'),
					],
				]),
		]);

		$traitAfter = new Trait_('tmp', [
				new ClassMethod('tmpMethod', [
					'stmts' => [
						new MethodCall(new Variable('test'), 'someOtherMethod'),
					],
				]),
		]);

		$analyzer = new ClassMethodAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::PATCH);
		$this->assertSame('[public] Method implementation changed.', $report['trait'][Level::PATCH][0]->getReason());
		$this->assertSame('tmp::tmpMethod', $report['trait'][Level::PATCH][0]->getTarget());
	}
}
