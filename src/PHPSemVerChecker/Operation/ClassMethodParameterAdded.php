<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterAdded extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V010', 'V011', 'V031'],
		'interface' => ['V036'],
		'trait'     => ['V042', 'V043', 'V059'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter added.';
}
