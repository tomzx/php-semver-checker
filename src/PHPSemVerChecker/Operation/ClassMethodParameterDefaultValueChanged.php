<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterDefaultValueChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V097', 'V098', 'V099'],
		'interface' => ['V079'],
		'trait'     => ['V115', 'V116', 'V117'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter default value changed.';
}
