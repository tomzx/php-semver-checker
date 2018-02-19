<?php

namespace PHPSemVerChecker\Operation;

class InterfaceAdded extends InterfaceOperationUnary {
	/**
	 * @var string
	 */
	protected $code = 'V032';
	/**
	 * @var string
	 */
	protected $reason = 'Interface was added.';
}
