<?php

namespace PHPSemVerChecker\Console\Command;

use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScanCommand extends Command {
	protected function configure()
	{
		$this
			->setName('scan')
			->setDescription('Scan a set of files to determine what semantic versioning change needs to be done')
			->setDefinition([
				new InputOption('file', null, InputOption::VALUE_REQUIRED, 'A single file to check'),
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = $input->getOption('file');
		$scanner = new Scanner();
		$scanner->scan($file);
	}
}