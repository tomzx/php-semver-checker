<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Node\Statement\Interface_ as PInterface;

class InterfaceOperationDelta extends Operation {
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interfaceBefore;
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
	 * @param \PhpParser\Node\Stmt\Interface_ $interface
	 */
	public function __construct($fileBefore, Interface_ $interfaceBefore, $fileAfter, Interface_ $interfaceAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->interfaceBefore = $interfaceBefore;
		$this->fileAfter = $fileAfter;
		$this->interfaceAfter = $interfaceAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->interfaceAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PInterface::getFullyQualifiedName($this->interfaceAfter);
	}
}
