<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Analyzer\TraitAnalyzer;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class TraitAnalyzerTest extends TestCase {
	public function testCompareSimilarTrait()
	{
		$before = new Registry();
		$after = new Registry();

		$traitBefore = new Trait_('tmp');
		$before->addTrait($traitBefore);

		$traitAfter = new Trait_('tmp');
		$after->addTrait($traitAfter);

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testV037TraitRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addTrait(new Trait_('tmp'));

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'trait', Level::MAJOR);
		$this->assertSame('Trait was removed.', $report['trait'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['trait'][Level::MAJOR][0]->getTarget());
	}

	public function testV046TraitAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addTrait(new Trait_('tmp'));

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'trait', Level::MINOR);
		$this->assertSame('Trait was added.', $report['trait'][Level::MINOR][0]->getReason());
		$this->assertSame('tmp', $report['trait'][Level::MINOR][0]->getTarget());
	}
}
