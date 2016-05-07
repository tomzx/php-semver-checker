<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPSemVerChecker\Analyzer\PropertyAnalyzer;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class TraitPropertyAnalyzerTest extends TestCase {
	public function testCompareSimilarProperty()
	{
		$traitBefore = new Trait_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$traitAfter = new Trait_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertNoDifference($report);
	}

	public function testPublicPropertyRemoved()
	{
		$traitBefore = new Trait_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$traitAfter = new Trait_('tmp');

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		$context = 'trait';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V040', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('[public] Property has been removed.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testPropertyAdded()
	{
		$traitBefore = new Trait_('tmp');

		$traitAfter = new Trait_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		$context = 'trait';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V049', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('[public] Property has been added.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report[$context][$expectedLevel][0]->getTarget());
	}
}
