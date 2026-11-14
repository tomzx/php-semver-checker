<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterDefaultAdded extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V071';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter default added.';
}
