<?php

namespace PHPSemVerChecker\Console\Command;

use File_Iterator_Facade;
use PHPSemVerChecker\Analyzer\Analyzer;
use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\Configuration\LevelMapping;
use PHPSemVerChecker\Filter\SourceFilter;
use PHPSemVerChecker\Reporter\JsonReporter;
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
				new InputOption('config', 'c', InputOption::VALUE_REQUIRED, 'A configuration file to configure php-semver-checker'),
				new InputOption('to-json', null, InputOption::VALUE_REQUIRED, 'Output the result to a JSON file')
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$startTime = microtime(true);

		$config = $input->getOption('config');
		$configuration = $config ? Configuration::fromFile($config) : new Configuration();

		// Set overrides
		LevelMapping::setOverrides($configuration->getLevelMapping());

		$fileIterator = new File_Iterator_Facade;
		$scannerBefore = new Scanner();
		$scannerAfter = new Scanner();

		$sourceBefore = $input->getArgument('source-before');
		$sourceBefore = $fileIterator->getFilesAsArray($sourceBefore, '.php');

		$sourceAfter = $input->getArgument('source-after');
		$sourceAfter = $fileIterator->getFilesAsArray($sourceAfter, '.php');

		$sourceFilter = new SourceFilter();
		$identicalCount = $sourceFilter->filter($sourceBefore, $sourceAfter);

		$progress = new ProgressBar($output, count($sourceBefore) + count($sourceAfter));
		$progress->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%");
		$output->writeln('');
		$progress->setMessage('Scanning before files');
		foreach ($sourceBefore as $file) {
			$scannerBefore->scan($file);
			$progress->advance();
		}

		$progress->setMessage('Scanning after files');
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

		$toJson = $input->getOption('to-json');
		if ($toJson) {
			$jsonReporter = new JsonReporter($report, $toJson);
			$jsonReporter->output();
		}

		$duration = microtime(true) - $startTime;
		$output->writeln('');
		$output->writeln('[Scanned files] Before: ' . count($sourceBefore) . ', After: ' . count($sourceAfter) . ', Identical: ' . $identicalCount);
		$output->writeln('Time: ' . round($duration, 3) . ' seconds, Memory: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . ' MB');
	}
}
