<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassRemoved extends ClassOperationUnary
{
	/**
	 * @var string
	 */
	protected $code = 'V005';
	/**
	 * @var string
	 */
	protected $reason = 'Class was removed.';
}
