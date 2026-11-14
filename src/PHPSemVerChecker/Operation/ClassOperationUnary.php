<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PHPSemVerChecker\Node\Statement\Class_ as PClass;

class ClassOperationUnary extends Operation
{
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $class;

	/**
	 * @param string|null                 $file
	 * @param \PhpParser\Node\Stmt\Class_ $class
	 */
	public function __construct(?string $file, Class_ $class)
	{
		$this->file = $file;
		$this->class = $class;
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
		return $this->class->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PClass::getFullyQualifiedName($this->class);
	}
}
