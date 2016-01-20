<?php

namespace PHPSemVerChecker\Console\Command;

use PHPSemVerChecker\Analyzer\Analyzer;
use PHPSemVerChecker\Configuration\Configuration;
use PHPSemVerChecker\Configuration\LevelMapping;
use PHPSemVerChecker\Console\InputMerger;
use PHPSemVerChecker\Filter\SourceFilter;
use PHPSemVerChecker\Finder\Finder;
use PHPSemVerChecker\Reporter\JsonReporter;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\Scanner\ProgressScanner;
use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Command\Command;
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
				new InputArgument('source-before', InputArgument::REQUIRED, 'A base directory to check (ex my-test)'),
				new InputArgument('source-after', InputArgument::REQUIRED, 'A base directory to check against (ex my-test)'),
				new InputOption('include-before', null,  InputOption::VALUE_OPTIONAL, 'List of paths to include <info>(comma separated)</info>'),
				new InputOption('include-after', null, InputOption::VALUE_OPTIONAL, 'List of paths to include <info>(comma separated)</info>'),
				new InputOption('exclude-before', null,  InputOption::VALUE_REQUIRED, 'List of paths to exclude <info>(comma separated)</info>'),
				new InputOption('exclude-after', null, InputOption::VALUE_REQUIRED, 'List of paths to exclude <info>(comma separated)</info>'),
				new InputOption('full-path', null, InputOption::VALUE_NONE, 'Display the full path to the file instead of the relative path'),
				new InputOption('config', null, InputOption::VALUE_REQUIRED, 'A configuration file to configure php-semver-checker'),
				new InputOption('to-json', null, InputOption::VALUE_REQUIRED, 'Output the result to a JSON file')
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$startTime = microtime(true);

		$configPath = $input->getOption('config');
		$configuration = $configPath ? Configuration::fromFile($configPath) : Configuration::defaults();
		$im = new InputMerger();
		$im->merge($input, $configuration);

		// Set overrides
		LevelMapping::setOverrides($configuration->getLevelMapping());

		$finder = new Finder();
		$scannerBefore = new Scanner();
		$scannerAfter = new Scanner();

		$sourceBefore = $input->getArgument('source-before');
		$includeBefore = $input->getOption('include-before');
		$excludeBefore = $input->getOption('exclude-before');

		$sourceAfter = $input->getArgument('source-after');
		$includeAfter = $input->getOption('include-after');
		$excludeAfter = $input->getOption('exclude-after');

		$sourceBefore = $finder->findFromString($sourceBefore, $includeBefore, $excludeBefore);
		$sourceAfter = $finder->findFromString($sourceAfter, $includeAfter, $excludeAfter);

		$sourceFilter = new SourceFilter();
		$identicalCount = $sourceFilter->filter($sourceBefore, $sourceAfter);

		$progress = new ProgressScanner($output);
		$progress->addJob($input->getArgument('source-before'), $sourceBefore, $scannerBefore);
		$progress->addJob($input->getArgument('source-after'), $sourceAfter, $scannerAfter);
		$progress->runJobs();

		$registryBefore = $scannerBefore->getRegistry();
		$registryAfter = $scannerAfter->getRegistry();

		$analyzer = new Analyzer();
		$report = $analyzer->analyze($registryBefore, $registryAfter);

		$reporter = new Reporter($report);
		$reporter->setFullPath($input->getOption('full-path'));
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
