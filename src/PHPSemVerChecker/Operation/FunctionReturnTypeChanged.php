<?php

namespace PHPSemVerChecker\Operation;

class FunctionReturnTypeChanged extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V171';
	/**
	 * @var string
	 */
	protected $reason = 'Function return type was changed.';
}
