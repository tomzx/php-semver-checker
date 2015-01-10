<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodParameterMismatch extends Operation {
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter mismatch.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodAfter;

	/**
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodAfter
	 */
	public function __construct($fileBefore, ClassMethod $classMethodBefore, $fileAfter, ClassMethod $classMethodAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->classMethodBefore = $classMethodBefore;
		$this->fileAfter = $fileAfter;
		$this->classMethodAfter = $classMethodAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter . '#' . $this->classMethodAfter->getLine() . ' ' . $this->classMethodAfter->name;
	}
}