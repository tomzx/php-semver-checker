<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;

class FunctionRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V001';
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
		return PFunction::getFullyQualifiedName($this->functionBefore);
	}
}
