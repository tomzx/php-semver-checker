<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class Analyzer
{
	/** @var array */
	protected $args = ['apiOnly' => null];
	/**
	 * Compare with a destination registry (what the new source code is like).
	 *
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter): Report
	{
		$finalReport = new Report();

		$analyzers = [
			new FunctionAnalyzer(),
			new ClassAnalyzer($this->args),
			new InterfaceAnalyzer(),
			new TraitAnalyzer(),
		];

		foreach ($analyzers as $analyzer) {
			$report = $analyzer->analyze($registryBefore, $registryAfter);
			$finalReport->merge($report);
		}

		return $finalReport;
	}

	/**
	 * @param array $args
	 * @return void
	 */
	public function setArgs(array $args): void
	{
		$this->args = $args;
	}
}
