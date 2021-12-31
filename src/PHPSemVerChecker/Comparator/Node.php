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
			$valueA = $nodeA->$key;
			$valueB = $nodeB->$key;
			$result = true;
			if ($valueA instanceof \PhpParser\Node && $valueB instanceof \PhpParser\Node) {
				$result = self::isEqual($valueA, $valueB);
			} else {
				$result = $valueA === $valueB;
			}

			if ( ! $result) {
				return false;
			}
		}

		return true;
	}
}
