<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodReturnTypeAdded extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V161'],
		'interface' => ['V162'],
		'trait'     => ['V163'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method return type added.';
}
