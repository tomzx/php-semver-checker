<?php

namespace PHPSemVerChecker\Test\Console;

use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\Console\Command\CompareCommand;
use PHPSemVerChecker\Console\InputMerger;
use PHPSemVerChecker\Console\InspectableArgvInput;
use Symfony\Component\Console\Input\StringInput;

class InputMergerTest extends \PHPUnit_Framework_TestCase
{
	public function testMerge()
	{
		$config = new Configuration([]);
		// Prepare options and arguments
		$config->set('include-before', 'in-before config');
		$config->set('source-after', 'src-after config');
		$config->set('full-path', true);

		// Specify options and arguments for input
		$input = new InspectableArgvInput([null, '--include-before', 'in-before cli', 'src-before cli']);
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
		$this->assertEquals(true, $config->get('full-path'), 'CLI option should use Configuration value and not CLI default');
	}

	/**
	 * @expectedException \Symfony\Component\Console\Exception\RuntimeException
	 */
	public function testEmptyInputShouldThrowException()
	{
		// Default/empty configuration
		$config = new Configuration([]);
		// No input arguments
		$input = new InspectableArgvInput([null]);
		$command = new CompareCommand();
		$input->bind($command->getDefinition());
		$im = new InputMerger();
		$im->merge($input, $config);
		$input->validate();
	}
}
