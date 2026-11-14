<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodRemoved extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V006', 'V007', 'V029'],
		'interface' => ['V035'],
		'trait'     => ['V038', 'V039', 'V058'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method has been removed.';
}
