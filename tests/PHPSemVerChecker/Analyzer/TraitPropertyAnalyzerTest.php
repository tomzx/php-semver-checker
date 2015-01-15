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
			new Property(Class_::MODIFIER_PUBLIC, [
				new PropertyProperty('tmpProperty'),
			]),
		]);

		$traitAfter = new Trait_('tmp', [
			new Property(Class_::MODIFIER_PUBLIC, [
				new PropertyProperty('tmpProperty'),
			]),
		]);

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertNoDifference($report);
	}

	public function testV006PublicPropertyRemoved()
	{
		$traitBefore = new Trait_('tmp', [
			new Property(Class_::MODIFIER_PUBLIC, [
				new PropertyProperty('tmpProperty'),
			]),
		]);

		$traitAfter = new Trait_('tmp');

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('Property has been removed.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testV015PropertyAdded()
	{
		$traitBefore = new Trait_('tmp');

		$traitAfter = new Trait_('tmp', [
			new Property(Class_::MODIFIER_PUBLIC, [
				new PropertyProperty('tmpProperty'),
			]),
		]);

		$analyzer = new PropertyAnalyzer('trait');
		$report = $analyzer->analyze($traitBefore, $traitAfter);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('Property has been added.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report['trait'][Level::MAJOR][0]->getTarget());
	}
}
