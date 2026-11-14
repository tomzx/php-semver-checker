<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;

class TraitVisitor extends VisitorAbstract
{
	/**
	 * @param \PhpParser\Node $node
	 */
	public function leaveNode(Node $node)
	{
		if ($node instanceof Trait_) {
			$this->registry->addTrait($node);
		}
	}
}
