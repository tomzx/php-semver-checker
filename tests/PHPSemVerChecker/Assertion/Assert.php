<?php

namespace PHPSemVerChecker\Test\Assertion;

use PHPSemVerChecker\Report\Report;
use PHPUnit\Framework\Assert as BaseAssert;

class Assert
{
	public static function assertDifference(Report $report, $context, $level, $expected = 1)
	{
		BaseAssert::assertCount($expected, $report[$context][$level]);
	}

	public static function assertNoDifference(Report $report)
	{
		foreach ($report as $context => $levels) {
			foreach ($levels as $entries) {
				BaseAssert::assertCount(0, $entries);
			}
		}
	}
}
