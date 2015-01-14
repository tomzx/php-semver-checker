<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;

class TraitRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V037';
	/**
	 * @var string
	 */
	protected $reason = 'Trait was removed.';
	/**
	 * @var
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Trait_
	 */
	protected $traitBefore;

	/**
	 * @param string                      $fileBefore
	 * @param \PhpParser\Node\Stmt\Trait_ $traitBefore
	 */
	public function __construct($fileBefore, Trait_ $traitBefore)
	{
		$this->fileBefore = $fileBefore;
		$this->traitBefore = $traitBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore . '#' . $this->traitBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->traitBefore->name;
		if ($this->traitBefore->namespacedName) {
			$fqcn = $this->traitBefore->namespacedName->toString();
		}
		return $fqcn;
	}
}
