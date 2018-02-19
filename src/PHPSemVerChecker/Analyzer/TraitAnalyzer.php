<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\TraitAdded;
use PHPSemVerChecker\Operation\TraitRemoved;
use PHPSemVerChecker\Operation\TraitRenamedCaseOnly;
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

		$traitsBefore = $registryBefore->data['trait'];
		$traitsAfter = $registryAfter->data['trait'];

		$traitsBeforeKeyed = [];
		$filesBeforeKeyed = [];
		foreach($traitsBefore as $key => $traitBefore)
		{
			$traitsBeforeKeyed[strtolower($traitBefore->name)] = $traitBefore;
			$filesBeforeKeyed[strtolower($traitBefore->name)] = $registryBefore->mapping['trait'][$key];
		}

		$traitsAfterKeyed = [];
		$filesAfterKeyed = [];
		foreach($traitsAfter as $key => $traitAfter)
		{
			$traitsAfterKeyed[strtolower($traitAfter->name)] = $traitAfter;
			$filesAfterKeyed[strtolower($traitAfter->name)] = $registryAfter->mapping['trait'][$key];
		}

		$traitNamesBefore = array_keys($traitsBeforeKeyed);
		$traitNamesAfter = array_keys($traitsAfterKeyed);
		$added = array_diff($traitNamesAfter, $traitNamesBefore);
		$removed = array_diff($traitNamesBefore, $traitNamesAfter);
		$toVerify = array_intersect($traitNamesBefore, $traitNamesAfter);

		foreach ($removed as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			$traitBefore = $traitsBeforeKeyed[$key];

			$data = new TraitRemoved($fileBefore, $traitBefore);
			$report->addTrait($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $traitBefore */
			$traitBefore = $traitsBeforeKeyed[$key];
			$fileAfter = $filesAfterKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $traitBefore */
			$traitAfter = $traitsAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($traitBefore != $traitAfter) {

				// Check for name case change.
				if(
					$traitBefore->name !== $traitAfter->name
					&& strtolower($traitBefore->name) === strtolower($traitAfter->name)
				) {
					$report->add($this->context, new TraitRenamedCaseOnly($fileAfter, $traitAfter));
				}

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
			$fileAfter = $filesAfterKeyed[$key];
			$traitAfter = $traitsAfter[$key];

			$data = new TraitAdded($fileAfter, $traitAfter);
			$report->addTrait($data);
		}

		return $report;
	}
}
