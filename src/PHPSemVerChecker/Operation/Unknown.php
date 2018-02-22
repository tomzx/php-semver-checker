<?php

namespace PHPSemVerChecker\Operation;

class Unknown extends Operation
{
	/**
	 * @var string
	 */
	protected $code = 'V000';
	/**
	 * @var string
	 */
	protected $reason = 'Reason unknown.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;

	/**
	 * @param string $fileBefore
	 * @param string $fileAfter
	 */
	public function __construct($fileBefore = null, $fileAfter = null)
	{
		$this->fileBefore = $fileBefore;
		$this->fileAfter = $fileAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore . ' ' . $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return 0;
	}
}
