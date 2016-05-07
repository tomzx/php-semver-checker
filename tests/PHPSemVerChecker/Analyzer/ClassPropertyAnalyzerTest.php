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

	public function testPublicPropertyRemoved()
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

		$context = 'class';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V008', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('[public] Property has been removed.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report[$context][$expectedLevel][0]->getTarget());
	}

	public function testPropertyAdded()
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

		$context = 'class';
		$expectedLevel = Level::MAJOR;
		Assert::assertDifference($report, $context, $expectedLevel);
		$this->assertSame('V019', $report[$context][$expectedLevel][0]->getCode());
		$this->assertSame('[public] Property has been added.', $report[$context][$expectedLevel][0]->getReason());
		$this->assertSame('tmp::$tmpProperty', $report[$context][$expectedLevel][0]->getTarget());
	}
}
