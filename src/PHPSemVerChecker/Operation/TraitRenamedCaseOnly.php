<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Trait_;
use PHPSemVerChecker\Node\Statement\Trait_ as PTrait;

class TraitRenamedCaseOnly extends TraitOperationDelta {
	/**
	 * @var string
	 */
	protected $code = 'V155';
	/**
	 * @var string
	 */
	protected $reason = 'Trait was renamed (case only).';
}
