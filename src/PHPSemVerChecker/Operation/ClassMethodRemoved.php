<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Node\Statement\ClassMethod as PClassMethod;

class ClassMethodRemoved extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V006', 'V007', 'V029'],
		'interface' => ['V035'],
		'trait'     => ['V038', 'V039', 'V058'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method has been removed.';
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
	 * @param string                           $context
	 * @param string                           $fileBefore
	 * @param \PhpParser\Node\Stmt             $contextBefore
	 * @param \PhpParser\Node\Stmt\ClassMethod $classMethodBefore
	 */
	public function __construct($context, $fileBefore, Stmt $contextBefore, ClassMethod $classMethodBefore)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($classMethodBefore);
		$this->fileBefore = $fileBefore;
		$this->contextBefore = $contextBefore;
		$this->classMethodBefore = $classMethodBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->classMethodBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PClassMethod::getFullyQualifiedName($this->contextBefore, $this->classMethodBefore);
	}
}
