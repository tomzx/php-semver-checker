<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Node\Statement\Interface_ as PInterface;

class InterfaceRenamedCaseOnly extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V153';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was renamed (case only).';
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
