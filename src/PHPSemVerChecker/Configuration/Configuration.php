<?php

namespace PHPSemVerChecker\Configuration;

use PHPSemVerChecker\SemanticVersioning\Level;

class Configuration
{
	/**
	 * @var array
	 */
	protected $mapping = [];

	/**
	 * @param string $file
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 */
	public static function fromFile($file)
	{
		$configuration = new Configuration();
		$config = json_decode(file_get_contents($file), true);

		$configuration->extractMapping($config);

		return $configuration;
	}

	/**
	 * @param array $config
	 */
	protected function extractMapping(array $config)
	{
		if ( ! array_key_exists('level', $config) || ! array_key_exists('mapping', $config['level'])) {
			return;
		}

		$mapping = $config['level']['mapping'];
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
