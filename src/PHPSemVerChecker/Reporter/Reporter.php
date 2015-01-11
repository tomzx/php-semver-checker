<?php

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Registry\Registry;
use Symfony\Component\Console\Output\OutputInterface;

class Reporter
{
	public function output(Registry $beforeRegistry, Registry $afterRegistry, OutputInterface $output)
	{
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
}