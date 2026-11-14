<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt;

abstract class PropertyOperation extends Operation
{
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
	public function getCode(): string
	{
		return $this->code[$this->context][Visibility::get($this->visibility)];
	}

	/**
	 * @return string
	 */
	public function getReason(): string
	{
		return '[' . Visibility::toString($this->visibility) . '] ' . $this->reason;
	}

	/**
	 * @param \PhpParser\Node\Stmt $context
	 * @return int
	 */
	protected function getVisibility(Stmt $context): int
	{
		return Visibility::getForContext($context);
	}
}
