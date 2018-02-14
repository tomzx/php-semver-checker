<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Node\Statement\Trait_ as PTrait;

class TraitRenamedCaseOnly extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V155';
	/**
	 * @var string
	 */
	protected $reason = 'Trait was renamed (case only).';
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
		return PTrait::getFullyQualifiedName($this->traitAfter);
	}
}
