<?php

namespace fixtures;

trait TraitMethodImplementationChanged
{
	public function publicMethod()
	{
		$x = 0;
	}

	protected function protectedMethod()
	{
		$x = 0;
	}

	private function privateMethod()
	{
		$x = 0;
	}
}
