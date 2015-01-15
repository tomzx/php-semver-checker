<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSemVerChecker\Node\Statement\ClassMethod as PClassMethod;
use PHPSemVerChecker\SemanticVersioning\Level;

class ClassMethodImplementationChanged extends ClassMethodOperation {
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V023', 'V024', 'V025'],
		'trait' => ['V052', 'V053', 'V054'],
	];
	/**
	 * @var int
	 */
	protected $level = [
		'class' => [Level::PATCH, Level::PATCH, Level::PATCH],
		'trait' => [Level::PATCH, Level::PATCH, Level::PATCH],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method implementation changed.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var Stmt
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
	 * @var Stmt
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
		return PClassMethod::getFullyQualifiedName($this->contextAfter, $this->classMethodAfter);
	}
}
