<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodReturnTypeChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V164'],
		'interface' => ['V165'],
		'trait'     => ['V166'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method return type changed.';
}
