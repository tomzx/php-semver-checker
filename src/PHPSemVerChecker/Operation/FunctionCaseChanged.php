<?php

namespace PHPSemVerChecker\Operation;

class FunctionCaseChanged extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V160';
	/**
	 * @var string
	 */
	protected $reason = 'Function name case was changed.';
}
