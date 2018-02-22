<?php

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
			$this->registry->addClass($node);
		}
	}
}
