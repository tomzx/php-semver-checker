<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Console\Command;

use PHPSemVerChecker\Analyzer\Analyzer;
use PHPSemVerChecker\Filter\SourceFilter;
use PHPSemVerChecker\Finder\Finder;
use PHPSemVerChecker\Reporter\JsonReporter;
use PHPSemVerChecker\Reporter\Reporter;
use PHPSemVerChecker\Scanner\ProgressScanner;
use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompareCommand extends BaseCommand
{
	/**
	 * @return void
	 */
	protected function configure()
	{
		$this
			->setName('compare')
			->setDescription('Compare a set of files to determine what semantic versioning change needs to be done')
			->setDefinition([
				new InputArgument('source-before', InputArgument::REQUIRED, 'A base directory to check (ex my-test)'),
				new InputArgument('source-after', InputArgument::REQUIRED, 'A base directory to check against (ex my-test)'),
				new InputOption('include-before', null, InputOption::VALUE_REQUIRED, 'List of paths to include <info>(comma separated)</info>'),
				new InputOption('include-after', null, InputOption::VALUE_REQUIRED, 'List of paths to include <info>(comma separated)</info>'),
				new InputOption('exclude-before', null, InputOption::VALUE_REQUIRED, 'List of paths to exclude <info>(comma separated)</info>'),
				new InputOption('exclude-after', null, InputOption::VALUE_REQUIRED, 'List of paths to exclude <info>(comma separated)</info>'),
				new InputOption('full-path', null, InputOption::VALUE_NONE, 'Display the full path to the file instead of the relative path'),
				new InputOption('config', null, InputOption::VALUE_REQUIRED, 'A configuration file to configure php-semver-checker'),
				new InputOption('to-json', null, InputOption::VALUE_REQUIRED, 'Output the result to a JSON file')
			]);
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface   $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$startTime = microtime(true);

		$finder = new Finder();
		$scannerBefore = new Scanner();
		$scannerAfter = new Scanner();

		$sourceBefore = $this->config->get('source-before');
		$includeBefore = $this->config->get('include-before');
		$excludeBefore = $this->config->get('exclude-before');

		$sourceAfter = $this->config->get('source-after');
		$includeAfter = $this->config->get('include-after');
		$excludeAfter = $this->config->get('exclude-after');

		$sourceBefore = $finder->findFromString($sourceBefore, $includeBefore, $excludeBefore);
		$sourceAfter = $finder->findFromString($sourceAfter, $includeAfter, $excludeAfter);

		$sourceFilter = new SourceFilter();
		$identicalCount = $sourceFilter->filter($sourceBefore, $sourceAfter);

		$progress = new ProgressScanner($output);
		$progress->addJob($this->config->get('source-before'), $sourceBefore, $scannerBefore);
		$progress->addJob($this->config->get('source-after'), $sourceAfter, $scannerAfter);
		$progress->runJobs();

		$registryBefore = $scannerBefore->getRegistry();
		$registryAfter = $scannerAfter->getRegistry();

		$analyzer = new Analyzer();
		$report = $analyzer->analyze($registryBefore, $registryAfter);

		$reporter = new Reporter($report);
		$reporter->setFullPath((bool)$this->config->get('full-path'));
		$reporter->output($output);

		$toJson = $this->config->get('to-json');
		if ($toJson) {
			$jsonReporter = new JsonReporter($report, $toJson);
			$jsonReporter->output();
		}

		$duration = microtime(true) - $startTime;
		$output->writeln('');
		$output->writeln('[Scanned files] Before: ' . count($sourceBefore) . ', After: ' . count($sourceAfter) . ', Identical: ' . $identicalCount);
		$output->writeln('Time: ' . round($duration, 3) . ' seconds, Memory: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . ' MB');

		return 0;
	}
}
