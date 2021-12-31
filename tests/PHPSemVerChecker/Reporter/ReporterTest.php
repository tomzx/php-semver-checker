<?php

namespace PHPSemVerChecker\Test\Reporter;

use Mockery as m;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\TestCase;
use Symfony\Component\Console\Output\NullOutput;

class ReporterTest extends TestCase {
	public function testOutput()
	{
		$output = new NullOutput();
		$report = m::mock('PHPSemVerChecker\Report\Report');
		$operation = m::mock('PHPSemVerChecker\Operation\Operation');

		$levels = [
			Level::PATCH => [],
			Level::MINOR => [],
			Level::MAJOR => [],
		];

		$differences = [
			'class'     => [
				Level::PATCH => [],
				Level::MINOR => [],
				Level::MAJOR => [
					$operation
				],
			],
			'function'  => $levels,
			'interface' => $levels,
			'method'    => $levels,
			'trait'     => $levels,
		];

		$report->shouldReceive('getSuggestedLevel')->andReturn(Level::MAJOR)
			->shouldReceive('hasDifferences')->with('class')->andReturn(true)
			->shouldReceive('offsetGet')->with('class')->andReturn($differences['class'])
			->shouldReceive('hasDifferences')->andReturn(false)
			->shouldReceive('getLevelForContext')->andReturn(Level::MAJOR);

		$operation->shouldReceive('getLocation')->once()->andReturn('test-location')
			->shouldReceive('getLine')->once()->andReturn(1)
			->shouldReceive('getTarget')->once()->andReturn('test-target')
			->shouldReceive('getReason')->once()->andReturn('test-reason')
			->shouldReceive('getCode')->once()->andReturn('test-code');

		$reporter = new Reporter($report);
		$reporter->output($output);

		$this->assertTrue(true);
	}
}
