<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionRemoved extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V001';
	/**
	 * @var string
	 */
	protected $reason = 'Function has been removed.';
}
