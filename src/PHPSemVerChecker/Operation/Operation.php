<?php

namespace PHPSemVerChecker\Operation;

abstract class Operation {
	/**
	 * @var string
	 */
	protected $reason;
	/**
	 * @var string
	 */
	protected $location;

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
}