<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterDefaultRemoved extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V094', 'V095', 'V096'],
		'interface' => ['V078'],
		'trait'     => ['V112', 'V113', 'V114'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter default removed.';
}
