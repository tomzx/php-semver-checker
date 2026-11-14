<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterNameChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V060', 'V061', 'V062'],
		'interface' => ['V063'],
		'trait'     => ['V064', 'V065', 'V066'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter name changed.';
}
