<?php

namespace PHPSemVerChecker\Configuration;

use Noodlehaus\Config;
use PHPSemVerChecker\SemanticVersioning\Level;

class Configuration
{
	/**
	 * @var array
	 */
	protected $mapping = [];

	/**
	 * @param string|array $file
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 */
	public static function fromFile($file)
	{
		$configuration = new Configuration();
		$config = new Config($file);

		$configuration->extractMapping($config->get('level.mapping', []));

		return $configuration;
	}

	/**
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 */
	public static function defaults()
	{
		return self::fromFile(['?php-semver-checker.yml.dist', '?php-semver-checker.yml']);
	}

	/**
	 * @param array $mapping
	 */
	protected function extractMapping(array $mapping)
	{
		foreach ($mapping as &$level) {
			$level = Level::fromString($level);
		}
		$this->mapping = $mapping;
	}

	/**
	 * @return array
	 */
	public function getLevelMapping()
	{
		return $this->mapping;
	}
}
