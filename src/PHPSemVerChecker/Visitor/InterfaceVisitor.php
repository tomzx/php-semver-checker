<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;

class InterfaceVisitor extends VisitorAbstract
{
	/**
	 * @param \PhpParser\Node $node
	 */
	public function leaveNode(Node $node)
	{
		if ($node instanceof Interface_) {
			$this->registry->addInterface($node);
		}
	}
}
