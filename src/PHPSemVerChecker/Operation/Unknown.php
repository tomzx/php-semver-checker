<?php

namespace PHPSemVerChecker\Operation;

class Unknown extends Operation {
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
}