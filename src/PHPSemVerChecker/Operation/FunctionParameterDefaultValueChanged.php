<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterDefaultValueChanged extends FunctionOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V073';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter default value changed.';
}
