<?php
declare(strict_types=1);

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
	 * @param string|null $fileBefore
	 * @param string|null $fileAfter
	 */
	public function __construct(string $fileBefore = null, string $fileAfter = null)
	{
		$this->fileBefore = $fileBefore;
		$this->fileAfter = $fileAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation(): string
	{
		return $this->fileBefore . ' ' . $this->fileAfter;
	}

	/**
	 * @return int
	 */
	public function getLine(): int
	{
		return 0;
	}
}
