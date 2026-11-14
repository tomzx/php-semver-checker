<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodCaseChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V150', 'V156', 'V157'],
		'interface' => ['V151'],
		'trait'     => ['V152', 'V158', 'V159'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method name case was changed.';
}
