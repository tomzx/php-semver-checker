<?php

namespace PHPSemVerChecker\Operation;

class FunctionReturnTypeAdded extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V170';
	/**
	 * @var string
	 */
	protected $reason = 'Function return type was added.';
}
