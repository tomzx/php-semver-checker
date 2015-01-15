<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Interface_ as BaseInterface;

class Interface_ {
	public static function getFullyQualifiedName(BaseInterface $interface)
	{
		if ($interface->namespacedName) {
			return $interface->namespacedName->toString();
		}
		return $interface->name;
	}
}
