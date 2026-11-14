<?php
declare(strict_types=1);

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
