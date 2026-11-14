<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class ClassCaseChanged extends ClassOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V154';
	/**
	 * @var string
	 */
	protected $reason = 'Class name case was changed.';
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Class_
	 */
	protected $classAfter;
}
