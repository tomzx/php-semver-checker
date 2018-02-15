<?php

namespace PHPSemVerChecker\Operation;

class InterfaceRemoved extends InterfaceOperationUnary {
	/**
	 * @var string
	 */
	protected $code = 'V033';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was removed.';
}
