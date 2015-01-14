<?php

namespace fixtures;

trait TraitMethodImplementationChanged
{
	public function publicMethod()
	{
		$x = 3;
	}

	protected function protectedMethod()
	{
		$x = 3;
	}

	private function privateMethod()
	{
		$x = 3;
	}
}
