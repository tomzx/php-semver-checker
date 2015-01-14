<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PHPSemVerChecker\SemanticVersioning\Level;

class ClassRemoved extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V005';
	/**
	 * @var int
	 */
	protected $level = Level::MAJOR;
	/**
	 * @var string
	 */
	protected $reason = 'Class was removed.';
	/**
	 * @var
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classBefore;

	/**
	 * @param string                      $fileBefore
	 * @param \PhpParser\Node\Stmt\Class_ $classBefore
	 */
	public function __construct($fileBefore, Class_ $classBefore)
	{
		$this->fileBefore = $fileBefore;
		$this->classBefore = $classBefore;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore;
	}

	public function getLine()
	{
		$this->classBefore->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqcn = $this->classBefore->name;
		if ($this->classBefore->namespacedName) {
			$fqcn = $this->classBefore->namespacedName->toString();
		}
		return $fqcn;
	}
}
