<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod as BaseClassMethod;

class ClassMethod
{
	/**
	 * @param \PhpParser\Node\Stmt             $context
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
	 * @return string
	 */
	public static function getFullyQualifiedName(Stmt $context, BaseClassMethod $classMethod): string
	{
		$namespace = $context->name->toString();
		if (isset($context->namespacedName)) {
			$namespace = $context->namespacedName->toString();
		}
		return $namespace . '::' . $classMethod->name->toString();
	}
}
