<?php

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Operation\Operation;
use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class Reporter {
	/**
	 * @var \PHPSemVerChecker\Report\Report
	 */
	protected $report;
	/**
	 * @var string
	 */
	protected $cwd;
	/**
	 * @var bool
	 */
	protected $fullPath = false;

	public function __construct(Report $report)
	{
		$this->report = $report;
		$this->cwd = getcwd();
	}

	public function setFullPath($fullPath)
	{
		$this->fullPath = $fullPath;

		return $this;
	}

	public function output(OutputInterface $output)
	{
		$suggestedChange = $this->report->getSuggestedLevel();

		$output->writeln(''); // line clear
		$output->writeln('Suggested semantic versioning change: ' . Level::toString($suggestedChange));

		$contexts = [
			'class',
			'function',
			'interface',
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
		$output->writeln(ucfirst($context) . ' (' . Level::toString($report->getLevelForContext($context)) . ')');
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
		$table->setHeaders(['Level', 'Location', 'Target', 'Reason', 'Code']);
		foreach (Level::asList('desc') as $level) {
			$reportForLevel = $report[$context][$level];
			/** @var \PHPSemVerChecker\Operation\Operation $operation */
			foreach ($reportForLevel as $operation) {
				$table->addRow([Level::toString($level), $this->getLocation($operation), $operation->getTarget(), $operation->getReason(), $operation->getCode()]);
			}
		}
		$table->render();
	}

	protected function getLocation(Operation $operation)
	{
		$isFullPath = $this->fullPath;
		if ($isFullPath) {
			$location = $operation->getLocation();
		} else {
			$fullPath = realpath($operation->getLocation());
			$location = str_replace($this->cwd . DIRECTORY_SEPARATOR, '', $fullPath);
		}
		return $location . '#' . $operation->getLine();
	}
}
