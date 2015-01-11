<?php

namespace PHPSemVerChecker\Console\Command;

use File_Iterator_Facade;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareCommand extends Command {
	protected function configure()
	{
		$this
			->setName('compare')
			->setDescription('Compare a set of files to determine what semantic versioning change needs to be done')
			->setDefinition([
				new InputArgument('source-before', InputArgument::REQUIRED, 'A directory to check'),
				new InputArgument('source-after', InputArgument::REQUIRED, 'A directory to check against'),
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$fileIterator = new File_Iterator_Facade;
		$beforeScanner = new Scanner();
		$afterScanner = new Scanner();

		$beforeFiles = $input->getArgument('source-before');
		$beforeFiles = $fileIterator->getFilesAsArray($beforeFiles, '.php');

		$afterFiles = $input->getArgument('source-after');
		$afterFiles = $fileIterator->getFilesAsArray($afterFiles, '.php');

		$progress = new ProgressBar($output, count($beforeFiles) + count($afterFiles));
		foreach ($beforeFiles as $file) {
			$beforeScanner->scan($file);
			$progress->advance();
		}

		foreach ($afterFiles as $file) {
			$afterScanner->scan($file);
			$progress->advance();
		}

		$progress->clear();

		$beforeRegistry = $beforeScanner->getRegistry();
		$afterRegistry = $afterScanner->getRegistry();

		(new Reporter())->output($beforeRegistry, $afterRegistry, $output);
	}
}