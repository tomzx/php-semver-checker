<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Stmt\Class_;
use PHPSemVerChecker\Analyzer\ClassAnalyzer;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class ClassAnalyzerTest extends TestCase {
	public function testSimilarClasses()
	{
		$before = new Registry();
		$after = new Registry();

		$classBefore = new Class_('tmp');
		$before->addClass($classBefore);

		$classAfter = new Class_('tmp');
		$after->addClass($classAfter);

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testClassAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addClass(new Class_('tmp'));

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'class';
		$expectedLevel = Level::MINOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V014', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Class was added.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testClassRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addClass(new Class_('tmp'));

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'class';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V005', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Class was removed.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testClassRenamedCaseOnly()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addClass(new Class_('TestCLASS'));
		$after->addClass(new Class_('TestClass'));

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		$context = 'class';
		$expectedLevel = Level::PATCH;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V154', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('Class was renamed (case only).', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('TestClass', $report[$context][$expectedLevel][0]->getTarget());
	}
}
