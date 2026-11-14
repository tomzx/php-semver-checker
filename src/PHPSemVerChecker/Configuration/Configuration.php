<?php
declare(strict_types=1);

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
	 * @throws \Noodlehaus\Exception\EmptyDirectoryException
	 */
	public function __construct($path)
	{
		$this->config = new Config($path);
		$this->extractMapping($this->get('level.mapping', []));
	}

	/**
	 * @param string|array $file
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 * @throws \Noodlehaus\Exception\EmptyDirectoryException
	 */
	public static function fromFile($file): Configuration
	{
		return new Configuration($file);
	}

	/**
	 * @param string $name
	 * @return \PHPSemVerChecker\Configuration\Configuration
	 * @throws \Noodlehaus\Exception\EmptyDirectoryException
	 */
	public static function defaults(string $name): Configuration
	{
		return self::fromFile(['?' . $name . '.yml.dist', '?' . $name . '.yml']);
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
	public function getLevelMapping(): array
	{
		return $this->mapping;
	}

	/**
	 * @param string     $key
	 * @param mixed|null $default
	 * @return array|mixed|null
	 *@see \Noodlehaus\Config::get
	 */
	public function get(string $key, $default = null)
	{
		return $this->config->get($key, $default);
	}

	/**
	 * @param string $key
	 * @param mixed  $value
		  *@see \Noodlehaus\Config::set
	 */
	public function set(string $key, $value)
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
	public function merge(array $data)
	{
		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
	}
}
