<?php

namespace PHPSemVerChecker\Node\Statement;

use PhpParser\Node\Stmt\Trait_ as BaseTrait;

class Trait_ {
	public static function getFullyQualifiedName(BaseTrait $trait)
	{
		if ($trait->namespacedName) {
			return $trait->namespacedName->toString();
		}
		return $trait->name;
	}
}
