<?php

namespace PHPSemVerChecker\Comparator;

class Type {
	/**
	 * @param \PhpParser\Node\Name|string|null $typeA
	 * @param \PhpParser\Node\Name|string|null $typeB
	 * @return bool
	 */
	public static function isSame($typeA, $typeB)
	{
		$typeA = self::get($typeA);
		$typeB = self::get($typeB);
		return $typeA === $typeB;
	}

	/**
	 * @param \PhpParser\Node\Name|string|null $type
	 * @return string|null
	 */
	public static function get($type)
	{
		return is_object($type) ? $type->toString() : $type;
	}
}
