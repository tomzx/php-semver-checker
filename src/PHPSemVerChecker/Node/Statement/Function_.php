<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Function_ as BaseFunction;

class Function_ {
	public static function getFullyQualifiedName(BaseFunction $function)
	{
		$fqfn = '';
		if ($function->namespacedName) {
			$fqfn = $function->namespacedName->toString() . '::';
		}
		return $fqfn . $function->name;
	}
}
