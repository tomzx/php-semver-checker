<?php

namespace PHPSemVerChecker\Test\Reporter;

use ArrayIterator;
use Mockery as m;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ReporterTest extends TestCase {
	public function testOutput()
	{
		$input = new ArrayInput([]);
		$output = new NullOutput();
		$report = m::mock('PHPSemVerChecker\Report\Report');

		$levels = [
			Level::PATCH => [],
			Level::MINOR => [],
			Level::MAJOR => [],
		];

		$differences = [
			'class'     => $levels,
			'function'  => $levels,
			'interface' => $levels,
			'method'    => $levels,
			'trait'     => $levels,
		];

		$report->shouldReceive('getSuggestedLevel')->andReturn(Level::MAJOR)
			->shouldReceive('getIterator')->andReturn(new ArrayIterator($differences))
			->shouldReceive('offsetGet')->andReturn($levels)
			->shouldReceive('hasDifferences')->andReturn(true)
			->shouldReceive('getLevelForContext')->andReturn(Level::MAJOR);

		$reporter = new Reporter($report, $input);
		$reporter->output($output);
	}
}
