<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Comparator;

use PhpParser\Node\NullableType;

class Type
{
	/**
	 * @param \PhpParser\Node\Name|string|null $typeA
	 * @param \PhpParser\Node\Name|string|null $typeB
	 * @return bool
	 */
	public static function isSame($typeA, $typeB): bool
	{
		$typeA = self::get($typeA);
		$typeB = self::get($typeB);
		return $typeA === $typeB;
	}

	/**
	 * @param \PhpParser\Node\Name|\PhpParser\Node\NullableType|string|null $type
	 * @return string|null
	 */
	public static function get($type)
	{
		if (! is_object($type)) {
			return $type;
		}

		if ($type instanceof NullableType) {
			return '?'.static::get($type->type);
		}

		return $type->toString();
	}
}
