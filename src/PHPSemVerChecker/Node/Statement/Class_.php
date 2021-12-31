<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Class_ as BaseClass;

class Class_
{
	/**
	 * @param \PhpParser\Node\Stmt\Class_ $class
	 * @return null|string
	 */
	public static function getFullyQualifiedName(BaseClass $class): ?string
	{
		if (isset($class->namespacedName)) {
			return $class->namespacedName->toString();
		}
		return $class->name->toString();
	}
}
