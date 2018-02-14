<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PHPSemVerChecker\Node\Statement\Class_ as PClass;

class ClassRenamedCaseOnly extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V154';
	/**
	 * @var string
	 */
	protected $reason = 'Class was renamed (case only).';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classAfter;

	/**
	 * @param string                      $fileAfter
	 * @param \PhpParser\Node\Stmt\Class_ $classAfter
	 */
	public function __construct($fileAfter, Class_ $classAfter)
	{
		$this->fileAfter = $fileAfter;
		$this->classAfter = $classAfter;
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
		return $this->classAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return PClass::getFullyQualifiedName($this->classAfter);
	}
}
