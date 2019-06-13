<?php

namespace PHPSemVerChecker\Test;

use Mockery as m;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
	protected function tearDown(): void
	{
		m::close();

		parent::tearDown();
	}
}
