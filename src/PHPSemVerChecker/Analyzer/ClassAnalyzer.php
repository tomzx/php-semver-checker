<?php

namespace PHPSemVerChecker\Analyzer;

use PHPSemVerChecker\Operation\ClassAdded;
use PHPSemVerChecker\Operation\ClassRemoved;
use PHPSemVerChecker\Registry\Registry;
use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;

class ClassAnalyzer
{
	protected $context = 'class';

	public function analyze(Registry $registryBefore, Registry $registryAfter)
	{
		$report = new Report();

		$keysBefore = array_keys($registryBefore->data['class']);
		$keysAfter = array_keys($registryAfter->data['class']);
		$added = array_diff($keysAfter, $keysBefore);
		$removed = array_diff($keysBefore, $keysAfter);
		$toVerify = array_intersect($keysBefore, $keysAfter);

		foreach ($removed as $key) {
			$fileBefore = $registryBefore->mapping['class'][$key];
			$classBefore = $registryBefore->data['class'][$key];

			$data = new ClassRemoved($fileBefore, $classBefore);
			$report->addClass($data, Level::MAJOR);
		}

		foreach ($toVerify as $key) {
			$fileBefore = $registryBefore->mapping['class'][$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classBefore = $registryBefore->data['class'][$key];
			$fileAfter = $registryAfter->mapping['class'][$key];
			/** @var \PhpParser\Node\Stmt\Class_ $classBefore */
			$classAfter = $registryAfter->data['class'][$key];

			// Leave non-strict comparison here
			if ($classBefore != $classAfter) {
				$analyzer = new ClassMethodAnalyzer('class', $fileBefore, $fileAfter);
				$classMethodReport = $analyzer->analyze($classBefore, $classAfter);
				$report->merge($classMethodReport);
			}
		}

		foreach ($added as $key) {
			$fileAfter = $registryAfter->mapping['class'][$key];
			$classAfter = $registryAfter->data['class'][$key];

			$data = new ClassAdded($fileAfter, $classAfter);
			$report->addClass($data, Level::MINOR);
		}

		return $report;
	}
}
