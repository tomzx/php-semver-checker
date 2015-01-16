<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Comparator\Signature;
use PHPSemVerChecker\Operation\FunctionAdded;
use PHPSemVerChecker\Operation\FunctionImplementationChanged;
use PHPSemVerChecker\Operation\FunctionParameterChanged;
use PHPSemVerChecker\Operation\FunctionParameterNameChanged;
use PHPSemVerChecker\Operation\FunctionRemoved;
use PHPSemVerChecker\Operation\Unknown;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class FunctionAnalyzer {
	protected $context = 'function';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$keysBefore = array_keys($registryBefore->data['function']);
		$keysAfter = array_keys($registryAfter->data['function']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_intersect($keysBefore, $keysAfter);

		foreach ($removed as $key) {
			$fileBefore = $registryBefore->mapping['function'][$key];
			$functionBefore = $registryBefore->data['function'][$key];

			$data = new FunctionRemoved($fileBefore, $functionBefore);
			$report->addFunction($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $registryBefore->mapping['function'][$key];
			$functionBefore = $registryBefore->data['function'][$key];
			$fileAfter = $registryAfter->mapping['function'][$key];
			$functionAfter = $registryAfter->data['function'][$key];

			// Leave non-strict comparison here
			if ($functionBefore != $functionAfter) {
				$paramsBefore = $functionBefore->params;
				$paramsAfter = $functionAfter->params;
				// Signature

				if ( ! Signature::isSameTypehints($paramsBefore, $paramsAfter)) {
					$data = new FunctionParameterChanged($fileBefore, $functionBefore, $fileAfter, $functionAfter);
					$report->addFunction($data);
					continue;
				}

				if ( ! Signature::isSameVariables($paramsBefore, $paramsAfter)) {
					$data = new FunctionParameterNameChanged($fileBefore, $functionBefore, $fileAfter, $functionAfter);
					$report->addFunction($data);
					continue;
				}

				// Different length (considering params with defaults)

				// Difference in source code
				if ($functionBefore->stmts != $functionAfter->stmts) {
					$data = new FunctionImplementationChanged($fileBefore, $functionBefore, $fileAfter, $functionAfter);
					$report->addFunction($data);
					continue;
				}

				// Unable to match an issue, but there is one...
				$data = new Unknown($fileBefore, $fileAfter);
				$report->addFunction($data);
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registryAfter->mapping['function'][$key];
			$functionAfter = $registryAfter->data['function'][$key];

			$data = new FunctionAdded($fileAfter, $functionAfter);
			$report->addFunction($data);
		}

		return $report;
	}
}
