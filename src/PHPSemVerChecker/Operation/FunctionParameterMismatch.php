<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;

class FunctionParameterMismatch extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter mismatch.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionAfter;

	/**
	 * @param string                         $fileBefore
	 * @param \PhpParser\Node\Stmt\Function_ $functionBefore
	 * @param string                         $fileAfter
	 * @param \PhpParser\Node\Stmt\Function_ $functionAfter
	 */
	public function __construct($fileBefore, Function_ $functionBefore, $fileAfter, Function_ $functionAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->functionBefore = $functionBefore;
		$this->fileAfter = $fileAfter;
		$this->functionAfter = $functionAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		$fqfn = $this->functionAfter->name;
		if ($this->functionAfter->namespacedName) {
			$fqfn = $this->functionAfter->namespacedName->toString() . '::' . $this->functionAfter->name;
		}
		return $this->fileBefore . '#' . $this->functionAfter->getLine() . ' ' . $fqfn;
	}
}