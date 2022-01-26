<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodReturnTypeChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V167'],
		'interface' => ['V168'],
		'trait'     => ['V169'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method return type was changed.';
}
