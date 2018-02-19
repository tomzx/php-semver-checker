<?php

namespace PHPSemVerChecker\Operation;

class FunctionRenamedCaseOnly extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V160';
	/**
	 * @var string
	 */
	protected $reason = 'Function renamed (case only).';
}
