<?php

namespace PHPSemVerChecker\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_ as BaseClass;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;
use PHPSemVerChecker\Registry\Registry;

class ClassVisitor extends NodeVisitorAbstract {
	/**
	 * @var \PHPSemVerChecker\Registry\Registry
	 */
	protected $registry;

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registry
	 */
	public function __construct(Registry $registry)
	{
		$this->registry = $registry;
	}

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