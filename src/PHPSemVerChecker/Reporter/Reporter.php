<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Operation\Operation;
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
	/**
	 * @var string
	 */
	protected $cwd;
	/**
	 * @var bool
	 */
	protected $fullPath = false;

	/**
	 * @param \PHPSemVerChecker\Report\Report $report
	 */
	public function __construct(Report $report)
	{
		$this->report = $report;
		$this->cwd = getcwd();
	}

	/**
	 * @param bool $fullPath
	 * @return $this
	 */
	public function setFullPath(bool $fullPath): Reporter
	{
		$this->fullPath = $fullPath;

		return $this;
	}

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
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

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @param \PHPSemVerChecker\Report\Report                   $report
	 * @param string                                            $context
	 */
	protected function outputReport(OutputInterface $output, Report $report, string $context)
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
	protected function outputTable(OutputInterface $output, Report $report, string $context)
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

	/**
	 * @param \PHPSemVerChecker\Operation\Operation $operation
	 * @return string
	 */
	protected function getLocation(Operation $operation): string
	{
		$isFullPath = $this->fullPath;
		if ($isFullPath) {
			$location = $operation->getLocation();
		} else {
			$location = str_replace($this->cwd . DIRECTORY_SEPARATOR, '', $operation->getLocation());
		}
		return $location . ':' . $operation->getLine();
	}
}
