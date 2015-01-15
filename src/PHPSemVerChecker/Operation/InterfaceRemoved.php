<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Node\Statement\Interface_ as PInterface;
use PHPSemVerChecker\SemanticVersioning\Level;

class InterfaceRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V033';
	/**
	 * @var int
	 */
	protected $level = Level::MAJOR;
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
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->interfaceBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PInterface::getFullyQualifiedName($this->interfaceBefore);
	}
}
