<?php

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Registry\Registry;
use Symfony\Component\Console\Helper\Table;
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
		$output->writeln('Class');
		$this->outputTable($output, $differences, 'class');

		$output->writeln(''); // line clear
		$output->writeln('Function');
		$this->outputTable($output, $differences, 'function');
	}

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @param array                                             $differences
	 * @param string                                            $type
	 */
	protected function outputTable(OutputInterface $output, array $differences, $type)
	{
		$table = new Table($output);
		$table->setHeaders(['Level', 'Location', 'Target', 'Reason']);
		foreach ([Registry::MAJOR, Registry::MINOR, Registry::PATCH, Registry::NONE] as $level) {
			$differencesForLevel = $differences[$type][$level];
			foreach ($differencesForLevel as $difference) {
				$table->addRow([Registry::levelToString($level), $difference->getLocation(), $difference->getTarget(), $difference->getReason()]);
			}
		}
		$table->render();
	}
}