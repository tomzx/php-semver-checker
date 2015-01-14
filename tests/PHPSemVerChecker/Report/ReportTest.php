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

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addClass($operation);
		$this->assertSame($operation, $report['class'][Level::MAJOR][0]);
	}

	public function testAddFunction()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addFunction($operation);
		$this->assertSame($operation, $report['function'][Level::MAJOR][0]);
	}

	public function testAddInterface()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addInterface($operation);
		$this->assertSame($operation, $report['interface'][Level::MAJOR][0]);
	}

	public function testAddTrait()
	{
		$report = new Report();

		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addTrait($operation);
		$this->assertSame($operation, $report['trait'][Level::MAJOR][0]);
	}

	public function testMerge()
	{
		$reportA = new Report();
		$reportB = new Report();

		$operationA = m::mock('PHPSemVerChecker\Operation\Operation');
		$operationB = m::mock('PHPSemVerChecker\Operation\Operation');

		$operationA->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);
		$operationB->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$reportA->addClass($operationA);
		$reportB->addFunction($operationB);
		$this->assertEmpty($reportA['function'][Level::MAJOR]);

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

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addClass($operation);

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

		$operation->shouldReceive('getLevel')->once()->andReturn(Level::MAJOR);

		$report->addClass($operation);

		$this->assertSame(Level::MAJOR, $report->getLevelForContext());
	}
}
