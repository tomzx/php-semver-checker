<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionParameterTypingRemoved extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V070';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter typing removed.';
}
