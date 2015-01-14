<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodRemoved extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => 'V006',
		'interface' => 'V035',
		'trait' => 'V038',
	];
	/**
	 * @var string
	 */
	protected $context;
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
	protected $contextBefore;
	/**
	 * @var \PhpParser\Node\Stmt\ClassMethod
	 */
	protected $classMethodBefore;

	/**
	 * @param string                           $context
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt             $contextBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 */
	public function __construct($context, $fileBefore, Stmt $contextBefore, ClassMethod $classMethodBefore)
	{
		$this->context = $context;
		$this->fileBefore = $fileBefore;
		$this->contextBefore = $contextBefore;
		$this->classMethodBefore = $classMethodBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore . '#' . $this->classMethodBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->contextBefore->name;
		if ($this->contextBefore->namespacedName) {
			$fqcn = $this->contextBefore->namespacedName->toString();
		}
		return $fqcn . '::' . $this->classMethodBefore->name;
	}
}
