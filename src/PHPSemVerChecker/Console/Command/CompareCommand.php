<?php

namespace PHPSemVerChecker\Console\Command;

use File_Iterator_Facade;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Scanner\Scanner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
				new InputOption('source-before', null, InputOption::VALUE_REQUIRED, 'A single file to check'),
				new InputOption('source-after', null, InputOption::VALUE_REQUIRED, 'A single file to check against'),
			]);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$fileIterator = new File_Iterator_Facade;
		$beforeScanner = new Scanner();
		$afterScanner = new Scanner();

		$beforeFiles = $input->getOption('source-before');
		$beforeFiles = $fileIterator->getFilesAsArray($beforeFiles, '.php');

		$afterFiles = $input->getOption('source-after');
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

		$differences = $beforeRegistry->compare($afterRegistry);

		$suggestedChange = Registry::NONE;
		foreach ([Registry::MAJOR, Registry::MINOR, Registry::PATCH, Registry::NONE] as $level) {
			if ( ! empty($differences['function'][$level]) || ! empty($differences['class'][$level])) {
				$suggestedChange = $level;
				break;
			}
		}

		$output->writeln(''); // line clear
		$output->writeln('Suggested semantic versioning change: ' . Registry::levelToString($suggestedChange));

		$output->writeln(''); // line clear
		$output->writeln('CLASS');
		$output->writeln("LEVEL\tLOCATION\tREASON");

		foreach ([Registry::MAJOR, Registry::MINOR, Registry::PATCH, Registry::NONE] as $level) {
			$differencesForLevel = $differences['class'][$level];
			foreach ($differencesForLevel as $difference) {
				$output->writeln(Registry::levelToString($level) . "\t" . $difference['location'] . "\t" . $difference['reason']);
			}
		}

		$output->writeln(''); // line clear
		$output->writeln('FUNCTION');
		$output->writeln("LEVEL\tLOCATION\tREASON");

		foreach ([Registry::MAJOR, Registry::MINOR, Registry::PATCH, Registry::NONE] as $level) {
			$differencesForLevel = $differences['function'][$level];
			foreach ($differencesForLevel as $difference) {
				$output->writeln(Registry::levelToString($level) . "\t" . $difference['location'] . "\t" . $difference['reason']);
			}
		}
	}

	protected function fileScanner($pattern)
	{
		$dir = new RecursiveDirectoryIterator('.');
		$ite = new RecursiveIteratorIterator($dir);
		$files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
		foreach ($files as $file) {
			echo $file . PHP_EOL;
		}
	}
}