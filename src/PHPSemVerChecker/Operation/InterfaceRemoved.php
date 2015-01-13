<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;

class InterfaceRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Interface was removed.';
	/**
	 * @var
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interfaceBefore;

	/**
	 * @param string                          $fileBefore
	 * @param \PhpParser\Node\Stmt\Interface_ $interfaceBefore
	 */
	public function __construct($fileBefore, Interface_ $interfaceBefore)
	{
		$this->fileBefore = $fileBefore;
		$this->interfaceBefore = $interfaceBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore . '#' . $this->interfaceBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->interfaceBefore->name;
		if ($this->interfaceBefore->namespacedName) {
			$fqcn = $this->interfaceBefore->namespacedName->toString();
		}
		return $fqcn;
	}
}
