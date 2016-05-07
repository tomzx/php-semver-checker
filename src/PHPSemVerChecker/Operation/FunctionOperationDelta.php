<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;

abstract class FunctionOperationDelta extends Operation
{
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
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->functionAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PFunction::getFullyQualifiedName($this->functionAfter);
	}
}
