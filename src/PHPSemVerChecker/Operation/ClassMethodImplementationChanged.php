<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodImplementationChanged extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Method implementation changed.';
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
	protected $classMethodAfter;

	/**
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt\Class_      $classBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt\Class_      $classAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodAfter
	 */
	public function __construct($fileBefore, Class_ $classBefore, ClassMethod $classMethodBefore, $fileAfter, Class_ $classAfter, ClassMethod $classMethodAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->classBefore = $classBefore;
		$this->classMethodBefore = $classMethodBefore;
		$this->fileAfter = $fileAfter;
		$this->classAfter = $classAfter;
		$this->classMethodAfter = $classMethodAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter . '#' . $this->classMethodAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->classAfter->name;
		if ($this->classAfter->namespacedName) {
			$fqcn = $this->classAfter->namespacedName->toString();
		}
		return $fqcn . '::' . $this->classMethodBefore->name;
	}
}