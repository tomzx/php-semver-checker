<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodAdded extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Method has been added.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classAfter;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethod;

	/**
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt\Class_      $classAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
	 */
	public function __construct($fileAfter, Class_ $classAfter, ClassMethod $classMethod)
	{
		$this->fileAfter = $fileAfter;
		$this->classAfter = $classAfter;
		$this->classMethod = $classMethod;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		$fqcn = $this->classAfter->name;
		if ($this->classAfter->namespacedName) {
			$fqcn = $this->classAfter->namespacedName->toString();
		}
		return $this->fileAfter . '#' . $this->classMethod->getLine() . ' ' . $fqcn . '::' . $this->classMethod->name;
	}
}