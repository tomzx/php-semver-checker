<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterRemoved extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V082', 'V083', 'V084'],
		'interface' => ['V074'],
		'trait'     => ['V100', 'V101', 'V102'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter removed.';
}
