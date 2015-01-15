<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;
use PHPSemVerChecker\SemanticVersioning\Level;

class FunctionImplementationChanged extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V004';
	/**
	 * @var int
	 */
	protected $level = Level::PATCH;
	/**
	 * @var string
	 */
	protected $reason = 'Function implementation changed.';
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionAfter;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionBefore;
	/**
	 * @var string
	 */
	protected $fileBefore;

	/**
	 * @param string                         $fileBefore
	 * @param \PhpParser\Node\Stmt\Function_ $functionBefore
	 * @param string                         $fileAfter
	 * @param \PhpParser\Node\Stmt\Function_ $functionAfter
	 */
	public function __construct($fileBefore, Function_ $functionBefore, $fileAfter, Function_ $functionAfter)
	{
		$this->functionAfter = $functionAfter;
		$this->fileAfter = $fileAfter;
		$this->functionBefore = $functionBefore;
		$this->fileBefore = $fileBefore;
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
