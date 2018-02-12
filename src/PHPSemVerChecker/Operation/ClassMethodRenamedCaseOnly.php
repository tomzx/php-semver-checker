<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodRenamedCaseOnly extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V150'],
		'interface' => ['V151'],
		'trait' => ['V152'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method has been renamed (case only).';
}
