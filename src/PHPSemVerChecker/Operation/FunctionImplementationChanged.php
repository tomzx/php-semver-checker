<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionImplementationChanged extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V004';
	/**
	 * @var string
	 */
	protected $reason = 'Function implementation changed.';
}
