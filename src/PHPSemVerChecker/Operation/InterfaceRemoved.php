<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class InterfaceRemoved extends InterfaceOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V033';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was removed.';
}
