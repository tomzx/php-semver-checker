<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;

class TraitAdded extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V046';
	/**
	 * @var string
	 */
	protected $reason = 'Trait was added.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Trait_
	 */
	protected $traitAfter;

	/**
	 * @param string                      $fileAfter
	 * @param \PhpParser\Node\Stmt\Trait_ $traitAfter
	 */
	public function __construct($fileAfter, Trait_ $traitAfter)
	{
		$this->fileAfter = $fileAfter;
		$this->traitAfter = $traitAfter;
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
		return $this->traitAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->traitAfter->name;
		if ($this->traitAfter->namespacedName) {
			$fqcn = $this->traitAfter->namespacedName->toString();
		}
		return $fqcn;
	}
}
