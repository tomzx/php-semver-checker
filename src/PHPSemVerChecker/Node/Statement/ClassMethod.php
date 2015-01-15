<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod as BaseClassMethod;

class ClassMethod {
	public static function getFullyQualifiedName(Stmt $context, BaseClassMethod $classMethod)
	{
		$fqcn = $context->name;
		if ($context->namespacedName) {
			$fqcn = $context->namespacedName->toString();
		}
		return $fqcn . '::' . $classMethod->name;
	}
}
