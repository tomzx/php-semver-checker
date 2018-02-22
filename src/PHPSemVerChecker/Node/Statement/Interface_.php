<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Interface_ as BaseInterface;

class Interface_
{
	/**
	 * @param \PhpParser\Node\Stmt\Interface_ $interface
	 * @return null|string
	 */
	public static function getFullyQualifiedName(BaseInterface $interface)
	{
		if (isset($interface->namespacedName)) {
			return $interface->namespacedName->toString();
		}
		return $interface->name;
	}
}
