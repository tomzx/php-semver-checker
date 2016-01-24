<?php

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Configuration\Configuration;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Merges CLI input with existing configuration values.
 *
 * This is to ensure that CLI input has priority and is prepared for validation
 * by symfony/console commands.
 */
class InputMerger
{
	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \PHPSemVerChecker\Configuration\Configuration $config
	 */
	public function merge(InputInterface $input, Configuration $config)
	{
		foreach ($input->getArguments() as $argument => $value) {
			if ($input->hasArgumentSet($argument)) {
				$config->set($argument, $value);
			} else {
				$input->setArgument($argument, $config->get($argument));
			}
		}

		foreach ($input->getOptions() as $option => $value) {
			if ($input->hasOptionSet($option)) {
				$config->set($option, $value);
			} else {
				$input->setOption($option, $config->get($option));
			}
		}
	}
}
