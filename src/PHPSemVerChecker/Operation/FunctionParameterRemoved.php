<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\Node\Statement\Function_ as PFunction;

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
