<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class InterfaceAdded extends InterfaceOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V032';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was added.';
}
