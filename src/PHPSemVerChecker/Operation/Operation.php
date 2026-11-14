<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

use PHPSemVerChecker\Configuration\LevelMapping;

abstract class Operation
{
	/**
	 * @var string
	 */
	protected $code;
	/**
	 * @var string
	 */
	protected $reason;
	/**
	 * @var string
	 */
	protected $target;

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 * @return $this
	 */
	public function setCode(string $code): Operation
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getLevel(): int
	{
		return LevelMapping::getLevelForCode($this->getCode());
	}

	/**
	 * @return string
	 */
	public function getReason(): string
	{
		return $this->reason;
	}

	/**
	 * @param string $reason
	 * @return $this
	 */
	public function setReason(string $reason): Operation
	{
		$this->reason = $reason;

		return $this;
	}

	/**
	 * @return string
	 */
	public abstract function getLocation(): string;

	/**
	 * @return int
	 */
	public abstract function getLine(): int;

	/**
	 * @return string
	 */
	public function getTarget(): string
	{
		return $this->target;
	}

	/**
	 * @param string $target
	 * @return $this
	 */
	public function setTarget(string $target): Operation
	{
		$this->target = $target;

		return $this;
	}
}
