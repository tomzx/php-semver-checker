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
		$missingArguments = $this->getKeysOfNullValues($input->getArguments());
		foreach ($missingArguments as $key) {
			$input->setArgument($key, $config->get($key));
		}
		$missingOptions = $this->getKeysOfNullValues($input->getOptions());
		foreach ($missingOptions as $key) {
			$input->setOption($key, $config->get($key));
		}
		$config->merge(array_merge($input->getArguments(), $input->getOptions()));
	}

	/**
	 * @param array $array
	 * @return array
	 */
	private function getKeysOfNullValues(array $array)
	{
		return array_keys(array_filter($array, 'is_null'));
	}
}