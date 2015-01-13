<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;

class InterfaceAdded extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Interface was added.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interfaceAfter;

	/**
	 * @param string                          $fileAfter
	 * @param \PhpParser\Node\Stmt\Interface_ $interfaceAfter
	 */
	public function __construct($fileAfter, Interface_ $interfaceAfter)
	{
		$this->fileAfter = $fileAfter;
		$this->interfaceAfter = $interfaceAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter . '#' . $this->interfaceAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->interfaceAfter->name;
		if ($this->interfaceAfter->namespacedName) {
			$fqcn = $this->interfaceAfter->namespacedName->toString();
		}
		return $fqcn;
	}
}
