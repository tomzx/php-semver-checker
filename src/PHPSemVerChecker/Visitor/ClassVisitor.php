<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_ as BaseClass;

class ClassVisitor extends VisitorAbstract
{
	/**
	 * @param \PhpParser\Node $node
	 */
	public function leaveNode(Node $node)
	{
		if ($node instanceof BaseClass) {
			// Anonymous class (PHP 7.0<=)
			if (!$node->name) {
				return;
			}

			$this->registry->addClass($node);
		}
	}
}
