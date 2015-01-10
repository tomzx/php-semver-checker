<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Method has been removed.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classBefore;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodBefore;

	/**
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt\Class_      $classBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 */
	public function __construct($fileBefore, Class_ $classBefore, ClassMethod $classMethodBefore)
	{
		$this->fileBefore = $fileBefore;
		$this->classBefore = $classBefore;
		$this->classMethodBefore = $classMethodBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		$fqcn = $this->classBefore->name;
		if ($this->classBefore->namespacedName) {
			$fqcn = $this->classBefore->namespacedName->toString();
		}
		return $this->fileBefore . '#' . $this->classMethodBefore->getLine() . ' ' . $fqcn . '::' . $this->classMethodBefore->name;
	}
}