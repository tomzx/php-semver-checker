<?php

namespace PHPSemVerChecker\Console;

use Symfony\Component\Console\Input\ArgvInput;

class InspectableArgvInput extends ArgvInput
{
	/**
	 * Returns true if the argument value is set.
	 *
	 * @param string $name The argument name
	 *
	 * @return bool true if the argument is set (not a default value)
	 */
	public function hasArgumentSet($name)
	{
		return isset($this->arguments[$name]);
	}

	/**
	 * Returns true if the option value is set.
	 *
	 * @param string $name The option name
	 *
	 * @return bool true if the option is set (not a default value)
	 */
	public function hasOptionSet($name)
	{
		return isset($this->options[$name]);
	}
}
