<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\SemanticVersioning\Level;

class InterfaceAdded extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V032';
	/**
	 * @var int
	 */
	protected $level = Level::MAJOR;
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
		$fqcn = $this->interfaceAfter->name;
		if ($this->interfaceAfter->namespacedName) {
			$fqcn = $this->interfaceAfter->namespacedName->toString();
		}
		return $fqcn;
	}
}
