<?php

namespace PHPSemVerChecker\Operation;

class TraitRemoved extends TraitOperationUnary {
	/**
	 * @var string
	 */
	protected $code = 'V037';
	/**
	 * @var string
	 */
	protected $reason = 'Trait was removed.';
}
