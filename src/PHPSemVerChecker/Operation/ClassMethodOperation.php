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
		return $this->code[$this->context][Visibility::get($this->visibility)];
	}

	public function getLevel()
	{
		return $this->level[$this->context][Visibility::get($this->visibility)];
	}

	public function getReason()
	{
		return '[' . Visibility::toString($this->visibility) . '] ' . $this->reason;
	}

	protected function getVisibility($context)
	{
		return Visibility::getForContext($context);
	}
}
