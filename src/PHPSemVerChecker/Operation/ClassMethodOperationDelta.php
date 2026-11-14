<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Node\Statement\ClassMethod as PClassMethod;

abstract class ClassMethodOperationDelta extends Operation
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
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt
	 */
	protected $contextBefore;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt
	 */
	protected $contextAfter;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodAfter;

	/**
	 * @param string                           $context
	 * @param string|null                      $fileBefore
	 * @param \PhpParser\Node\Stmt             $contextBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 * @param string|null                      $fileAfter
	 * @param \PhpParser\Node\Stmt             $contextAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodAfter
	 */
	public function __construct(string      $context,
								?string      $fileBefore,
								Stmt        $contextBefore,
								ClassMethod $classMethodBefore,
								?string      $fileAfter,
								Stmt        $contextAfter,
								ClassMethod $classMethodAfter)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($classMethodAfter);
		$this->fileBefore = $fileBefore;
		$this->contextBefore = $contextBefore;
		$this->classMethodBefore = $classMethodBefore;
		$this->fileAfter = $fileAfter;
		$this->contextAfter = $contextAfter;
		$this->classMethodAfter = $classMethodAfter;
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
		return $this->classMethodAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PClassMethod::getFullyQualifiedName($this->contextAfter, $this->classMethodAfter);
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
