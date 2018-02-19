<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodRenamedCaseOnly extends ClassMethodOperationUnary
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
