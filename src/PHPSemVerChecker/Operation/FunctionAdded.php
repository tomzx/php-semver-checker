<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;
use PHPSemVerChecker\SemanticVersioning\Level;

class FunctionAdded extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V003';
	/**
	 * @var int
	 */
	protected $level = Level::MINOR;
	/**
	 * @var string
	 */
	protected $reason = 'Function has been added.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionAfter;

	/**
	 * @param string                         $fileAfter
	 * @param \PhpParser\Node\Stmt\Function_ $functionAfter
	 */
	public function __construct($fileAfter, Function_ $functionAfter)
	{
		$this->fileAfter = $fileAfter;
		$this->functionAfter = $functionAfter;
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
