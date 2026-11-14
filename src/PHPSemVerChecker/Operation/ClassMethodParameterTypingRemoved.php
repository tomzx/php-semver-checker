<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterTypingRemoved extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V088', 'V089', 'V090'],
		'interface' => ['V076'],
		'trait'     => ['V106', 'V107', 'V108'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter typing removed.';
}
