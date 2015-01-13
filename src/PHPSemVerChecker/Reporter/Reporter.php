<?php

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class Reporter
{
	/**
	 * @var \PHPSemVerChecker\Report\Report
	 */
	protected $report;

	public function __construct(Report $report)
	{
		$this->report = $report;
	}

	public function output(OutputInterface $output)
	{
		$suggestedChange = Level::NONE;
		foreach (Level::asList('desc') as $level) {
			foreach ($this->report as $context => $levels) {
				if ( ! empty($levels[$level])) {
					$suggestedChange = $level;
					break 2;
				}
			}
		}

		$output->writeln(''); // line clear
		$output->writeln('Suggested semantic versioning change: ' . Level::toString($suggestedChange));

		$contexts = [
			'class',
			'function',
			'interface',
			'method',
			'trait',
		];

		foreach ($contexts as $context) {
			$this->outputReport($output, $this->report, $context);
		}
	}

	protected function outputReport(OutputInterface $output, Report $report, $context)
	{
		if ( ! $report->hasDifferences($context)) {
			return;
		}

		$output->writeln(''); // line clear
		$output->writeln(ucfirst($context).' ('.Level::toString($report->getLevelForContext($context)).')');
		$this->outputTable($output, $report, $context);
	}

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @param \PHPSemVerChecker\Report\Report                   $report
	 * @param string                                            $context
	 */
	protected function outputTable(OutputInterface $output, Report $report, $context)
	{
		$table = new Table($output);
		$table->setHeaders(['Level', 'Location', 'Target', 'Reason']);
		foreach (Level::asList('desc') as $level) {
			$reportForLevel = $report[$context][$level];
			foreach ($reportForLevel as $difference) {
				$table->addRow([Level::toString($level), $difference->getLocation(), $difference->getTarget(), $difference->getReason()]);
			}
		}
		$table->render();
	}
}
