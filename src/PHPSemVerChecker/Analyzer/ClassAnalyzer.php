<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\ClassAdded;
use PHPSemVerChecker\Operation\ClassCaseChanged;
use PHPSemVerChecker\Operation\ClassRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class ClassAnalyzer
{
	/**
	 * @var string
	 */
	protected $context = 'class';

	/**
	 * @param \PHPSemVerChecker\Registry\Registry $registryBefore
	 * @param \PHPSemVerChecker\Registry\Registry $registryAfter
	 * @return \PHPSemVerChecker\Report\Report
	 */
	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$classesBefore = $registryBefore->data['class'];
		$classesAfter = $registryAfter->data['class'];

		$classesBeforeKeyed = [];
		$filesBeforeKeyed = [];
		foreach ($classesBefore as $key => $classBefore) {
			$classesBeforeKeyed[strtolower($classBefore->name)] = $classBefore;
			$filesBeforeKeyed[strtolower($classBefore->name)] = $registryBefore->mapping['class'][$key];
		}

		$classesAfterKeyed = [];
		$filesAfterKeyed = [];
		foreach ($classesAfter as $key => $classAfter) {
			$classesAfterKeyed[strtolower($classAfter->name)] = $classAfter;
			$filesAfterKeyed[strtolower($classAfter->name)] = $registryAfter->mapping['class'][$key];
		}

		$classNamesBefore = array_keys($classesBeforeKeyed);
		$classNamesAfter = array_keys($classesAfterKeyed);
		$added = array_diff($classNamesAfter, $classNamesBefore);
		$removed = array_diff($classNamesBefore, $classNamesAfter);
		$toVerify = array_intersect($classNamesBefore, $classNamesAfter);

		foreach ($removed as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			$classBefore = $classesBeforeKeyed[$key];

			$data = new ClassRemoved($fileBefore, $classBefore);
			$report->addClass($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $filesBeforeKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classBefore = $classesBeforeKeyed[$key];
			$fileAfter = $filesAfterKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classAfter = $classesAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($classBefore != $classAfter) {
				// Check for case change of class name.
				// If we entered this section then the normalized names (lowercase) were equal.
				if ($classBefore->name !== $classAfter->name) {
					$report->add(
						$this->context,
						new ClassCaseChanged(
							$fileBefore,
							$classBefore,
							$fileAfter,
							$classAfter
						)
					);
				}

				$analyzers = [
					new ClassMethodAnalyzer('class', $fileBefore, $fileAfter),
					new PropertyAnalyzer('class', $fileBefore, $fileAfter),
				];

				foreach ($analyzers as $analyzer) {
					$internalReport = $analyzer->analyze($classBefore, $classAfter);
					$report->merge($internalReport);
				}
			}
		}

		foreach ($added as $key) {
			$fileAfter = $filesAfterKeyed[$key];
			$classAfter = $classesAfterKeyed[$key];

			$data = new ClassAdded($fileAfter, $classAfter);
			$report->addClass($data);
		}

		return $report;
	}
}
