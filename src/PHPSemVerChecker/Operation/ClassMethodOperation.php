<?php

namespace PHPSemVerChecker\Operation;

abstract class ClassMethodOperation extends Operation
{
	public function getCode()
	{
		return $this->code[$this->context];
	}
}
