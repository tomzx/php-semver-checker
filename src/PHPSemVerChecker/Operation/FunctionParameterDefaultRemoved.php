<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterDefaultRemoved extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V072';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter default removed.';
}
