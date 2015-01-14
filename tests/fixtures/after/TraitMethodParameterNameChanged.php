<?php

namespace fixtures;

trait TraitMethodParameterNameChanged
{
	public function publicMethod($someOtherParameterName)
	{

	}

	protected function protectedMethod($someOtherParameterName)
	{

	}

	private function privateMethod($someOtherParameterName)
	{

	}
}
