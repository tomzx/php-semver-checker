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

		$beforeClass = new Class_('tmp');
		$before->addClass($beforeClass);

		$afterClass = new Class_('tmp');
		$after->addClass($afterClass);

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($report);
	}

	public function testV005ClassRemoved()
	{
		$before = new Registry();
		$after = new Registry();

		$before->addClass(new Class_('tmp'));

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('Class was removed.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testV014ClassAdded()
	{
		$before = new Registry();
		$after = new Registry();

		$after->addClass(new Class_('tmp'));

		$analyzer = new ClassAnalyzer();
		$report = $analyzer->analyze($before, $after);

		Assert::assertDifference($report, 'class', Level::MINOR);
		$this->assertSame('Class was added.', $report['class'][Level::MINOR][0]->getReason());
		$this->assertSame('tmp', $report['class'][Level::MINOR][0]->getTarget());
	}
}
