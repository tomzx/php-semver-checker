<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodAdded extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V015', 'V016', 'V028'],
		'interface' => ['V034'],
		'trait'     => ['V047', 'V048', 'V057'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method has been added.';
}
