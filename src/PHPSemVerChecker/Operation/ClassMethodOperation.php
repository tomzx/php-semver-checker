<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

abstract class ClassMethodOperation extends Operation {
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var int
	 */
	protected $visibility;

	public function getCode()
	{
		$visiblityMapping = $this->getVisibilityMapping();
		return $this->code[$this->context][$visiblityMapping[$this->visibility]];
	}

	public function getLevel()
	{
		$visiblityMapping = $this->getVisibilityMapping();
		return $this->level[$this->context][$visiblityMapping[$this->visibility]];
	}

	protected function getVisibilityMapping()
	{
		return [
			Class_::MODIFIER_PUBLIC    => 0,
			Class_::MODIFIER_PROTECTED => 1,
			Class_::MODIFIER_PRIVATE   => 2,
		];
	}

	protected function getVisibility(ClassMethod $classMethod)
	{
		if ($classMethod->isPublic()) {
			return Class_::MODIFIER_PUBLIC;
		} elseif ($classMethod->isProtected()) {
			return Class_::MODIFIER_PROTECTED;
		} else {
			return Class_::MODIFIER_PRIVATE;
		}
	}
}
