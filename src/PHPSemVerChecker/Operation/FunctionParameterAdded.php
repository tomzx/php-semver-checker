<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterAdded extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V002';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter added.';
}
