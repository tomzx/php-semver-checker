<?php

namespace PHPSemVerChecker\Operation;

class TraitCaseChanged extends TraitOperationDelta
{
	/**
	 * @var string
	 */
	protected $code = 'V155';
	/**
	 * @var string
	 */
	protected $reason = 'Trait name case was changed.';
}
