<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterDefaultAdded extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V091', 'V092', 'V093'],
		'interface' => ['V077'],
		'trait'     => ['V109', 'V110', 'V111'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter default added.';
}
