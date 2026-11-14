<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class PropertyRemoved extends PropertyOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V008', 'V009', 'V027'],
		'trait' => ['V040', 'V041', 'V056'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Property has been removed.';
}
