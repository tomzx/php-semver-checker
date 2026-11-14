<?php
declare(strict_types=1);

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
	 * @param string|null                    $fileBefore
	 * @param \PhpParser\Node\Stmt\Function_ $functionBefore
	 * @param string|null                    $fileAfter
	 * @param \PhpParser\Node\Stmt\Function_ $functionAfter
	 */
	public function __construct(?string $fileBefore, Function_ $functionBefore, ?string $fileAfter, Function_ $functionAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->functionBefore = $functionBefore;
		$this->fileAfter = $fileAfter;
		$this->functionAfter = $functionAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return $this->functionAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PFunction::getFullyQualifiedName($this->functionAfter);
	}
}
