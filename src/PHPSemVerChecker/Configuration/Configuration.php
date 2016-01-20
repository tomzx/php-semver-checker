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
	 * @var \Noodlehaus\Config
	 */
	protected $config;

	/**
	 * @param string|array $path
	 */
	public function __construct($path)
	{
		$this->config = new Config($path);
		$this->extractMapping($this->get('level.mapping', []));
	}

	/**
	 * @param string|array $file
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 */
	public static function fromFile($file)
	{
		return new Configuration($file);
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

	/**
	 * @see \Noodlehaus\Config::get
	 * @param string $key
	 * @param mixed|null $default
	 * @return array|mixed|null
	 */
	public function get($key, $default = null)
	{
		return $this->config->get($key, $default);
	}

	/**
	 * @see \Noodlehaus\Config::set
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value)
	{
		$this->config->set($key, $value);
	}

	/**
	 * Merge this configuration with an associative array.
	 *
	 * Note that dot notation is used for keys.
	 *
	 * @param array $data
	 */
	public function merge($data)
	{
		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
	}
}
