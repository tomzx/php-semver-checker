<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;

class FunctionAdded extends Operation {
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

		return $this->fileAfter . '#' . $this->functionAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqfn = $this->functionAfter->name;
		if ($this->functionAfter->namespacedName) {
			$fqfn = $this->functionAfter->namespacedName->toString() . '::' . $this->functionAfter->name;
		}
		return $fqfn;
	}
}