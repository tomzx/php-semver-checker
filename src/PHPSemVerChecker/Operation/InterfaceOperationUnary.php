<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Node\Statement\Interface_ as PInterface;

class InterfaceOperationUnary extends Operation
{
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interface;

	/**
	 * @param string                          $fileAfter
	 * @param \PhpParser\Node\Stmt\Interface_ $interface
	 */
	public function __construct($fileAfter, Interface_ $interface)
	{
		$this->file = $fileAfter;
		$this->interface = $interface;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->interface->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PInterface::getFullyQualifiedName($this->interface);
	}
}
