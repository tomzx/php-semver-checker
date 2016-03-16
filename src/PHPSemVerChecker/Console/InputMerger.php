<?php

namespace PHPSemVerChecker\Console;

use PHPSemVerChecker\Configuration\Configuration;
use Symfony\Component\Console\Input\InputDefinition;
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
	 * @param \Symfony\Component\Console\Input\InputInterface  $input           Actual input
	 * @param \Symfony\Component\Console\Input\InputDefinition $inputDefinition Definition of input arguments/options
	 * @param \PHPSemVerChecker\Configuration\Configuration    $config
	 */
	public function merge(InputInterface $input, InputDefinition $inputDefinition, Configuration $config)
	{
		foreach ($input->getArguments() as $argument => $value) {
			if ($value !== null) {
				$config->set($argument, $value);
			} else {
				$configValue = $config->get($argument);
				// Only set an argument from config if actually known
				if ($configValue !== null) {
					$input->setArgument($argument, $configValue);
				}
			}
		}

		foreach ($input->getOptions() as $optionName => $value) {
			$option = $inputDefinition->getOption($optionName);
			// Make sure VALUE_NONE is only used when differing from default
			if ((!$option->acceptValue() && $value !== $option->getDefault()) || ($option->acceptValue() && $value !== null)) {
				$config->set($optionName, $value);
			} else {
				$configValue = $config->get($optionName);
				if ($configValue !== null) {
					$input->setOption($optionName, $configValue);
				}
			}
		}
	}
}
