<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Node\Statement\ClassMethod as PClassMethod;

abstract class ClassMethodOperationUnary extends Operation
{
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var int
	 */
	protected $visibility;
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt
	 */
	protected $contextValue;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethod;

	/**
	 * @param string                           $context
	 * @param string|null                      $file
	 * @param \PhpParser\Node\Stmt             $contextValue
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
	 */
	public function __construct(string $context, ?string $file, Stmt $contextValue, ClassMethod $classMethod)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($classMethod);
		$this->file = $file;
		$this->contextValue = $contextValue;
		$this->classMethod = $classMethod;
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
		return $this->classMethod->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PClassMethod::getFullyQualifiedName($this->contextValue, $this->classMethod);
	}

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code[$this->context][Visibility::get($this->visibility)];
	}

	/**
	 * @return string
	 */
	public function getReason(): string
	{
		return '[' . Visibility::toString($this->visibility) . '] ' . $this->reason;
	}

	/**
	 * @param mixed $context
	 * @return int
	 */
	protected function getVisibility($context): int
	{
		return Visibility::getForContext($context);
	}
}
