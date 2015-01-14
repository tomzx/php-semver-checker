<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\SemanticVersioning\Level;

class FunctionRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V001';
	/**
	 * @var int
	 */
	protected $level = Level::MAJOR;
	/**
	 * @var string
	 */
	protected $reason = 'Function has been removed.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionBefore;

	/**
	 * @param string                         $fileBefore
	 * @param \PhpParser\Node\Stmt\Function_ $functionBefore
	 */
	public function __construct($fileBefore, Function_ $functionBefore)
	{
		$this->fileBefore = $fileBefore;
		$this->functionBefore = $functionBefore;
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
		return $this->functionBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqfn = $this->functionBefore->name;
		if ($this->functionBefore->namespacedName) {
			$fqfn = $this->functionBefore->namespacedName->toString() . '::' . $this->functionBefore->name;
		}
		return $fqfn;
	}
}
