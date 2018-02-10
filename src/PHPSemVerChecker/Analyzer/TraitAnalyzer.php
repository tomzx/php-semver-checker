<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\TraitAdded;
use PHPSemVerChecker\Operation\TraitRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class TraitAnalyzer {
	/**
	 * @var string
	 */
	protected $context = 'trait';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$keysBefore = array_keys($registryBefore->data['trait']);
		$keysAfter = array_keys($registryAfter->data['trait']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_intersect($keysBefore, $keysAfter);

		foreach ($removed as $key) {
			$fileBefore = $registryBefore->mapping['trait'][$key];
			$traitBefore = $registryBefore->data['trait'][$key];

			$data = new TraitRemoved($fileBefore, $traitBefore);
			$report->addTrait($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $registryBefore->mapping['trait'][$key];
			/** @var \PhpParser\Node\Stmt\Class_ $traitBefore */
			$traitBefore = $registryBefore->data['trait'][$key];
			$fileAfter = $registryAfter->mapping['trait'][$key];
			/** @var \PhpParser\Node\Stmt\Class_ $traitBefore */
			$traitAfter = $registryAfter->data['trait'][$key];

			// Leave non-strict comparison here
			if ($traitBefore != $traitAfter) {
				$analyzers = [
					new ClassMethodAnalyzer('trait', $fileBefore, $fileAfter),
					new PropertyAnalyzer('trait', $fileBefore, $fileAfter),
				];

				foreach ($analyzers as $analyzer) {
					$internalReport = $analyzer->analyze($traitBefore, $traitAfter);
					$report->merge($internalReport);
				}
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registryAfter->mapping['trait'][$key];
			$traitAfter = $registryAfter->data['trait'][$key];

			$data = new TraitAdded($fileAfter, $traitAfter);
			$report->addTrait($data);
		}

		return $report;
	}
}
