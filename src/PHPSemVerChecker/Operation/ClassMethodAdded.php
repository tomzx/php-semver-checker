<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;

class ClassMethodAdded extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => 'V015',
		'interface' => 'V034',
		'trait' => 'V047',
	];
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var string
	 */
	protected $reason = 'Method has been added.';
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
	protected $classMethod;

	/**
	 * @param string                           $context
	 * @param string                           $fileAfter
	 * @param \PhpParser\Node\Stmt             $contextAfter
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
	 */
	public function __construct($context, $fileAfter, Stmt $contextAfter, ClassMethod $classMethod)
	{
		$this->context = $context;
		$this->fileAfter = $fileAfter;
		$this->contextAfter = $contextAfter;
		$this->classMethod = $classMethod;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->classMethod->getLine();
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
		return $fqcn . '::' . $this->classMethod->name;
	}
}
