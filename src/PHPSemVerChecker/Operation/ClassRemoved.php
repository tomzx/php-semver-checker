<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;

class ClassRemoved extends Operation {
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
		return $this->fileBefore . '#' . $this->classBefore->getLine();
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