<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property as BaseProperty;

class Property {
	public static function getFullyQualifiedName(Stmt $context, BaseProperty $property)
	{
		$namespace = $context->name;
		if (isset($context->namespacedName)) {
			$namespace = $context->namespacedName->toString();
		}
		return $namespace . '::$' . $property->props[0]->name;
	}
}
