<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Function_ as BaseFunction;

class Function_ {
	public static function getFullyQualifiedName(BaseFunction $function)
	{
		if (isset($function->namespacedName)) {
			return $function->namespacedName->toString() . '::' . $function->name;
		}
		return $function->name;
	}
}
