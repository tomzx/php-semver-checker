<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Comparator;

class Node
{
	/**
	 * @param \PhpParser\Node $nodeA
	 * @param \PhpParser\Node $nodeB
	 * @return bool
	 */
	public static function isEqual(\PhpParser\Node $nodeA, \PhpParser\Node $nodeB): bool
	{
		if ($nodeA->getType() !== $nodeB->getType()) {
			return false;
		}

		$subNodesA = $nodeA->getSubNodeNames();
		$subNodesB = $nodeB->getSubNodeNames();
		if ($subNodesA !== $subNodesB) {
			return false;
		}

		foreach ($subNodesA as $key) {
			if ( ! self::isSubNodeEqual($nodeA->$key, $nodeB->$key)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Compare two sub node values.
	 *
	 * A sub node may hold a node (compared recursively), an array of sub node values (compared element-wise,
	 * as a strict comparison of arrays of nodes would compare object identity) or a scalar value (compared directly).
	 * Node attributes (line numbers, comments, ...) are not part of the sub node names
	 * and are therefore never taken into account.
	 *
	 * @param mixed $valueA
	 * @param mixed $valueB
	 * @return bool
	 */
	private static function isSubNodeEqual($valueA, $valueB): bool
	{
		if ($valueA instanceof \PhpParser\Node && $valueB instanceof \PhpParser\Node) {
			return self::isEqual($valueA, $valueB);
		}

		if (is_array($valueA) && is_array($valueB)) {
			if (array_keys($valueA) !== array_keys($valueB)) {
				return false;
			}

			foreach ($valueA as $key => $elementA) {
				if ( ! self::isSubNodeEqual($elementA, $valueB[$key])) {
					return false;
				}
			}

			return true;
		}

		return $valueA === $valueB;
	}
}
