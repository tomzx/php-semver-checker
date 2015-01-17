<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Node\Statement\Trait_ as PTrait;

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
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->traitBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PTrait::getFullyQualifiedName($this->traitBefore);
	}
}
