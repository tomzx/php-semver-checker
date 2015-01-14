<?php

namespace PHPSemVerChecker\Operation;

abstract class Operation {
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
	protected $location;
	/**
	 * @var string
	 */
	protected $target;

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 * @return $this
	 */
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getReason()
	{
		return $this->reason;
	}

	/**
	 * @param string $reason
	 * @return $this
	 */
	public function setReason($reason)
	{
		$this->reason = $reason;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->location;
	}

	/**
	 * @param string $location
	 * @return $this
	 */
	public function setLocation($location)
	{
		$this->location = $location;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * @param string $target
	 * @return $this
	 */
	public function setTarget($target)
	{
		$this->target = $target;

		return $this;
	}
}
