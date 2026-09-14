<?php

namespace PHPSemVerChecker\Operation;

class FunctionReturnTypeRemoved extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V172';
	/**
	 * @var string
	 */
	protected $reason = 'Function return type removed.';
}
