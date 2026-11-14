<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class FunctionAdded extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V003';
	/**
	 * @var string
	 */
	protected $reason = 'Function has been added.';
}
