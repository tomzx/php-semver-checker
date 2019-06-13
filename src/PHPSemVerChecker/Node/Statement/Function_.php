<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Function_ as BaseFunction;

class Function_
{
	/**
	 * @param \PhpParser\Node\Stmt\Function_ $function
	 * @return string
	 */
	public static function getFullyQualifiedName(BaseFunction $function)
	{
		if (isset($function->namespacedName)) {
			return $function->namespacedName->toString() . '::' . $function->name->toString();
		}
		return $function->name->toString();
	}
}
