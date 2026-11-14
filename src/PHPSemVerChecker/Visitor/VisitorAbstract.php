<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Visitor;

use PhpParser\NodeVisitorAbstract;
use PHPSemVerChecker\Registry\Registry;

class VisitorAbstract extends NodeVisitorAbstract
{
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
}
