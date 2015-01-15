<?php

namespace PHPSemVerChecker\Console\Command;

use File_Iterator_Facade;
use PHPSemVerChecker\Analyzer\Analyzer;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
				new InputOption('full-path', null, InputOption::VALUE_NONE, 'Display the full path to the file instead of the relative path'),
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$startTime = microtime(true);
		$fileIterator = new File_Iterator_Facade;
		$scannerBefore = new Scanner();
		$scannerAfter = new Scanner();

		$sourceBefore = $input->getArgument('source-before');
		$sourceBefore = $fileIterator->getFilesAsArray($sourceBefore, '.php');

		$sourceAfter = $input->getArgument('source-after');
		$sourceAfter = $fileIterator->getFilesAsArray($sourceAfter, '.php');

		$progress = new ProgressBar($output, count($sourceBefore) + count($sourceAfter));
		foreach ($sourceBefore as $file) {
			$scannerBefore->scan($file);
			$progress->advance();
		}

		foreach ($sourceAfter as $file) {
			$scannerAfter->scan($file);
			$progress->advance();
		}

		$progress->clear();

		$registryBefore = $scannerBefore->getRegistry();
		$registryAfter = $scannerAfter->getRegistry();

		$analyzer = new Analyzer();
		$report = $analyzer->analyze($registryBefore, $registryAfter);

		$reporter = new Reporter($report, $input);
		$reporter->output($output);

		$duration = microtime(true) - $startTime;
		$output->writeln('');
		$output->writeln('Time: ' . round($duration, 3) . ' seconds, Memory: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . ' MB');
	}
}
