<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodImplementationChanged extends ClassMethodOperationDelta
{
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V023', 'V024', 'V025'],
		'trait' => ['V052', 'V053', 'V054'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method implementation changed.';
}
