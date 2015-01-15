<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property as BaseProperty;

class Property {
	public static function getFullyQualifiedName(Stmt $context, BaseProperty $property)
	{
		$fqcn = $context->name;
		if ($context->namespacedName) {
			$fqcn = $context->namespacedName->toString();
		}
		return $fqcn . '::$' . $property->props[0]->name;
	}
}
