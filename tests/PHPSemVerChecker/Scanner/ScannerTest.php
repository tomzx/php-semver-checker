<?php

namespace PHPSemVerChecker\Test\Scanner;

use PHPSemVerChecker\Scanner\Scanner;
use PHPSemVerChecker\Test\TestCase;

class ScannerTest extends TestCase {
	public function testScan()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/before/ClassRemoved.php');
		$this->assertNotEmpty($scanner->getRegistry());
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testInvalidCodeParsing()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/InvalidCode.php');
	}

	public function testPHP70()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP7.0.php');

		$this->assertTrue(true);
	}

	public function testPHP71()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP7.1.php');

		$this->assertTrue(true);
	}

	public function testPHP72()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP7.2.php');

		$this->assertTrue(true);
	}
}
