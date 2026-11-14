<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Node\Statement\Trait_ as PTrait;

class TraitOperationDelta extends Operation
{
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Trait_
	 */
	protected $traitBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Trait_
	 */
	protected $traitAfter;

	public function __construct($fileBefore, Trait_ $traitBefore, $fileAfter, Trait_ $traitAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->traitBefore = $traitBefore;
		$this->fileAfter = $fileAfter;
		$this->traitAfter = $traitAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return $this->traitAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PTrait::getFullyQualifiedName($this->traitAfter);
	}
}
