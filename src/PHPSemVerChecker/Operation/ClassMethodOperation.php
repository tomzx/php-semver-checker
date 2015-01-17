<?php

namespace PHPSemVerChecker\Operation;

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

	public function getReason()
	{
		return '[' . Visibility::toString($this->visibility) . '] ' . $this->reason;
	}

	protected function getVisibility($context)
	{
		return Visibility::getForContext($context);
	}
}
