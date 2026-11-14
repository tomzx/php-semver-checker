<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterRemoved extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V068';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter removed.';
}
