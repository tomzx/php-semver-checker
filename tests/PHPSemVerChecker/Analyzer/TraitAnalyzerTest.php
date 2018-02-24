<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Name;
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

	public function testTraitRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addTrait(new Trait_('tmp'));

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'trait';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V037', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Trait was removed.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testTraitAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addTrait(new Trait_('tmp'));

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'trait';
		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V046', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Trait was added.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testTraitCaseChanged()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addTrait(new Trait_('testTRAIT'));
		$after->addTrait(new Trait_('testtrait'));

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'trait';
		$expectedLevel = Level::PATCH;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V155', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Trait name case was changed.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('testtrait', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testTraitNamespaceDoNotOverlap()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addTrait(new Trait_('tmp'));
		$trait = new Trait_('tmp');
		$trait->namespacedName = Name::concat('namespaceTmp', 'tmp');
		$before->addTrait($trait);

		$analyzer = new TraitAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'trait';
		$expectedLevel = Level::MAJOR;
		$this->assertCount(2, $report[$context][$expectedLevel], 'Traits with similar names but different namespace are possibly considered the same in the analyzer logic');
	}
}
