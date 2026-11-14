<?php

namespace PHPSemVerChecker\Test\Reporter;

use Mockery as m;
use PHPSemVerChecker\Reporter\JsonReporter;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Test\TestCase;

class JsonReporterTest extends TestCase {
	public function testOutput()
	{
		$report = m::mock('PHPSemVerChecker\Report\Report');
		$filesystem = m::mock('PHPSemVerChecker\Wrapper\Filesystem');
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
			->shouldReceive('getDifferences')->andReturn($differences)
			->shouldReceive('hasDifferences')->andReturn(true)
			->shouldReceive('getLevelForContext')->andReturn(Level::MAJOR);

		$filesystem->shouldReceive('write')->with('filename', m::on(function($json) {
			$data = json_decode($json, true);
			if ($data['level'] !== Level::toString(Level::MAJOR)) {
				return false;
			}

			$operation = $data['changes']['class'][0];

			return $operation['level'] === Level::toString(Level::MAJOR) &&
				$operation['line'] === 1 &&
				$operation['location'] === 'test-location' &&
				$operation['target'] === 'test-target' &&
				$operation['reason'] === 'test-reason' &&
				$operation['code'] === 'test-code';
		}));

		$operation->shouldReceive('getLocation')->once()->andReturn('test-location')
			->shouldReceive('getLine')->once()->andReturn(1)
			->shouldReceive('getTarget')->once()->andReturn('test-target')
			->shouldReceive('getReason')->once()->andReturn('test-reason')
			->shouldReceive('getCode')->once()->andReturn('test-code');

		$reporter = new JsonReporter($report, 'filename', $filesystem);
		$reporter->output();

		$this->assertTrue(true);
	}
}
