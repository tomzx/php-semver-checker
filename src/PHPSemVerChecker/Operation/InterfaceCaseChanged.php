<?php

namespace PHPSemVerChecker\Operation;

class InterfaceCaseChanged extends InterfaceOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V153';
	/**
	 * @var string
	 */
	protected $reason = 'Interface name case was changed.';
}
