<?php
declare(strict_types=1);

namespace PHPSemVerChecker\SemanticVersioning;

class Level
{
	const NONE = 0; // TODO: Get rid of this *level* <tom@tomrochette.com>
	const PATCH = 1;
	const MINOR = 2;
	const MAJOR = 3;

	/**
	 * @param string $order
	 * @return array
	 */
	public static function asList(string $order = 'asc'): array
	{
		$levels = [
			self::PATCH,
			self::MINOR,
			self::MAJOR,
		];
		if ($order === 'asc') {
			return $levels;
		} else {
			rsort($levels);
			return $levels;
		}
	}

	/**
	 * @param int $level
	 * @return string
	 */
	public static function toString(int $level): string
	{
		$mapping = [
			self::NONE  => 'NONE',
			self::PATCH => 'PATCH',
			self::MINOR => 'MINOR',
			self::MAJOR => 'MAJOR',
		];

		return $mapping[$level];
	}

	/**
	 * @param string $level
	 * @return int
	 */
	public static function fromString(string $level): int
	{
		$mapping = [
			'NONE'  => self::NONE,
			'PATCH' => self::PATCH,
			'MINOR' => self::MINOR,
			'MAJOR' => self::MAJOR,
		];

		return $mapping[strtoupper($level)];
	}
}
