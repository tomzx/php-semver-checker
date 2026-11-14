<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Node\Statement\Trait_ as PTrait;

class TraitOperationUnary extends Operation
{
	/**
	 * @var string
	 */
	protected $file;
	/**
	 * @var \PhpParser\Node\Stmt\Trait_
	 */
	protected $trait;

	/**
	 * @param string|null                 $file
	 * @param \PhpParser\Node\Stmt\Trait_ $trait
	 */
	public function __construct(?string $file, Trait_ $trait)
	{
		$this->file = $file;
		$this->trait = $trait;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return $this->trait->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return PTrait::getFullyQualifiedName($this->trait);
	}
}
