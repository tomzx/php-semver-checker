<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Property as BaseProperty;

class Property
{
	/**
	 * @param \PhpParser\Node\Stmt          $context
	 * @param \PhpParser\Node\Stmt\Property $property
	 * @return string
	 */
	public static function getFullyQualifiedName(Stmt $context, BaseProperty $property): string
	{
		$namespace = $context->name;
		if (isset($context->namespacedName)) {
			$namespace = $context->namespacedName->toString();
		}
		return $namespace . '::$' . $property->props[0]->name;
	}
}
