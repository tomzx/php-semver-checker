<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Class_ as BaseClass;

class Class_ {
	public static function getFullyQualifiedName(BaseClass $class)
	{
		if (isset($class->namespacedName)) {
			return $class->namespacedName->toString();
		}
		return $class->name;
	}
}
