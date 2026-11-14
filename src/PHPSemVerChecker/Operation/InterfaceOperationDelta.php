<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Interface_;
use PHPSemVerChecker\Node\Statement\Interface_ as PInterface;

class InterfaceOperationDelta extends Operation
{
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interfaceBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Interface_
	 */
	protected $interfaceAfter;

	/**
	 * @param string|null                     $fileBefore
	 * @param \PhpParser\Node\Stmt\Interface_ $interfaceBefore
	 * @param string|null                     $fileAfter
	 * @param \PhpParser\Node\Stmt\Interface_ $interfaceAfter
	 */
	public function __construct(?string $fileBefore, Interface_ $interfaceBefore, ?string $fileAfter, Interface_ $interfaceAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->interfaceBefore = $interfaceBefore;
		$this->fileAfter = $fileAfter;
		$this->interfaceAfter = $interfaceAfter;
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
		return $this->interfaceAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PInterface::getFullyQualifiedName($this->interfaceAfter);
	}
}
