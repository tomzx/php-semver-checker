<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\ClassAdded;
use PHPSemVerChecker\Operation\ClassRemoved;
use PHPSemVerChecker\Operation\ClassRenamedCaseOnly;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;

class ClassAnalyzer {
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
		$mappingsBeforeKeyed = [];
		foreach($classesBefore as $key => $classBefore)
		{
			$classesBeforeKeyed[strtolower($classBefore->name)] = $classBefore;
			$mappingsBeforeKeyed[strtolower($classBefore->name)] = $registryBefore->mapping['class'][$key];
		}

		$classesAfterKeyed = [];
		$mappingsAfterKeyed = [];
		foreach($classesAfter as $key => $classAfter)
		{
			$classesAfterKeyed[strtolower($classAfter->name)] = $classAfter;
			$mappingsAfterKeyed[strtolower($classAfter->name)] = $registryAfter->mapping['class'][$key];
		}

		$classNamesBefore = array_keys($classesBeforeKeyed);
		$classNamesAfter = array_keys($classesAfterKeyed);
		$added = array_diff($classNamesAfter, $classNamesBefore);
		$removed = array_diff($classNamesBefore, $classNamesAfter);
		$toVerify = array_intersect($classNamesBefore, $classNamesAfter);

		foreach ($removed as $key) {
			$fileBefore = $mappingsBeforeKeyed[$key];
			$classBefore = $classesBeforeKeyed[$key];

			$data = new ClassRemoved($fileBefore, $classBefore);
			$report->addClass($data);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $mappingsBeforeKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classBefore = $classesBeforeKeyed[$key];
			$fileAfter = $mappingsAfterKeyed[$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classAfter = $classesAfterKeyed[$key];

			// Leave non-strict comparison here
			if ($classBefore != $classAfter) {

				// Check for case change of class name
				if(
					$classBefore->name !== $classAfter->name
					&& strtolower($classBefore->name) === strtolower($classAfter->name)
				) {
					$report->add($this->context, new ClassRenamedCaseOnly($fileAfter, $classAfter));
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
			$fileAfter = $mappingsAfterKeyed[$key];
			$classAfter = $classesAfterKeyed[$key];

			$data = new ClassAdded($fileAfter, $classAfter);
			$report->addClass($data);
		}

		return $report;
	}
}
