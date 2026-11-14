<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;

class FunctionVisitor extends VisitorAbstract
{
	/**
	 * @param \PhpParser\Node $node
	 */
	public function leaveNode(Node $node)
	{
		if ($node instanceof Function_) {
			$this->registry->addFunction($node);
		}
	}
}
