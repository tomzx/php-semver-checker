<?php

namespace PHPSemVerChecker\Comparator;

class Type {
	/**
	 * @param \PhpParser\Node\Name|string $typeA
	 * @param \PhpParser\Node\Name|string $typeB
	 * @return bool
	 */
	public static function isSame($typeA, $typeB)
	{
		$typeA = is_object($typeA) ? $typeA->toString() : $typeA;
		$typeB = is_object($typeB) ? $typeB->toString() : $typeB;
		return $typeA === $typeB;
	}
}
