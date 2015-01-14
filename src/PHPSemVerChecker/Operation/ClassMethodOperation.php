<?php

namespace PHPSemVerChecker\Operation;

class ClassMethodOperation extends Operation
{
	public function getCode()
	{
		return $this->code[$this->context];
	}
}
