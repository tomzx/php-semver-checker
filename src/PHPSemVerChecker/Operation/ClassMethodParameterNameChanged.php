<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\SemanticVersioning\Level;

class ClassMethodParameterNameChanged extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V060', 'V061', 'V062'],
		'interface' => ['V063'],
		'trait' => ['V064', 'V065', 'V066'],
	];
	/**
	 * @var int
	 */
	protected $level = [
		'class' => [Level::PATCH, Level::PATCH, Level::PATCH],
		'interface' => [Level::PATCH],
		'trait' => [Level::PATCH, Level::PATCH, Level::PATCH],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter name changed.';
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
	public function __construct($context,
								$fileBefore,
								Stmt $contextBefore,
								ClassMethod $classMethodBefore,
								$fileAfter,
								Stmt $contextAfter,
								ClassMethod $classMethodAfter)
	{
		$this->context = $context;
		$this->visibility = $this->getVisibility($classMethodAfter);
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
		return $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->classMethodAfter->getLine();
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
