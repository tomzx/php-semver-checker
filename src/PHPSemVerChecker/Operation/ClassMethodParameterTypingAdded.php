<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassMethodParameterTypingAdded extends ClassMethodOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class'     => ['V085', 'V086', 'V087'],
		'interface' => ['V075'],
		'trait'     => ['V103', 'V104', 'V105'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Method parameter typing added.';
}
