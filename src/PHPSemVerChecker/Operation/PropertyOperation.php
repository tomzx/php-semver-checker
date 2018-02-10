<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;

abstract class PropertyOperation extends Operation {
	/**
	 * @var string
	 */
	protected $context;
	/**
	 * @var int
	 */
	protected $visibility;

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code[$this->context][Visibility::get($this->visibility)];
	}

	/**
	 * @return string
	 */
	public function getReason()
	{
		return '[' . Visibility::toString($this->visibility) . '] ' . $this->reason;
	}

	/**
	 * @param \PhpParser\Node\Stmt $context
	 * @return int
	 */
	protected function getVisibility(Stmt $context)
	{
		return Visibility::getForContext($context);
	}
}
