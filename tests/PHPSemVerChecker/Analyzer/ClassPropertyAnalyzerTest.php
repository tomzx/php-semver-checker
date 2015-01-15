<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPSemVerChecker\Analyzer\PropertyAnalyzer;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class ClassPropertyAnalyzerTest extends TestCase {
	public function testCompareSimilarProperty()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$analyzer = new PropertyAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertNoDifference($report);
	}

	public function testV008PublicPropertyRemoved()
	{
		$classBefore = new Class_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$classAfter = new Class_('tmp');

		$analyzer = new PropertyAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('Property has been removed.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report['class'][Level::MAJOR][0]->getTarget());
	}

	public function testV019PropertyAdded()
	{
		$classBefore = new Class_('tmp');

		$classAfter = new Class_('tmp', [
			'stmts' => [
				new Property(Class_::MODIFIER_PUBLIC, [
					new PropertyProperty('tmpProperty'),
				]),
			],
		]);

		$analyzer = new PropertyAnalyzer('class');
		$report = $analyzer->analyze($classBefore, $classAfter);

		Assert::assertDifference($report, 'class', Level::MAJOR);
		$this->assertSame('Property has been added.', $report['class'][Level::MAJOR][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report['class'][Level::MAJOR][0]->getTarget());
	}
}
