<?php

namespace PHPSemVerChecker\Comparator;

use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;

class Type
{
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

		if ($type instanceof UnionType) {
			return $type->getType();
		}

		return $type->toString();
	}
}
