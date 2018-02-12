<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Comparator\Implementation;
use PHPSemVerChecker\Comparator\Signature;
use PHPSemVerChecker\Operation\FunctionAdded;
use PHPSemVerChecker\Operation\FunctionImplementationChanged;
use PHPSemVerChecker\Operation\FunctionOperationUnary;
use PHPSemVerChecker\Operation\FunctionParameterAdded;
use PHPSemVerChecker\Operation\FunctionParameterChanged;
use PHPSemVerChecker\Operation\FunctionParameterDefaultAdded;
use PHPSemVerChecker\Operation\FunctionParameterDefaultRemoved;
use PHPSemVerChecker\Operation\FunctionParameterDefaultValueChanged;
use PHPSemVerChecker\Operation\FunctionParameterNameChanged;
use PHPSemVerChecker\Operation\FunctionParameterRemoved;
use PHPSemVerChecker\Operation\FunctionParameterTypingAdded;
use PHPSemVerChecker\Operation\FunctionParameterTypingRemoved;
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

				$signatureResult = Signature::analyze($functionBefore, $functionAfter);

				$changes = [
					'parameter_added' => FunctionParameterAdded::class,
					'parameter_removed' => FunctionParameterRemoved::class,
					'parameter_renamed' => FunctionParameterNameChanged::class,
					'parameter_typing_added' => FunctionParameterTypingAdded::class,
					'parameter_typing_removed' => FunctionParameterTypingRemoved::class,
					'parameter_default_added' => FunctionParameterDefaultAdded::class,
					'parameter_default_removed' => FunctionParameterDefaultRemoved::class,
					'parameter_default_value_changed' => FunctionParameterDefaultValueChanged::class,
				];

				foreach ($changes as $changeType => $class) {
					if ( ! $signatureResult[$changeType]) {
						continue;
					}
					if (is_a($class, FunctionOperationUnary::class, true)) {
						$data = new $class($fileAfter, $functionAfter);
					} else {
						$data = new $class($fileBefore, $functionBefore, $fileAfter, $functionAfter);
					}
					$report->addFunction($data);
				}

				// Difference in source code
				if ( ! Implementation::isSame($functionBefore->stmts, $functionAfter->stmts)) {
					$data = new FunctionImplementationChanged($fileBefore, $functionBefore, $fileAfter, $functionAfter);
					$report->addFunction($data);
				}
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
