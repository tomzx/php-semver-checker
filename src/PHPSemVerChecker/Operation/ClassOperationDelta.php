<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PHPSemVerChecker\Node\Statement\Class_ as PClass;

class ClassOperationDelta extends Operation
{
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classAfter;

	/**
	 * @param string                      $file
	 * @param \PhpParser\Node\Stmt\Class_ $class
	 */
	public function __construct($fileBefore, Class_ $classBefore, $fileAfter, Class_ $classAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->classBefore = $classBefore;
		$this->fileAfter = $fileAfter;
		$this->classAfter = $classAfter;
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
		return $this->classAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PClass::getFullyQualifiedName($this->classAfter);
	}
}
