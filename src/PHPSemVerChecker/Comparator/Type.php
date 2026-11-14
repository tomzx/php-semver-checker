<?php
declare(strict_types=1);

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
	public static function isSame($typeA, $typeB): bool
	{
		$typeA = self::get($typeA);
		$typeB = self::get($typeB);
		return $typeA === $typeB;
	}

	/**
	 * @param \PhpParser\Node\Name|\PhpParser\Node\NullableType|\PhpParser\Node\UnionType|string|null $type
	 * @return string|null
	 */
	public static function get($type): ?string
	{
		if ( ! is_object($type)) {
			return $type;
		}

		if ($type instanceof NullableType) {
			return '?' . static::get($type->type);
		}

		if ($type instanceof UnionType) {
			$types = [];
			foreach ($type->types as $unionType) {
				$types[] = static::get($unionType);
			}
			// Sort to ensure consistent comparison even with different order of types
			sort($types);
			return implode('|', $types);
		}

		return $type->toString();
	}
}
