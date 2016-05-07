<?php

namespace PHPSemVerChecker\Operation;

class FunctionParameterTypingAdded extends FunctionOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V069';
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter typing added.';
}
