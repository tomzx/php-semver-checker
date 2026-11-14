<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;

abstract class FunctionOperationUnary extends Operation
{
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $function;

	/**
	 * @param string|null                    $file
	 * @param \PhpParser\Node\Stmt\Function_ $function
	 */
	public function __construct(?string $file, Function_ $function)
	{
		$this->file = $file;
		$this->function = $function;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return $this->function->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PFunction::getFullyQualifiedName($this->function);
	}
}
