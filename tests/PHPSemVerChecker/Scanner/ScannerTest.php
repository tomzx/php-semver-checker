<?php

namespace PHPSemVerChecker\Test\Scanner;

use PHPSemVerChecker\Scanner\Scanner;
use PHPSemVerChecker\Test\TestCase;
use RuntimeException;

class ScannerTest extends TestCase {
	public function testScan()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/before/ClassRemoved.php');
		$this->assertNotEmpty($scanner->getRegistry());
	}

	public function testInvalidCodeParsing()
	{
		$this->expectException(RuntimeException::class);
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

	public function testPHP73()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP7.3.php');

		$this->assertTrue(true);
	}

	public function testPHP74()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP7.4.php');

		$this->assertTrue(true);
	}

	public function testPHP80()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP8.0.php');

		$this->assertTrue(true);
	}

	public function testPHP81()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP8.1.php');

		$this->assertTrue(true);
	}

	public function testPHP82()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP8.2.php');

		$this->assertTrue(true);
	}

	public function testPHP83()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP8.3.php');

		$this->assertTrue(true);
	}

	public function testPHP84()
	{
		$scanner = new Scanner();
		$scanner->scan(__DIR__.'/../../fixtures/general/PHP8.4.php');

		$this->assertTrue(true);
	}
}
