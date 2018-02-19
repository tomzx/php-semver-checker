<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodCaseChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V150', 'V156', 'V157'],
		'interface' => ['V151'],
		'trait' => ['V152', 'V158', 'V159'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method has been renamed (case only).';
}
