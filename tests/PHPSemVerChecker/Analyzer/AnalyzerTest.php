<?php

namespace PHPSemVerChecker\Test\Analyzer;

use PHPSemVerChecker\Analyzer\Analyzer;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Test\Assertion\Assert;
use PHPSemVerChecker\Test\TestCase;

class AnalyzerTest extends TestCase {
	public function testCompareEmptyRegistries()
	{
		$before = new Registry();
		$after = new Registry();

		$analyzer = new Analyzer();
		$differences = $analyzer->analyze($before, $after);

		Assert::assertNoDifference($differences);
	}
}
