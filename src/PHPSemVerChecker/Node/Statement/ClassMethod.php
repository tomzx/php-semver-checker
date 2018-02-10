<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod as BaseClassMethod;

class ClassMethod {
	/**
	 * @param \PhpParser\Node\Stmt             $context
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
	 * @return string
	 */
	public static function getFullyQualifiedName(Stmt $context, BaseClassMethod $classMethod)
	{
		$namespace = $context->name;
		if (isset($context->namespacedName)) {
			$namespace = $context->namespacedName->toString();
		}
		return $namespace . '::' . $classMethod->name;
	}
}
