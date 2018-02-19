<?php

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
	 * @param string                      $file
	 * @param \PhpParser\Node\Stmt\Class_ $class
	 */
	public function __construct($file, Class_ $class)
	{
		$this->file = $file;
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->class->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PClass::getFullyQualifiedName($this->class);
	}
}
