<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Stmt;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodImplementationChanged extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => 'V023',
		'trait' => 'V038',
	];
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var string
	 */
	protected $reason = 'Method implementation changed.';
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
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt             $contextBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt             $contextAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodAfter
	 */
	public function __construct($context, $fileBefore, \PhpParser\Node\Stmt $contextBefore, ClassMethod $classMethodBefore, $fileAfter, \PhpParser\Node\Stmt $contextAfter, ClassMethod $classMethodAfter)
	{
		$this->context = $context;
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
	public function getLocation()
	{
		return $this->fileAfter . '#' . $this->classMethodAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->contextAfter->name;
		if ($this->contextAfter->namespacedName) {
			$fqcn = $this->contextAfter->namespacedName->toString();
		}
		return $fqcn . '::' . $this->classMethodBefore->name;
	}
}
