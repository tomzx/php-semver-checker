<?php

namespace PHPSemVerChecker\Operation;

class InterfaceRenamedCaseOnly extends InterfaceOperationDelta {
	/**
	 * @var string
	 */
	protected $code = 'V153';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was renamed (case only).';
}
