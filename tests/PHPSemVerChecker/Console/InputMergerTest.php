<?php

namespace PHPSemVerChecker\Test\Console;

use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\Console\Command\CompareCommand;
use PHPSemVerChecker\Console\InputMerger;
use Symfony\Component\Console\Input\StringInput;

class InputMergerTest extends \PHPUnit_Framework_TestCase
{
	public function testMerge()
	{
		$config = new Configuration([]);
		// Prepare options and arguments
		$config->set('include-before', 'in-before config');
		$config->set('source-after', 'src-after config');

		// Specify options and arguments for input
		$input = new StringInput('--include-before "in-before cli" "src-before cli"');
		$command = new CompareCommand();
		$input->bind($command->getDefinition());
		$this->assertEquals('in-before cli', $input->getOption('include-before'), 'Test setup: Could not prepare input arguments');

		$im = new InputMerger();
		$im->merge($input, $config);
		$this->assertEquals('in-before cli', $config->get('include-before'), 'Configuration must be overwritten by CLI option');
		$this->assertEquals('src-before cli', $config->get('source-before'), 'Configuration must be overwritten by CLI argument');
		$this->assertEquals('src-before cli', $input->getArgument('source-before'), 'Input arguments must not be overwritten by empty configuration');
		$this->assertEquals('src-after config', $config->get('source-after'), 'Configuration must not be overwritten by empty CLI argument');
		$this->assertEquals('src-after config', $input->getArgument('source-after'), 'Missing input arguments must take on existing configuration');
	}
}
