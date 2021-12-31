<?php
declare(strict_types=1);

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\TraitAdded;
use PHPSemVerChecker\Operation\TraitCaseChanged;
use PHPSemVerChecker\Operation\TraitRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class TraitAnalyzer
{
	/**
	 * @var string
	 */
	protected $context = 'trait';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter): Report
	{
		$report = new Report();

		$traitsBefore = $registryBefore->data['trait'];
		$traitsAfter = $registryAfter->data['trait'];

		$filesBeforeKeyed = [];
		$traitsBeforeKeyed = [];
		foreach ($traitsBefore as $key => $traitBefore) {
			$filesBeforeKeyed[strtolower($key)] = $registryBefore->mapping['trait'][$key];
			$traitsBeforeKeyed[strtolower($key)] = $traitBefore;
		}

		$filesAfterKeyed = [];
		$traitsAfterKeyed = [];
		foreach ($traitsAfter as $key => $traitAfter) {
			$filesAfterKeyed[strtolower($key)] = $registryAfter->mapping['trait'][$key];
			$traitsAfterKeyed[strtolower($key)] = $traitAfter;
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
			$traitBefore = $traitsBeforeKeyed[$key];
			$fileAfter = $filesAfterKeyed[$key];
			$traitAfter = $traitsAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($traitBefore != $traitAfter) {
				// Check for name case change.
				// If we entered this section then the normalized names (lowercase) were equal.
				if ($traitBefore->name !== $traitAfter->name) {
					$report->add(
						$this->context,
						new TraitCaseChanged(
							$fileBefore,
							$traitBefore,
							$fileAfter,
							$traitAfter
						)
					);
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
			$traitAfter = $traitsAfterKeyed[$key];

			$data = new TraitAdded($fileAfter, $traitAfter);
			$report->addTrait($data);
		}

		return $report;
	}
}
