<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class Analyzer
{
	/**
	 * Compare with a destination registry (what the new source code is like).
	 *
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$finalReport = new Report();

		$analyzers = [
			new FunctionAnalyzer(),
			new ClassAnalyzer(),
			new InterfaceAnalyzer(),
			new TraitAnalyzer(),
		];

		foreach ($analyzers as $analyzer) {
			$report = $analyzer->analyze($registryBefore, $registryAfter);
			$finalReport->merge($report);
		}

		return $finalReport;
	}
}
