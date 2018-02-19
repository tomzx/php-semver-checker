<?php

namespace PHPSemVerChecker\Operation;

class ClassAdded extends ClassOperationUnary {
	/**
	 * @var string
	 */
	protected $code = 'V014';
	/**
	 * @var string
	 */
	protected $reason = 'Class was added.';
}
