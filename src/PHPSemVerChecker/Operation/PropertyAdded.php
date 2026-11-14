<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Operation;

class PropertyAdded extends PropertyOperationUnary
{
	/**
	 * @var array
	 */
	protected $code = [
		'class' => ['V019', 'V020', 'V026'],
		'trait' => ['V049', 'V050', 'V055'],
	];
	/**
	 * @var string
	 */
	protected $reason = 'Property has been added.';
}
