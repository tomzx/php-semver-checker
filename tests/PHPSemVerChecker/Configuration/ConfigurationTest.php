<?php

namespace PHPSemVerChecker\Test\Configuration;

use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPUnit_Framework_TestCase;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \PHPSemVerChecker\Configuration\Configuration
	 */
	protected $config;

	public function setUp()
	{
		$this->config = new Configuration([__DIR__.'/../../fixtures/configuration/php-semver-checker.json']);
	}

	public function testGet()
	{
		$this->assertEquals('src', $this->config->get('include-before'));
	}

	public function testGetDefault()
	{
		$this->assertEquals('default', $this->config->get('missing key', 'default'));
	}

	public function testSet()
	{
		$unique = new \stdClass();
		$this->config->set('any key', $unique);
		$this->assertEquals($unique, $this->config->get('any key'));
	}

	public function testGetLevelMapping(){
		$levelMapping = $this->config->getLevelMapping();
		$this->assertTrue(is_array($levelMapping));
		$this->assertEquals($levelMapping['V001'], Level::PATCH);
		$this->assertEquals($levelMapping['V006'], Level::MAJOR);
	}
}
