<?php

namespace PHPSemVerChecker\Test\Report;

use Mockery as m;
use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\TestCase;

class ReportTest extends TestCase {
	public function testAddClass()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addClass($operation, Level::MAJOR);
		$this->assertSame($operation, $report['class'][Level::MAJOR][0]);
	}

	public function testAddClassMethod()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addClassMethod($operation, Level::MAJOR);
		$this->assertSame($operation, $report['method'][Level::MAJOR][0]);
	}

	public function testAddFunction()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addFunction($operation, Level::MAJOR);
		$this->assertSame($operation, $report['function'][Level::MAJOR][0]);
	}

	public function testAddInterface()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addInterface($operation, Level::MAJOR);
		$this->assertSame($operation, $report['interface'][Level::MAJOR][0]);
	}

	public function testAddTrait()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addTrait($operation, Level::MAJOR);
		$this->assertSame($operation, $report['trait'][Level::MAJOR][0]);
	}

	public function testMerge()
	{
		$reportA = new Report();
		$reportB = new Report();

		$operationA = m::mock('PHPSemVerChecker\Operation\Operation');
		$operationB = m::mock('PHPSemVerChecker\Operation\Operation');

		$reportA->addClass($operationA, Level::MAJOR);
		$reportB->addFunction($operationB, Level::MAJOR);
		$this->assertEmpty($reportA['method'][Level::MAJOR]);

		$reportA->merge($reportB);
		$this->assertSame($operationB, $reportA['function'][Level::MAJOR][0]);
		$this->assertSame($operationA, $reportA['class'][Level::MAJOR][0]);
	}

	public function testHasNoDifferences()
	{
		$report = new Report();

		$this->assertFalse($report->hasDifferences());
	}

	public function testHasDifferences()
	{
		$report = new Report();
		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addClass($operation, Level::MAJOR);

		$this->assertTrue($report->hasDifferences());
	}

	public function testGetLevelForContextWithNoDifferences()
	{
		$report = new Report();

		$this->assertSame(Level::NONE, $report->getLevelForContext());
	}

	public function testGetLevelForContextWithDifferences()
	{
		$report = new Report();
		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$report->addClass($operation, Level::MAJOR);

		$this->assertSame(Level::MAJOR, $report->getLevelForContext());
	}
}
